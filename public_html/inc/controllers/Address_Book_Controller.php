<?php
require_once dirname(__FILE__) . '/Controller.php';

class Address_Book_Controller extends Controller {
	public function index() {
		$this->_configureTemplate();
	}

	private function _configureTemplate() {
		$this->_setTemplate(new Template('ajax.php'));
		$V = new View('address_book_home.php');
		$this->_setView($V);
		$V->bind('CUSTOMER', $this->_user);
		$V->bind('ADDRESS_BOOK', $this->_user->getAddressBook());
		$V->bind('DEFAULT_SHIPPING', new Customer_Address($this->_user->default_shipping));
		$V->bind('DEFAULT_BILLING', new Customer_Address($this->_user->default_billing));
		$V->bind('FORM_ADDRESS', new Customer_Address());
		$V->bind('MS', new Message_Stack());
	}

	public function edit($address_id) {
		$address = new Customer_Address($address_id);
		if(true == $this->_userCanEditAddress($address)) {
			$this->_configureTemplate();
			$this->_view->bind('FORM_ADDRESS', $address);
		} else {
			$this->redirect('/myaccount/addressbook/');
		}
	}

	protected function _userCanEditAddress(Customer_Address $address) {
		$permission = false;
		if(false == $address->exists()) {
			$permission = true;
		}
		if($this->_user->ID == $address->customer_id) {
			$permission = true;
		}
		return $permission;
	}

	public function process() {
		$address = new Customer_Address(post_var('address_id', 0));
		$MS = new Message_Stack();
		if(true == $this->_userCanEditAddress($address)) {
			$address->load(post_var('address', array()));
			if(false == $address->exists()) {
				$address->customer_id = $this->_user->ID;
			}
			$address->write();
			$MS->add('address_book', 'Address has been saved.', MS_SUCCESS);
			if(abs(intval(post_var('default_billing'))) > 0) {
				$this->_user->default_billing = $address->ID;
				$this->_user->write();
			}
			if(abs(intval(post_var('default_shipping'))) > 0) {
				$this->_user->default_shipping = $address->ID;
				$this->_user->write();
			}
		}
		return json_encode(array('redir_loc' => '/myaccount/addressbook/'));
	}

	public function drop() {
		$return = array('success' => false,
			'redir_loc' => '/myaccount/addressbook/');
		$address = new Customer_Address(post_var('address_id', 0));
		if(true == $address->exists() && true == $this->_userCanEditAddress($address)) {
			$address->delete();
			$return['success'] = true;
		}
		return json_encode($return);
	}
}
?>