<?php
require_once dirname(__FILE__) . '/Controller.php';

class Denied_Controller extends Controller {
	public function index() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
		$V = new View('denied_index.php');
		$this->_setView($V);
	}
}
?>
