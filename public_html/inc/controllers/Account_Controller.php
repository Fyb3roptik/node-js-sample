<?php
require_once dirname(__FILE__) . '/Controller.php';

class Account_Controller extends Controller {
	public function edit() {
		$this->_setTemplate(new Template('ajax.php'));
		$V = new View('account_edit.php');
		$V->bind('CUSTOMER', $this->_user);
		$this->_setView($V);
	}
}
?>
