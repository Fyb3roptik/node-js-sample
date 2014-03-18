<?php
class Shopping_Feed_Exporter {
	private $_products = array();
	private $_formatted_products = array();

	const SHOPPING_FEED_EXPORT_FILE = '/tmp/shopping_feed_export.csv';
	const MANUFACTURER_ATTR = 'Brand';
	const MANU_PART_ATTR = 'Part No.';

	public function export() {
		$this->_loadProducts();
		$this->_formatProducts();
		$this->_writeExport();
	}

	private function _writeExport() {
		$export_file = fopen(self::SHOPPING_FEED_EXPORT_FILE, 'w');
		foreach($this->_formatted_products as $i => $product_data) {
			fputcsv($export_file, $product_data);
		}		
		fclose($export_file);
	}

	private function _loadProducts() {
		$products = array();
		$sql = SQL::get()
			->select('product_id')
			->from('products')
			->where("active = '1'")
			->where("sales_only = '0'");
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$products[] = new Product($rec['product_id']);
		}
		$query->free();
		$this->_products = $products;
	}

	private function _formatProducts() {
		$this->_loadHeaders();
		foreach($this->_products as $i => $P) {
			$formatted = $this->_getEmptyArray();
			$formatted['Stock Code'] = $P->catalog_code;
			$formatted['Legacy ID'] = $P->legacy_id;
			$formatted['Description'] = $this->_makeDescription($P);
			$formatted['Product Price'] = $P->getUnitPrice(1);
			$formatted['Height'] = $P->height;
			$formatted['Length'] = $P->length;
			$formatted['Image Url'] = $this->_getImage($P);
			$formatted['Product Url'] = $this->_getProductUrl($P);
			$formatted['Name'] = $P->name;
			$formatted['Product Id'] = $P->ID;
			$formatted['Product Number'] = self::getProductNumber($P);
			$formatted['Quantity'] = $P->quantity;
			$formatted['Weight'] = $P->weight;
			$formatted['Width'] = $P->width;
			$formatted['Manufacturer'] = self::getManufacturer($P);
			$this->_formatted_products[] = $formatted;
		}
	}

	public static function getManufacturer(Product $P) {
		$manufacturer = null;
		$product_attributes = $P->getAttributes(true);
		foreach($product_attributes as $i => $PA) {
			if(self::MANUFACTURER_ATTR == $PA->getName()) {
				$manufacturer = $PA->getValue();
				break;
			}
		}
		return $manufacturer;
	}

	public static function getProductNumber(Product $P) {
		$part_number = null;
		$product_attributes = $P->getAttributes(true);
		foreach($product_attributes as $i => $PA) {
			if(self::MANU_PART_ATTR == $PA->getName()) {
				$part_number = $PA->getValue();
			}
		}
		return $part_number;
	}

	private function _getProductUrl(Product $P) {
		return SITE_URL . get_product_url($P);	
	}

	private function _getImage(Product $P) {
		$image_url = SITE_URL . '/' . $P->getDefaultImage();
		return $image_url;
	}

	private function _makeDescription(Product $P) {
		$product_attributes = $P->getAttributes();
		$formatted_attributes = array(); 
		foreach($product_attributes as $i => $PA) {
			$formatted_attributes[] = $PA->getName() . ": " . $PA->getValue();
		}
		return implode(' ', $formatted_attributes);
	}

	private function _loadHeaders() {
		$empty_product = $this->_getEmptyArray();
		$header_line = array();
		foreach($empty_product as $header => $val) {
			$header_line[] = $header;
		}
		$this->_formatted_products[] = $header_line;
	}

	private function _getEmptyArray() {
		$headers = array(
				'Stock Code', 'Legacy ID', 'Description', 'Condition', 'Product Price', 'Height', 'Length',
				'Image Url', 'Product Url', 'Name', 'Product Id',
				'Product Number', 'Quantity', 'Shipping Cost', 'Taxable', 'Weight',
				'Width', 'Category', 'Manufacturer', 'Lead Time');
		$empty_product = array();
		foreach($headers as $i => $header) {
			$empty_product[$header] = null;
		}
		//default some values.
		$empty_product['Condition'] = 'New';
		$empty_product['Category'] = 'Indoor Living > Lighting > Accessories';
		$empty_product['Taxable'] = 'Y';
		return $empty_product;
	}

	public function lastGenerated() {
		$last_updated = 0;
		if(true == file_exists(self::SHOPPING_FEED_EXPORT_FILE)) {
			$last_updated = filemtime(self::SHOPPING_FEED_EXPORT_FILE);
		}
		return $last_updated;
	}
}
?>
