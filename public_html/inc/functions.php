<?php
require_once 'functions/db.php';
require_once 'functions/crypto.php';
require_once 'functions/html.php';
require_once 'functions/standard_lib.php';

/**
 * Autoloads our classes.
 */
function __autoload($class) {
	$class_file = dirname(__FILE__) . '/classes/' . trim($class) . '.php';
	$controller_file = dirname(__FILE__) . '/controllers/' . trim($class). '.php';
	$model_file = dirname(__FILE__) . '/models/' . trim($class) . '.php';

	$places_to_look = array($class_file, $controller_file, $model_file);

	foreach($places_to_look as $place) {
		if(true == file_exists($place)) {
			require_once $place;
		}
	}
}

/**
 * Draws a hidden input field used by global.php to make sure the user
 * actually posted the request and it didn't actually come form some
 * fake referer.
 */
function draw_xsrf_field() {
	global $CUSTOMER;
	$field = null;
	if(true == $CUSTOMER->exists()) {
		$field = '<input type="hidden" name="' . get_xsrf_field_name() . '" value="' . get_xsrf_field_value() . '" />';
	}
	return $field;
}

function get_xsrf_field_name() {
	global $CUSTOMER;
	$field = null;
	if(true == $CUSTOMER->exists()) {
		$field = md5($CUSTOMER->session_token);
	}
	return $field;
}

function get_xsrf_field_value() {
	global $CUSTOMER;
	$value = null;
	if(true == $CUSTOMER->exists()) {
		$value = sha1($CUSTOMER->session_token);
	}
	return $value;
}

/**
 * Returns true if it finds the xsrf value in the post.
 */
function xsrf_check() {
	global $_POST, $CUSTOMER;
	$passed = false;
	if(true == $CUSTOMER->exists()) {
		$value_field = md5($CUSTOMER->session_token);
		if(true == exists($value_field, $_POST)) {
			//looks like the user did, in fact post this request.
			$passed = true;
		}
	}
	return $passed;
}

function caching_enabled($constant_name = 'ENABLE_CACHING') {
	$enabled = false;
	if(true == defined($constant_name) && true == is_bool(constant($constant_name))) {
		$enabled = constant($constant_name);
	}
	return $enabled;
}

function require_login($redirect = '/myaccount/') {
	global $_SESSION;
	$_SESSION['login_redirect'] = $redirect;
	redirect(LOC_LOGIN, $_REQUEST);
}
?>
