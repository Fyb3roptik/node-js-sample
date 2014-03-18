<?php
require_once dirname(__FILE__) . '/Sentry.php';
require_once dirname(__FILE__) . '/Admin_Permission_Register.php';

class Admin_Sentry implements Sentry {
	private $_admin;

	public function __construct(Admin $admin) {
		if(false == $admin->exists()) {
			throw new Exception('Admin must exist.');
		}
		$this->_admin = $admin;
	}

	public function actionAllowed($controller, $action = null) {
		$allowed = true;
		$action_code = Admin_Permission_Register::lookupCode($controller, $action);
		if(false == is_null($action_code)) {
			$allowed = false;
			if(true == $this->_admin->hasPermission($action_code)) {
				$allowed = true;
			}
		}
		return $allowed;
	}
}
?>