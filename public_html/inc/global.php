<?php
$START_TIME = microtime(true);
session_start();
require_once 'config.php';
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));

function okb_exception_handler($exception) {
	global $_SERVER;
	error_log($_SERVER['REQUEST_URI'] . ' - Exception: ' . $exception->getMessage());
}

function okb_error_handler($error_no, $error_string) {
	if($error_no == E_USER_NOTICE) {
		global $_SERVER;
		error_log($_SERVER['REQUEST_URI'] . ' - ' . $error_no . ' - ' . $error_string);
	}
}

function okb_shutdown_handler() {
	$last_error = error_get_last();
	if(true == is_array($last_error)) {
		error_log($_SERVER['REQUEST_URI'] . ' - ' . $last_error['message']);
		error_log($_SERVER['REQUEST_URI'] . ' - ' . $last_error['file']);
	}
}

register_shutdown_function('okb_shutdown_handler');
set_error_handler('okb_error_handler');
error_reporting(E_RECOVERABLE_ERROR);
set_exception_handler('okb_exception_handler');

define('DIR_CACHE', dirname(__FILE__) . '/cache/', false);

if(false == defined('ENABLE_FIREPHP')) {
	define('ENABLE_FIREPHP', false, false);
}

require_once 'FirePHPCore/fb.php';
if(true == defined('ENABLE_FIREPHP') && true == ENABLE_FIREPHP) {
	ob_start();
}
FB::setEnabled(ENABLE_FIREPHP);
FB::group('globals.php');
FB::log("Firebug as been enabled in global.php");
FB::log(BUILD, "Build: ");
FB::log($_POST, "POST");
FB::log($_GET, "GET");
FB::log($_COOKIE, "COOKIE");
FB::log($_REQUEST, "REQUEST");
FB::log($_SESSION, "SESSION");
FB::log($_SERVER, "SERVER");
FB::groupEnd();

require_once 'classes/DB.php';

try {
	$DB = DB::db_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME); //instantiate just so we can test below
} catch(Exception $e) {
	//handle database connection errors...
	//TODO: email admin on error, produce a styled error page.
	?>
	<center><h1>whoops!</h1><p><strong>Could not connect to database:</strong></p><p><?php echo mysqli_connect_error(); ?></p>
	<?php exit;
}
require_once 'defines.php';
require_once 'functions.php';

require_once 'routes.php';

if(true == defined('MAINTANENCE_MODE') && defined($_REQUEST['debug']) && ($_SERVER['REQUEST_URI'] != "/")) {
	redirect('/');
}

//initiate our message stack.
$MS = new Message_Stack();

$session_token = exists('session_id', $_COOKIE, 0);
if($session_token == "0" && ($_SERVER['REQUEST_URI'] != "/login" && $_SERVER['REQUEST_URI'] != "/login/processLogin" && $_SERVER['REQUEST_URI'] != "/recover_password" && $_SERVER['REQUEST_URI'] != "/reset_password")) {
    redirect('/login');
}
$CUSTOMER = User_Session::tokenFactory($session_token);

if(false == $CUSTOMER->exists() && 0 != $session_token) {
	global $_COOKIE;
	unset($_COOKIE['session_id']);
}

if("" != get_var('cc')) {
	$_SESSION['coupon_code'] = get_var('cc');
}

FB::log($CUSTOMER, 'Global Customer');

//SECURITY MEASURE:
//check this value for true or false if you need to question the authenticity of a POST request.
$XSRF_CHECK = xsrf_check();

//should be configurable probably.
$VIEW = 'home.php';
$LAYOUT_TITLE = "Fyberstudios";
?>