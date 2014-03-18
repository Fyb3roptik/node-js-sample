<?php
require_once 'Controller.php';

class Catalog_Export_Controller extends Controller {
	
	public function index() {
		$this->_requireAdmin(); //should only ever be an admin that runs this.
		$this->_setAdminTemplate();
		$V = new View('exporter_index.php');
		$SFE = new Shopping_Feed_Exporter();
		$last_generated = intval($SFE->lastGenerated());
		FB::log($last_generated, "last generated");
		$show_links = false;
		if($last_generated > 0) {
			$show_links = true;
			$last_generated = date('Y-m-d H:i:s', $last_generated);
			$V->bind('LAST_GENERATED', $last_generated);
		}
		FB::log($show_links, 'Show Links');
		$V->bind('SHOW_LINKS', $show_links);

		$PE = new Price_Exporter();
		$price_generated = intval($PE->getLastExported());
		$show_price_links = false;
		if($price_generated > 0) {
			$V->bind('PRICE_GENERATED', $price_generated);
			$show_price_links = true;
		}
		$V->bind('SHOW_PRICE_LINKS', $show_price_links);

		$AE = new Attr_Exporter();
		$attr_generated = intval($AE->getLastExported());
		$show_attr_links = false;
		if($attr_generated > 0) {
			$V->bind('ATTR_GENERATED', $attr_generated);
			$show_attr_links = true;
		}
		$V->bind('SHOW_ATTR_LINKS', $show_attr_links);

		$show_pc_links = false;
		$PCE = new Product_Category_Exporter();
		if(intval($PCE->getLastExported()) > 0) {
			$show_pc_links = true;
		}
		$V->bind('SHOW_PC_LINKS', $show_pc_links);

		$this->_setView($V);
	}

	public function generateFeed() {
		$this->_requireAdmin();
		$SFE = new Shopping_Feed_Exporter();
		$return_vals = array('success' => true);
		$SFE->export();
		echo json_encode($return_vals);
		exit;
	}

	public function generatePrices() {
		$this->_requireAdmin();
		$PE = new Price_Exporter();
		$return_vals = array('success' => true);
		$PE->export();
		echo json_encode($return_vals);
		exit;
	}
	
	public function generateAttr() {
		$this->_requireAdmin();
		$PE = new Attr_Exporter();
		$return_vals = array('success' => true);
		$PE->export();
		echo json_encode($return_vals);
		exit;
	}

	public function generateProdCat() {
		$this->_requireAdmin();
		$PCE = new Product_Category_Exporter();
		$return = array('success' => true);
		$PCE->export();
		echo json_encode($return);
		exit;
	}

	public function generateProducts() {
		$this->_requireAdmin();
		$return = array('success' => true);
		$PE = new Product_Exporter();
		$PE->export();
		echo json_encode($return);
		exit;
	}

	public function generateProductTabs() {
		$this->_requireAdmin();
		$return = array('success' => true);
		$PTE = new Product_Tab_Exporter();
		$PTE->export();
		echo json_encode($return);
		exit;
	}

