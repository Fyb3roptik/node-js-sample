<?php
require_once 'Object.php';

/**
 * Handles all things related to Categories.
 */
class Category extends Object {
	protected $_table = 'categories';
	protected $_table_id = 'category_id';

	protected $_unsanitized_fields = array('header', 'footer');

	private $_subcategories = array();

	protected $_set_hooks = array('parent_id' => 'parentSetter',
						'header' => 'sanitizeContent',
						'footer' => 'sanitizeContent',
						'view' => 'setView');
	protected $_get_hooks = array('header' => 'sanitizeContent',
					'footer' => 'sanitizeContent',
					'active' => '_getActive');
	protected $_default_vals = array(
		'active' => 1,
		'view' => self::VIEW_NORMAL,
		'show_name' => 1,
		'sales_only' => 0
	);

	const VIEW_NORMAL = 'normal';
	const VIEW_MULTI = 'multi';

	protected function _load($ID, $field = null) {
		parent::_load($ID, $field);
		if(true == $this->exists() && intval($this->ID) == intval($this->parent_id)) {
			$this->parent_id = 0;
		}
	}

	public function getDisplayName() {
		$name = $this->name;
		if(strlen($this->long_name) > 0) {
			$name = $this->long_name;
		}
		return $name;
	}

	/**
	 * Get the title to be used in a page's title tag.
	 */
	public function getTitle() {
		$title = $this->name;
		if(strlen($this->title_override) > 0) {
			$title = $this->title_override;
		}
		return $title;
	}

	public function setView($view) {
		$good_views = array(self::VIEW_NORMAL, self::VIEW_MULTI);
		if(false == in_array($view, $good_views)) {
			throw new Exception("Bad category view type!");
		}
		return $view;
	}

	protected function _getActive($active) {
		$parent = Object_Factory::OF()->newObject('Category', $this->parent_id);
		if(true == $parent->exists() && false == $real_active) {
			if(0 == $parent->active) {
				$active = 0;
			}
		}
		return $active;
	}

	public function getActive($real_active = false) {
		$active = $this->_data['active'];
		if(false == $real_active) {
			$active = $this->_getActive($active);
		}
		return $active;
	}

	public function getMetaTags() {
		$meta_list = array();

		$meta_description = $this->getMetaDescription();
		if(false == empty($meta_description)) {
			$MT = new Meta_Tag();
			$MT->name = 'description';
			$MT->content = $meta_description;
			$meta_list[] = $MT;
		}

		$meta_keywords = $this->getMetaKeywords();
		if(false == empty($meta_keywords)) {
			$MT = new Meta_Tag();
			$MT->name = 'keywords';
			$MT->content = $meta_keywords;
			$meta_list[] = $MT;
		}

		return $meta_list;
	}

	public function getMetaDescription() {
		$description = $this->meta_description;
		if(true == empty($description)) {
			$parent = Object_Factory::OF()->newObject('Category', $this->parent_id);
			if(true == $parent->exists()) {
				$description = $parent->getMetaDescription();
			}
		}
		return $description;
	}

	public function getMetaKeywords() {
		$keywords = $this->meta_keywords;
		if(true == empty($keywords)) {
			$parent = Object_Factory::OF()->newObject('Category', $this->parent_id);
			if(true == $parent->exists()) {
				$keywords = $parent->getMetaKeywords();
			}
		}
		return $keywords;
	}

	/**
	 * Returns the category ID, plus every ID of a subcategory / etc etc.
	 */
	public function allIDs($limit = null) {
		if(false == is_null($limit)) {
			$limit = intval($limit) - 1;
		}
		$category_list = array($this->_ID);

		if(true == is_null($limit) || $limit > 0) {
			$this->_loadSubcategories();
			foreach($this->_subcategories as $subcat) {
				if(intval($subcat->ID) !== intval($this->ID)) {
					$category_list = array_merge($category_list, $subcat->allIDs($limit));
				}
			}
		}
		return $category_list;
	}

	public function breadcrumb($reverse = true) {
		$breadcrumb = array();
		if(true == $this->exists()) {
			$breadcrumb[] = $this;
			$parent = new Category($this->parent_id);
			if(true == $parent->exists()) {
				$breadcrumb = array_merge($breadcrumb, $parent->breadcrumb(false));
			}
		}
		if(true == $reverse) {
			$breadcrumb = array_reverse($breadcrumb);
		}
		return $breadcrumb;
	}

	public function getSubcategories($sort_field = null, $active_only = true, $include_sales = false) {
		$this->_loadSubcategories($sort_field);
		$subcat_list = $this->_subcategories; 
		if(true == $active_only) {
			$subcat_list = array();
			foreach($this->_subcategories as $i => $subcat) {
				if(1 == $subcat->active) {
					$subcat_list[] = $subcat;
				}
			}
		}

		if(false == $include_sales) {
			foreach($subcat_list as $i => $subcat) {
				if(1 == $subcat->sales_only) {
					unset($subcat_list[$i]);
				}
			}
		}
		return $subcat_list;
	}

	public function getNavCategories() {
		$C = new Category();
		$subcat_list = array();
		if(true == $this->exists()) {
			$subcats_raw = $C->find('parent_id', $this->ID, 'nav_sort_order');
			foreach($subcats_raw as $i => $subcat) {
				if(1 == $subcat->nav && 1 == $subcat->active) {
					$subcat_list[] = $subcat;
				}
			}
		}
		return $subcat_list;
	}

	private function _loadSubcategories($sort_field = null) {
		if(intval($this->ID) > 0) {
			$SC = new Category();
			$this->_subcategories = $SC->find('parent_id', $this->ID, $sort_field);
		}
	}

	public function setParent($parent_id) {
		$this->parent_id = intval($parent_id);
	}

	public function parentSetter($parent_id) {
		$parent_id = abs(intval($parent_id));
		if(true == $this->exists() && $parent_id > 0) {
			$subcategory_ids = $this->allIDs(50);
			if(true == in_array($parent_id, $subcategory_ids)) {
				$parent_id = intval($this->_data['parent_id']);
			}
		}
		return $parent_id;
	}

	public function getPageWidgets() {
		$CW = new Category_Widget();
		return $CW->find('category_id', $this->ID);
	}

	/**
	 * Override Object::delete() to delete subcategories.
	 */
	public function delete() {
		$subcategory_list = $this->getSubcategories(null, false, true);
		foreach($subcategory_list as $i => $subcat) {
			$subcat->delete();
		}
		parent::delete();
	}

	public function sanitizeContent($content) {
		return stripslashes($content);
	}

	public function getImageUrl() {
		$image = '/images/categories/' . $this->image;
		if(0 == strlen($this->image)) {
			$image = '/images/bulbimage.jpg';
		}
		return $image;
	}

	public function getProductCount() {
		$product_count = 0;
		$id_list = $this->allIDs();
		if(count($id_list) > 0) {
			$sql = "SELECT COUNT(DISTINCT pc.`product_id`) as product_count
				FROM products_categories pc
				WHERE category_id IN (" . implode(',', $id_list) .")";
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$product_count = intval($rec['product_count']);
			}
		}
		return $product_count;
	}
}
?>