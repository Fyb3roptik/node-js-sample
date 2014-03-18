<?php
require_once dirname(__FILE__) . '/Object.php';

class Invoice_Detail extends Object {
	protected $_table = 'invoice_detail';
	protected $_table_id = 'invoice_detail_id';
	protected $_get_hooks = array('stock_code' => '_get_stock_code');

	const TYPE_FREIGHT = '_FRT';
	const TYPE_MISC = '_OTH';

	const MISC_DETAIL_ITEM_NO = 'MISC';
	const MISC_DESCRIPTION = 'Misc. Charge';

	protected function _get_stock_code($stock_code) {
		if(self::TYPE_MISC == $this->product_class) {
			$stock_code = self::MISC_DETAIL_ITEM_NO;
		}
		return $stock_code;
	}

	public function getDescription() {
		$description = null;
		if(self::TYPE_MISC == $this->product_class) {
			$description = self::MISC_DESCRIPTION;
		} else {
			$P = new Product($this->stock_code, 'catalog_code');
			if(true == $P->exists()) {
				$description = $P->name;
			} else {
				$description = $this->stock_code;
			}
		}
		return $description;
	}

	public function getUnitPrice() {
		$qty_invoiced = $this->qty_invoiced;
		if(0 == intval($qty_invoiced)) {
			$qty_invoiced = 1;
		}
		$unit_price = $this->net_sales_value / $qty_invoiced;
		return $unit_price;
	}
}
?>