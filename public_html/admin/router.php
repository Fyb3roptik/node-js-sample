<?php
require_once 'inc/global.php';
require_once dirname(__FILE__) . '/inc/admin_permissions.php';


$route = Router::route(get_var('url'));
FB::log($route, 'Route');

$admin_sentry = new Admin_Sentry($ADMIN);

try {
	$D = new Dispatcher($route['controller'], $route['action'], $route['id']);
	$D->setUser($ADMIN);
	$D->setSentry($admin_sentry);
	$D->dispatch();
} catch(Dispatcher_Permission_Exception $e) {
	redirect('/admin/denied/');
}
?>
