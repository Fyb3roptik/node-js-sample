<?php
/******************************************************
 *  All functions contained within should be agnostic *
 * toward the file they are being called from.		*
 ******************************************************/

/**
 * Converts a string for a URL, stripping out any bad stuff.
 */
function convert_for_url($string) {
	$string = trim($string);
	$search = array(" ", "#", "'", '"', "/", ".", '&');
	$replace = array("-", "", "", "", "", "", '&amp;');
	return str_replace($search, $replace, $string);
}

/**
 * Checks whether or not a key exists in an array and returns the value.
 */
function exists($key, $array = array(), $default = null) {
	$return_value = $default;
	if(true == is_array($array) && true == array_key_exists($key, $array)) {
		$return_value = $array[$key];
	}
	return $return_value;
}

function format_url($resource, $params = array()) {
	$url = $resource;
	if(count($params) > 0) {
		$url .= '?';
		$formatted_params = array();
		foreach($params as $key => $value) {
			$formatted_params[] = $key . "=" . $value;
		}
		$url .= implode('&amp;', $formatted_params);
	}
	return $url;
}

/**
 * Returns an associative array of `abbreviation` => `state_name`.
 */
function get_states() {
	$sql = "SELECT abbr
		FROM `states`
		ORDER BY state";
	$query = db_query($sql);
	$states = array();
	while($query->num_rows > 0 && $s = $query->fetch_assoc()) {
		$states[$s['abbr']] = $s['abbr'];
	}
	return $states;
}

function get_fullname_states($country = "United States") {
	$sql = "SELECT state, abbr
		FROM `states`
		WHERE country = '".$country."'
		ORDER BY state";
	$query = db_query($sql);
	$states = array();
	while($query->num_rows > 0 && $s = $query->fetch_assoc()) {
		$states[$s['abbr']] = $s['state'];
	}
	return $states;
}

function get_countries () {
	$sql = "SELECT DISTINCT country FROM `states` ORDER BY country";
	$query = db_query($sql);
	$countries = array();
	while($query->num_rows > 0 && $s = $query->fetch_assoc()) {
		$countries[$s['country']] = $s['country'];
	}
	return $countries;
}

/**
 * Returns the proper view file.
 */
function get_view() {
	global $VIEW;
	$VIEW = trim($VIEW);
	$view = 'views/' . $VIEW;
	$view_file = 'inc/views/' . $VIEW;
	if(false == file_exists($view_file)) {
		$view = 'views/' . VIEW_404;
	}
	return $view;
}

/**
 * Hashes out a password.
 */
function passwordify($password, $salt = null) {
	$hash = sha1($salt . $password . $salt);
	return $hash;
}

/**
 * Calls print_r() inside of <pre> brackets.
 */
function pprint_r($thing) {
	echo '<pre>' . print_r($thing, true) . '</pre>';
}

/**
 * Formats a price.
 */
function price_format($number) {
	$negative_flag = false;
	if($number < 0) {
		$negative_flag = true;
	}

	$formatted_price = "$" . number_format(abs($number), 2, '.', ',');
	if(true == $negative_flag) {
		$formatted_price = '-' . $formatted_price;
	}
	return $formatted_price;
}

/**
 * Returns a random value of a given length.
 */
function random_value($length = 8) {
	$length = abs(intval($length));
	$corpus = array(range(0, 9), range('a', 'z'), range('A', 'Z'));
	$value = '';
	while(strlen($value) < $length) {
		shuffle($corpus);
		shuffle($corpus[0]);
		$value .= $corpus[0][0];
	}
	return $value;
}

/**
 * Attempts to redirect to a URL.
 */
function redirect($url, $params = null) {
	Redirector::get()->redirect($url, $params);
}

/**
 * Sanitize a string? Yes.
 */
function sanitize_string($string) {
	if(true == is_string($string)) {
		$string = strip_tags($string);
		$bad_stuff = array("\\", '"', '"', '>', '<');
		$string = str_replace($bad_stuff, null, $string);
		$string = str_replace('&', '&amp;', $string);
		$string = strip_tags($string);
		$string = trim($string);
	}
	return $string;
}

