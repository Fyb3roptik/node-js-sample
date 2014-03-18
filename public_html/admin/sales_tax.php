<?php
require_once 'inc/global.php';

if(false == $ADMIN->hasPermission('edit_tax')) {
	redirect('/admin/denied/');
	exit;
}

$VIEW = 'sales_tax.php';
//do nothing but point to the sales_tax.php view.
$sql = "SELECT state_id
	FROM states
	ORDER BY state ASC";
$query = db_query($sql);
$STATE_LIST = array();
while($state = $query->fetch_assoc()) {
	$STATE_LIST[] = new State($state['state_id']);
}

require_once 'inc/layouts/default.php';
?>