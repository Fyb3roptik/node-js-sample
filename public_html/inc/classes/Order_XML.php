<?php
require_once dirname(__FILE__) . '/Order.php';

class Order_XML {
	private $_order;
	private $_customer;
	private $_sales_rep;
	private $_customer_sales_rep;

	const GEO_RESIDENTIAL = "01";
	const GEO_COMMERCIAL = "00";

	const INTERNET_SALES_REP = "027";

	const NON_TAX_EXEMPT = 'N';
	const TAX_EXEMPT = 'E';

	public function __construct(Order $O) {
		if(false == $O->exists()) {
			throw new Exception("Bad order.");
		}
		$this->_order = $O;
	}

	public function asXML() {
		$order_id = $this->_order->ID;
		$order_xml = new SimpleXMLElement('<order></order>');
		$order_xml->addChild('process_type', 1);
		$order_xml->addChild('salesperson', $this->_getSalesRepID());
		$order_xml->addChild('entered_by', $this->_getEnteredByID());
		$order_xml->addChild('warehouse', $this->_order->warehouse);
		$order_xml->addChild('branch', '00');
		$order_xml->addChild('geographic_area', $this->_getGeoCode());
		$order_xml->addChild('currency', '$');

		$order_xml->addChild('customer_id', $this->_getCustomer()->ID);
		$order_xml->addChild('customer_name', $this->_getCustomer()->name);
		$order_xml->addChild('email', $this->_getCustomer()->email);
		$order_xml->addChild('secondary_email', $this->_getCustomer()->secondary_email);

		$billing_node = $order_xml->addChild('billing_address');
		$billing_data = $this->_order->billing_address->dump();
		foreach($billing_data as $key => $value) {
			$billing_node->addChild($key, $value);
		}

		$shipping_node = $order_xml->addChild('shipping_address');
		$shipping_data = $this->_order->shipping_address->dump();
		foreach($shipping_data as $key => $value) {
			$shipping_node->addChild($key, $value);
		}
		$shipping_node->addChild('email', $this->_order->shipping_email);

		$order_xml->addChild('order_id', $this->_order->ID);
		$order_xml->addChild('order_type', 'I');

		$order_xml->addChild('sales_note', substr($this->_order->sales_note, 0, 100));
		$order_xml->addChild('note', substr($this->_order->note, 0, 100));

		$order_detail = $order_xml->addChild('order_detail');
		foreach($this->_order->getProducts() as $i => $product) {
			$line_item = $order_detail->addChild('line_item');
			$P = new Product($product->getProductID());
			$line_item->addChild('stock_code', $product->getCatalogCode());
			$line_item->addChild('quantity', $product->quantity);
			$line_item->addChild('price', $product->getFinalUnitPrice());
			$line_item->addChild('cost', $P->getLandedCost());
			if(false == is_null($product->getUtilityCode())) {
				$line_item->addChild('ubd_code', $product->getUtilityCode());
			}
		}

		$misc_detail = $order_xml->addChild('misc_detail');
		foreach($this->_order->getMiscCharges() as $charge) {
			$line_item = $misc_detail->addChild('line_item');
			$line_item->addChild('desc_code', $charge->name);
			$line_item->addChild('value', $charge->unit_price);
		}

		if($this->_order->getDiscountTotal() < 0) {
			$line_item = $misc_detail->addChild('line_item');
			$line_item->addChild('desc_code', '75');
			$line_item->addChild('value', $this->_order->getDiscountTotal());
		}

		$shipping_info = $order_xml->addChild('shipping_info');
		$shipping_info->addChild('shipping_instructions', $this->_order->syspro_shipping_code);
		$shipping_info->addChild('ship_date', $this->_getShipDate());
		$shipping_info->addChild('account_number', $this->_order->freight_account);

		$box_list = $shipping_info->addChild('box_list');
		foreach($this->_getBoxList() as $box) {
			$box_list->addChild('box', $box);
		}

		$shipping_info->addChild('freight_charge', floatval($this->_order->getShippingTotal()));
		$shipping_info->addChild('ship_complete', abs(intval($this->_order->ship_complete)));
		$order_xml->addChild('po_number', $this->_order->po_number);

		$order_xml->addChild('invoice_terms', $this->_order->syspro_invoice_code);

		$this->_addCouponCode($order_xml);

		$this->_addTransaction($order_xml);

		return $order_xml->asXML();
	}

