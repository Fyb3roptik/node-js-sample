<?php
class Order_Status_Change_Request_XML {
	const PROCESS_TYPE = 4;

	private $_request;

	public function __construct(Order_Status_Change_Request $osr) {
		if(false == $osr->exists()) {
			throw new Exception("Change request must exist.");
		}
		$this->_request = $osr;
	}

	public function asXML() {
		$xml = new SimpleXMLElement('<order />');
		$xml->addChild('process_type', self::PROCESS_TYPE);
		$xml->addChild('order_id', $this->_request->order_id);
		$xml->addChild('order_status', $this->_request->new_status);
		$xml->addChild('salesperson', $this->_getSalesPerson());
		return $xml->asXML();
	}

	private function _getSalesPerson() {
		$O = Object_Factory::OF()->newObject('Order', $this->_request->order_id);
		$rep = Object_Factory::OF()->newObject('Sales_Rep', $O->sales_rep_id);
		$rep_id = null;
		if(true == $rep->exists()) {
			$rep_id = $rep->syspro_id;
		}
		return $rep_id;
	}
}
?>
