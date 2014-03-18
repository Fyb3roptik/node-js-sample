<?php
require_once 'Foreign_Key_Widget.php';

/**
 * This is a widget that should be displayed on all pages unless otherwise overriden.
 */
class Global_Widget extends Foreign_Key_Widget {
	protected $_table = 'global_widgets';
	protected $_table_id = 'global_widget_id';
	protected $_foreign_key = 'foreign_key';
}
?>