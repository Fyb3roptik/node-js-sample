<?php
require_once 'Accordion_Tab.php';

class Global_Tab extends Accordion_Tab {
	protected $_table = 'global_product_tabs';
	protected $_table_id = 'global_tab_id';
	protected $_unsanitized_fields = array('data');

	public $product_id = 0;
}
?>

