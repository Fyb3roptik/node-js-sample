<?php
require_once 'Widget_List.php';

/**
 * This is the widget list we can turn to when all others fail.
 */
class Global_Widget_List extends Widget_List {
	/**
	 * Loads up all the configured widgets from the database.
	 */
	public function __construct() {
		$sql = "SELECT *
			  FROM `global_widgets`
			  ORDER BY sort_order";
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