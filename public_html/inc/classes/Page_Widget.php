<?php
require_once 'Foreign_Key_Widget.php';

/**
 * Handles the relationship between a Page and a Widget.
 */
class Page_Widget extends Foreign_Key_Widget {
	protected $_table = 'page_widgets';
	protected $_table_id = 'page_widget_id';
	protected $_foreign_key = 'page_id';
}
?>