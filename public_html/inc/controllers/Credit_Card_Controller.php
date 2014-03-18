<?php
require_once dirname(__FILE__) . '/Controller.php';
require_once dirname(__FILE__) . '/../classes/Credit_Card.php';

class Credit_Card_Controller extends Controller {
	const INDEX_URI = '/myaccount/payment_info/';

	public function index() {
		$this->_setupTemplate();
		$view = new View('credit_card_index.php');
		$cc_list = $this->_user->getCreditCards();
		$view->bind('CC_LIST', $cc_list);
		$this->_setView($view);
	}

	public function newCreditCard() {
		$this->_setupTemplate();
		$CC = new Credit_Card();
		$CC->nickname = 'My New Credit Card';
		$CC->name = $this->_user->name;
		$view = new View('credit_card_form.php');
		$view->bind('CC', $CC);
		$this->_setView($view);
	}

	public function edit($credit_card_id) {
		$CC = new Credit_Card($credit_card_id);
		$this->_checkPermissions($CC);
		$this->_setupTemplate();
		$view = new View('credit_card_form.php');
		$view->bind('CC', $CC);
		$this->_setView($view);
	}

	public function process() {
		$return = array('redir_loc' => self::INDEX_URI);
		$CC = new Credit_Card(post_var('credit_card_id'));
		if(false == $this->_userCanEditCard($CC)) {
			$return['error'] = 'Permission denied.';
			return json_encode($return);
		}
		$cc_data = post_var('credit_card', array());
		if(true == empty($cc_data['number'])) {
			unset($cc_data['number']);
		}
		$CC->customer_id = $this->_user->ID;
		$CC->load($cc_data);
		$CC->write();
		return json_encode($return);
	}

	protected function _userCanEditCard($CC) {
		$permission = false;
		if($CC->customer_id == $this->_user->ID) {
			$permission = true;
		}

		if(false == $CC->exists()) {
			$permission =true;
		}

		return $permission;
	}

	private function _checkCardPermissions(Credit_Card $CC) {
		if(true == $CC->exists()) {
			if($CC->customer_id != $this->_user->ID) {
				$this->redirect('/myaccount/payment_info/');
				exit;
			}
		}
	}

	private function _setupTemplate() {
		$this->_checkPermissions();
		$this->_setTemplate(new Template('ajax.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
	}

	private function _checkPermissions() {
		if(false == $this->_user->exists()) {
			$this->redirect(LOC_LOGIN);
			exit;
		}
	}

	public function drop() {
		if(false == $this->_user->exists()) {
			exit;
		}
		$CC = new Credit_Card(post_var('credit_card_id'));
		$this->_checkCardPermissions($CC);
		if(true == $CC->exists()) {
			$CC->delete();
		}
		$return_vals = array('success' => true);
		echo json_encode($return_vals);
		exit;
	}

	public function paymentOptions() {
		$this->_setTemplate(new Template('ajax.php'));
		$V = new View('payment_options.php');
		$this->_setView($V);
		$C = $this->_user;
		$sales_rep_options = array();
		if(true == is_a($this->_user, 'Sales_Rep')) {
			$C = new Customer(abs(intval(session_var('sales_customer', 0))));
			$sales_rep_options = $this->_getSalesRepOptions($this->_user);
		}
		$cc_list = $C->getCreditCards();
		$options = array(0 => 'New Credit Card');
		foreach($cc_list as $i => $CC) {
			$options[$CC->ID] = $CC->nickname;
		}
		$V->bind('SALES_REP_OPTIONS', $sales_rep_options);
		$V->bind('CC_OPTIONS', $options);
	}

	private function _getSalesRepOptions(Sales_Rep $rep) {
		$options = array('cc' => 'Credit Card');

		$max_sort = 0;

		$subquery = SQL::get()
			->select('sort')
			->from('payment_terms')
			->where("payment_term_id = '@syspro_code'")
			->bind('syspro_code', $rep->max_payment_term);
		$query = db_query($subquery);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$max_sort = $rec['sort'];
		}

		$sql = SQL::get()
			->select('payment_term_id, name')
			->from('payment_terms')
			->where("sort <= @max_sort")
			->bind('max_sort', $max_sort);
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$options[$rec['payment_term_id']] = $rec['name'];
		}
		return $options;
	}

	public function jsonDetails() {
		FB::group("CCC::jsonDetails");
		$this->_requireXsrf(); //must be posted by the user!
		$CC = new Credit_Card(post_var('credit_card_id'));
		FB::log($CC, "Credit Card");
		$C = $this->_user;
		if(true == is_a($this->_user, 'Sales_Rep')) {
			$C = new Customer(session_var('sales_customer'));
		}
		if(false == $CC->exists() || $CC->customer_id != $C->ID) {
			FB::log("Permission denied...");
			//user doesn't have permission for this card.
			exit;
		}
		$json_details = array();
		$json_details['cc_id'] = $CC->ID;
		$json_details['nickname'] = $CC->nickname;
		$json_details['exp_month'] = $CC->getPlainMonth();
		$json_details['exp_year'] = $CC->getPlainYear();
		$json_details['number'] = obfuscate_cc_number($CC->getPlainNumber());
		$json_details['name'] = $CC->name;
		FB::log($json_details, "json details");
		echo json_encode($json_details);
		FB::log(json_encode($json_details), "In json...");
		FB::groupEnd();
		exit;
	}
}
?>
