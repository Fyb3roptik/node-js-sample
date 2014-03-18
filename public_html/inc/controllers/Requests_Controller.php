<?php 
class Requests_Controller extends Controller {
	public function index() {
		$this->_config(true);
		$UUID = uniqid();
		$V = new View('request_queue.php');
		$V->bind('UUID', $UUID);
		$this->_setView($V);	
	}
	
	private function _config($require_login = false) {
		if(true == $require_login) {
			$this->_checkPermissions();
		}
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$REDIR = sanitize_string(exists('go', $_GET));
		global $LAYOUT_TITLE;
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE .= ' | Requests');
	}
	
	private function _checkPermissions() {
		if(false == $this->_user->exists()) {
			$_SESSION['login_redirect'] = $_SERVER['REDIRECT_URL'];
			$this->redirect(LOC_LOGIN);
			exit;
		}
	}
}
?>