<?php
require_once "Object.php";

class Category_Nav_Item_Column extends Object {
	protected $_table = 'category_nav_item_columns';
	protected $_table_id = 'category_nav_item_column_id';

	public function getTopCategory() {
		$parent_nav = Object_Factory::OF()->newObject('Category_Nav_Item', $this->nav_item_id);
		return $parent_nav->category_id;
	}

	public function addCategory(Category $category) {
		if(true == $category->exists() && true == $this->exists()) {
			$category->nav = 1;
			$category->nav_column_id = $this->ID;
			$category->nav_sort_order = 100000000;
			$category->write();
			$subcats = $category->getSubcategories();
			foreach($subcats as $i => $cat) {
				$cat->nav = 1;
				$cat->write();
			}
		}
	}

	public function getCategories($active_only = false) {
		$cat_list = array();
		if(true == $this->exists()) {
			$C = new Category();
			$cat_list = $C->find('nav_column_id', $this->ID, 'nav_sort_order');
			if(true == $active_only) {
				foreach($cat_list as $i => $cat) {
					if(0 == $cat->active) {
						unset($cat_list[$i]);
					}
				}
			}
		}
		return $cat_list;
	}
}
?>
