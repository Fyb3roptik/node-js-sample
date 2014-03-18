<?php
require 'inc/global.php';

$action = post_var('action');

switch($action) {
	case 'save_tax_rate': {
		$state = new State(intval(post_var('state_id')));
		if(true == xsrf_check() && true == $state->exists()) {
			$state->sales_tax = post_var('tax_rate');
			$state->write();
		}
		echo json_encode($state->dataDump());
		break;
	}
}
?>