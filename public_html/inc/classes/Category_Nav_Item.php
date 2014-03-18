<?php
require_once 'Object.php';

class Category_Nav_Item extends Object {
	protected $_table = 'category_nav_items';
	protected $_table_id = 'category_nav_item_id';

	private $_category;

	public function getName() {
		$this->_loadCategory();
		return $this->_category->name;
	}

	private function _loadCategory() {
		if(true == $this->exists() && false == isset($this->_category)) {
			$this->_category = Object_Factory::OF()->newObject('Category', $this->category_id);
		}
	}

	public function getColumns() {
		$col = new Category_Nav_Item_Column();
		return $col->find('nav_item_id', $this->ID, 'sort_order');
	}

	public function getActive() {
		$this->_loadCategory();
		return $this->_category->active;
	}
}
?>