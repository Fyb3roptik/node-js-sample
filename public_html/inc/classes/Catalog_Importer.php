<?php
require_once 'Product.php';
require_once 'Attribute.php';
require_once 'Attribute_Value.php';
require_once 'Product_Quantity_Discount.php';
require_once 'Meta_Tag.php';

/**
 * Monolithic class for importing monolithic CSV files into our schema.
 */
class Catalog_Importer {
	private $_table;
	private $_file_name;

	private $_attribute_hash = array();
	private $_attribute_value_hash = array();

	/**
	 * Log of importer events.
	 */
	public $log = array();

	/**
	 * Build a new catalog importer.
	 *
	 * @param table The MySQL table we'll be using for the importer.
	 * @param file_name Name of the CSV file or .Zip archive containing the CSV file to be read in.
	 */
	public function __construct($table, $file_name = null) {
		$this->_table = $table;
		if(false == is_null($file_name)) {
			if(false == file_exists($file_name)) {
				throw new Exception("Import file doesn't exist.");
			} else {
				$file_type = explode('/', mime_content_type($file_name));
				if('text' == $file_type[0]) {
					$this->_file_name = $file_name;
				} elseif('application' == $file_type[0]) {
					$good_zip_types = array('zip', 'x-zip');
					if(true == in_array($file_type[1], $good_zip_types)) {
						$zip = zip_open($file_name);
						if($zip) {
							$tmp_dir = '/tmp/'. sha1(microtime()) . '_';
							$entry_list = array();
							while($entry = zip_read($zip)) {
								$tmp_name = $tmp_dir . zip_entry_name($entry);
								file_put_contents($tmp_name, zip_entry_read($entry, zip_entry_filesize($entry)));
								$entry_list[] = $tmp_name;
								zip_entry_close($entry);
							}
							if(1 == count($entry_list)) {
								$file_name = $entry_list[0];
								$file_type = explode('/', mime_content_type($file_name));
								if('text' == $file_type[0]) {
									$this->_file_name = $file_name;
								} else {
									throw new Exception("Bad zipped file type.");
								}
							} else {
								throw new Exception("Zip file should only a single CSV file");
							}
						}
						zip_close($zip);
					} else {
						throw new Exception("Bad file type.");
					}
				} else {
					throw new Exception("Bad file type.");
				}
			}
			$this->importTable();
		}
	}

	/**
	 * Load up our hash table.
	 */
	public function loadHashes() {
		if(0 == count($this->_attribute_value_hash)) {
			$sql = "SELECT av.attribute_value_id, av.attribute_id, av.value
				  FROM `attribute_values` av";
			$query = db_query($sql);
			while($rec = $query->fetch_assoc()) {
				$hash = sha1($rec['attribute_id'] . '_' . $rec['value']);
				$this->_attribute_value_hash[$hash] = $rec['attribute_value_id'];
			}
		}

		if(0 == count($this->_attribute_hash)) {
			$sql = "SELECT a.attribute_id, a.name
				  FROM `attributes` a";
			$query = db_query($sql);
			while($rec = $query->fetch_assoc()) {
				$hash = sha1($rec['name']);
				$this->_attribute_hash[$hash] = $rec['attribute_id'];
			}
		}
	}

	/**
	 * Creates our MySQL table based on the structure of teh CSV.
	 */
	public function importTable() {
		$file_name = $this->_file_name;
		$handle = fopen($file_name, 'r');

		$first_line = fgets($handle);
		fclose($handle);
		$columns = explode(',', $first_line);

		$column_statements = array();
		foreach($columns as $i => $field) {
			$field = trim($field);
			if(false == empty($field)) {
				$column_statements[] = "`" . trim(str_replace('"', '', $field)) . "` TEXT NOT NULL";
			}
		}

		db_query("DROP TABLE IF EXISTS `" . $this->_table . "`");

		$column_statements[] = "`record_processed` TINYINT(1) NOT NULL DEFAULT 0";

		$create_sql = "CREATE TABLE `" . $this->_table . "` (";
		$create_sql .= implode(',', $column_statements);
		$create_sql .= ") ENGINE = MYISAM";
		db_query($create_sql);
	}

	/**
	 * Reads the file into our table.
	 */
	public function loadFile() {
		$infile_sql = "LOAD DATA LOCAL INFILE '" . $this->_file_name . "'
					INTO TABLE `" . $this->_table . "`
					FIELDS TERMINATED BY ',' ENCLOSED BY '" . '"' . "'
					IGNORE 1 LINES";
		db_query($infile_sql);
	}

