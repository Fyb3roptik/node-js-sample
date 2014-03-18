<?php
class Global_Tab_Controller extends Controller {
	public function index() {
		$this->_requireAdmin();
		$this->_setAdminTemplate();
		$V = new View('global_tab_index.php');
		$this->_setView($V);
		$SQL = "SELECT global_tab_id
			FROM `global_product_tabs`
			ORDER BY sort_order";
		$query = db_query($SQL);
		$tab_list = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$tab_list[] = new Global_Tab($rec['global_tab_id']);
		}
		$V->bind('TAB_LIST', $tab_list);
	}

	public function newTab() {
		$this->_requireAdmin();
		$this->_setAdminTemplate();
		$PT = new Global_Tab();
		$PT->title = 'New Global Tab';
		$V = new View('global_tab_form.php');
		$V->bind("PT", $PT);
		$V->bind('PDF_LIST', $this->_scanPdfs());
		$this->_setView($V);
	}

	private function _setAdminTemplate() {
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}

	public function edit($tab_id) {
		$this->_requireAdmin();
		$PT = new Global_Tab($tab_id);
		if(false == $PT->exists()) {
			redirect('/admin/gtab/');
			exit;
		}
		$this->_setAdminTemplate();
		$V = new View('global_tab_form.php');
		$V->bind('PT', $PT);
		$V->bind('PDF_LIST', $this->_scanPdfs());
		$this->_setView($V);
	}

	public function drop() {
		$this->_requireAdmin();
		$PT = new Global_Tab(post_var('global_tab_id'));
		$return = array('success' => false);
		if(true == $PT->exists()) {
			$PT->delete();
			$return['success'] = true;
		}
		echo json_encode($return);
		exit;
	}

	public function processTab() {
		$this->_requireAdmin();
		$tab = new Global_Tab(post_var('global_tab_id'));
		$tab_data = post_var('tab', array());
		$tab->load($tab_data);
		if(Global_Tab::TYPE_GALLERY == $tab->type) {
			$tab->title = 'Product Gallery';
		}
		if(Global_Tab::TYPE_PDF == $tab->type) {
			//look for a PDF file. o_0
			$pdf_action = post_var('pdf_action');
			if('new' == $pdf_action) {
				//process the uploaded file.
				$tab->data = $this->_processNewPdf();
			} else {
				//process the existing file.
				$tab->data = post_var('picked_pdf');
			}
		}
		$tab->write();
		redirect('/admin/gtab/');
		exit;
	}

	private function _processNewPdf() {
		$pdf = exists('new_pdf', $_FILES);
		$pdf_dir= DIR_ROOT . 'pdf/';
		$new_file_name = $pdf_dir . $pdf['name'];
		$final_name = null;
		if('application/pdf' == $pdf['type']) {
			while(true == file_exists($new_file_name)) {
				$parts = pathinfo($new_file_name);
				$new_file_name = $parts['dirname'] . '/';	
				$new_file_name .= $parts['filename'] . '_1.' . $parts['extension'];
			}
			if(false == copy($pdf['tmp_name'], $new_file_name)) {
				throw new Exception("Failed to copy PDF to new destination.");
			}
			$file_parts = pathinfo($new_file_name);
			$final_name = $file_parts['basename'];
		}
		return $final_name;
	}

	public function saveSort() {
		$this->_requireAdmin();
		$tab_list = post_var('tab', array());
		$return = array('success' => false);
		$sort_order = 100;
		foreach($tab_list as $i => $tab) {
			$PT = new Global_Tab($tab);
			if(true == $PT->exists()) {
				$PT->sort_order = $sort_order;
				$PT->write();
				$sort_order += 100;
				$return['success'] = true;
			}
		}
		echo json_encode($return);
		exit;
	}

	private function _scanPdfs() {
		$pdf_list = array();
		$pdf_dir = DIR_ROOT . 'pdf/';
		if(false == is_dir($pdf_dir)) {
			throw new Exception("$pdf_dir is not a directory!");
		}
		if($dh = opendir($pdf_dir)) {
			while(($file = readdir($dh)) !== false) {
				if('application/pdf' == mime_content_type($pdf_dir . '/' . $file)) {
					$pdf_list[$file] = $file;
				}
			}
		}
		asort($pdf_list);
		return $pdf_list;
	}
}
?>
