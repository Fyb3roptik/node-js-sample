<?php
require_once 'inc/global.php';

$VIEW = 'order_detail.php';

$O = new Order(exists('order', $_GET, 0));

if(true == $O->exists() && intval($O->getCustomerID()) !== intval($CUSTOMER->getID())) {
	$VIEW = sha1(time()) . '.php';
}

$PRINT = true;

require_once 'inc/layouts/popup.php';
?>