<?php
require_once dirname(__FILE__) . '/Controller.php';

class Search_Export_Controller extends Controller {
	public function export() {
		$this->_requireAdmin();

		//build up a Product_Finder for our subquery
		$PF = new Product_Finder();
		$PF->includeSysproOnly();

		$q = trim(get_var('q', null));
		if(strlen($q) > 0) {
			$PF->addQuery($q);
		}

		$stock_code = get_var('stock_code', null);
		$PF->addStockCode($stock_code);

		$C = new Category(get_var('cat', null));
		$PF->addCategory($C);
		$sql = $PF->getSQL(true);
		$SE = new Search_Exporter();
		$SE->setSubquery($sql);
		$SE->export();
		redirect('/admin/search_export/download/' . $SE->getHash());
	}

	public function download($hash) {
		$this->_requireAdmin();
		$download_name = 'batch_export-' . $hash . '.zip';
		$filename = '/tmp/' . $download_name;
		if(true == file_exists($filename)) {
				header("Content-Disposition: attachment; filename=" . $download_name . ";");
				header("Content-Length: " . filesize($filename));
				header("Content-Type: application/zip");
				readfile($filename);
		} else {
			header('HTTP/1.0 404 Not Found');
		}
	}
}
?>
