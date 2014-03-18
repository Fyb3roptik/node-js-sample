<?php
require_once 'Controller.php';
require_once 'classes/Category.php';

class Category_Controller extends Controller {
	const DEFAULT_TITLE = 'siing.co';

	public function __construct() {
		if(post_var('view') != 'ajax')
		{
			$this->_setTemplate(new Template('default.php'));
		} else {
			$this->_setTemplate(new Template('ajax.php'));
		}
		$this->_template->bind('LAYOUT_TITLE', 'siing.co Admin');
		$this->_template->bind('ADMIN', $this->_user);
	}

	protected function _requireAdmin() {
		parent::_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('LAYOUT_TITLE', 'siing.co Admin');
		$this->_template->bind('ADMIN', $this->_user);
	}

	public function changeParent() {
		$this->_requireAdmin();
		$return = array('success' => false);
		$C = new Category(post_var('category_id', 0));
		if(true == $C->exists()) {
			try {
				$C->parent_id = post_var('parent_id', 0);
				$C->sort_order = 10000000;
				$C->write();
				$return['success'] = true;
			} catch(Exception $e) {
				//do nothing
			}
		}

		echo json_encode($return);
		exit;
	}

	public function saveSort() {
		$this->_requireAdmin();
		$sort_data = post_var('category', array());
		foreach($sort_data as $parent_id => $parent_data) {
			$PARENT = Object_Factory::OF()->newObject('Category', $parent_id);
			foreach($parent_data as $category_id => $sort_order) {
				$CAT = Object_Factory::OF()->newObject('Category', $category_id);
				if(true == $CAT->exists()) {
					$CAT->parent_id = $PARENT->ID;
					$CAT->sort_order = $sort_order;
					$CAT->write();
				}
			}
		}
		echo json_encode(array('success' => true));
		exit;
	}

	public function chooser($parent_id = 0) {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		
		$sql = "SELECT c.`category_id`
			FROM `categories` c
			WHERE c.`parent_id` = '" . intval($parent_id) . "'
			ORDER BY c.`name`";
		$query = db_query($sql);
		$subcats = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$subcats[] = new Category($rec['category_id']);
		}