	private function _getShipDate() {
		$ship_date = $this->_order->date_purchased;
		if(strlen($this->_order->ship_date) > 0) {
			$ship_date = $this->_order->ship_date;
		}
		$formatted_ship_date = date('m/d/Y', strtotime($ship_date));
		return $formatted_ship_date;
	}

	private function _getBoxList() {
		$box_list = array();

		$box_recommender = new Box_Recommender(new Box_Finder());
		foreach($this->_order->getProducts() as $product) {
			$P = new Product($product->getProductID());
			$box_recommender->addProduct($P, $product->quantity);
		}

		try {
			foreach($box_recommender->recommend(false) as $box) {
				$dims = $box->length . 'x' . $box->width . 'x' . $box->height;
				$box_list[] = $dims;
			}
		} catch(Giant_Order_Exception $e) {
			$box_list = array(Box_Recommender::getDefaultBox(false));
		}

		return $box_list;
	}

	private function _addCouponCode($xml) {
		$coupon = new Coupon($this->_order->coupon_id);
		if(true == $coupon->exists()) {
			$xml->addChild('coupon_code', $coupon->code);
		}
	}

	private function _addTransaction($order_xml) {
		if('01' == $this->_order->syspro_invoice_code) {
			$transaction = $order_xml->addChild('transaction');
			$transaction->addChild('account_name', $this->_order->cc_name);
			$transaction->addChild('account_number', decrypt($this->_order->cc_number));
			$transaction->addChild('account_cvv', decrypt($this->_order->cc_ccv));
			$transaction->addChild('expires_month', decrypt($this->_order->cc_expires_month));
			$transaction->addChild('expires_year', decrypt($this->_order->cc_expires_year));
			$transaction->addChild('approved_amount', number_format($this->_order->getTotal(), 2, '.', ''));
			$transaction->addChild('approval_code', $this->_order->cc_auth_code);
			$transaction->addChild('transaction_id', $this->_order->cc_trans_id);
			$transaction->addChild('transaction_type', 'AUTH_ONLY');
		}
	}

	private function _getCustomer() {
		if(false == isset($this->_customer)) {
			$this->_customer = new Customer($this->_order->customer_id);
		}
		return $this->_customer;
	}

	private function _getSalesRep() {
		if(false == isset($this->_sales_rep)) {
			$this->_sales_rep = new Sales_Rep($this->_order->sales_rep_id);
		}
		return $this->_sales_rep;
	}

	private function _getCustomerSalesRep() {
		if(false == isset($this->_customer_sales_rep)) {
			$customer = $this->_getCustomer();
			$this->_customer_sales_rep = new Sales_Rep($customer->sales_rep);
		}
		return $this->_customer_sales_rep;
	}

	private function _getEnteredByID() {
		$entered_by_id = self::INTERNET_SALES_REP;
		$sales_rep = $this->_getSalesRep();
		if(true == $sales_rep->exists()) {
			$entered_by_id = $sales_rep->syspro_id;
		}
		return $entered_by_id;
	}

	private function _getSalesRepID() {
		$sales_rep_id = self::INTERNET_SALES_REP;
		$customer_sales_rep = $this->_getCustomerSalesRep();
		if(true == $customer_sales_rep->exists()) {
			$sales_rep_id = $customer_sales_rep->syspro_id;
		}
		return $sales_rep_id;
	}

	private function _getGeoCode() {
		$geo_code = self::GEO_RESIDENTIAL;
		if(false == empty($this->_order->getShippingAddress()->company)) {
			$geo_code = self::GEO_COMMERCIAL;
		}
		return $geo_code;
	}
}
?>
