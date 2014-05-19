<?php
require_once 'inc/global.php';

FB::log(get_var('url'), 'Router Url');
$route = Router::route(get_var('url'));
$url = Router::parseUrl(get_var('url'));
if($url[1] == "") {
	$url[1] = "view";
}

if(is_file($url[0] . ".php")) {
    include_once($url[0] . ".php");
    exit;
}


FB::log($route, 'Route');

$customer_sentry = new Customer_Sentry($CUSTOMER);

try {
	//try our normal MVC routing
	$D = new Dispatcher($route['controller'], $route['action'], $route['id']);
	$D->setUser($CUSTOMER);
	if(true == $CUSTOMER->exists()) {
		$D->setSentry($customer_sentry);
	}
	$D->dispatch();
} catch(Dispatcher_Permission_Exception $e) {
	redirect('/denied');
} catch(Controller_Not_Found_Exception $e) {
	//otherwise, try to find a DJ
	$C = new Customer($url[0], 'username');
	if(true == $C->exists()) {
	//	header("HTTP/1.1 301 Moved Permanently");
		$D = new Dispatcher('Customer_Controller', $url[1], $url[0], $url[2]);
		$D->setUser($CUSTOMER);
		if(true == $CUSTOMER->exists()) {
			$D->setSentry($customer_sentry);
		}
		$D->dispatch();
	} else {
    	redirect('/404');
	}
}
?>