		$V = new View('category_chooser.php');
		$V->bind('CAT_LIST', $subcats);
		$this->_setView($V);
	}

	public function processCategory() {
		$this->_requireAdmin();
		$C = new Category(post_var('category_id'));
		$C->show_name = abs(intval(post_var('category_show_name', 0)));
		$category_data = post_var('category', array());
		$category_data['active'] = post_var('category_active', 0);
		$C->parent_id = post_var('parent_id', 0);
		$C->load($category_data);
		$C->write();
		$MS = new Message_Stack();
		$MS->add('category', 'Category successfully saved.', MS_SUCCESS);
		redirect('/admin/category/edit/' . $C->ID);
		exit;
	}

	public function newCategory($parent_id = 0) {
		$this->_requireAdmin();
		$C = new Category();
		$C->name = 'New Category';
		$C->parent_id = $parent_id;
		$VIEW = new View('category_form.php');
		$this->_setView($VIEW);
		$VIEW->bind('C', $C);
		$MS = new Message_Stack();
		$MS->add('category', 'You are editing a new category, you must save this category before you can upload an image for it or manage its widgets.', MS_NORMAL);
		$VIEW->bind('MS', $MS);
	}

	public function edit($category_id) {
		$this->_requireAdmin();
		$C = new Category($category_id);
		if(false == $C->exists()) {
			redirect(LOC_CATEGORIES);
		}
		$VIEW = new View('category_form.php');
		$VIEW->bind('C', $C);
		$VIEW->bind('MS', new Message_Stack());
		$this->_setView($VIEW);
	}

	public function index() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);

		$C = new Category(get_var('category', 0));
		if(false == $C->exists()) {
			$C->name = 'All Categories';
		}
		$V = new View('category_index.php');
		$V->bind('CATEGORY', $C);
		$this->_setView($V);
	}

	public function listCategories($category_id = 0) {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		$C = new Category($category_id);
		$SUBCATEGORY_LIST = $C->find('parent_id', $C->ID, 'sort_order');
		$V = new View('category_index_list.php');
		$V->bind('SUBCATEGORY_LIST', $SUBCATEGORY_LIST);
		$this->_setView($V);
	}

	public function drop() {
		$this->_requireAdmin();
		$this->_requireXsrf();
		$C = new Category(post_var('category_id'));
		$return_vals = array('deleted' => false);
		if(true == $C->exists()) {
			$C->delete();
			if(false == $C->exists()) {
				$return_vals['deleted'] = true;
			}
		}
		echo json_encode($return_vals);
		exit;
	}

	public function view($url) {
		$C = new Category($url, 'url');
		if(false == $C->exists()) {
			$C->name = 'Shop by Shape';
		}
		if(true == $C->exists()) {
			$this->_redirectIfNecessary($C);
		}

		$LAYOUT_TITLE = self::DEFAULT_TITLE . " | " . $C->getTitle();

		$meta_list = $C->getMetaTags();

		$SUBCATEGORY_LIST = $this->_getScrubbedSubcategoryList($C);

		if(count($SUBCATEGORY_LIST) > 0) {
			$this->_displayCategoryChildren($C, $SUBCATEGORY_LIST, $url);
		} else {
			$this->_displayCategoryProducts($C, $url);
		}
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		$this->_template->bind('CUSTOMER', $this->_user);
		$this->_template->bind('META_LIST', $meta_list);
	}

	private function _redirectIfNecessary(Category $C) {
		if(0 == $C->active) {
			$parent = new Category($C->parent_id);
			if(true == $parent->exists()) {
				$this->redirect(get_category_url($parent));
			} else {
				$this->redirect('/');
			}
			exit;
		}
	}

	private function _getScrubbedSubcategoryList(Category $C) {
		$include_sales = false;
		if(true == is_a($this->_user, 'Sales_Rep')) {
			$include_sales = true;
		}

		$SUBCATEGORY_LIST = $C->getSubcategories('sort_order', true, $include_sales);
		if(false == $C->exists()) {
			$SUBCATEGORY_LIST = $C->find('parent_id', $C->ID, 'sort_order');
			foreach($SUBCATEGORY_LIST as $i => $SUBCAT) {
				if(0 == $SUBCAT->active || (false == $include_sales && 1 == $SUBCAT->sales_only)) {
					unset($SUBCATEGORY_LIST[$i]);
				}
			}
		}
		return $SUBCATEGORY_LIST;
	}

	private function _displayCategoryChildren(Category $C, $SUBCATEGORY_LIST, $url="") {
		$view_file = 'category_list.php';
		if(Category::VIEW_MULTI == $C->view) {
			$view_file = 'category_multi_list.php';
		}
		$VIEW =  new View($view_file);
		$VIEW->bind('SUBCATEGORY_LIST', $SUBCATEGORY_LIST);
		if($url != "")
		{
			$VIEW->bind('URL', $url);
			$VIEW->bind('VIEW_AJAX', post_var('view'));
		}
		$this->_setView($VIEW);
		$this->_template->bind('WIDGET_LIST', $this->_getWidgetList($C));
		$VIEW->bind('CUSTOMER', $this->_user);
		$VIEW->bind("BREADCRUMB", $this->_getBreadCrumb($C));
		$VIEW->bind("C", $C);
	}

	private function _displayCategoryProducts(Category $C, $url="") {
		$this->_setTemplate(new Template('product_list.php'));
		$PF = new Product_Finder();
		$PF->addCategory($C);
		if(true == is_a($this->_user, 'Sales_Rep')) {
			$PF->includeSysproOnly();
		}

		$sort_field = get_var('sort', null);
		$sort_order = Product_Finder::ASC;
		if(intval(get_var('order', -1)) > 0) {
			$sort_order = Product_Finder::DESC;
		}

		switch($sort_field) {
			case 'name': {
				$PF->sortByName($sort_order);
				break;
			}

			default: {
				$PF->sortByPrice($sort_order);
				break;
			}
		}

		$pk_params = array(
			'sort' => $sort_field,
			'sort_order' => -1
		);

		$link_params = $pk_params;
		$link_params['page'] = get_var('page', 1);
		$link_params['sort'] = 'price';
		$link_params['order'] = -1;
		$price_low_link = format_url("", $link_params);

		$link_params['sort'] = 'name';
		$name_low_link = format_url("", $link_params);

		$link_params['order'] = 1;
		$name_high_link = format_url("", $link_params);

		$link_params['sort'] = 'price';
		$price_high_link = format_url("", $link_params);

		$PRODUCT_SQL = $PF->getSQL();
		$this->_template->bind('PRODUCT_SQL', $PRODUCT_SQL);
		FB::log($PRODUCT_SQL, "Raw Query");
		$PK = new Page_Killer($PRODUCT_SQL, 20, get_var('page', 1));
		$PRODUCT_LIST = $PK->query();
		FB::log($PRODUCT_LIST, "Product List");
		$PK_LINKS = $PK->getLinks($pk_params);

		$VIEW = new View('product_list.php');
		$VIEW->bind('CUSTOMER', $this->_user);
		$this->_setView($VIEW);
		if($url != "")
		{
			$VIEW->bind('URL', $url);
			$VIEW->bind('VIEW_AJAX', post_var('view'));
		}
		$VIEW->bind('PK_LINKS', $PK_LINKS);
		$VIEW->bind('PRODUCT_LIST', $PRODUCT_LIST);
		$VIEW->bind('PF', $PF);
		if(intval($C->show_name) > 0) {
			$VIEW->bind('PAGE_TITLE', $C->getDisplayName());
		}
		$VIEW->bind('HTML_HEADER', $C->header);
		$VIEW->bind('HTML_FOOTER', $C->footer);
		$VIEW->bind('BREADCRUMB', $this->_getBreadCrumb($C));
		$VIEW->bind('PRICE_HIGH_LINK', $price_high_link);
		$VIEW->bind('PRICE_LOW_LINK', $price_low_link);
		$VIEW->bind('NAME_HIGH_LINK', $name_high_link);
		$VIEW->bind('NAME_LOW_LINK', $name_low_link);
		FB::log($PF, "Product Finder");
		if(0 == count($PRODUCT_LIST) && 'product_list.php' == $VIEW) {
			$LAYOUT = 'layouts/default.php';
			$VIEW = 'no_products_found.php';
		}
		$this->_template->bind('WIDGET_LIST', $this->_getWidgetList($C));
		$VIEW->bind('C', $C);
	}

	protected function _getBreadCrumb(Category $C) {
		$crumbs = $C->breadcrumb();
		array_pop($crumbs);
		return $crumbs;
	}

	private function _getWidgetList(Category $category) {
		$list = new Global_Widget_List();
		$CW = new Category_Widget();
		$cw_list = $CW->find('category_id', $category->ID);
		if(count($cw_list) > 0) {
			$list = new Widget_List();
			foreach($cw_list as $widget) {
				$WB = new Widget_Builder($widget->widget_id);
				$list->addWidget($WB->build());
			}
		}
		return $list;
	}

	public function processImage() {
		$this->_requireAdmin();
		$C = new Category(post_var('category_id'));
		if(false == $C->exists()) {
			redirect(LOC_CATEGORIES);
			exit;
		}
		$MS = new Message_Stack();
		global $_FILES;
		$file = exists('category_image', $_FILES);
		if(false == is_array($file)) {
			$MS->add('category', 'There was a problem uploading the image.', MS_ERROR);
			redirect('/admin/category/edit/' . $C->ID);
			exit;
		}
		$file_info = pathinfo($file['name']);
		$file_name = $C->ID . '.' . $file_info['extension'];
		$file_path = DIR_ROOT . 'images/categories/' . $file_name;
		rename($file['tmp_name'], $file_path);
		if(true == file_exists($file_path)) {
			$C->image = $file_name;
			$C->write();
			$MS->add('category', 'Image uploaded successfully.', MS_SUCCESS);
		} else {
			$MS->add('category', 'Image upload failed.', MS_ERROR);
		}
		redirect('/admin/category/edit/' . $C->ID);
		exit;
	}

	public function changeNavActive() {
		$this->_requireAdmin();
		$C = new Category(post_var('category_id'));
		if(true == $C->exists()) {
			$C->nav = intval(post_var('active'));
			$C->write();
			echo json_encode(array('success' => true));
		}
		exit;
	}

	public function getChildren($parent_id = 0) {
		$parent_id = abs(intval($parent_id));
		$categories = array('children' => array());
		$sql = SQL::get()
			->select('category_id, name')
			->from('categories')
			->where("parent_id = '@parent_id'")
			->bind('parent_id', $parent_id)
			->orderBy('name');
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$categories['children'][] = array(
				'id' => $rec['category_id'],
				'name' => $rec['name']
			);
		}
		echo json_encode($categories);
		exit;
	}
}
?>