	public function processPricing() {
		$this->_requireAdmin();
		$import_file = exists('import_file', $_FILES);
		$MS = new Message_Stack();
		$type_parts = explode('/', $import_file['type']);
		if('text' !== $type_parts[0]) {
			$MS->add('price_import', 'Error importing CSV, wrong file type.', MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		try {
			$PI = new Price_Importer();
			$PI->import($import_file['tmp_name']);
		} catch(Exception $e) {
			$MS->add('price_import', 'EXCEPTION: ' . $e->getMessage(), MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		$MS->add('price_import', 'Prices successfully imported!', MS_SUCCESS);
		redirect('/admin/exporter/');
		exit;
	}

	public function processProducts() {
		$this->_requireAdmin();
		$import_file = exists('import_file', $_FILES);
		$MS = new Message_Stack();
		$type_parts = explode('/', $import_file['type']);
		if('text' !== $type_parts[0]) {
			$MS->add('products', 'Error importing CSV, wrong file type.', MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		try {
			$FI = new Product_Importer();
			$FI->import($import_file['tmp_name']);
		} catch(Exception $e) {
			$MS->add('products', 'Exception: ' . $e->getMessage(), MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		$MS->add('products', 'Products successfully imported!', MS_SUCCESS);
		redirect('/admin/exporter/');
		exit;
	}

	public function processProductTabs() {
		$this->_requireAdmin();
		$import_file = exists('import_file', $_FILES);
		$MS = new Message_Stack();
		$type_parts = explode('/', $import_file['type']);
		if('text' !== $type_parts[0]) {
			$MS->add('product_tabs', 'Error importing CSV, wrong file type.', MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		try {
			$FI = new Product_Tab_Importer();
			$FI->import($import_file['tmp_name']);
		} catch(Exception $e) {
			$MS->add('product_tabs', 'Exception: ' . $e->getMessage(), MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		$MS->add('product_tabs', 'Product tabs successfully imported!', MS_SUCCESS);
		redirect('/admin/exporter/');
		exit;
	}

	public function processProdCat() {
		$this->_requireAdmin();
		$import_file = exists('import_file', $_FILES);
		$MS = new Message_Stack();
		$type_parts = explode('/', mime_content_type($import_file['tmp_name']));
		if('text' !== $type_parts[0]) {
			$MS->add('pc_import', 'Error importing CSV, wrong file type. "' . $import_file['type'] . '"', MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		try {
			$PI = new Product_Category_Importer();
			$PI->import($import_file['tmp_name']);
		} catch(Exception $e) {
			$MS->add('pc_import', 'EXCEPTION: ' . $e->getMessage(), MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		$MS->add('pc_import', 'Products/Categories successfully imported!', MS_SUCCESS);
		redirect('/admin/exporter/');
		exit;
	}

	public function processAttributes() {
		$this->_requireAdmin();
		$import_file = exists('import_file', $_FILES);
		$MS = new Message_Stack();
		$type_parts = explode('/', $import_file['type']);
		if('text' !== $type_parts[0]) {
			$MS->add('attr_import', 'Error importing CSV, wrong file type.', MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		$START_TIME = microtime(true);
		try {
			$AI = new Attr_Importer();
			$AI->import($import_file['tmp_name']);
		} catch(Exception $e) {
			$MS->add('attr_import', 'EXCEPTION: ' . $e->getMessage(), MS_ERROR);
			redirect('/admin/exporter/');
			exit;
		}
		$MS->add('attr_import', 'Attributes successfully imported!', MS_SUCCESS);
		$memory_usage = (memory_get_peak_usage() / 1024 / 1024);
		error_log("Attribute importer used: " . $memory_usage . 'M');
		error_log("Script Runtime: " . (microtime(true) - $START_TIME));
		redirect('admin/exporter');
		exit;
	}

	public function download($file = 'shopping') {
		$this->_requireAdmin();
		$file_lookup = array(
				'shopping' => Shopping_Feed_Exporter::SHOPPING_FEED_EXPORT_FILE,
				'pricing' => Price_Exporter::EXPORT_FILE,
				'attributes' => Attr_Exporter::EXPORT_FILE,
				'product_category' => Product_Category_Exporter::EXPORT_FILE,
				'products' => Product_Exporter::EXPORT_FILE,
				'product_tabs' => Product_Tab_Exporter::EXPORT_FILE
			);
		if(true == array_key_exists($file, $file_lookup)) {
			$download_file = $file_lookup[$file];
			if(true == file_exists($download_file)) {
				$pathinfo = pathinfo($download_file);
				$filename = $pathinfo['basename'];
				header("Content-Disposition: attachment; filename=" . $filename . ";");
				header("Content-Length: " . filesize($download_file));
				header("Content-Type: text/csv");
				readfile($download_file);
			} else {
				header('HTTP/1.0 404 Not Found');
			}
		} else {
			header('HTTP/1.0 404 Not Found');
		}
		exit;
	}

	public function _setAdminTemplate() {
		global $LAYOUT_TITLE;
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		$this->_template->bind('ADMIN', $this->_user);
	}
}
?>