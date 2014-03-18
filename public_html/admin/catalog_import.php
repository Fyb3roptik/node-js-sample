<?php
require_once 'inc/global.php';

if(false == $ADMIN->hasPermission('import_products')) {
	redirect('/admin/denied/');
	exit;
}

FB::group('/admin/catalog_import.php');

define('PRODUCT_IMPORT_TABLE', 'product_import', false);

$action = request_var('action');
FB::log($action, "Action");
switch($action) {
	case 'import_catalog_csv': {
		$VIEW = 'catalog_import_index.php';

		$csv_data = exists('catalog_csv', $_FILES, array());
		try {
			$CI = new Catalog_Importer(PRODUCT_IMPORT_TABLE, $csv_data['tmp_name']);
			$CI->loadFile();
			FB::log($CI, 'CI Object');
		} catch(Exception $e) {
			echo $e->getMessage();
			FB::log($e->getMessage(), "CI Exception");
		}
		break;
	}

	/**
	 * See about importing the images in the upload-images folder.
	 */
	case 'batch_image_import': {
		$VIEW = 'catalog_import_index.php';
		try {
			$PIBI = new Product_Image_Batch_Importer(DIR_BATCH_IMAGE_UPLOAD);
			$PIBI->processImages();
			$CI = new Catalog_Importer(PRODUCT_IMPORT_TABLE);
		} catch(Exception $e) {
			$MS->add('catalog_import', $e->getMessage(), MS_ERROR);
		}
		break;
	}

	default: {
		$VIEW = 'catalog_import_index.php';
		try {
			$PIBI = new Product_Image_Batch_Importer(DIR_BATCH_IMAGE_UPLOAD);
			$CI = new Catalog_Importer(PRODUCT_IMPORT_TABLE);
		} catch(Exception $e) {
			$MS->add('catalog_import', $e->getMessage(), MS_ERROR);
		}
		break;
	}
}

require_once 'layouts/default.php';
FB::groupEnd();
?>