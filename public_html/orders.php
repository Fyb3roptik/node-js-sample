<?php
require_once 'inc/global.php';

$action = exists('action', $_REQUEST, null);

switch($action) {
	case 'view': {
		$O = new Order(exists('order', $_GET, 0));
		if(false == $O->exists() || (intval($O->getCustomerID()) !== intval($CUSTOMER->getID()) && false == is_a($CUSTOMER, 'Sales_Rep')) ) {
			redirect(LOC_ORDER_HISTORY);
		}
        if($O->coupon_id != "") {
			$COUP = new Coupon($O->coupon_id);
			$O->applyCoupon($COUP);
        }
		$SYS = new Syspro_API();
		$O->syspro_status = trim($SYS->getOrderStatus($O->ID));
		$V = new View('order_detail.php');
		$V->bind('ORDER_ID', $O->ID);
		$V->bind('BILLING_ADDRESS', $O->billing_address);
		$V->bind('SHIPPING_ADDRESS', $O->shipping_address);
		$V->bind('PRODUCT_LIST', $O->getProducts());
		$V->bind('ORDER_TOTALS', $O->getTotals());
		$V->bind('O', $O);
		$V->bind('OH', new Order_Holder($O));
		$V->bind('CUSTOMER', $CUSTOMER);
		$V->bind('MS', new Message_Stack());
		$V->bind('INVOICE_LIST', $O->getInvoices());
		$V->bind('SALES_REP', new Sales_Rep($O->sales_rep_id));
		$shipping_method = array_pop($O->getShippingList())->name;
		$V->bind('SHIPPING_METHOD', $shipping_method);
		$VIEW = $V;
		$info = null;
		if(strlen($O->cc_name) > 0) {
			$last_four = substr(decrypt($O->cc_number), -4);
			$info[] = $O->cc_name;
			$info[] = "Credit Card Ending: " . $last_four;
			$info[] = decrypt($O->cc_expires_month) . ' / ' . decrypt($O->cc_expires_year);
			$info = nl2br(implode("\n", $info));
		} else {
			$term = new Payment_Term($O->syspro_invoice_code, 'syspro_code');
			$info = 'Invoice: ' . $term->name;
		}

		$VIEW->bind('PAYMENT_INFO', $info);
		break;
	}


	/**
	 * List all orders.
	 */
	default: {
		redirect('/report/');
		exit;
	}
}

require_once 'inc/layouts/wide.php';
?>