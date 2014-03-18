<?php
require_once dirname(__FILE__) . '/Attr_Exporter.php';
require_once dirname(__FILE__) . '/Price_Exporter.php';
require_once dirname(__FILE__) . '/Product_Category_Exporter.php';
require_once dirname(__FILE__) . '/Product_Exporter.php';
require_once dirname(__FILE__) . '/Product_Tab_Exporter.php';

class Search_Exporter {
	private $_exporter_list;
	private $_subquery;

	/**
	 * For dependency injection.
	 */
	public function setExporterList(array $exporter_list) {
		$this->_exporter_list = $exporter_list;
	}

	/**
	 * Sets the product-specific subquery for all the exporters.
	 */
	public function setSubquery($subquery) {
		$this->_subquery = $subquery;
		$exporters = $this->_getExporters();
		foreach($exporters as $exporter) {
			$exporter->setSubquery($subquery);
		}
	}

	/**
	 * Returns the list of exporters to use.
	 */
	private function _getExporters() {
		if(false == isset($this->_exporter_list)) {
			$this->_exporter_list = $this->_getDefaultExporters();
		}
		return $this->_exporter_list;
	}

	/**
	 * Returns the default list of exporters to use.
	 */
	private function _getDefaultExporters() {
		$exporter_list = array(
			new Attr_Exporter(),
			new Price_Exporter(),
			new Product_Category_Exporter(),
			new Product_Exporter(),
			new Product_Tab_Exporter()
		);
		return $exporter_list;
	}

	public function export() {
		$zip_file_name = $this->_getZipFileName();
		foreach($this->_getExporters() as $exporter) {
			$exporter->export();
			$command = "zip " . $zip_file_name . " " . $exporter->getExportFile();
			exec($command);
		}
	}

	private function _getZipFileName() {
		$hash = $this->getHash();
		$filename = '/tmp/batch_export-' . $hash . '.zip';
		return $filename;
	}

	public function getHash() {
		$hash = substr(sha1($this->_subquery), 0, 16);
		return $hash;
	}
}
?>
