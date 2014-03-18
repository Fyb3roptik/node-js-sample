<?php
require_once 'inc/global.php';

ini_set('max_execution_time', 600);

if(false == xsrf_check()) {
	exit;
}

$action = post_var('action');
switch($action) {
	case 'process_records': {
		$CI = new Catalog_Importer('product_import');
		$limit = rand(10,19);
		$limit = rand(1,100);
		$limit = rand(25, 30);
		$CI->importTableProducts();
		echo json_encode(array('status' => 'done'));
		break;
	}

	case 'poll_progress': {
		$CI = new Catalog_Importer('product_import');
		$total_records = $CI->getRecordCount(null);

		$percentage = floor((($CI->getRecordCount(1) / $total_records)*100));

		$return_vals = array(	'unprocessed_records' => $CI->getRecordCount(0),
						'percent_finished' => $percentage,
						'processed_records' => $CI->getRecordCount(1),
						'total_records' => $CI->getRecordCount(null));
		echo json_encode($return_vals);
		break;
	}
}