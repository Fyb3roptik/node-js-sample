<?php
require_once 'inc/global.php';

if(false == $CUSTOMER->exists()) {
	//TODO: Add a message to the message stack.
	if(get_var('pss') == "") {
		$MS->add('login', 'You must be logged in to view that page.', MS_WARNING);
	}
	redirect(LOC_LOGIN);
	exit;
}

$VIEW = 'account_home.php';

$BREADCRUMBS = "0";

$action = exists('action', $_REQUEST);
switch(strtolower($action)) {
	case 'edit': {
		$VIEW = 'account_edit.php';
		$LAYOUT_TITLE .= " | Manage My Account";
		break;
	}

	case 'process_updates': {
		//TODO: add some validation.
		$CUSTOMER->setName(exists('customer_name', $_POST, null));
		$CUSTOMER->stage_name = exists('customer_stage_name', $_POST, null);
		$CUSTOMER->setEmail(exists('customer_email', $_POST, null));
		$CUSTOMER->username = exists('customer_username', $_POST, null);

        $old_password = post_var('current_password');
		$new_password = post_var('new_password');
		$confirm_password = post_var('confirm_password');
		
		if($old_password != "" && $new_password != "" && $confirm_password != "") {
			if($new_password != $confirm_password) {
				$MS->add('account', 'New password and Confirm Password do not match.', MS_ERROR);
			} else {
				$set_password = $CUSTOMER->setPassword($new_password, $old_password);
	            if(true == $set_password) {
					$CUSTOMER->write();
					$MS->add('login', 'Your password has been reset. Please login again.', MS_SUCCESS);
					$_SESSION['login_redirect'] = '/myaccount?pss=true';
					redirect('/logout');
	            } else {
					$MS->add('account', 'Your old password is incorrect or your new password is not at least 6 characters.', MS_ERROR);
	            }
			}
		}

		$CUSTOMER->write();
		redirect(LOC_ACCOUNT_HOME);
		break;
	}

	default: {
		//send them to the dashboard if we don't have any other idea of what to do.
		$VIEW = 'account_home.php';
		$LAYOUT_TITLE .= " | My Account";
		break;
	}
}

//$WIDGET_LIST = new Account_Widget_List();
//$WIDGET_LIST->addWidget(new Customer_Wishlist_Widget());

require_once 'inc/layouts/default.php';
?>