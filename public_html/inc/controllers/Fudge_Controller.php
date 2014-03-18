<?php
require_once dirname(__FILE__) . '/Controller.php';

class Fudge_Controller extends Controller {
	public function index() {
		$this->_configureTemplate();
		$V = new View('fudge_index.php');
		$V->bind('PRODUCT_OVERHEAD', floatval(Config::get()->value('global_product_overhead')));
		$this->_setView($V);
	}

	private function _configureTemplate() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}

	public function process() {
		Config::get()->set('global_product_overhead', floatval(post_var('global_product_overhead')));
		redirect('/admin/fudge/');
	}
}
?>