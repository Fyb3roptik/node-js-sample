<?php
require_once 'inc/global.php';
FB::group("/products.php");

$VIEW = 'product_list.php';
$LAYOUT = 'layouts/default.php';

$action = exists('action', $_REQUEST, null);

$PAGE_TITLE = 'All Products'; //just a default value

FB::log($action, 'products.php action');

$PRODUCT_WIDGET_LIST = new Global_Widget_List();

switch(strtolower($action)) {

	case 'view_product': {
		$product_id = exists('product', $_GET, 0);
		$UM = new Utility_Modifier(new Utility_Mod_Finder());
		$P = new Product($product_id);
		if(intval($P->sales_only) > 0 && false == ($CUSTOMER instanceof Sales_Rep)) {
			redirect('/category/');
			exit;
		}
		$LAYOUT_TITLE = $P->name;
		if(strlen($P->title) > 0) {
			$LAYOUT_TITLE = $P->title;
		}
		$UM->modify($P, session_var('ubd_zip'));
		$VIEW = 'product_detail.php';
		$category_list = $P->getCategories();

		$C = Breadcrumb_Parser::getCategory($_SERVER['HTTP_REFERER']);
		if(false == is_null($C)) {
			$BREADCRUMB = $C->breadCrumb();
		} elseif(count($category_list) > 0) {
			$BREADCRUMB = array_pop($category_list)->breadCrumb();
		}
		$PWL = new Product_Widget_List($P);
		try {
			if(count($PWL->getWidgets()) > 0) {
				$PRODUCT_WIDGET_LIST = $PWL;
			}
		} catch(Exception $e) {
			/* do nothing */
		}
		$META_LIST = $P->getMetaTags();
		$PRICE_LIST = new Html_Template('inc/modules/product_price_table.php');
		$PRICE_LIST->bind('P', $P);
		break;
	}

	/**
	 * Default action is to check the get vars and search for products.
	 */
	default: {
		redirect(LOC_ALL_PRODUCTS);
		break;
	}
}

if(count($PRODUCT_WIDGET_LIST) > 0) {
	$WIDGET_LIST = $PRODUCT_WIDGET_LIST;
}

FB::log($WIDGET_LIST, 'WIDGET LIST');

require_once $LAYOUT;
FB::groupEnd();
?>