<?php
require_once dirname(__FILE__) . '/Object.php';

class Order_Report_Record extends Object {
	protected $_table = 'order_reports';
	protected $_table_id = 'order_report_id';
	protected $_unsanitized_fields = array('filters', 'selected_fields');

	private static $_user_report_class = array(
		'Customer' => 'Customer_Report_Builder',
		'Admin' => 'Admin_Report_Builder',
		'Sales_Rep' => 'Sales_Rep_Report_Builder');

	public function setUser(User $user) {
		if(false == $user->exists()) {
			throw new Exception("Bad user record.");
		}
		$this->user_id = $user->ID;
		$this->user_type = get_class($user);
	}

	public function setReportBuilder(Report_Builder $report) {
		$this->title = $report->title;
		$this->filters = json_encode($report->getFilters());
		$this->selected_fields = json_encode($report->getSelectedFields());
	}

	public function getReportBuilder() {
		$report_class = self::$_user_report_class[$this->user_type];
		$report = new $report_class($this->user_id);
		$report->title = $this->title;
		$report->setFilterList(json_decode($this->filters));

		$decoded_fields = json_decode($this->selected_fields);
		if(false == is_null($decoded_fields)) {
			foreach(json_decode($this->selected_fields) as $field) {
				$report->addSort($field);
			}
		}
		return $report;
	}
}
?>