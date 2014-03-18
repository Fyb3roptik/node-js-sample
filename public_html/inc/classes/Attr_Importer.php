<?php
class Attr_Importer {
	private $_file;
	private $_headers;
	private $_attribute_hash = array();

	public function import($filename) {
		if(false == file_exists($filename)) {
			throw new Exception("CSV File doesn't exist");
		}
		$this->_file = $filename;
		$this->_importFile();
	}

	private function _importFile() {
		$handle = fopen($this->_file, 'r');
		$this->_headers = fgetcsv($handle);
		$this->_setAttributeHash($this->_headers);
		$line_index = 2;
		while($rec = fgetcsv($handle)) {
			try {
				$this->_processRecord($rec);
			} catch(Exception $e) {
				fclose($handle);
				throw new Exception($e->getMessage() . ' (line# ' . $line_index . ')');
			}
			$line_index++;
		}
		fclose($handle);
	}

	private function _setAttributeHash($headers) {
		$attribute_hash = array();

		$scrubbed_names = array();
		foreach($headers as $header) {
			if(0 === strpos($header, 'a:')) {
				//we found an attribute
				$name = str_replace('a:', '', $header);
				$scrubbed_names[] = "'" . db_input($name) . "'";
			}
		}
		$sql = "SELECT attribute_id, name
			FROM attributes
			WHERE name IN (" . implode(',', $scrubbed_names) . ")";
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$attribute_hash[$rec['name']] = $rec['attribute_id'];
		}
		$query->free();

		$this->_attribute_hash = $attribute_hash;
	}

	private function _processRecord($record) {
		$scrubbed_record = $this->_scrubRecord($record);
		$P = $this->_getProduct($scrubbed_record['product_id']);
		if(false == $P->exists()) {
			//we only handle existing products here!
			throw new Exception("Product doesn't exist.");
		}
		foreach($scrubbed_record['attr'] as $attribute_name => $value) {
			$attribute_id = $this->_lookupAttributeID($attribute_name);
			$product_attribute = $this->_lookupProductAttribute($P->ID, $attribute_id);
			$value = trim($value);
			if(0 == strlen($value)) {
				if(true == $product_attribute->exists()) {
					$product_attribute->delete();
				}
			} else {
				$product_attribute->attribute_id = $attribute_id;
				$product_attribute->attribute_value_id = $this->_lookupAttributeValue($attribute_id, $value);
				$product_attribute->product_id = $P->ID;
				$product_attribute->write();
			}
			Object_Factory::OF()->clearTable('Product_Attribute');
		}
		if($scrubbed_record['name'] != $P->name) {
			$P->name = $scrubbed_record['name'];
			$P->write();
		}
		Object_Factory::OF()->clearTable('Product');
	}

	private function _getProduct($product_id) {
		$P = Object_Factory::OF()->newObject('Product', $product_id);
		return $P;
	}

	private function _lookupAttributeValue($attribute_id, $value) {
		$value_id = 0;
		$sql = "SELECT attribute_value_id
			FROM attribute_values
			WHERE attribute_id = '" . intval($attribute_id) . "'
				AND value = '" . db_input($value) . "'";
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$value_id = $rec['attribute_value_id'];
		}
		$query->free();
		$V = Object_Factory::OF()->newObject('Attribute_Value', $value_id);
		if(false == $V->exists()) {
			$V->value = $value;
			$V->attribute_id = $attribute_id;
			$V->write();
		}
		return $V->ID;
	}

	private function _lookupProductAttribute($product_id, $attribute_id) {
		$sql = "SELECT product_attribute_id
			FROM `products_attributes`
			WHERE product_id = '" . intval($product_id) . "'
				AND attribute_id = '" . intval($attribute_id) . "'";
		$query = db_query($sql);
		$pav_id = 0;
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$pav_id = intval($rec['product_attribute_id']);
		}
		$query->free();
		$PA = Object_Factory::OF()->newObject('Product_Attribute', $pav_id);
		if(false == $PA->exists()) {
			$PA->visible = true;
		}
		return $PA;
	}

	private function _lookupAttributeID($attribute_name) {
		$attribute_id = exists($attribute_name, $this->_attribute_hash, 0);
		if(0 == $attribute_id) {
			throw new Exception("Attribute '$attribute_name' doesn't exist!");
		}
		return $attribute_id;
	}

	private function _scrubRecord($record) {
		$scrubbed_1 = array();
		foreach($this->_headers as $i => $header) {
			$scrubbed_1[$header] = $record[$i];
		}

		$scrubbed_2 = array(
			'product_id' => intval($scrubbed_1['product_id']),
			'name' => trim($scrubbed_1['name']),
			'attr' => array()
		);

		foreach($scrubbed_1 as $header => $value) {
			if(0 === strpos($header, 'a:')) {
				$attribute_name = str_replace('a:', '', $header);
				$scrubbed_2['attr'][$attribute_name] = trim($value);
			}
		}

		return $scrubbed_2;
	}
}
?>
