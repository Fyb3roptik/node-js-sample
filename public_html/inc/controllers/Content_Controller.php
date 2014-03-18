<?php
require_once 'Controller.php';

class Content_Controller extends Controller {
	public function index() {
		$this->_configure();
		$V = new View('content_index.php');
		$this->_setView($V);
	}

	public function editHome() {
		$this->_configure();
		$V = new View('content_form.php');
		$V->bind('TITLE', 'Edit Home Page');
		$CR = new Config_Record('HOME_PAGE', 'config_key');
		$CR->config_key = 'HOME_PAGE';
		$V->bind('CR', $CR);
		$this->_setView($V);
	}

	public function editFooter() {
		$this->_configure();
		$V = new View('content_form.php');
		$V->bind('TITLE', 'Edit Global Footer');
		$CR = new Config_Record('GLOBAL_FOOTER', 'config_key');
		$CR->config_key = 'GLOBAL_FOOTER';
		$V->bind('CR', $CR);
		$this->_setView($V);
	}

	public function processContent() {
		$this->_requireAdmin();
		$config_key = post_var('config_key');
		if(false == is_null($config_key)) {
			$CR = new Config_Record($config_key, 'config_key');
			$CR->config_key = $config_key;
			$CR->config_text = post_var('config_text');
			$CR->write();
		}
		redirect('/admin/content/');
		exit;
	}

	private function _configure() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}
}
?>