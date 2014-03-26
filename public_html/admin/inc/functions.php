<?php
//require some stuff from our main library
require_once DIR_ROOT_FUNCTIONS . 'db.php';
require_once DIR_ROOT_FUNCTIONS . 'html.php';
require_once DIR_ROOT_FUNCTIONS . 'standard_lib.php';

/**
 * Attempts to load our classes from admin/inc/classes/ then from the root class lib.
 */
function __autoload($class) {
	$class_file = 'inc/classes/' . trim($class) . '.php';
	$controller_file = 'inc/controllers/' . trim($class). '.php';
	$model_file = 'inc/models/' . trim($class) . '.php';
	$places_to_look = array($class_file, $controller_file, $model_file);
	foreach($places_to_look as $place) {
		if(true == file_exists($place)) {
			require_once $place;
		} elseif(true == file_exists(DIR_ROOT . $place)) {
			require_once DIR_ROOT . $place;
		}
	}
}

function product_edit_url(Product $P) {
	return '/admin/product/edit/' . $P->ID . '/';
}

/**
 * Draws a hidden input field used by global.php to make sure the user
 * actually posted the request and it didn't actually come form some
 * fake referer.
 */
function draw_xsrf_field() {
	global $ADMIN;
	$field = null;
	if(true == $ADMIN->exists()) {
		$field = '<input type="hidden" name="' . get_xsrf_field_name() . '" value="' . get_xsrf_field_value() . '" />';
	}
	return $field;
}

function get_xsrf_field_name() {
	global $ADMIN;
	$field = null;
	if(true == $ADMIN->exists()) {
		$field = md5($ADMIN->session_token);
	}
	return $field;
}

function get_xsrf_field_value() {
	global $ADMIN;
	$value = null;
	if(true == $ADMIN->exists()) {
		$value = sha1($ADMIN->session_token);
	}
	return $value;
}

/**
 * Returns true if it finds the xsrf value in the post.
 */
function xsrf_check() {
	global $_POST, $ADMIN;
	$passed = false;
	if(true == $ADMIN->exists()) {
		$value_field = get_xsrf_field_name();
		if(true == exists($value_field, $_POST)) {
			//looks like the user did, in fact post this request.
			$passed = true;
		}
	}
	return $passed;
}

/**
 * Draws a select field of configured widgets.
 */
function draw_widget_select($name = 'widget_id', $default_value = null, $params = null) {
	$sql = "SELECT widget_id, nickname, widget_class
		  FROM `widgets`
		  ORDER BY `nickname`";
	$options = array(0 => '-Select Widget');
	$query = db_query($sql);
	while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
		$options[$rec['widget_id']] = $rec['nickname'] . ' (' . $rec['widget_class'] . ')';
	}
	return draw_select($name, $options, $default_value, $params);
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = '//www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}
?>