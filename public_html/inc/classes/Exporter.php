<?php
require_once dirname(__FILE__) . '/Exporter_Interface.php';

abstract class Exporter implements Exporter_Interface {
	protected $_subquery;
	protected $_headers;
	protected $_data = array();
	protected $_export_file = '/tmp/export.csv';

	public function setSubquery(SQL_Select $sql) {
		$this->_subquery = $sql;
	}

	protected function _writeFile() {
		$export_file = fopen($this->getExportFile(), 'w');
		fputcsv($export_file, $this->_headers);
		foreach($this->_data as $i => $data) {
			fputcsv($export_file, $data);
		}
		fclose($export_file);
	}

	/**
	 * Gets the latest time the file was exported.
	 * @return 0 if never exported / some time if exported.
	 */
	public function getLastExported() {
		$last_updated = 0;
		if(true == file_exists($this->getExportFile())) {
			$last_updated = filemtime($this->getExportFile());
		}
		return $last_updated;
	}

	protected function _addSubQuery(SQL_Select $sql) {
		if(true == isset($this->_subquery)) {
			$sql->where("p.product_id in (@subquery)")
				->bind('subquery', $this->_subquery);
		}
	}

	public function getExportFile() {
		return $this->_export_file;
	}
}
?>
