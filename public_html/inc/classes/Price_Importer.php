<?php
require_once dirname(__FILE__) . '/../functions/standard_lib.php';

class Price_Importer {
	private $_file;
	private $_headers;

	public function import($filename) {
		if(false == file_exists($filename)) {
			throw new Exception("CSV File doesn't exist!");
		}
		$this->_file = $filename;
		$this->_importFile();
	}

	private function _importFile() {
		$handle = fopen($this->_file, 'r');
		$this->_headers = fgetcsv($handle);
		$line_index = 2;
		while($rec = fgetcsv($handle)) {
			try {
				$this->_processRecord($this->_scrubRecord($rec));
			} catch(Exception $e) {
				throw new Exception($e->getMessage() . ' (line# ' . $line_index . ')');
			}
			$line_index++;
		}
	}

	private function _processRecord($record) {
		$P = Object_Factory::OF()->newObject('Product', $record['product_id']);
		if(true == $P->exists()) {
			//update whatever product fields
			$P->unit_measure = $record['unit'];
			$P->quantity = $record['base_qty'];
			$P->fudge_factor = $record['fudge'];
			$P->fudge_type = $record['fudge_type'];
			$P->write();

			//update admin override
			$BCL = new Base_Cost_Lookup($P->ID);
			if(true == $BCL->exists()) {
				$BCL->admin_override = abs(floatval($record['sales_cost_override']));
				$BCL->write();
			}

			//delete all old tiers for this product
			$sql = "DELETE FROM `product_quantity_discounts`
				WHERE product_id = '" . intval($P->ID) . "'";
			db_query($sql);

			//write new tiers
			foreach($this->extractTiers($record) as $qty => $markup) {
				$PQD = new Product_Quantity_Discount();
				$PQD->product_id = $P->ID;
				$PQD->min_quantity = intval($qty);
				$PQD->markup = floatval($markup);
				$PQD->write();
			}
		}
	}

	private function _scrubRecord($rec) {
		reset($this->_headers);
		$scrubbed_data = array();
		$removed_fields = array('base_cost', 'global_overhead', 'landed_cost');
		foreach($this->_headers as $i => $header) {
			if(false == in_array($header, $removed_fields)) {
				$scrubbed_data[$header] = $rec[$i];
			}
		}
		return $scrubbed_data;
	}

	public function extractTiers($scrubbed_record) {
		$tiers = array();
		foreach($scrubbed_record as $header => $val) {
			list($tier_id) = sscanf($header, 'min_quantity_%d');
			if(false == is_null($tier_id)) {
				$min_quantity = intval($val);
				$markup = exists('markup_' . $tier_id, $scrubbed_record, 0);
				if(abs(floatval($markup)) > 0) {
					$tiers[$min_quantity] = $markup;
				}
			}
		}
		return $tiers;
	}
}
?>