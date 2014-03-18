<?php
require_once 'Controller.php';

class Payment_Terms_Controller extends Controller {
	public function index() {
		$this->_setupTemplate();

		$sql = SQL::get()
			->select('payment_term_id')
			->from('payment_terms')
			->orderBy('sort')
			->orderBy('syspro_code');
		$query = db_query($sql);

		$term_list = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$term_list[] = new Payment_Term($rec['payment_term_id']);
		}

		$V = new View('payment_terms_index.php');
		$V->bind('TERM_LIST', $term_list);
		$this->_setView($V);
	}

	public function saveSort() {
		$this->_requireAdmin();
		$return = array(
			'success' => false,
			'message' => null);

		$term_list = post_var('term_id', array());
		foreach($term_list as $term_id) {
			$PT = new Payment_Term($term_id);
			if(true == $PT->exists()) {
				$PT->sort = $sort_index;
				$PT->write();
				$sort_index++;
				$return['success'] = true;
			}
		}

		if(true == $return['success']) {
			$return['message'] = date("Y-m-d @ H:i:s") . ' - Payment term sort order saved.';
		}


		echo json_encode($return);
		exit;
	}

	private function _setupTemplate() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}
}
?>