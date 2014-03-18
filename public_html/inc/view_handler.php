<?php
if(is_null($VIEW)) {
	$VIEW = "home.php";
}
if(true == is_string($VIEW)) {
	require_once get_view();
} elseif(true == is_object($VIEW)) {
	$VIEW->render();
}
?>