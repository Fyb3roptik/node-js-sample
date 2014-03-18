<?php
require_once dirname(__FILE__) . '/Exporter.php';

class Price_Exporter extends Exporter {
	const EXPORT_FILE = '/tmp/catalog_prices.csv';
	
	protected $_headers;
	protected $_data = array();
	protected $_max_price_count = 0;

	public function export() {
		$this->_loadData();
		$this->_loadHeaders();
		$this->_writeFile();
	}

	private function _loadHeaders() {
		for($i = 0; $i <= $this->_max_price_count; $i++) {
			$this->_headers[] = 'min_quantity_' . $i;
			$this->_headers[] = 'markup_' . $i;
		}
	}

	private function _loadData() {
		$sql = SQL::get()
			->select('p.product_id, p.catalog_code,
				p.quantity AS base_qty, p.unit_measure as unit')
			->from('products p');
		$this->_addSubQuery($sql);

		$query = db_query($sql);
		$global_overhead = floatval(Config::get()->value('global_product_overhead'));
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$P = Object_Factory::OF()->newObject('Product', $rec['product_id']);
			$BCL = new Base_Cost_Lookup($P->ID);
			$rec['base_cost'] = $BCL->getActualCost();
			$rec['fudge'] = floatval($P->fudge_factor);
			$rec['fudge_type'] = $P->fudge_type;
			$rec['global_overhead'] = $global_overhead;
			$rec['landed_cost'] = $P->getLandedCost();
			$rec['sales_cost_override'] = floatval($BCL->admin_override);

			if(false == isset($this->_headers)) {
				$this->_headers = array_keys($rec);
			}

			foreach($P->getPrices() as $i => $PQD) {
				$rec['min_quantity_' . $i] = intval($PQD->min_quantity);
				$rec['markup_' . $i] = floatval($PQD->markup);
			}
			$this->_data[] = $rec;

			if($i > $this->_max_price_count) {
				$this->_max_price_count = $i;
			}
		}
	}

	public function getExportFile() {
		return self::EXPORT_FILE;
	}
}
?>