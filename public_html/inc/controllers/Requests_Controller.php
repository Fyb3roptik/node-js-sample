<?php 
class Requests_Controller extends Controller {
	public function index() {
		$this->_config(true);
		$UUID = uniqid();
		$V = new View('request_queue.php');
		$V->bind('UUID', $UUID);
		$this->_setView($V);	
	}
	
	private function _config($require_login = false, $user = "", $set_redirect = true) {
		if(true == $require_login) {
			$this->_checkPermissions($set_redirect);
		}
		
		if(false == $require_login && true == $set_redirect) {
    		$this->_setRedirect();
		}
		
		$this->_setTemplate(new Template('user.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$REDIR = sanitize_string(exists('go', $_GET));
		global $LAYOUT_TITLE;
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE .= ' | '.$user);
	}
	
	private function _checkPermissions($set_redirect = true) {
		if(false == $this->_user->exists()) {
			if($set_redirect == true) {
			    $_SESSION['login_redirect'] = current_page_url();
            }
			$this->redirect(LOC_LOGIN);
			exit;
		}
	}
	
	private function _setRedirect() {
    	$_SESSION['login_redirect'] = current_page_url();
	}
}
?>