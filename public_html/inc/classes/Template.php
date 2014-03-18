<?php
require_once dirname(__FILE__) . '/Template_Provider.php';
require_once dirname(__FILE__) . '/Html_Template.php';

class Template extends Html_Template {
	public function __construct($file_name) {
		$file_name = Template_Provider::getLayout($file_name);
		parent::__construct($file_name);
	}
}
?>
