<?php
class Admin_Permission_Register {
	private static $_permissions = array();

	public static function addPermission($controller, $action, $code, $description = '') {
		if(true == self::_codeAlreadyUsed($code)) {
			throw new Exception("Permission code '" . $code . "' already registered.");
		}

		self::$_permissions[$controller][] = array('action' => $action,
			'code' => $code,
			'description' => $description);
	}

	private static function _codeAlreadyUsed($code) {
		$code_used = false;
		foreach(self::$_permissions as $controller => $controller_data) {
			foreach($controller_data as $data) {
				if($code == $data['code']) {
					$code_used = true;
					break;
				}
			}
		}
		return $code_used;
	}

	public static function lookupCode($lookup_controller, $lookup_action) {
		$code = null;
		$controller_data = self::_getControllerData($lookup_controller);
		foreach($controller_data as $data) {
			if($data['action'] == $lookup_action) {
				$code = $data['code'];
				break;
			}
		}
		return $code;
	}

	private function _getControllerData($controller) {
		$data = array();
		if(true == array_key_exists($controller, self::$_permissions)) {
			$data = self::$_permissions[$controller];
		}
		return $data;
	}

	public static function getRegister() {
		return self::$_permissions;
	}

	public static function clearRegister() {
		self::$_permissions = array();
	}
}
?>
