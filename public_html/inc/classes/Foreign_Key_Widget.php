<?php
require_once 'Object.php';

/**
 * Abstract class for defining Foreign_Key_Widgets.
 */
abstract class Foreign_Key_Widget extends Object {
	protected $_foreign_key = null;

	/**
	 * Override Object::write() to do some validation.
	 */
	public function write() {
		if(0 == intval($this->_data[$this->_foreign_key])) {
			throw new Exception("Foreign Key required.");
		} elseif(0 == intval($this->_data['widget_id'])) {
			throw new Exception("Widget ID required.");
		} else {
			parent::write();
		}
	}

	public function getForeignKey() {
		return $this->_foreign_key;
	}
}
?>