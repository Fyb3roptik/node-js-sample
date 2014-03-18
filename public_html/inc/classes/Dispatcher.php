<?php
require_once dirname(__FILE__) . '/exceptions/Dispatcher_Permission_Exception.php';
require_once dirname(__FILE__) . '/exceptions/Controller_Not_Found_Exception.php';
require_once dirname(__FILE__) . '/Redirector.php';
require_once dirname(__FILE__) . '/Controller_Locator.php';


/**
 * The Dispatcher class dispatches actions to controllers, etc.
 */
class Dispatcher {
	private $_controller;
	private $_action;
	private $_id;
	private $_option_id;
	private $_user;
	private $_sentry;

	public function __construct($controller, $action = null, $id = null, $option_id = null) {
		$this->_setController($controller);
		$this->_setAction($action);
		$this->_setId($id);
		$this->_setOptionId($option_id);
	}

	private function _setController($controller) {
		if(true == class_exists($controller)) {
			$this->_controller = $controller;
		} else {
			throw new Controller_Not_Found_Exception("Controller '$controller' doesn't exist!?");
		}
	}

	private function _setAction($action) {
		$this->_action = 'index';
		if(true == isset($this->_controller)) {
			if(false == is_null($action) && false == empty($action)) {
				if(true == method_exists($this->_controller, $action)) {
					$this->_action = $action;
				}
			}
		}
	}

	private function _setId($id) {
		if(true == isset($this->_controller) && true == isset($this->_action)) {
			if(false == is_null($id) && false == empty($id)) {
				$this->_id = $id;
			}
		}
	}
	
	private function _setOptionId($option_id) {
		if(true == isset($this->_controller) && true == isset($this->_action) && true == isset($this->_id)) {
			if(false == is_null($option_id) && false == empty($option_id)) {
				$this->_option_id = $option_id;
			}
		}
	}

	public function setSentry(Sentry $sentry) {
		$this->_sentry = $sentry;
	}

	public function dispatch() {
		if(true == isset($this->_controller) && true == isset($this->_action)) {
			$this->_checkPermissions();
			$class_name = $this->_controller;
			$C = Controller_Locator::get()->findController($class_name);
			if(true == isset($this->_user)) {
				$C->setUser($this->_user);
			}
			$action_name = $this->_action;
			try {
				$output = $C->$action_name($this->_id, $this->_option_id);
				if(false == is_null($output)) {
					echo $output;
				} else {
					$C->render();
				}
			} catch(Redirect_Exception $e) {
				redirect($e->getLocation());
			}
		}
	}

	private function _checkPermissions() {
		if(true == isset($this->_sentry)) {
			if(false == $this->_sentry->actionAllowed($this->_controller, $this->_action)) {
				throw new Dispatcher_Permission_Exception("Permission not allowed.");
			}
		}
	}

	public function setUser(User $user) {
		$this->_user = $user;
	}
}
?>