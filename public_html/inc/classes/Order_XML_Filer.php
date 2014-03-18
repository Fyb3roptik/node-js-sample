<?php
require_once dirname(__FILE__) . '/Order_XML.php';

class Order_XML_Filer {
	private $_order_list;
	private $_order_change_list = array();
	private $_order_status_list = array();

	public function __construct() {
		$this->_loadOrderList();
		$this->_loadOrderChanges();
		$this->_loadStatusChanges();
	}

	private function _loadOrderList() {
		$sql = SQL::get()
			->select('order_id')
			->from('orders')
			->where("sent_to_syspro = '0'")
			->where("type = '@order_type'")
			->bind('order_type', db_input(Order::TYPE_ORDER));
		$query = db_query($sql);
		$this->_order_list = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$this->_order_list[] = new Order($rec['order_id']);
		}
		$query->free();
	}

	private function _loadStatusChanges() {
		$sql = SQL::get()
			->select('order_status_change_request_id')
			->from('order_status_change_requests')
			->where("sent_to_syspro = '0'");
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$this->_order_status_list[] = new Order_Status_Change_Request($rec['order_status_change_request_id']);
		}
		$query->free();
	}

	private function _loadOrderChanges() {
		$sql = SQL::get()
			->select('order_change_id')
			->from('order_change_history')
			->where("syspro = '0'");
		$query = db_query($sql);
		$this->_order_change_list = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$this->_order_change_list[] = new Order_Change_History($rec['order_change_id']);
		}
		$query->free();
	}

	private function _writeOrders() {
		if(count($this->_order_list) > 0) {
			fwrite(STDOUT, "\nWriting " . count($this->_order_list) . " order XML files.\n");
			foreach($this->_order_list as $i => $order) {
				try {
					fwrite(STDOUT, '.');
					$OX = new Order_XML($order);
					fwrite(STDOUT, 'o_0');
					$file_name = DIR_XML_DUMP . date('YmdHis', strtotime($order->date_purchased)) . '_' . $order->ID . '.xml';
					$handle = fopen($file_name, 'w');
					fwrite(STDOUT, 'o_0');
					fwrite($handle, $OX->asXML());
					fwrite(STDOUT, 'o_0');
					fclose($handle);
					$order->sent_to_syspro = 1;
					$order->write();
				} catch(Exception $e) {
					fwrite(STDERR, $e->getMessage());
				}
			}
		}
	}

	private function _writeChanges() {
		foreach($this->_order_change_list as $och) {
			$OCH_XML = new Order_Change_History_XML($och);
			$file_name = DIR_XML_DUMP . date('YmdHis', strtotime($och->timestamp)) . '_' . $och->order_id . '.xml';
			$handle = fopen($file_name, 'w');
			fwrite($handle, $OCH_XML->asXML());
			fclose($handle);
			$och->syspro = 1;
			$och->write();
		}
	}

	private function _writeStatusRequests() {
		foreach($this->_order_status_list as $osr) {
			$OSR_XML = new Order_Status_Change_Request_XML($osr);
			$file_name = DIR_XML_DUMP . date('YmdHis', strtotime($osr->timestamp)) . '_' . $osr->order_id . '.xml';
			$handle = fopen($file_name, 'w');
			fwrite($handle, $OSR_XML->asXML());
			fclose($handle);
			$osr->sent_to_syspro = 1;
			$osr->write();
		}
	}

	public function writeFiles() {
		fwrite(STDOUT, "\nWriting orders...");
		$this->_writeOrders();
		fwrite(STDOUT, "\nWriting changes...");
		$this->_writeChanges();
		fwrite(STDOUT, "\nWriting status changes...");
		$this->_writeStatusRequests();
	}
}
?>
