<?php
require_once dirname(__FILE__) . '/Widget_List.php';

class Account_Widget_List extends Widget_List {
	/**
	 * Loads up all the configured widgets from the database.
	 */
	public function __construct() {
		$sql = SQL::get()
			->select('widget_id')
			->from('account_widgets')
			->orderBy('sort_order');
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$WB = new Widget_Builder($rec['widget_id']);
			try {
				$widget = $WB->build();
				$this->addWidget($widget);
			} catch(Exception $e) {
				//don't do anything.
			}
		}
	}
}
?>