/**
 * This func takes a list of products and produces an array of
 * attributes and values associated with that list o' products for
 * use in narrowing down.
 */
function find_attributes($product_list = array()) {
	$cleaned_products = array();
	foreach($product_list as $i => $product_id) {
		$cleaned_products[] = intval($product_id);
	}

	$imploded_products = implode(',', $cleaned_products) . "";
	$product_count = count($cleaned_products);

	$attributes = array();
	if($product_count > 0) {
		unset($cleaned_products);
		unset($product_list);

		$table = "temp_" . sha1(session_id());

		$sql = "DROP TABLE IF EXISTS `" . $table . "`";
		db_query($sql);

		$sql = "CREATE TEMPORARY TABLE `" . $table . "` (
			`attribute_id` INT( 11 ) NOT NULL ,
			`value_id` INT( 11 ) NOT NULL ,
			`product_count` INT( 5 ) NOT NULL ,
			INDEX ( `attribute_id` , `value_id` )
			) ENGINE = MEMORY" ;

		db_query($sql);

		$sql = "INSERT INTO `" . $table . "`
			SELECT pa.attribute_id, pa.attribute_value_id, count(pa.product_id)
			FROM `products_attributes` pa
				LEFT JOIN `attributes` a
					ON pa.attribute_id = a.attribute_id
			WHERE a.narrow = 1
				AND pa.product_id IN (" . $imploded_products . ")
			GROUP BY pa.attribute_id, pa.attribute_value_id";
		db_query($sql);

		$sql = "SELECT t.attribute_id, sum(t.product_count) AS attribute_sum
			FROM `" . $table . "` t
			GROUP BY t.attribute_id
			ORDER BY `attribute_sum` DESC
			LIMIT 10";
		$query = db_query($sql);
		while($query->num_rows > 0 && $a = $query->fetch_assoc()) {
			$attribs[] = $a['attribute_id'];
		}

		$sql = "SELECT t.attribute_id, a.name, t.value_id, av.value, sum(t.product_count) AS attribute_sum
			FROM `" . $table . "` t
				LEFT JOIN `attributes` a
					ON t.attribute_id = a.attribute_id
				LEFT JOIN `attribute_values` av
					ON t.value_id = av.attribute_value_id
			WHERE t.attribute_id IN (" . implode(',', $attribs) . ")
			GROUP BY t.attribute_id, a.name, t.value_id, av.value
			ORDER BY a.name ASC, av.value ASC, `attribute_sum` DESC";
		$query = db_query($sql);

		while($query->num_rows > 0 && $a = $query->fetch_assoc()) {
			$attribute_id = $a['attribute_id'];
			$attributes[$attribute_id]['name'] = $a['name'];
			$attributes[$attribute_id]['values'][$a['value_id']] = array(
										'value' => $a['value'],
										'count' => $a['attribute_sum']
										);
		}

		$sql = "DROP TABLE `" . $table . "`";
		db_query($sql);
	}

	return $attributes;
}

/**
 * By the power of greyskull / regex this function validates email addresses.
 */
function validate_email($email_address) {
	$email_address = trim($email_address);
	$email_address = filter_var($email_address, FILTER_SANITIZE_EMAIL);
	$valid = false;
	if(false !== filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
		$valid = true;
	}
	return $valid;
}

/**
 * Finds the value from the global $_GET array.
 */
function get_var($var_key, $default_value = null) {
	global $_GET;
	return exists($var_key, $_GET, $default_value);
}

/**
 * Finds the value from the global $_POST array.
 */
function post_var($var_key, $default_value = null) {
	global $_POST;
	return exists($var_key, $_POST, $default_value);
}

/**
 * Finds the value from the global $_REQUEST array.
 */
function request_var($var_key, $default_value = null) {
	global $_REQUEST;
	return exists($var_key, $_REQUEST, $default_value);
}

/**
 * Finds the value from the global $_SESSION array.
 */
function session_var($var_key, $default_value = null) {
	global $_SESSION;
	return exists($var_key, $_SESSION, $default_value);
}

