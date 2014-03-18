<?php
class Template_Provider {
	private static $_instance;

	public $view_path;
	public $layout_path;

	private function __construct() { /* nada nada */ }

	public static function get() {
		if(false == isset(self::$_instance)) {
			$C = __CLASS__;
			self::$_instance = new $C();
		}
		return self::$_instance;
	}

	public static function getView($view_file) {
		$TP = self::get();
		$path = $TP->getViewPath() . '/' . $view_file;
		return $path;
	}

	public function getViewPath() {
		if(false == isset($this->view_path)) {
			$this->view_path = realpath(dirname(__FILE__) . '/../views/');
		}
		return $this->view_path;

	}

	public static function getLayout($layout_file) {
		$TP = self::get();
		$path = $TP->getLayoutPath() . '/' . $layout_file;
		return $path;
	}

	public function getLayoutPath() {
		if(false == isset($this->layout_path)) {
			$this->layout_path = realpath(dirname(__FILE__) . '/../layouts/');
		}
		return $this->layout_path;

	}
}
?>
