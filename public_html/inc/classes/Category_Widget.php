<?php
require_once 'Foreign_Key_Widget.php';

/**
 * Handles the relationship between a Page and a Widget.
 */
class Category_Widget extends Foreign_Key_Widget {
	protected $_table = 'category_widgets';
	protected $_table_id = 'category_widget_id';
	protected $_foreign_key = 'category_id';
}
?>