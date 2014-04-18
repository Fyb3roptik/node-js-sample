<?php
require_once 'inc/global.php';

$VIEW = 'login.php';

checkSecureSite($_SERVER['REQUEST_URI']);

$action = exists('action', $_REQUEST, null);

switch(strtolower($action)) {

	/**
	 * Shows the recover password form.
	 */
	case 'recover_password': {
		$LAYOUT_TITLE .= ' | Recover Password';
		$VIEW = 'recover_password.php';
		break;
	}

	/**
	 * Process the recover password form and email out a new password reset token.
	 */
	case 'process_recover_password': {
		echo "Here";
		$email = exists('email', $_POST, null);
		$cust = new Customer($email, 'email');
		if(true == $cust->exists()) {
			recover_customer_password($cust);
			$MS->add('login', "Your password has been sent to your email address.", MS_SUCCESS);
			redirect(LOC_LOGIN);
		} else {
			$MS->add('recover_password', "We couldn't find a customer record matching that email address.<br />Please check your spelling and try again.", MS_ERROR);
			redirect(LOC_RECOVER_PASSWORD);
		}
		break;
	}

	/**
	 * Show the reset password form to reset a password with a password reset token.
	 */
	case 'reset_password': {
		$LAYOUT_TITLE .= ' | Reset Password';
		$TOKEN = sanitize_string(strip_tags(exists('token', $_GET)));
		$VIEW = 'reset_password.php';
		break;
	}

	/**
	 * Process the password reset.
	 */
	case 'process_password_reset': {
		$token = exists('token', $_POST, null);
		$email = exists('email', $_POST, null);
		$errors = false;

		$cust = Password_Token::tokenFactory($token);

		if($email == $cust->getEmail()) {
			//now, if only the passwords would match...
			$new_password = trim(exists('new_password', $_POST, null));
			$confirm_new_password = trim(exists('confirm_new_password', $_POST, null));

			if($new_password == $confirm_new_password) {
				if(true == $cust->resetPassword($new_password, $token)) {
					$MS->add('login', "Your password has been reset, please login.", MS_SUCCESS);
					redirect(LOC_LOGIN);
					break;
				} else {
					$MS->add('reset_password', "Your token is out of date. Please use the forgot password tool again.", MS_WARNING);
					redirect(LOC_RESET_PASSWORD, array('token' => $token));
					break;
				}
			} else {
				$MS->add('reset_password', "The passwords you provided didn't match.", MS_WARNING);
				redirect(LOC_RESET_PASSWORD, array('token' => $token));
				break;
			}
		} else {
			$MS->add('reset_password', "We couldn't find a customer based on the information provided.<br />Please check your spelling and try again.", MS_WARNING);
			redirect(LOC_RESET_PASSWORD, array('token' => $token));
			break;
		}

		break;
	}
}

require_once 'inc/layouts/default.php';
?>