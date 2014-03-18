<?php
class Order_Report {
	public $title = 'Order Report';
	public $order_url;
	private $_fields = array();
	private $_product_fields = array();
	private $_sort_field;
	private $_direction;
	private $_filter_added = false;

	private $_unsortable_fields = array();
	protected static $_product_only = array(
		'product_name',
		'catalog_code',
		'product_quantity',
		'product_price',
		'product_image',
		'product_id',
		'product_price',
		'product_subtotal',
		'landed_cost',
		'gross_profit',
		'margin'
	);

	private $_filter_list = array();

	public function addField($field) {
		if(false == in_array($field, self::$_product_only)) {
			$this->_fields[] = $field;
		} else {
			$this->_product_fields[] = $field;
		}
	}

	public function setFilterList($filter_list) {
		if(true == is_array($filter_list)) {
			foreach($filter_list as $field => $value) {
				$this->setFilter($field, $value);
			}
		}
	}

	public function setFilter($field, $value) {
		$this->_filter_list[$field] = $value;
	}

	public function getFields() {
		return $this->_fields;
	}

	public function getProductFields() {
		return $this->_product_fields;
	}

	public function sortBy($field, $direction = 'DESC') {
		if(false == in_array($field, $this->_unsortable_fields)) {
			$this->_sort_field = $field;
			$this->_direction = $direction;
		}
	}

	public function getHeaders() {
		$headers = array();
		$fields = $this->getFields();
		foreach($fields as $field) {
			$headers[] = $this->_getHeader($field);
		}
		return $headers;
	}

	private function _getHeader($field) {
		$formatted_field = str_replace('_', ' ', $field);
		$url = $this->_getSortUrl($field);
		if(false == is_null($url)) {
			$formatted_field = sprintf('<a href="%s">%s</a>', $url, $formatted_field);
		}
		return $formatted_field;
	}

	private function _getSortUrl($field) {
		$url = null;
		if(false == in_array($field, $this->_unsortable_fields)) {
			$direction = $this->_getSortDirection($field);
			$url = sprintf('?sort=%s&amp;direction=%s', $field, $direction);
		}
		return $url;
	}

	private function _getSortDirection($field) {
		$direction = 'DESC';
		if($field == $this->_sort_field && $this->_direction == 'DESC') {
			$direction = 'ASC';
		}
		return $direction;
	}

	public function getSummary() {
		$summary_list = array();
		$summary_list['Order Count'] = $this->_getOrderCount();
		$summary_list['Total Sales'] = price_format($this->_getTotalSales());
		$summary_list['Freight Total'] = price_format($this->_getFreightTotal());
		$summary_list['Tax Total'] = price_format($this->_getTaxTotal());
		$summary_list['Total'] = price_format($this->_getTotal());

		return $summary_list;
	}

