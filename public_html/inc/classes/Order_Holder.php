<?php
require_once dirname(__FILE__) . '/Order_Status_Change_Request.php';

class Order_Holder {
	private $_order;
	private $_syspro_api;
	private static $_holdable_status_list = array('1', '2', '4');

	public function __construct(Order $O) {
		$this->_order = $O;
	}

	public static function getHoldableStatusList() {
		return self::$_holdable_status_list;
	}

	/**
	 * For dependency injection.
	 */
	public function setSysproApi(Syspro_API $syspro) {
		$this->_syspro_api = $syspro;
	}

	private function _getSysproApi() {
		if(false == isset($this->_syspro_api)) {
			$this->_syspro_api = new Syspro_API();
		}
		return $this->_syspro_api;
	}

	public function holdable() {
		$holdable = false;
		$syspro_status = $this->_getSysproStatus();
		if(true == in_array($syspro_status, self::$_holdable_status_list)) {
			$holdable = true;
		}
		return $holdable;
	}

	public function unholdable() {
		$unholdable = false;
		$status = $this->_getSysproStatus();
		if('S' == $status) {
			$unholdable = true;
		}
		return $unholdable;
	}

	public function requestHold() {
		if(false == $this->holdable()) {
			throw new Exception("Can't hold this order.");
		}
		$request = $this->_getRequest();
		$request->new_status = 'S';
		$this->_order->syspro_status = 'req-S';
		$this->_order->write();
		return $request;
	}

	public function requestUnhold() {
		if(false == $this->unholdable()) {
			throw new Exception("Can't unhold this order.");
		}
		$request = $this->_getRequest();
		$request->new_status = '1';
		$this->_order->syspro_status = 'req-1';
		$this->_order->write();
		return $request;
	}

	private function _getRequest() {
		$request = new Order_Status_Change_Request();
		$request->order_id = $this->_order->ID;
		$request->timestamp = date('Y-m-d H:i:s');
		return $request;
	}

	private function _getSysproStatus() {
		$syspro = $this->_getSysproApi();
		return $syspro->getOrderStatus($this->_order->ID);
	}
}
?>
