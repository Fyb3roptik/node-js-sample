<?php
require_once 'inc/global.php';

$action = request_var('action');

switch($action) {
	default: {
		$VIEW = 'category_selector.php';
		break;
	}
}

require_once 'layouts/category_selector.php';
?>