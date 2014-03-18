<?php
require_once 'Controller.php';

class Page_Controller extends Controller {
	public function view($page_url) {
		$layout = 'default.php';
		$P = new Page($page_url, 'url');
		global $WIDGET_LIST;
		$CSS_LIST = array();
		if(true == $P->exists()) {
			foreach($P->getWidgets() as $i => $W) {
				$WIDGET_LIST->addWidget($W);
			}
			$VIEW = new View('page_view.php');
			if($P->full_page > 0) {
				$layout = 'wide.php';
			}
			$CSS_LIST[] = '/page/css/' . $P->ID;
			$VIEW->bind('P', $P);
		} else {
			$VIEW = new View('error_404.php');
		}
		$this->_setTemplate(new Template($layout));
		$this->_template->bind('WIDGET_LIST', $WIDGET_LIST);
		$this->_template->bind('CUSTOMER', $this->_user);
		$this->_template->bind('CSS_LIST', $CSS_LIST);
		$this->_setView($VIEW);
	}

	public function css($page_id) {
		$P = new Page($page_id);
		if(false == $P->exists()) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
		header('Content-Type: text/css');
		echo stripslashes($P->css);
		exit;	
	}

	public function edit($page_id) {
		$this->_requireAdmin();
		$P = new Page($page_id);
		if(false == $P->exists()) {
			redirect(LOC_PAGES);
			exit;
		}
		$V = new View('page_form.php');
		$V->bind('P', $P);
		$this->_setView($V);
	}

	public function newPage() {
		$this->_requireAdmin();
		$P = new Page();
		$P->nickname = 'New Page ' . date('Y-m-d');
		$P->title = 'New Page';
		$P->url = 'new_page';
		$VIEW = new View('page_form.php');
		$VIEW->bind("P", $P);
		$this->_setView($VIEW);
	}

	public function processPage() {
		$this->_requireAdmin();
		$P = new Page(post_var('page_id'));
		$page_data = post_var('page', array());
		$P->load($page_data);
		$P->write();

		$meta_list = post_var('meta_tag');
		foreach($meta_list as $i => $tag) {
			$PMT = Object_Factory::OF()->newObject('Page_Meta_Tag', $tag['ID']);
			if(false == array_key_exists('delete', $tag)) {
				$PMT->load($tag);
				$PMT->setPage($P);
				$PMT->write();
			} elseif(true == $PMT->exists()) {
				$PMT->delete();
			}
		}

		if(true == array_key_exists('save_continue', $_POST)) {
			redirect('/admin/page/edit/' . $P->ID);
			exit;
		}
		redirect(LOC_PAGES);
		exit;
	}

	public function index() {
		$this->_requireAdmin();
		$sql = "SELECT page_id
			  FROM `pages`
			  ORDER BY title";
		$query = db_query($sql);
		$PAGE_LIST = array();
		while($query->num_rows > 0 && $p = $query->fetch_assoc()) {
			$PAGE_LIST[] = new Page($p['page_id']);
		}
		$V = new View('page_list.php');
		$V->bind('PAGE_LIST', $PAGE_LIST);
		$this->_setView($V);
	}

	public function drop() {
		parent::_requireAdmin();
		$P = new Page(post_var('page_id'));
		$return = array('status' => false);
		if(true == $P->exists()) {
			$P->delete();
			if(false == $P->exists()) {
				$return['status'] = true;
			}
		}
		echo json_encode($return);
		exit;
	}

	public function addWidget() {
		parent::_requireAdmin();
		$P = new Page(post_var('page_id'));
		$WB = new Widget_Builder(post_var('widget_id'));

		$return_vals = array('widget_id' => 0);

		if(true == $P->exists() && true == $WB->exists()) {
			$PW = new Page_Widget();
			$PW->page_id = $P->ID;
			$PW->widget_id = $WB->ID;
			$PW->sort_order = (count($P->getPageWidgets()) * 2000);
			$PW->write();
			$return_vals['widget_id'] = $PW->ID;
		}
		echo json_encode($return_vals);
		exit;
	}

	public function dropWidget() {
		parent::_requireAdmin();
		$return_vals = array('status' => false);
		$PW = new Page_Widget(post_var('widget_id'));
		$return_vals['widget_id'] = $PW->ID;
		if(true == $PW->exists()) {
			$PW->delete();
			$return_vals['status'] = true;
		}
		echo json_encode($return_vals);
		exit;
	}

	public function getWidgets() {
		parent::_requireAdmin();
		$return_vals = array('widgets' => array());
		$P = new Page(post_var('foreign_key'));
		if(true == $P->exists()) {
			$pw_list = $P->getPageWidgets();
			foreach($pw_list as $i => $PW) {
				$pw_data = $PW->dump();
				$pw_data['ID'] = $PW->ID;
				$WB = new Widget_Builder($PW->widget_id);
				$pw_data['nickname'] = $WB->nickname;
				$pw_data['widget_class'] = $WB->widget_class;
				$return_vals['widgets'][] = $pw_data;
			}
		}
		echo json_encode($return_vals);
		exit;
	}

	protected function _requireAdmin() {
		parent::_requireAdmin();
		$this->_setAdminTemplate();
	}

	protected function _setAdminTemplate() {
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}
}
?>