<?php
require_once dirname(__FILE__) . '/../classes/Template.php';
require_once dirname(__FILE__) . '/../classes/View.php';
require_once dirname(__FILE__) .'/../classes/User.php';
require_once dirname(__FILE__) . '/../classes/Redirect_Exception.php';
require_once dirname(__FILE__) . '/Login_Controller.php';

abstract class Controller { 
	protected $_template;
	protected $_view;
	protected $_user;

	protected function _setTemplate(Template $template) {
		$this->_template = $template;
	}

	protected function _setView(Html_Template $view) {
		$this->_view = $view;
	}

	public function render() {
		$template = $this->_template;
		$template->bind('VIEW', $this->_view);
		$template->render();	
	}

	public function setUser(User $user) {
		$this->_user = $user;
	}

	protected function _requireAdmin() {
		if(false == ($this->_user instanceof Admin)) {
			redirect('/admin/login.php');
		}
	}

	protected function _requireSalesRep() {
		if(false == ($this->_user instanceof Sales_Rep)) {
			$this->redirect('/sales_login.php');
		}
	}

	protected function _requireSalesManager() {
		if(false == ($this->_user instanceof Sales_Rep) ||  0 == intval($this->_user->manager)) {
			$this->redirect('/sales_login.php');
		}
	}

	protected function _requireXsrf() {
		if(false == xsrf_check()) {
			exit;
		}
	}

	protected function _requireUser() {
		if(false == $this->_user->exists()) {
			redirect(LOC_LOGIN);
			exit;
		}
	}

	protected function redirect($location) {
		$exception = new Redirect_Exception("Something happened, please redirect.");
		$exception->setLocation($location);
		throw $exception;
	}

	public function getView() {
		return $this->_view;
	}

	public function getTemplate() {
		return $this->_template;
	}

	protected function requireLogin($login_location) {
		global $_SESSION;
		$_SESSION[Login_Controller::LOGIN_SESSION_REDIR] = $login_location;
		$this->redirect(LOC_LOGIN);
	}
}
?>