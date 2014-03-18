<?php
require_once dirname(__FILE__) . '/Controller.php';

/**
 * Heads up! This class does double duty as the controller for the frontend
 * and the admin panel.
 *
 * @WTF
 */
class Report_Controller extends Controller {

	const DEFAULT_BASE_URL = '/report/';
	const ADMIN_BASE_URL = '/admin/report/';

	const DEFAULT_INDEX_URL = '/report/index/';
	const ADMIN_INDEX_URL = '/admin/report/index/';

	public function index() {
		$this->_setupTemplate();
		$report = $this->_getDefaultReport();
		$report->sortBy(get_var('sort', null), get_var('direction', 'DESC'));
		$this->_reportView($report);
	}

	public function clear() {
		$this->_setupTemplate();
		global $_SESSION;
		unset($_SESSION[$this->_getSessionKey()]);
		$this->index();
	}

	private function _setupTemplate() {
		if(true == ($this->_user instanceof Admin)) {
			$this->_requireAdmin();
			$this->_setTemplate(new Template('default.php'));
			$this->_template->bind('ADMIN', $this->_user);
		} else {
			$this->_setTemplate(new Template('wide.php'));
			$this->_template->bind('CUSTOMER', $this->_user);
		}
	}

	private function _reportView(Report_Builder $RB) {
		$V = new Html_Template(dirname(__FILE__) . '/../modules/report_view.php');
		$V->bind('RB', $RB);
		$V->bind('BASE_URL', $this->_getBaseUrl());
		$OR = new Order_Report();
		$V->bind('REPORT', $RB->buildReport($OR));
		$V->bind('SHOW_SUMMARY', $this->_showSummary());
		$V->bind('ORDER_LIST', $OR->getOrders());
		$V->bind('REP_LIST', $this->_getRepList());
		$V->bind('SAVED_REPORTS', $this->_getSavedReports());
		$V->bind('USER', $this->_user);
		$this->_setView($V);
	}

	protected function _getBaseUrl() {
		$base_url = self::DEFAULT_BASE_URL;
		if(true == ($this->_user instanceof Admin)) {
			$base_url = self::ADMIN_BASE_URL;
		}
		return $base_url;
	}

	protected function _showSummary() {
		$show_summary = false;
		if(true == ($this->_user instanceof Admin)) {
			$show_summary = true;
		}
		return $show_summary;
	}

	private function _getRepList() {
		$rep_list = array(
			'0' => "Select Rep",
			'027' => "Internet");
		$sql = SQL::get()
			->select('sales_rep_id')
			->from('sales_reps')
			->orderBy('name');
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$rep = new Sales_Rep($rec['sales_rep_id']);
			$rep_list[$rep->ID] = $rep->name;
		}
		return $rep_list;
	}

	protected function _getSavedReports() {
		$report_list = array();

		$sql = SQL::get()
			->select("order_report_id, title")
			->from('order_reports')
			->where("user_id = '@user_id'")
			->where("user_type = '@user_type'")
			->bind('user_id', intval($this->_user->ID))
			->bind('user_type', get_class($this->_user));
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$report_list[$rec['order_report_id']] = $rec['title'];
		}
		return $report_list;
	}

	private function _getDefaultReport() {
		global $_SESSION;
		$key = $this->_getSessionKey();
		$RB = $this->_getReportBuilder();
		$RB->addSort('shipping_name');
		$RB->addSort('billing_name');
		$RB->addSort('date_purchased');
		$RB->title = 'Orders';
		if(true == array_key_exists($key, $_SESSION)) {
			$RB = unserialize($_SESSION[$key]);
		}
		$_SESSION[$key] = serialize($RB);
		return $RB;
	}

	public function updateReport() {
		$this->_setupTemplate();
		$RB = $this->_getReportBuilder();
		$order_id = abs(intval(post_var('order_id', 0)));
		if($order_id > 0) {
			$this->_searchOrder($order_id);
		}
		$fields = post_var('selected_fields', array());
		if(0 == count($fields)) {
			$this->redirect($this->_getIndexUrl());
		}
		foreach($fields as $field) {
			$RB->addSort(trim($field));
		}
		$RB->title = stripslashes(post_var('report_name', 'Orders'));
		$RB->setFilterList(post_var('report_filter', array()));
		global $_SESSION;
		$_SESSION[$this->_getSessionKey()] = serialize($RB);

		$save_report = abs(intval(post_var('save_report', 0)));
		if($save_report > 0) {
			$this->_saveReport($RB);
		}

		$this->redirect($this->_getIndexUrl());
	}

	private function _getReportBuilder() {
		$ORR = new Order_Report_Record();
		$ORR->setUser($this->_user);
		$RB = $ORR->getReportBuilder();
		if(true == ($RB instanceof Sales_Rep_Report_Builder)) {
			$RB->setFilter('sales_rep', $this->_user->ID);
		}
		return $RB;
	}

	private function _searchOrder($order_id) {
		if(true == ($this->_user instanceof Admin)) {
			$O = new Order($order_id);
			if(true == $O->exists()) {
				$this->redirect('/admin/ralph/view/' . $O->ID);
				exit;
			}
		}
	}

	protected function _getIndexUrl() {
		$url = self::DEFAULT_INDEX_URL;
		if(true == ($this->_user instanceof Admin)) {
			$url = self::ADMIN_INDEX_URL;
		}
		return $url;
	}

	protected function _saveReport(Report_Builder $RB) {
		$report_record = $this->_findReportRecordByTitle($RB->title);
		$report_record->setReportBuilder($RB);
		$report_record->write();
	}

	protected function _findReportRecordByTitle($title) {
		$order_report_id = 0;
		$sql = SQL::get()
			->select('order_report_id')
			->from('order_reports')
			->where("title = '@title'")
			->bind('title', $title)
			->where("user_id = '@user_id'")
			->bind('user_id', intval($this->_user->ID))
			->where("user_type = '@user_type'")
			->bind('user_type', get_class($this->_user));
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$order_report_id = intval($rec['order_report_id']);
		}
		$report = new Order_Report_Record($order_report_id);
		if(false == $report->exists()) {
			$report->setUser($this->_user);
			$report->title = $title;
		}
		return $report;
	}

	public function load($report_id) {
		$report = $this->_findReport($report_id);
		if(true == is_null($report)) {
			$this->redirect($this->_getIndexUrl());
		}
		$this->_setupTemplate();
		$this->_reportView($report);
	}

	protected function _findReport($report_id) {
		$report = null;
		$record = new Order_Report_Record($report_id);
		if(true == $record->exists()) {
			if($record->user_type == get_class($this->_user) && $record->user_id == $this->_user->ID) {
				$report = $record->getReportBuilder();
			}
		}
		return $report;
	}

	/**
	 * Creates a unique hash per-user. Hash is used as the key for storing the
	 * Report_Builder in the session. Prevents cross-contamination of sessions
	 * between admin/sales/customer reports.
	 */
	private function _getSessionKey() {
		$root_key = 'report_builder';
		$root_key .= '_' . get_class($this->_user);
		$root_key .= '_' . abs(intval($this->_user->ID));
		$hash = sha1($root_key);
		return $hash;
	}
}
?>