	/**
	 * Reads the database of raw data into our actual database schema. It's a big hairy do lots of stuff method.
	 *
	 * @param limit Limit the number of imported products to this number.
	 */
	public function importTableProducts($limit = 0) {
		$this->loadHashes();

		$sql = "SELECT *
			  FROM `" . $this->_table . "`
			  WHERE record_processed = '0'";
		$query = db_query($sql);
		$count = 0;
		$limit = abs(intval($limit));
		$updated_products = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$p = $this->importProduct($rec);
			if(true == is_a($p, "Product")) {
				$updated_products[] = "'" . db_input($p->legacy_id) . "'";
			}

			$count++;
			if($limit > 0 && $count >= $limit) {
				break;
			}

			if(count($updated_products) > 10) {
				$data = array('record_processed' => 1);
				$where = "WHERE product_id IN (" . implode(',', $updated_products) . ")";
				db_perform($this->_table, $data, SQL_UPDATE, $where);
				$updated_products = array();
			}
		}
		if(count($updated_products) > 0) {
			$data = array('record_processed' => 1);
			$where = "WHERE product_id IN (" . implode(',', $updated_products) . ")";
			db_perform($this->_table, $data, SQL_UPDATE, $where);
		}
	}

	/**
	 * Creates a Product_Quantity_Discount item based on some raw data.
	 *
	 * @param product_id ID of the prodcut this PQD will be associated with.
	 * @param minimum Minimum quantity to trigger the discount.
	 * @param base_price The base price of the discount.
	 * @param markup The markup value for this discount.
	 *
	 * @return NULL if we don't have proper data, otherwise a Product_Quantity_Discount object.
	 */
	public function importQuantityDiscount($product_id, $minimum, $base_price, $markup) {
		$return_val = null;

		$product_id = abs(intval($product_id));
		if($product_id > 0 && abs(floatval($markup)) > 0) {
			$discount = new Product_Quantity_Discount();
			$discount->product_id = $product_id;
			$discount->min_quantity = abs(intval($minimum));
			$discount->actual_price = abs(floatval($base_price));
			$discount->markup = abs(floatval($markup));

			$return_val = $discount;
		}

		return $return_val;
	}

	/**
	 * Imports a Product based on the raw data from a record in our raw table.
	 *
	 * @param rec Array of key/values from the database (i.e. fetch_assoc())
	 * @return Product object created from this record's data.
	 */
	public function importProduct($rec = array()) {
		$process_rec = true;
		$product = null;
		$required_keys = array('product_id', 'catalog_code', 'name', 'description', 'quantity', 'weight');

		if(false == is_array($rec)) {
			$process_rec = false;
		} else {
			foreach($required_keys as $i => $key) {
				if(false == array_key_exists($key, $rec)) {
					$process_rec = false;
				}
			}
		}

		if(true == $process_rec) {
			$legacy_id = $rec['product_id'];
			$product = new Product($legacy_id, 'legacy_id');

			if(true == $product->exists()) {
				$product->emptyCategories();
			}

			$product->legacy_id = $legacy_id;
			$product->catalog_code = $rec['catalog_code'];
			$product->name = $rec['name'];
			$product->description = $rec['description'];
			$product->active = ('true' == strtolower($rec['active'])) ? 1 : 0;
			$product->orderable = ('true' == strtolower($rec['non_orderable'])) ? 0 : 1;
			$product->quantity = $rec['quantity'];
			$product->weight = floatval($rec['weight']);
			$product->is_new = ('true' == strtolower($rec['is_new'])) ? 1 : 0;
			$product->on_sale = ('true' == strtolower($rec['on_sale'])) ? 1 : 0;
			$product->clickserve_category = $rec['clickserve_category'];
			if(true == empty($product->url)) {
				$product->url = convert_for_url($product->name);
			}

			$price_list = array();
			$attribute_list = array();
			$attribute_id_list = array();
			$meta_list = array();
			//figure out anything else we need to infer from the denormalized_keys
			foreach($rec as $key => $value) {
				$matches = array();
				if(preg_match('/price_([0-9]+)_([a-zA-Z]+)/', $key, $matches)) {
					if(false == empty($value)) {
						$price_list[$matches[1]][$matches[2]] = $value;
					}
				}

				$matches = array();
				if(preg_match('/attribute_([0-9]+)_([a-zA-Z]+)/', $key, $matches)) {
					if(false == empty($value)) {
						$attribute_list[$matches[1]][$matches[2]] = trim($value);
					}
				}

				$matches = array();
				if(preg_match('/meta_tag_([0-9]+)_([a-zA-z]+)/', $key, $matches)) {
					$meta_list[$matches[1]][$matches[2]] = trim($value);
				}
			}
			$product->write();

			if(count($price_list) > 0 && true == $product->exists()) {
				db_query("DELETE FROM `product_quantity_discounts` WHERE product_id = '" . intval($product->ID) . "'");

				$pqd_list = array();
				$used_minimums = array();
				foreach($price_list as $i => $price) {
					$PQD = $this->importQuantityDiscount($product->ID, $price['start'], $price['actual'], $price['markup']);
					if(false == is_null($PQD) && false == in_array($PQD->min_quantity, $used_minimums)) {
						$pqd_data = $PQD->dataDump();
						unset($pqd_data['discount_id']);
						$pqd_list[] = $pqd_data;
						$used_minimums[] = $pqd_data['min_quantity'];
					}
				}

				if(count($pqd_list) > 0) {
					$keys = array_keys($pqd_list[0]);
					foreach($keys as $i => $key) {
						$keys[$i] = "`" . $key . "`";
					}
					$sql = "INSERT INTO `product_quantity_discounts` (" . implode(',', $keys) . ") VALUES ";

					$data_blocks = array();
					foreach($pqd_list as $i => $data) {
						$cleaned_data = array();
						foreach($data as $key => $val) {
							$cleaned_data[] = "'" . db_input($val) . "'";
						}
						$data_blocks[] = "(" . implode(',', $cleaned_data) . ")";
					}
					$sql .= implode(',', $data_blocks);
					db_query($sql);
				}
			}

			if(count($attribute_list) > 0 && true == $product->exists()) {
				$attribute_id_list = array();
				foreach($attribute_list as $i => $attr) {
					if(false == is_null($attr['id'])) {
						$attribute_id_list[] = $this->createAttribute($attr['id']);
					}
				}

				$pav_list = array();
				$used_attributes = array();
				foreach($attribute_list as $i => $attr) {
					if(false == empty($attr['value'])) {
						$attribute_id = $this->createAttribute($attr['id']);
						if(false == in_array($attribute_id, $used_attributes)) {
							$used_attributes[] = $attribute_id;
							$pav_data['product_id'] = $product->ID;
							$pav_data['attribute_id'] = $attribute_id;
							$pav_data['attribute_value_id'] = $this->createAttributeValue($attribute_id, $attr['value']);
							$pav_data['visible'] = 1;
							unset($pav_data['product_attribute_id']);
							if(intval($pav_data['attribute_value_id']) > 0) {
								$pav_list[] = $pav_data;
							}
						}
					}
				}

				if(count($pav_list) > 0) {
					$keys = array_keys($pav_list[0]);
					foreach($keys as $i => $key) {
						$keys[$i] = "`" . $key . "`";
					}
					$sql = "INSERT INTO `products_attributes` (" . implode(',', $keys) . ") VALUES ";

					$data_blocks = array();
					foreach($pav_list as $i => $data) {
						$cleaned_data = array();
						foreach($data as $key => $val) {
							$cleaned_data[] = "'" . db_input($val) . "'";
						}
						$data_blocks[] = "(" . implode(',', $cleaned_data) . ")";
					}
					$delete_sql = "DELETE FROM `products_attributes` WHERE product_id = '" . intval($product->ID) . "'";
					db_query($delete_sql);

					$sql .= implode(',', $data_blocks);
					db_query($sql);
				}
			}

			if(count($meta_list) > 0 && true == $product->exists()) {
				$product->emptyMetaTags();
				foreach($meta_list as $i => $tag) {
					$this->createMetaTag($product, $tag['name'], $tag['content']);
				}
			}
		}
		Object_Factory::OF()->destroy($product);
		return $product;
	}

	/**
	 * Creates a new Attribute (or looks one up) based on the name supplied.
	 *
	 * @param attribute_name The name of the attribute we're looking for.
	 * @return Ideally returns an Attribute object with the given attribute_name.
	 */
	public function createAttribute($attribute_name) {
		$return_val = null;
		$attribute_name = trim($attribute_name);
		if(false == empty($attribute_name)) {
			$hash = sha1($attribute_name);
			if(false == array_key_exists($hash, $this->_attribute_hash)) {
				$attribute_id = 0;
				$sql = "SELECT attribute_id
					  FROM `attributes`
					  WHERE name = '" . db_input($attribute_name) . "'";
				$query = db_query($sql);
				while($query->num_rows > 0 && $a = $query->fetch_assoc()) {
					$attribute_id = $a['attribute_id'];
				}
				if(0 == $attribute_id) {
					$A = new Attribute(convert_for_url($attribute_name), 'key');
					if(false == $A->exists()) {
						$A->name = $attribute_name;
						$A->key = strtolower(convert_for_url($A->name));
						$A->write();
						$attribute_id = $A->ID;
					}
				}
				$this->_attribute_hash[$hash] = $attribute_id;
			}
			$return_val = $this->_attribute_hash[$hash];
		}
		return $return_val;
	}

	/**
	 * Creates an Attribute_Value for a given Attribute ID and value name (or looks up an existing Attribute_Value.
	 *
	 * @param attribute_id The ID of the Attribute object the Attribute_Value *should* be associated with.
	 * @param value_name The name of the Attribute_Value we seek!
	 * @return Returns an Attribute_Value with the given attribute_id and value_name.
	 */
	public function createAttributeValue($attribute_id, $value_name) {
		$return_val = null;
		$attribute_id = abs(intval($attribute_id));
		$value_name = trim($value_name);
		$value_name = str_replace('\\', '', $value_name);

		$hash = sha1($attribute_id . '_' . $value_name);

		if(false == array_key_exists($hash, $this->_attribute_value_hash)) {
			if($attribute_id > 0 && false == empty($value_name)) {
				$sql = "SELECT attribute_value_id
					  FROM `attribute_values`
					  WHERE attribute_id = '" . intval($attribute_id) . "'
						AND value = '" . db_input($value_name) . "'";
				$query = db_query($sql);
				$attribute_value_id = 0;
				while($query->num_rows > 0 && $avi = $query->fetch_assoc()) {
					$attribute_value_id = $avi['attribute_value_id'];
				}

				if(0 == $attribute_value_id) {
					$AV = new Attribute_Value();
					$AV->attribute_id = $attribute_id;
					$AV->value = $value_name;
					$AV->write();
					$attribute_value_id = $AV->ID;
				}
				$this->_attribute_value_hash[$hash] = $attribute_value_id;
			}
		}
		$return_val = $this->_attribute_value_hash[$hash];
		return $return_val;
	}

	/**
	 * Creates a  Meta_Tag and adds it to a given Product.
	 *
	 * @param product Product to add this new Meta_Tag to.
	 * @param name Name of the Meta_Tag.
	 * @param content Content of the Meta_Tag.
	 * @return True if the Meta tag was successfully created and added to the Product. False otherwise.
	 */
	public function createMetaTag(Product $product, $name, $content) {
		$created_meta_tag = false;

		$name = trim($name);
		$content = trim($content);

		if(true == $product->exists() && false == empty($name) && false == empty($content)) {
			$tag = new Meta_Tag();
			$tag->name = $name;
			$tag->content = $content;
			$product->addMetaTag($tag);
			$product->write();
			$created_meta_tag = true;
		}
		return $created_meta_tag;
	}

	/**
	 * Returns the count of records in the raw data table and optionally filters by the processed flag.
	 *
	 * @param processed Optional parameter to filter results based on the processed flag.
	 * @return Returns the count of records in the raw data table.
	 */
	public function getRecordCount($processed = null) {
		$count = 0;
		if(true == $this->_tableExists()) {
			$where = null;
			if(false == is_null($processed)) {
				$processed = intval($processed);
				if($processed > 0) {
					$processed = 1;
				}
				$where = "WHERE record_processed = '" . intval($processed) . "'";
			}
			$sql = "SELECT COUNT(product_id) AS count
				  FROM `" . $this->_table . "` " . $where;
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$count = $rec['count'];
			}
		}
		return $count;
	}

	private function _tableExists() {
		$table_exists = false;
		$sql = "SHOW TABLES";
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_array()) {
			if($rec[0] == $this->_table) {
				$table_exists = true;
				break;
			}
		}
		return $table_exists;
	}
}
?>