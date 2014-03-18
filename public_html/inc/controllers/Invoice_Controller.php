<?php
require_once dirname(__FILE__) . '/Controller.php';

class Invoice_Controller extends Controller {
	public function view($order_id = 0) {
		$O = new Order($order_id);
		$this->_validatePermissions($order_id);
		$invoice_id = get_var('invoice', 0);
		if(0 == $invoice_id) {
			$this->redirect($this->_getOrderUrl($order_id));
		}
		$invoice = $this->_findInvoice($O, $invoice_id);
		if(true == is_null($invoice)) {
			$this->redirect($this->_getOrderUrl($order_id));
		}
		$this->_invoiceView($O, $invoice);
	}

	protected function _validatePermissions($order_id) {
		$permission = false;
		if(true == is_a($this->_user, 'Sales_Rep')) {
			$permission = true;
		} else {
			$O = new Order($order_id);
			if($O->customer_id == $this->_user->ID) {
				$permission = true;
			}
		}

		if(false == $permission) { $this->redirect('/'); }
		return true;
	}

	protected function _getOrderUrl($order_id) {
		return sprintf('/orders.php?action=view&order=%d', $order_id);
	}

	private function _findInvoice(Order $O, $invoice_id) {
		$invoice = null;
		$I = new Invoice($invoice_id);
		if(true == $I->exists() && $O->getSysproKey() == $I->order_id) {
			$invoice = $I;
		}

		return $invoice;
	}

	private function _invoiceView(Order $order, Invoice $invoice) {
		FB::log($invoice, 'Invoice');
		$this->_setTemplate(new Template('wide.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$this->_template->bind('NAV_FILE', 'nav_invoice.php');
		$V = new View('invoice_detail.php');
		$V->bind('INV', $invoice);
		$V->bind('BILL', $order->billing_address);
		$V->bind('SHIP', $order->shipping_address);
		$V->bind('SHIPPING_METHOD', $this->_getShippingMethod($order));
		$V->bind('SALES_REP', $this->_getSalesRep($order));
		$V->bind('TERMS_LOOKUP', $this->_getTerms());
		$V->bind('O', $order);
		$this->_setView($V);
	}

	private function _getShippingMethod($order) {
		$method = null;
		if(count($order->getShippingList()) > 0) {
			$list = $order->getShippingList();
			$method = $list[0]->name;
		}
		return $method;
	}

	protected function _getSalesRep(Order $order) {
		$sales_rep = null;
		$SR = new Sales_Rep($order->sales_rep_id);
		if(true == $SR->exists()) {
			$sales_rep = $SR;
		}
		return $sales_rep;
	}

	private function _getTerms() {
		$terms = array();
		$sql = SQL::get()
			->select('syspro_code, name')
			->from('payment_terms');
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$terms[$rec['syspro_code']] = $rec['name'];
		}
		return $terms;
	}
}
?>