<?php
require_once dirname(__FILE__) . '/Controller.php';
checkSecureSite($_SERVER['REQUEST_URI']);
class Admin_Controller extends Controller {
	public function index() {
		$this->_configureTemplate();
		$sql = "SELECT admin_id
			  FROM `administrators`
			  ORDER BY `name`";
		$query = db_query($sql);
		$ADMIN_LIST = array();
		while($query->num_rows > 0 && $a = $query->fetch_assoc()) {
			$ADMIN_LIST[] = new Admin($a['admin_id']);
		}

		$V = new View('admin_list.php');
		$V->bind('ADMIN_LIST', $ADMIN_LIST);
		$V->bind("MS", new Message_Stack());
		$this->_setView($V);
	}

	public function confirmDelete($admin_id) {
		$this->_configureTemplate();
		$A = new Admin($admin_id);
		if(false == $A->exists()) {
			redirect(LOC_ADMIN_MANAGE);
		}
		$V = new View('admin_confirm_delete.php');
		$V->bind('DROP_ADMIN', $A);
		$this->_setView($V);
	}

	public function dropAdmin() {
		$this->_requireAdmin();
		$MS = new Message_Stack();
		$A = new Admin(post_var('admin_id'));
		if(true == $A->exists()) {
			if($this->_user->ID == $A->ID) {
				$MS->add('admin', "You can't delete your own admin account.", MS_WARNING);
			} else {
				$A->delete();
				$MS->add('admin', "Admin successfully deleted.", MS_SUCCESS);
			}
		}
		redirect(LOC_ADMIN_MANAGE);
	}

	private function _configureTemplate() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}

	public function edit($admin_id = 0) {
		$this->_configureTemplate();
		$A = new Admin($admin_id);
		$this->_adminForm($A);
	}

	private function _adminForm(Admin $admin) {
		$V = new View('admin_form.php');
		$V->bind('A', $admin);
		$this->_setView($V);
	}

	public function newAdmin() {
		$this->_configureTemplate();
		$A = new Admin();
		$A->name = 'New Admin';
		$this->_adminForm($A);
	}

	public function process() {
		$MS = new Message_Stack();
		$A = new Admin(post_var('admin_id'));
		$A->load(exists('admin', $_POST));
		
		$new_password = post_var('new_password');
		$confirm_password = post_var('confirm_password');
		
		if($new_password != "" && $confirm_password == $new_password) {
			try {
				$A->setPassword($new_password, null, true);
				$MS->add('admin', "Password has been reset");
			} catch(Exception $e) {
				$MS->add('admin_form', "Password could not be reset", MS_ERROR);
				$error = true;
			}

		}
		
		$A->write();
		redirect(LOC_ADMIN_MANAGE);
	}

	public function editPermissions($admin_id) {
		$this->_configureTemplate();
		$A = new Admin($admin_id);
		if(false == $A->exists()) {
			redirect(LOC_ADMIN_MANAGE);
			exit;
		}

		$V = new View('permissions_form.php');
		$V->bind('ADMIN', $A);
		$V->bind('REG', Admin_Permission_Register::getRegister());
		$this->_setView($V);
	}

	public function processPermissions() {
		$this->_requireAdmin();
		$A = new Admin(post_var('admin_id'));
		if(false == $A->exists()) {
			redirect('/admin/admin/');
			exit;
		}
		$perm_list = post_var('perm', array());
		foreach($perm_list as $code => $val) {
			$perm = $this->_getPermission($A, $code);
			$perm->allowed = abs(intval($val));
			$perm->write();
		}
		redirect('/admin/admin/');
		exit;
	}

	private function _getPermission(Admin $admin, $permission_code) {
		$permission_id = null;
		$sql = SQL::get()
			->select('admin_permission_id')
			->from('admin_permissions')
			->where("admin_id = '@admin_id'")
			->where("code = '@code'")
			->bind('admin_id', $admin->ID)
			->bind('code', $permission_code);
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$permission_id = $rec['admin_permission_id'];
		}
		$AP = new Admin_Permission($permission_id);
		$AP->admin_id = $admin->ID;
		$AP->code = $permission_code;
		return $AP;
	}
}
?>
