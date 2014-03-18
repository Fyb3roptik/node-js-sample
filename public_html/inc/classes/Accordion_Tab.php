<?php
require_once 'Object.php';
abstract class Accordion_Tab extends Object {
	protected $_default_vals = array('type' => self::TYPE_HTML, 'default_view' => self::CLOSED);
	protected $_set_hooks = array('type' => 'setType', 'default_view' => 'setDefaultView');
	protected $_unsanitized_fields = array('data');
	protected $_get_hooks = array('data' => 'cleanData');

	const TYPE_GALLERY = 'gallery';
	const TYPE_HTML = 'html';
	const TYPE_PDF = 'pdf';
	const TYPE_OVERVIEW = 'overview';

	const OPEN = 1;
	const CLOSED = 2;

	public function setType($type) {
		$good_types = array(
			self::TYPE_GALLERY, 
			self::TYPE_HTML, 
			self::TYPE_PDF,
			self::TYPE_OVERVIEW);
		if(false == in_array($type, $good_types)) {
			throw new Exception("Bad Product_Tab type.");
		}
		return $type;
	}

	public function cleanData($data) {
		return stripslashes($data);
	}

	public function setDefaultView($default) {
		$default = abs(intval($default));
		$good_types = array(self::OPEN, self::CLOSED);
		if(false == in_array($default, $good_types)) {
			throw new Exception("Bad default view.");
		}
		return $default;
	}

	public function render() {
		switch($this->type) {
			case Product_Tab::TYPE_HTML: {
				echo stripslashes($this->data);
				break;
			}

			case Product_Tab::TYPE_GALLERY: {
				$TEMPLATE = new Html_Template('inc/modules/product_gallery.php');
				$TEMPLATE->bind('P', new Product($this->product_id));
				$TEMPLATE->render();	
				break;
			}

			case Product_Tab::TYPE_PDF: {
				$TEMPLATE = new Html_Template('inc/modules/pdf_tab.php');
				$TEMPLATE->bind('FILE', stripslashes($this->data));
				$TEMPLATE->render();
				break;
			}

			case Product_Tab::TYPE_OVERVIEW: {
				$TEMPLATE = new Html_Template('inc/modules/product_overview.php');
				$TEMPLATE->bind('P', new Product($this->product_id));
				$TEMPLATE->render();
				break;
			}
		}
	}
}
