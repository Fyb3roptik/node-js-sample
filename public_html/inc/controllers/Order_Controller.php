<?php
require_once 'Controller.php';

class Order_Controller extends Controller {
	public function hold($order_id) {
		$this->_requireSalesRep();
		$O = new Order($order_id);
		$MS = new Message_Stack();
		try {
			$OH = new Order_Holder($O);
			$req = $OH->requestHold();
			$req->write();
			$MS->add('order', 'Request to hold order has been sent.', MS_SUCCESS);
		} catch(Exception $e) {
			$MS->add('order', "This order could not be placed on hold.", MS_ERROR);
		}
		redirect($this->_getSalesViewUrl($O));
	}

	public function unhold($order_id) {
		$this->_requireSalesRep();
		$O = new Order($order_id);
		$MS = new Message_Stack();
		try {
			$OH = new Order_Holder($O);
			$req = $OH->requestUnhold();
			$req->write();
			$MS->add('order', 'Request to unhold order has been sent.', MS_SUCCESS);
		} catch(Exception $e) {
			$MS->add('order', "This order could not be placed on unhold.", MS_ERROR);
		}
		redirect($this->_getSalesViewUrl($O));
	}

	public function findOrder() {
		$order_id = post_var('order_id');
    	$salesrep = $this->_validateSalesRep();
		$MS = new Message_Stack();
		try {
			$results = $this->_searchOrders($order_id, $salesrep);
		} catch(Exception $e) {
			$results = "No search results found";
		}
		echo $results;
		exit;
	}