	private function _getOrderCount() {
		$sql = SQL::get()
			->select('count(order_id) as order_count')
			->from('(@subquery) as orderz0rz')
			->bind('subquery', $this->_getSubQuery());
		$query = db_query($sql);
		$count = 0;
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$count = $rec['order_count'];
		}
		return $count;
	}

	private function _getTotalSales() {
		$sql = SQL::get()
			->select('sum(subtotal) as total_sales')
			->from("order_subtotals")
			->where("order_id in (@subquery)")
			->bind('subquery', $this->_getSubQuery());
		$subtotal = 0.00;
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$subtotal = $rec['total_sales'];
		}
		return $subtotal;
	}

	private function _getFreightTotal() {
		$sql = SQL::get()
			->select('sum(total) as shipping_total')
			->from('order_shipping_totals')
			->where('order_id in (@subquery)')
			->bind('subquery', $this->_getSubQuery());
		$query = db_query($sql);
		$freight_total = 0.00;
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$freight_total = $rec['shipping_total'];
		}
		return $freight_total;
	}

	private function _getTaxTotal() {
		$tax_total = 0;
		$sql = SQL::get()
			->select('sum(total) as tax_total')
			->from('order_tax_totals')
			->where('order_id in (@subquery)')
			->bind('subquery', $this->_getSubQuery());
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$tax_total = $rec['tax_total'];
		}
		return $tax_total;
	}

	private function _getTotal() {
		$total = 0;
		$sql = SQL::get()
			->select('sum(total) as total')
			->from('order_totals')
			->where('order_id in (@subquery)')
			->bind('subquery', $this->_getSubQuery());
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$total = $rec['total'];
		}
		return $total;
	}

	public function getOrders() {
		$orders = array();
		$query = db_query($this->_getSQL());
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$item = new Order_Report_Item( new Order($rec['order_id']));
			if(true == isset($this->order_url)) {
				$item->setLinkFormat($this->order_url);
			}
			$orders[] = $item;
		}
		return $orders;
	}

	private function _getSQL() {
		$sql = $this->_getSubQuery();
		if(false == $this->_filter_added) {
			$sql->limit(250);
		}
		return $sql;
	}

	private function _getSubQuery() {
		$sql = SQL::get()
			->select('DISTINCT o.order_id')
			->where("o.type = '@order_type'")
			->bind('order_type', Order::TYPE_ORDER)
			->from('orders o');
		$this->_addSort($sql);
		$this->_addFiltersToSQL($sql);
		return $sql;
	}

	private function _addSort(SQL_Select $sql) {
		if(true == isset($this->_sort_field)) {
			$sort_function = '_sort_by_' . $this->_sort_field;
			if(true == method_exists($this, $sort_function)) {
				$this->$sort_function($sql);
			} else {
				$this->_default_sort($sql);
			}
		}
		$sql->orderBy('date_purchased', SQL_SELECT::DESC);
	}

	private function _addFiltersToSQL(SQL_Select $sql) {
		foreach($this->_filter_list as $filter_field => $filter_value) {
			$filter_method = '_filter_by_' . $filter_field;
			if(true == method_exists($this, $filter_method) && false == empty($filter_value)) {
				$this->$filter_method($sql, $filter_value);
				$this->_filter_added = true;
			}
		}
	}

	############################################################
	# Thes methods filter the results based on given criteria. #
	############################################################

	private function _filter_by_sales_rep(SQL_Select $sql, $sales_rep_id) {
		$sql->where("o.sales_rep_id = '@sales_rep'")
			->bind('sales_rep', $sales_rep_id);
	}

	private function _filter_by_min_date(SQL_Select $sql, $min_date) {
		$sql->where("o.date_purchased >= '@min_date'")
			->bind('min_date', date("Y-m-d 00:00:00", strtotime($min_date)));
	}

	private function _filter_by_max_date(SQL_Select $sql, $max_date) {
		$sql->where("o.date_purchased <= '@max_date'")
			->bind('max_date', date('Y-m-d 23:59:59', strtotime($max_date)));
	}

	private function _filter_by_coupon_code(SQL_Select $sql, $coupon_code) {
		$sql->leftJoin('coupons cou', 'o.coupon_id', 'cou.coupon_id')
			->where("cou.code like '%@code%'")
			->bind('code', $coupon_code);
	}

	private function _filter_by_ubd_code(SQL_Select $sql, $ubd_code) {
		$sql->leftJoin('order_line_items oli', 'oli.order_id', 'o.order_id')
			->leftJoin('order_products op', 'oli.item_id', 'op.item_id')
			->where("op.ubd_code = '@ubd_code'")
			->bind('ubd_code', $ubd_code);
	}

	private function _filter_by_customer_id(SQL_Select $sql, $customer_id) {
		$sql->where("o.customer_id = '@customer'")
			->bind('customer', $customer_id);
	}

	private function _filter_by_city(SQL_Select $sql, $city) {
		$sql->where("(o.billing_city = '@city' OR o.shipping_city = '@city')")
			->bind('city', $city);
	}

	private function _filter_by_state(SQL_Select $sql, $state) {
		$sql->where("(o.billing_state = '@state' OR o.shipping_state = '@state')")
			->bind('state', $state);
	}

	private function _filter_by_zip(SQL_Select $sql, $zip_code) {
		$sql->where("(o.billing_zip_code = '@zip_code' OR o.shipping_zip_code = '@zip_code')")
			->bind('zip_code', $zip_code);
	}

	private function _filter_by_phone_number(SQL_Select $sql, $phone) {
		$sql->where("(shipping_phone = '@phone' OR billing_phone = '@phone')")
			->bind('phone', $phone);
	}

	private function _filter_by_customer_name(SQL_Select $sql, $name) {
		$sql->where("(shipping_name LIKE '%@name%' OR billing_name LIKE '%@name%')")
			->bind('name', $name);
	}

	###########################################################################
	# Here be a bunch o' _sort methods for sorting the results of the report. #
	###########################################################################

	private function _default_sort(SQL_Select $sql) {
		$sql->orderBy($this->_sort_field, $this->_direction);
	}

	private function _sort_by_sales_rep(SQL_Select $sql) {
		$sql->leftJoin('sales_reps sr', 'o.sales_rep_id', 'sr.sales_rep_id')
			->orderBy('sr.name', $this->_direction);
	}

	private function _sort_by_coupon_code(SQL_Select $sql) {
		$sql->leftJoin('coupons c', 'o.coupon_id', 'c.coupon_id')
			->orderBy('c.code', $this->_direction);
	}

	private function _sort_by_total(SQL_Select $sql) {
		$sql->leftJoin('order_totals ot', 'ot.order_id', 'o.order_id')
			->orderBy('ot.total', $this->_direction);
	}

	private function _sort_by_subtotal(SQL_Select $sql) {
		$sql->leftJoin('order_subtotals ost', 'ost.order_id', 'o.order_id')
			->orderBy('ost.subtotal', $this->_direction);
	}

	private function _sort_by_shipping_cost(SQL_Select $sql) {
		$sql->leftJoin('order_shipping_totals ost', 'ost.order_id', 'o.order_id')
			->orderBy('ost.total', $this->_direction);
	}

	private function _sort_by_tax(SQL_Select $sql) {
		$sql->leftJoin('order_tax_totals ott', 'ott.order_id', 'o.order_id')
			->orderBy('ott.total', $this->_direction);
	}
}
?>
