<?php
require_once 'Controller.php';
require_once 'classes/Attribute.php';

class Attribute_Controller extends Controller {
	public function index() {
		$this->_requireAdmin();
		$this->_setupTemplate();
		$SQL = "SELECT attribute_id
			FROM `attributes`
			ORDER BY name";
		$page = get_var('page', 1);
		$PK = new Page_Killer($SQL, 30, $page);
		$attr_id_list = $PK->query();
		$attribute_list = array();
		foreach($attr_id_list as $rec) {	
			$attribute_list[] = new Attribute($rec['attribute_id']);
		}
		$V = new View('attribute_index.php');
		$V->bind('ATTR_LIST', $attribute_list);
		$V->bind('PK_LINKS', $PK->getLinks());
		$this->_setView($V);
	}

	public function newAttribute() {
		$this->_requireAdmin();
		$this->_setupTemplate();
		$A = new Attribute();
		$A->name = 'New Attribute';
		$V = new View('attribute_form.php');
		$V->bind('A', $A);
		$this->_setView($V);
	}

	public function editSort() {
		$this->_requireAdmin();
		$this->_setupTemplate();
		$sql = SQL::get()
			->select('attribute_id')
			->from('attributes')
			->orderBy('sort')
			->orderBy('name');
		$query = db_query($sql);

		$attribute_list = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$attribute_list[] = new Attribute($rec['attribute_id']);
		}
		$V = new View('attribute_sort.php');
		$V->bind('ATTR_LIST', $attribute_list);
		$this->_setView($V);
	}

	public function saveSort() {
		$this->_requireAdmin();
		$return = array('success' => false, 'message' => null);
		$attribute_list = post_var('attribute_id', array());
		$processed_count = 0;
		$sort_index = 1000;
		foreach($attribute_list as $attribute_id) {
			$A = new Attribute($attribute_id);
			if(true == $A->exists()) {
				$A->sort = $sort_index;
				$A->write();
				$processed_count++;
				$sort_index += 1000;
			}
		}

		if($processed_count > 0) {
			$return['success'] = true;
			$return['message'] = 'Sort order saved!';
		}

		echo json_encode($return);
		exit;
	}

	public function editValue($value_id) {
		$this->_requireAdmin();
		$AV = new Attribute_Value($value_id);
		if(false == $AV->exists()) {
			exit("ERROR: VALUE DOESN'T EXIST!");	
		}
		$V = new View('attribute_value_form.php');
		$V->bind('AV', $AV);
		$this->_setTemplate(new Template('ajax.php'));
		$this->_setView($V);
	}

	public function dropValue() {
		$this->_requireAdmin();
		$return = array('success' => false);
		$AV = new Attribute_Value(post_var('value_id'));
		if(true == $AV->exists()) {
			$AV->delete();
			$return['success'] = true;
		}
		echo json_encode($return);
		exit;
	}

	public function edit($attribute_id) {
		$this->_requireAdmin();
		$A = new Attribute($attribute_id);
		if(false == $A->exists()) {
			redirect('/admin/attribute/');
			exit;
		}
		$this->_setupTemplate();
		$V = new View('attribute_form.php');
		$V->bind('A', $A);
		$this->_setView($V);
	}

	public function valueTable($attribute_id) {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		$A = new Attribute($attribute_id);
		$V = new View('attribute_value_table.php');
		$this->_setView($V);
		$V->bind('A', $A);
	}

	public function process() {
		$this->_requireAdmin();
		$A = new Attribute(post_var('attribute_id'));
		$attribute_data = post_var('attribute', array());
		$attribute_data['display'] = abs(intval($attribute_data['display']));
		$A->load($attribute_data);
		$A->write();
		redirect('/admin/attribute/edit/' . $A->ID);
		exit;
	}

	public function drop() {
		$this->_requireAdmin();
		$A = new Attribute(post_var('attribute_id'));
		$return = array('success' => false);
		if(true == $A->exists()) {
			$A->delete();
			$return['success'] = true;
		}
		echo json_encode($return);
		exit;
	}

	public function processValue() {
		$this->_requireAdmin();
		$AV = $this->_processValue();
		redirect('/admin/attribute/edit/' . $AV->attribute_id);
		exit;
	}

	public function processValueAjax() {
		$this->_requireAdmin();
		$AV = $this->_processValue();
		echo json_encode(array('success' => true));
		exit;
	}

	private function _processValue() {
		$AV = new Attribute_Value(post_var("attribute_value_id"));
		if(false == $AV->exists()) {
			$AV->attribute_id = post_var('attribute_id');
		}
		$AV->load(post_var('value', array()));
		$AV->write();
		return $AV;
	}

	public function getValuesJson($attribute_id) {
		$A = new Attribute($attribute_id);
		$return_vals = array('values' => array(), 'status' => true);
		$return_vals['values'][] = array('id' => 0, 'value' => '-Select Value-');
		$return_vals['values'][] = array('id' => 'new', 'value' => '-New Value-');
		if(true == $A->exists()) {
			$values = $A->getValues();
			foreach($values as $i => $V) {
				$return_vals['values'][] = array(
								'id' => $V->ID,
								'value' => $V->value);
			}
		}
		echo json_encode($return_vals);
		exit;
	}

	private function _setupTemplate() {
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}
}
?>
