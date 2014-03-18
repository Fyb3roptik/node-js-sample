<?php
require_once dirname(__FILE__) . '/standard_lib.php';

/**
 * Draws a string of parameters for a URL.
 */
function draw_get_param_string($params = array()) {
	$get_vars = null;
	$param_array = array();
	foreach($params as $key => $value) {
		$value = trim($value);
		if(false == empty($value)) {
			$param_array[] = $key.'='.$value;
		}
	}
	if(count($param_array) > 0) {
		$get_vars = '?' . implode('&amp;', $param_array);
	}
	return $get_vars;
}

/**
 * Draws a select input field with given options / params / default value.
 */
function draw_select($name = "select", $options = array(), $default = null, $params = null, $firstchoice = null, $useselect = true) {
	$select_string = "";
	if(true == $useselect) {
		$select_string = '<select name="' . $name . '" ' . $params . ' >';
	}
	if($firstchoice != null) {
		$select_string .= '<option value="">-- '.$firstchoice.' --</option>';
	}
	foreach($options as $value => $option) {
		$select_string .= '<option name="' . $option .'" value="' . $value . '" ';
		if($value == $default) {
			$select_string .= 'selected="selected"';
		}
		$select_string .= '>' . $option . '</option>';
	}
	if(true == $useselect) {
		$select_string .= '</select>';
	}
	return $select_string;
}

function draw_hidden($name, $value, $params = null) {
	$input_string = '<input type="hidden" name="' . sanitize_string($name) . '" value="' . sanitize_string($value) . '" ' . $params . ' />';
	return $input_string;
}

function draw_checkbox($name, $value, $checked = false, $params = null) {
	$input_string = '<input type="checkbox" name="' . sanitize_string($name) . '" value="' . sanitize_string($value) . '" ' . $params;

	if(true == $checked) {
		$input_string .= ' checked="checked"';
	}
	$input_string .= ' />';
	return $input_string;
}

function draw_radio($name, $value, $checked = false, $params = null) {
	$input_string = '<input type="radio" name="' . sanitize_string($name) . '" value="' . sanitize_string($value) . '" ' . $params;

	if(true == $checked) {
		$input_string .= ' checked="checked"';
	}
	$input_string .= ' />';
	return $input_string;
}
?>