<?php
require_once 'Controller.php';

class Nav_Controller extends Controller {
	public function index() {
		$this->_setAdminTemplate();
		$V = new View('nav_index.php');
		$V->bind('NAV_LIST', $this->_findNavItems());
		$V->bind('NAV_CACHE_RAW', $this->_getNavCacheTime());
		$V->bind('NAV_CACHE_TIME', date('Y-m-d @ H:i:s', $this->_getNavCacheTime()));
		$this->_setView($V);
	}

	public function getNavSubCategoryList()
	{
		$ID = post_var('ID');
		$LEFT_NAV = new Nav();
		$SUB_NAV = $LEFT_NAV->getNavSubCategories($ID);
		echo $SUB_NAV;
		exit();
	}

	public function getNavSubCategoryListSub()
	{
		$ID = post_var('ID');
		$url = post_var('url');
		$LEFT_NAV = new Nav();
		$SUB_NAV = $LEFT_NAV->getNavSubCategoriesSub($ID, $url);
		echo $SUB_NAV;
		exit();
	}

	public function checkCats()
	{
		$url = post_var('url');
		$LEFT_NAV = new Nav();
		$SUB_NAV = $LEFT_NAV->checkCats($url);
		echo $SUB_NAV;
		exit();
	}

	public function clearCache() {
		$this->_requireAdmin();
		$cache_file = DIR_ROOT . 'inc/cache/header_nav.cache.php'; 
		if(true == file_exists($cache_file)) {
			unlink($cache_file);
		}
		redirect('/admin/nav/');
		exit;
	}

	private function _getNavCacheTime() {
		$nav_cache_time = 0;

		$cache_file = DIR_ROOT . 'inc/cache/header_nav.cache.php'; 
		if(true == file_exists($cache_file)) {
			$nav_cache_time = filemtime($cache_file);
		}
		return $nav_cache_time;
	}

	public function edit($nav_id) {
		$this->_setAdminTemplate();
		$N = new Category_Nav_Item($nav_id);
		if(false == $N->exists()) {
			redirect('/admin/nav/');
			exit;
		}
		$V = new View('nav_item_edit.php');
		$V->bind('ITEM', $N);
		$this->_setView($V);
	}

	private function _findNavItems() {
		$sql = "SELECT category_nav_item_id
			FROM  category_nav_items
			ORDER BY sort_order";
		$query = db_query($sql); 
		$nav_list = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$nav_list[] = new Category_Nav_Item($rec['category_nav_item_id']);
		}
		return $nav_list;
	}

	public function newItem() {
		$this->_setAdminTemplate();
		$nav_item = new Category_Nav_Item();
		$current_category = new Category();
		$current_category->name = 'No Category Selected';
		$V = new View('nav_item_form.php');
		$V->bind('ITEM', $nav_item);
		$V->bind('CURRENT_CAT', $current_category);
		$this->_setView($V);
	}

	public function process() {
		$this->_requireAdmin();
		$nav = new Category_Nav_Item(post_var('nav_item_id'));
		$C = new Category(post_var('category_id'));
		if(true == $C->exists()) {
			$nav->category_id = $C->ID;
			if(false == $nav->exists()) {
				$nav->sort_order = 10000000;
			}
			$nav->write();
		}
		redirect('/admin/nav/');
	}

	public function saveSort() {
		$this->_requireAdmin();
		$return_vals = array('success' => true);
		$nav_ids = post_var('nav', array());
		$sort_order = 0;
		foreach($nav_ids as $i => $nav) {
			$item = new Category_Nav_Item($nav);
			if(true == $item->exists()) {
				$item->sort_order = $sort_order;
				$item->write();
				$sort_order += 1000;
			}
		}
		echo json_encode($return_vals);
		exit;
	}

	private function _setAdminTemplate() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}

	public function newColumn($nav_item_id) {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$nav = new Category_Nav_Item($nav_item_id);
		if(false == $nav->exists()) {
			redirect('/admin/nav/');
		}
		$column = new Category_Nav_Item_Column();
		$column->nav_item_id = $nav->ID;
		$column->sort_order = 1000000; //set it really high
		$column->write();
		redirect('/admin/nav/editColumn/' . $column->ID);
	}

	public function editColumn($column_id) {
		$this->_requireAdmin();
		$this->_setAdminTemplate();
		$column = new Category_Nav_Item_Column($column_id);
		if(false == $column->exists()) {
			redirect('/admin/nav/');
		}
		$V = new view('nav_item_column_form.php');
		$V->bind('COLUMN', $column);
		$this->_setView($V);
	}

	public function columnAddCategory($column_id) {
		$this->_requireAdmin();
		$column = new Category_Nav_Item_Column($column_id);
		if(false == $column->exists()) {
			exit;
		}
		$category = new Category(post_var('category_id'));
		$column->addCategory($category);
		$return = array('success' => true);
		echo json_encode($return);
		exit;
	}

	public function getColumnCategories($column_id) {
		$this->_requireAdmin();
		$column = new Category_Nav_Item_Column($column_id);
		$category_list = $column->getCategories();
		$V = new View('column_category_form.php');
		$V->bind('CATEGORY_LIST', $category_list);
		$this->_setView($V);
		$this->_setTemplate(new Template('ajax.php'));
	}

	public function columnSaveSort() {
		$this->_requireAdmin();
		$sort_data = post_var('category', array());
		$sort_index = 0;
		foreach($sort_data as $category_id => $active) {
			$C = new Category($category_id);
			if(true == $C->exists()) {
				$C->nav = $active;
				$C->nav_sort_order = $sort_index;
				$C->write();
				$sort_index += 1000;
			}
		}
		echo json_encode(array('success' => true));
		exit;
	}

	public function columnDropCategory() {
		$this->_requireAdmin();
		$C = new Category(post_var('category_id'));
		$return = array('success' => false);
		if(true == $C->exists()) {
			$C->nav_column_id = 0;
			$C->write();
			$return['success'] = true;
		}
		echo json_encode($return);
		exit;
	}

	public function saveItemSortOrder($nav_item) {
		$this->_requireAdmin();
		$nav_item = new Category_Nav_Item($nav_item);
		if(true == $nav_item->exists()) {
			$sort_data = post_var('sort_data', array());
			$column_sort_index = 0;
			foreach($sort_data as $column_id => $column_data) {
				$column = new Category_Nav_Item_Column($column_id);
				if(true == $column->exists()) {
					$column->nav_item_id = $nav_item->ID;
					$column->sort_order = $column_sort_index;
					$column->write();
					$column_sort_index += 1000;
					$category_sort_index = 0;
					foreach($column_data as $category_id => $foobar) {
						//we don't care about $foobar at all.
						$cat = new Category($category_id);
						if(true == $cat->exists()) {
							$cat->nav_sort_order = $category_sort_index;
							$cat->nav_column_id = $column->ID;
							$cat->write();
							$category_sort_index += 1000;
						}
					}
				}
			}
		}
		echo json_encode(array('success' => true));
		exit;
	}

	public function dropColumn() {
		$this->_requireAdmin();
		$column = new Category_Nav_Item_Column(post_var('column_id'));
		$column->delete();
		echo json_encode(array('success' => true));
		exit;
	}

	public function dropItem() {
		$this->_requireAdmin();
		$nav_item = new Category_Nav_Item(post_var('nav_item_id'));
		$nav_item->delete();
		echo json_encode(array('success' => true));
		exit;
	}
}
?>