function cart_subtotal(Cart_Interface $C) {
	$subtotal = 0;
	foreach($C->getProducts() as $i => $CP) {
		$subtotal += $CP->getFinalPrice();
	}
	return $subtotal + cart_ubd_markdown_subtotal($C);
}

function cart_ubd_markdown_subtotal(Cart_Interface $C) {
	$subtotal = 0;
	foreach($C->getProducts() as $i => $CP) {
		$mod = $CP->getMod();
		if(true == is_a($mod, 'Utility_Mod') && Utility_Mod::MARKDOWN == $mod->mod_type) {
			$P = new Product($CP->getProductID());
			$unit_difference = $P->getPrice($CP->getQuantity()) - $mod->price;
			$subtotal += ($unit_difference * $CP->getQuantity());
		}
	}
	return $subtotal;
}

/**
 * Convert HTML into escaped characters so
 *
 * Ejemplo: <p>foobar</p> => &lt;p&gt;foobar&lt;/p&gt;
 */
function convert_for_tinymce($html) {
	$html = trim($html);
	$search_chars = array('<', '>');
	$replace_chars = array('&lt;', '&gt;');

	return str_replace($search_chars, $replace_chars, $html);
}

/**
 * Get a URL for a given Category.
 */
function get_category_url(Category $category) {
	$url = '/category/' . urlencode($category->url) . '/';
	return $url;
}

/**
 * Get a link to the URL for a given Category.
 */
function get_category_link(Category $category) {
	$link = '<a href="' . get_category_url($category) . '">' . $category->name . '</a>';
	return $link;
}

function address_book_options(Customer $CUSTOMER) {
	$address_book = $CUSTOMER->getAddressBook();
	$ADDRESS_OPTIONS = array();
	$ADDRESS_OPTIONS['new'] = 'New Address';
	foreach($address_book as $address) {
		$ADDRESS_OPTIONS[$address->getID()] = $address->getNickname();
	}
	return $ADDRESS_OPTIONS;
}

function address_book_dump(Customer $CUSTOMER) {
	$address_book = $CUSTOMER->getAddressBook();
	$ADDRESS_DUMP = array();
	foreach($address_book as $address) {
		$ADDRESS_DUMP[$address->getID()] = $address->dataDump();
	}
	$address_template = new Customer_Address();
	$ADDRESS_DUMP['new'] = $address_template->dataDump();
	unset($address_template);
	return $ADDRESS_DUMP;
}

function obfuscate_cc_number($number) {
	$number_length = strlen($number);
	$last_four = substr($number, -4);
	$first_digits = str_repeat('&bull;', $number_length - 4);
	return $first_digits . ' '. $last_four;
}

function draw_expires_month_select($default_month = '01') {
	$months = array('0' => 'Month', '1' => '01','2' => '02','3' => '03','4' => '04','5' => '05','6' => '06','7' => '07','8' => '08','9' => '09','10' => '10','11' => '11','12' => '12');
	return draw_select('credit_card[expires_month]', $months, $default_month);
}

function draw_expires_year_select($default_year = null) {
	$years = range(date('Y'), date('Y') + 10);
	$year_options = array('0' => 'Year');
	foreach($years as $i => $year) {
		$year_options[$year] = $year;
	}
	return draw_select('credit_card[expires_year]', $year_options, $default_year);
}

function get_nav_items($active_only = false) {
	$sql = "SELECT category_nav_item_id
		FROM category_nav_items
		ORDER BY sort_order";
	$query = db_query($sql);
	$nav_item_list = array();
	while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
		$nav_item = new Category_Nav_Item($rec['category_nav_item_id']);
		if((true == $active_only && 1 == $nav_item->getActive()) || false == $active_only) {
			$nav_item_list[] = $nav_item;
		}
	}
	return $nav_item_list;
}

function checkSecureSite($url) {
    if(1 == FORCE_SSL)
	{
		if($_SERVER['HTTPS'] != "on") {
			redirect(SITE_SECURE_URL.$url, $_REQUEST);
		}
	}
}
?>