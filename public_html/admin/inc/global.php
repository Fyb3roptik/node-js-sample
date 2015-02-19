<?php
//admin shares the config file with the front end.
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(dirname(__FILE__))) . '/inc');
require_once 'config.php';
session_start();

@$DB = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//handle database connection errors...
//TODO: email admin on error, produce a styled error page.
if(mysqli_connect_errno()) { ?><center><h1>whoops!</h1><p><strong>Could not connect to database:</strong></p><p><?php echo mysqli_connect_error(); ?></p><?php exit; }

require_once 'FirePHPCore/fb.php';
if(true == defined('ENABLE_FIREPHP') && true == ENABLE_FIREPHP) {
	ob_start();
}
FB::setEnabled(ENABLE_FIREPHP);
FB::group('globals.php');
FB::log("Firebug as been enabled in admin/inc/global.php");
FB::log($_POST, "POST");
FB::log($_GET, "GET");
FB::log($_COOKIE, "COOKIE");
FB::log($_REQUEST, "REQUEST");
FB::log($_FILES, "FILES");
FB::groupEnd();

require_once 'defines.php';
require_once 'functions.php';
require_once 'routes.php';
$MS = new Message_Stack();
FB::log($MS, 'Message_Stack');

$TP = Template_Provider::get();
$TP->layout_path = dirname(__FILE__) . '/layouts/';
$TP->view_path = dirname(__FILE__) . '/views/';

$VIEW = 'home.php';
$LAYOUT_TITLE = 'Beast Franchise | Admin';

$token = exists('admin_token', $_COOKIE, null);
$ADMIN = User_Session::tokenFactory($token, User::TYPE_ADMIN);
if(true == $ADMIN->exists()) {
	$token = $ADMIN->getToken();
	$expires = time() + PASSWORD_TOKEN_EXPIRATION;
	setcookie('admin_token', $token, $expires, '/admin/', $_SERVER['SERVER_NAME']);
}

if((false == $ADMIN->exists() && LOC_LOGIN != $_SERVER['REQUEST_URI']) || false == is_a($ADMIN, 'Admin')) {
	redirect(LOC_LOGIN);
}

FB::log($ADMIN, 'ADMIN');
?>