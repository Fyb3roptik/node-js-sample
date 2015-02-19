<?php
require_once 'inc/global.php';
FB::group('admin/login.php');
checkSecureSite($_SERVER['REQUEST_URI']);
$VIEW = 'login.php';

$action = exists('action', $_REQUEST, null);

switch(strtolower($action)) {
	case 'reset_password': {
		$VIEW = 'reset_password_form.php';
		break;
	}

	case 'process_password_reset': {
		$token = post_var('token', null);
		$admin = Password_Token::tokenFactory($token);
		if(true == $admin->exists()) {
			$password = trim(post_var('password'));
			$confirm_password = trim(post_var('confirm_password'));
			if($password === $confirm_password) {
				if(false == $admin->resetPassword($password, $token)) {
					$MS->add('login', "Password reset failed for some reason. Please try again later or contact your systems administrator.", MS_WARNING);
					redirect(LOC_RESET_PASSWORD);
				} else {
					$MS->add('login', "Password reset, please login.", MS_SUCCESS);
					redirect(LOC_LOGIN);
				}
			} else {
				$MS->add('login', "The new passwords you provided didn't match, please check your spelling and try again.", MS_WARNING);
				redirect(LOC_RESET_PASSWORD);
			}
		} else {
			$MS->add('login', "The information you provided didn't match any of our records, please check your spelling and try again.", MS_WARNING);
			redirect(LOC_RESET_PASSWORD);
		}
		break;
	}

	case 'recover_password': {
		$VIEW = 'recover_password_form.php';
		break;
	}

	case 'process_recover_password': {
		$email = trim(post_var('email'));

		$admin = new Admin($email, 'email');
		if(true == $admin->exists()) {
			recover_admin_password($admin);
			$MS->add('login', 'A password reset token has been sent to your email address.', MS_SUCCESS);
			redirect(LOC_RESET_PASSWORD);
		}
		break;
	}

	case 'login': {
		$email = trim(exists('email', $_POST, null));
		$password = trim(exists('password', $_POST, null));
		$admin = new Admin($email, 'email');
		if(true == $admin->exists() && passwordify($password, $admin->salt) == $admin->getPassword()) {
			$token = $admin->newToken();
			$expires = time() + PASSWORD_TOKEN_EXPIRATION;
			setcookie('admin_token', $token, $expires, '/admin/', $_SERVER['SERVER_NAME']);
			$admin->write();
			redirect(LOC_DEFAULT);
		}
		break;
	}

	case 'logout': {
		if(true == array_key_exists('admin_token', $_COOKIE)) {
			$ADMIN->logout();
			unset($_COOKIE['admin_token']);
			redirect(LOC_DEFAULT);
		}
		break;
	}
}

require_once 'inc/layouts/login.php';
FB::groupEnd();
?>