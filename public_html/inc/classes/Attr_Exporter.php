<?php
require_once dirname(__FILE__) . '/Exporter.php';

class Attr_Exporter extends Exporter {
	const EXPORT_FILE = '/tmp/attribute_export.csv';
	protected $_data = array();
	protected$_headers = array();
	private $_master_attributes = array();

	public function export() {
		$this->_loadData();
		$this->_writeFile();
	}

	protected function _writeFile() {
		$csv_file = fopen(self::EXPORT_FILE, 'w');
		$headers = array('product_id', 'catalog_code', 'name');
		foreach($this->_master_attributes as $i => $attr_name) {
			$headers[] = 'a:' . $attr_name;
		}
		fputcsv($csv_file, $headers);
		foreach($this->_data as $product_id => $data) {
			$formatted_data = array($product_id, $data['catalog_code'], $data['name']);
			foreach($this->_master_attributes as $i => $attr_name) {
				$formatted_data[] = exists($attr_name, $data['attr'], null);
			}
			fputcsv($csv_file, $formatted_data);
		}
		fclose($csv_file);
	}

	private function _loadData() {
		$sql = SQL::get()->select('p.product_id, p.name, p.catalog_code, a.name as attr_name, av.value')
			->from('`products` p')
			->leftJoin('products_attributes pa', 'pa.product_id', 'p.product_id')
			->leftJoin('attributes a', 'a.attribute_id', 'pa.attribute_id')
			->leftJoin('attribute_values av', 'av.attribute_value_id', 'pa.attribute_value_id')
			->orderBy('p.name');

		$this->_addSubQuery($sql);

		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$this->_data[$rec['product_id']]['name'] = $rec['name'];
			$this->_data[$rec['product_id']]['catalog_code'] = $rec['catalog_code'];
			if(false == empty($rec['attr_name']) && false == empty($rec['value'])) {
				$this->_data[$rec['product_id']]['attr'][$rec['attr_name']] = $rec['value'];
				$this->_master_attributes[] = $rec['attr_name'];
			}
		}
		$this->_master_attributes = array_unique($this->_master_attributes);
		sort($this->_master_attributes);
	}

	public function getExportFile() {
		return self::EXPORT_FILE;
	}
}
?>