	private function _searchOrders($order_id, $salesrep) {
    	if(true == $salesrep) {
    		$sql = SQL::get()
			->select("order_id")
			->from('orders')
			->where("order_id = '@order_id'")
			->bind('order_id', $order_id);
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
            	$result = $rec['order_id'];
			}
    	} else {
        	$ID = $this->_user->ID;
			$sql = SQL::get()
			->select("order_id")
			->from('orders')
			->where("order_id = '@order_id'")
			->where("customer_id = '@customer_id'")
			->bind('order_id', $order_id)
			->bind('customer_id', $ID);
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
            	$result = $rec['order_id'];
			}
    	}
		return $result;
	}

	private function _getSalesViewUrl(Order $O) {
		$url = '/orders.php?action=view&order=' . $O->ID;
		return $url;
	}

	public function salesOptions($order_id) {
		if(false == $this->_validateSalesRep()) {
			exit; //not allowed here.
		}
		$this->_setTemplate(new Template('ajax.php'));

		$O = new Order($order_id);
		if(false == $O->exists()) {
			exit("Order doesn't exist");
		}
		$sql = SQL::get()
			->select("order_change_id")
			->from('order_change_history')
			->where("order_id = '@order_id'")
			->where("syspro = '0'")
			->bind('order_id', $O->ID);
		$query = db_query($sql);
		if($query->num_rows > 0) {
			exit("Order has pending changes and can't be updated at this moment.");
		}

		$V = new View('order_sales_detail.php');
		$V->bind('SR', $this->_user);
		$V->bind('O', $O);
		$this->_setView($V);
	}

	private function _validateSalesRep() {
		$sales_rep = false;
		if(true == $this->_user->exists() && true == is_a($this->_user, 'Sales_Rep')) {
			$sales_rep = true;
		}
		return $sales_rep;
	}

	public function change($order_id) {
		$this->_requireSalesRep();
		$O = new Order($order_id);
		if(false == $O->exists() || 0 == intval($this->_user->change_permission)) {
			redirect('/sales/');
			exit;
		}

		$SYS = new Syspro_API();
		$O->syspro_status = $SYS->getOrderStatus($O->ID);

		if(1 != $O->syspro_status && 2 != $O->syspro_status) {
			redirect('/sales/');
			exit;
		}

		$reason_codes = $this->_getReasonCodes();
		$OC = $this->_findOrderChange($O);
		$this->_setTemplate(new Template('wide.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$V = new View('order_change_form.php');
		$V->bind('O', $O);

		$cancel_list = $OC->getCancelList();

		$product_list = array();
		foreach($O->getProducts() as $OP) {
			$P = new Product($OP->getProductId());
			if(false == array_key_exists($P->catalog_code, $cancel_list) && $OP->quantity > 0) {
				$product_list[$P->catalog_code] = $OP;
			}
		}
		$misc_cancel_list = $OC->getMiscList();

		$misc_list = array();
		foreach($O->getMiscCharges() as $misc) {
			if(false == array_key_exists($misc->ID, $misc_cancel_list)) {
				$misc_list[] = $misc;
			}
		}

		$V->bind('PRODUCT_LIST', $product_list);
		$V->bind('OC', $OC);
		$V->bind('CANCEL_LIST', $OC->getCancelList());
		$V->bind('MISC_CANCEL_LIST', $OC->getMiscList());
		$V->bind('NEW_QTY', $OC->getNewQuantityList());
		$V->bind('NEW_PRICE', $OC->getNewPriceList());
		$V->bind('REASON_LIST', $reason_codes);
		$V->bind('MISC_LIST', $misc_list);
		$this->_setView($V);
	}

	public function saveChanges() {
		$this->_requireSalesRep();
		$O = new Order(post_var('order_id'));
		FB::log($O, "Order");
		$return = array('success' => false);
		if(true == $this->_orderChangeStatus($O)) {
			$OC = $this->_findOrderChange($O);
			$OC->setNote(post_var('sales_note'));
			$qty_changes = post_var('new_quantity', array());
			foreach($qty_changes as $stock_code => $qty) {
				$OC->changeQuantity($stock_code, $qty);
			}
			$_SESSION['order_changes'][$O->ID] = serialize($OC);
			$return['success'] = true;
		}
		echo json_encode($return);
		exit;
	}

	public function processChanges() {
		$this->_requireSalesRep();
		$O = new Order(post_var('order_id'));
		$return = array('success' => false);
		if(true == $this->_orderChangeStatus($O)) {
			$OC = $this->_findOrderChange($O);
			$OC->setSalesRep($this->_user);
			$original_total = $OC->getOrder()->getTotal();
			FB::log($original_total, "Old Total");
			$OC->process();
			$O = new Order($OC->getOrder()->ID);
			$new_total = $O->getTotal();
			FB::log($new_total, "New Total");
			if($new_total > $original_total) {
				$this->_reauthorizeCreditCard($O);
			}
			$return['success'] = true;
			unset($_SESSION['order_changes'][$O->ID]);
		}
		echo json_encode($return);
		exit;
	}

	private function _reauthorizeCreditCard(Order $order) {
		FB::group('_reauthorizeCreditCard');
		FB::log($order, 'Order');
		FB::log('foo');
		if('01' == $order->syspro_invoice_code) {
			$A = new Authnet(AUTHNET_API_LOGIN, AUTHNET_TRANS_KEY, AUTHNET_TEST_MODE);
			$A->setCardNumber(decrypt($order->cc_number));
			$A->setExpirationDate(decrypt($order->cc_expires_month),
				decrypt($order->cc_expires_year));
			$A->setAmount($order->getTotal());
			$A->transact();
			$OCH = $this->_getOCH($order);
			$OCH->cc_trans_id = $A->transaction_id;
			$OCH->cc_auth_code = $A->authorization_code;
			$OCH->write();
			FB::log($OCH, 'OCH');
		} else {
			FB::log($order->syspro_invoice_code, 'Syspro code?');
		}
		FB::log('bar');
		FB::groupEnd();
	}

	private function _getOCH(Order $order) {
		$OCH = null;
		$sql = SQL::get()
			->select('order_change_id')
			->from("order_change_history")
			->where("order_id = '@order_id'")
			->where("syspro = '0'")
			->orderBy('timestamp', 'DESC')
			->bind('order_id', $order->ID);
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$OCH = new Order_Change_History($rec['order_change_id']);
		}
		return $OCH;
	}

	public function cancelItem($order_id) {
		$this->_requireSalesRep();
		$O = new Order($order_id);
		if(false == $this->_orderChangeStatus($O)) {
			exit;
		}

		$this->_setTemplate(new Template('ajax.php'));
		$V = new View('item_cancel_form.php');
		$V->bind('ORDER_ID', $O->ID);
		$V->bind('STOCK_CODE', get_var('item'));
		$V->bind('ITEM_TYPE', get_var('type', 'product'));
		$V->bind('REASON_LIST', $this->_getReasonCodes());
		$this->_setView($V);
	}

	public function changeCancel() {
		$this->_requireSalesRep();
		$O = new Order(post_var('order_id'));
		if(true == $this->_orderChangeStatus($O)) {
			$OC = $this->_findOrderChange($O);
			try {
				if('product' == post_var('type', 'product')) {
					$OC->cancelProduct(post_var('stock_code'), post_var('cancel_code'));
				} else {
					$OC->cancelMisc(post_var('stock_code'), post_var('cancel_code'));
				}
				$_SESSION['order_changes'][$O->ID] = serialize($OC);
			} catch(Exception $e) {
				/* do nothing */
			}

		}
		redirect('/order/change/' . $O->ID);
		exit;
	}

	public function uncancelItem($order_id) {
		$this->_requireSalesRep();
		$O = new Order($order_id);
		if(true == $this->_orderChangeStatus($O)) {
			$OC = $this->_findOrderChange($O);
			if('product' == get_var('type', 'product')) {
				$OC->uncancelProduct(get_var('item'));
			} else {
				$OC->uncancelMisc(get_var('item'));
			}
			$_SESSION['order_changes'][$O->ID] = serialize($OC);
		}
		redirect('/order/change/' . $O->ID);
		exit;
	}

	private function _findOrderChange(Order $order) {
		$order_changes = session_var('order_changes', array());
		if(true == array_key_exists($order->ID, $order_changes)) {
			$order_change = unserialize($order_changes[$order->ID]);
		} else {
			$order_change = new Order_Change($order);
			$_SESSION['order_changes'][$order->ID] = serialize($order_change);
		}
		return $order_change;
	}

	private function _getReasonCodes() {
		$sql = SQL::get()
			->select('reason_code', 'reason_text')
			->from('order_cancel_reasons')
			->orderBy('reason_code');
		$query = db_query($sql);
		$reason_codes = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$reason_codes[$rec['reason_code']] = $rec['reason_text'];
		}
		return $reason_codes;
	}

	public function cancel($order_id) {
		$this->_requireSalesRep();
		$O = new Order($order_id);
		if(false == $O->exists() || 0 == intval($this->_user->cancel_permission)) {
			redirect('/sales/');
			exit;
		}

		$SYS = new Syspro_API();
		$syspro_status = $SYS->getOrderStatus($O->ID);
		if(2 != $syspro_status && 1 != $syspro_status && '' != $syspro_status) {
			redirect('/sales/');
			exit;
		}

		$reason_codes = $this->_getReasonCodes();
		$this->_setTemplate(new Template('wide.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$V = new View('order_cancel_form.php');
		$V->bind('O', $O);
		$V->bind('CANCEL_REASON_LIST', $reason_codes);
		$this->_setView($V);
	}

	public function processCancel() {
		$this->_requireSalesRep();
		$O = new Order(post_var('order_id'));
		if(false == $O->exists()) {
			throw new Exception("Bad order ID!");
		}
		//TODO: VALIDATE REASON / ORDER CAN BE CANCELLED / CANCEL CODE IS VALID
		$SYS = new Syspro_API();
		$syspro_status = $SYS->getOrderStatus($O->ID);
		if(2 == $syspro_status || 1 == $syspro_status || '' == $syspro_status) {
			$O->cancel($this->_user->ID, post_var('cancel_code'), post_var('cancel_reason'));
			redirect('/sales/', array('action' => 'customer_detail', 'customer' => $O->customer_id));
		} else {
			redirect('/sales/');
			exit;
		}
	}
	
	/**
	 * Overly verbose function that tells you if an order can be changed.
	 */
	private function _orderChangeStatus(Order $order) {
		FB::group("_orderChangeStatus()");
		$change_status = true;	
		//non-existent orders can't be changed
		if(false == $order->exists()) {
			$change_status = false;
			FB::log("Order doesn't exist.");
		}

		//sales reps without permission can't change orders
		if(0 == $this->_user->change_permission) {
			FB::log("Sales rep doesn't have permission.");
			$change_status = false;
		}

		//orders in the wrong status can't be changed.
		$good_syspro_status = array(1, 2);
		$SYS = new Syspro_API();
		$syspro_status = $SYS->getOrderStatus($order->ID);
		if(false == in_array($syspro_status, $good_syspro_status)) {
			FB::log("Syspro order status, not changeable. (" . $syspro_status . ')');
			$change_status = false;
		}
		FB::groupEnd();
		return $change_status;
	}
}
?>
