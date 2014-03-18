<?php
require_once 'Object.php';
require_once 'Meta_Tag.php';

/**
 * Class for meta tags associated with Pages.
 */
class Page_Meta_Tag extends Meta_Tag {
	private $_page_meta_tag_id = 0;
	private $_page_id = 0;

	public function __construct($value = null, $key = null) {
		parent::__construct($value, $key);
		if($this->ID > 0) {
			$this->_loadPageData();
		}
	}

	public function _loadPageData() {
		$sql = "SELECT *
			  FROM `page_meta_tags`
			  WHERE meta_tag_id = '" . intval($this->ID) . "'";
		$query = db_query($sql);
		while($query->num_rows > 0 && $pmt = $query->fetch_assoc()) {
			$this->_page_meta_tag_id = intval($pmt['page_meta_tag_id']);
			$this->_page_id = intval($pmt['page_id']);
		}
	}

	/**
	 * Override Object::exists() to work on the primary key from `page_meta_tags`
	 */
	public function exists() {
		return (intval($this->_page_meta_tag_id) > 0) ? true : false;
	}

	public function setPage(Page $P) {
		if(0 === intval($this->_page_id)) {
			$this->_page_id = intval($P->ID);
		}
	}

	public function getPage() {
		return intval($this->_page_id);
	}

	public function write() {
		parent::write();
		$this->_writePageData();
	}

	/**
	 * Writes the record to `page_meta_tags`
	 */
	private function _writePageData() {
		if(intval($this->_page_id) > 0) {
			if(false == $this->exists()) {
				$this->_insertPageData();
			}
		} else {
			throw new Exception('Invalid page_id');
		}
	}

	/**
	 * Inserts the record in `page_meta_tags`
	 */
	private function _insertPageData() {
		if(false == $this->exists()) {
			$data = $this->_makePageDataRecord();
			db_perform('page_meta_tags', $data);
			$this->_page_meta_tag_id = db_insert_id();
		}
	}

	private function _makePageDataRecord() {
		$data = array(
					'page_id' => intval($this->_page_id),
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
			$sql = "DELETE FROM `page_meta_tags`
				  WHERE page_meta_tag_id = '" . intval($this->_page_meta_tag_id) . "'";
			db_query($sql);
			$this->_page_meta_tag_id = 0;
		}
	}
}
?>
