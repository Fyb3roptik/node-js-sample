<?php
require_once dirname(__FILE__) . '/Object_Factory.php';

class Breadcrumb_Parser {
	public static function parse($url) {
		$url_parts = parse_url($url);
		$path = $url_parts['path'];
		list($cat_url) = sscanf($path, "/category/%s");
		$cat_url = str_replace('/', '', $cat_url);
		return $cat_url;
	}

	public static function getCategory($url) {
		$cat_url = self::parse($url);
		$C = new Category($cat_url, 'url');
		if(false == $C->exists()) {
			$C = null;
		}
		return $C;
	}
}
?>
