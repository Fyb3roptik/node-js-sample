<?php
require_once dirname(__FILE__) . '/Foreign_Key_Widget.php';

class Account_Widget extends Foreign_Key_Widget {
	protected $_table = 'account_widgets';
	protected $_table_id = 'account_widget_id';
	protected $_foreign_key = 'foreign_key';
}
?>
