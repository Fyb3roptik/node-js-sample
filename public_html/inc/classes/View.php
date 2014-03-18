<?php
require_once dirname(__FILE__) . '/Template_Provider.php';
require_once dirname(__FILE__) . '/Html_Template.php';

class View extends Html_Template {
	public function __construct($file_name) {
		$file_name = Template_Provider::getView($file_name);
		parent::__construct($file_name);
	}

	/**
	 * Sets a variable.
	 */
	public function setVar($varname, $var_value, $sanitize = true) {
		$this->bind($varname, $var_value);
	}
}
?>
