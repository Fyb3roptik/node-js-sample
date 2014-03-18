<?php
require_once dirname(__FILE__) . '/User.php';
require_once dirname(__FILE__) . '/Admin_Permission.php';

/**
 * An Admin can login to the back end.
 */
class Admin extends User {
	protected $_table = 'administrators';
	protected $_table_id = 'admin_id';

	protected $_user_type = User::TYPE_ADMIN;

	public function hasPermission($action_code) {
		$allowed = false;
		$AP = new Admin_Permission();
		$permission_list = $AP->find('admin_id', $this->ID);
		foreach($permission_list as $P) {
			if($action_code == $P->code) {
				$allowed = (bool)$P->allowed;
				break;
			}
		}
		return $allowed;
	}
}

/**
 * Generate a new password token and send an email to the admin.
 */
function recover_admin_password(Admin $admin) {
	recover_user_password($admin);
}
?>