<?php
require_once 'Object.php';

/**
 * Class for creating "static" content pages.
 */
class Page extends Object {
	protected $_table = 'pages';
	protected $_table_id = 'page_id';

	protected $_unsanitized_fields = array('content', 'css');

	protected $_set_hooks = array('content' => 'sanitizeContent');
	protected $_get_hooks = array('content' => 'sanitizeContent');

	protected $_meta_tags = array();

	public function __construct($value = null, $key = null) {
		parent::__construct($value, $key);
		if(true == $this->exists()) {
			$this->_loadMetaTags();
		}
	}

	private function _loadMetaTags() {
		$sql = "SELECT pmt.meta_tag_id
			  FROM `page_meta_tags` pmt
			  WHERE pmt.page_id = '" . intval($this->ID) . "'";
		$query = db_query($sql);
		$this->_meta_tags = array();
		while($query->num_rows > 0 && $m = $query->fetch_assoc()) {
			$this->_meta_tags[] = Object_Factory::OF()->newObject('Page_Meta_Tag', $m['meta_tag_id']);
		}
	}

	public function getPageWidgets() {
		$PW = new Page_Widget();
		return $PW->find('page_id', $this->ID, 'sort_order');
	}

	public function getWidgets() {
		$widget_list = array();
		$pw_list = $this->getPageWidgets();
		foreach($pw_list as $i => $pw) {
			$WB = new Widget_Builder($pw->widget_id);
			if(true == $WB->exists()) {
				$widget = $WB->build();
				if(true == is_a($widget, 'Widget')) {
					$widget_list[] = $widget;
				}
			}
		}
		return $widget_list;
	}

	public function addMetaTag(Page_Meta_Tag $M) {
		$this->_meta_tags[] = $M;
	}

	public function getMetaTags() {
		return $this->_meta_tags;
	}

	public function write() {
		parent::write();
		foreach($this->_meta_tags as $i => $M) {
			$M->setPage($this);
			$M->write();
		}
	}

	/**
	 * Override Object::delete() to delete our meta tags.
	 */
	public function delete() {
		parent::delete();
		if(false == $this->exists()) {
			foreach($this->_meta_tags as $i => $T) {
				$T->delete();
				if(false == $T->exists()) {
					unset($this->_meta_tags[$i]);
				}
			}
		}
	}

	public function sanitizeContent($content) {
		return stripslashes($content);
	}
}
?>