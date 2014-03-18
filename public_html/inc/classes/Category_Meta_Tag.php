<?php
require_once 'Meta_Tag.php';
require_once 'Object.php';

class Category_Meta_Tag extends Meta_Tag {
	private $_category_meta_tag_id = 0;
	private $_category_id = 0;

	public function __construct($value = null, $key = null) {
		parent::__construct($value, $key);
		if($this->ID > 0) {
			$this->_loadCategoryData();
		}
	}

	private function _loadCategoryData() {
		$sql = "SELECT *
			FROM `category_meta_tags`
			WHERE meta_tag_id = '" . intval($this->ID) . "'";
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$this->_category_meta_tag_id = $rec['category_meta_tag_id'];
			$this->_category_id = intval($rec['category_id']);
		}
	}

	/**
	 * Override Object::exists() to work on the primary key from `category_meta_tags`
	 */
	public function exists() {
		return (intval($this->_category_meta_tag_id) > 0) ? true : false;
	}

	public function setCategory(Category $P) {
		if(0 === intval($this->_category_id)) {
			$this->_category_id = intval($P->ID);
		}
	}

	public function getCategory() {
		return intval($this->_category_id);
	}

	public function write() {
		parent::write();
		$this->_writeCategoryData();
	}

	/**
	 * Writes the record to `category_meta_tags`
	 */
	private function _writeCategoryData() {
		if(intval($this->_category_id) > 0) {
			if(false == $this->exists()) {
				$this->_insertCategoryData();
			}
		} else {
			throw new Exception('Invalid category_id');
		}
	}

	/**
	 * Inserts the record in `category_meta_tags`
	 */
	private function _insertCategoryData() {
		if(false == $this->exists()) {
			$data = $this->_makeCategoryDataRecord();
			db_perform('category_meta_tags', $data);
			$this->_category_meta_tag_id = db_insert_id();
		}
	}

	private function _makeCategoryDataRecord() {
		$data = array(
					'category_id' => intval($this->_category_id),
					'meta_tag_id' => $this->ID
				);
		return $data;
	}

	/**
	 * Override Object::delete() to extend our deleting to a second table.
	 */
	public function delete() {
		parent::delete();
		if(0 === intval($this->ID) && true == $this->exists()) {
			$sql = "DELETE FROM `category_meta_tags`
				  WHERE category_meta_tag_id = '" . intval($this->_category_meta_tag_id) . "'";
			db_query($sql);
			$this->_category_meta_tag_id = 0;
		}
	}
}
?>
