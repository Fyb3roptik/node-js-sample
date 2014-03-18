<?php
require_once 'Controller.php';

class Batch_Edit_Controller extends Controller {
	public function index() {
		$this->_setup();
		$V = new View('batch_index.php');
		$this->_setView($V);
		$product_list = $this->_getProducts();
		$V->bind('PRODUCT_LIST', $product_list);
		$V->bind('MS', new Message_Stack());
		$product_id_list = array();
		foreach($product_list as $P) {
			$product_id_list[] = $P->ID;
		}
		$V->bind('PRODUCT_ID_LIST', implode('+', $product_id_list));
	}

	public function pricing() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		$product_list = $this->_getProducts();
		$V = new View('batch_pricing.php');
		$V->bind('PRODUCT_LIST', $product_list);
		$this->_setView($V);
	}

	public function category() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		$product_list = $this->_getProducts();
		$category_list = array();
		$category_counts = array();
		foreach($product_list as $P) {
			foreach($P->getCategories() as $C) {
				$category_counts[$C->ID]++;
				if(false == array_key_exists($C->ID, $category_list)) {
					$category_list[$C->ID] = $C;
				}
			}
		}
		arsort($category_counts);
		$V = new View('batch_category.php');
		$V->bind('CATEGORY_COUNTS', $category_counts);
		$V->bind('CATEGORY_LIST', $category_list);
		$V->bind('PRODUCT_LIST', $product_list);
		$this->_setView($V);
	}

	public function addCategory() {
		$this->_requireAdmin();
		$return = array('success' => false, 'message' => null);
		$product_id_list = post_var('product_id', array());
		$category_id = abs(intval(post_var('category_id', 0)));
		$processed_products = array();
		$C = new Category($category_id);
		if(true == $C->exists()) {
			foreach($product_id_list as $product_id) {
				$P = new Product($product_id);
				if(true == $P->exists()) {
					$processed_products[] = $P->ID;
					$P->addCategory($C->ID);
					$P->write();
				}
			}
			$message = 'Added ' . count($processed_products) . ' to the category: "' . $C->name . '"';
			$return['success'] = true;
			$return['message'] = $message;
		}

		echo json_encode($return);
		exit;
	}

	public function removeCategory() {
		$this->_requireAdmin();
		$return = array('success' => false);
		$category_id = intval(post_var('category_id'));
		$product_id_list = post_var('product_id', array());

		$final_product_list = array();
		foreach($product_id_list as $product_id) {
			$final_product_list[] = abs(intval($product_id));
		}
		if($category_id > 0 && count($final_product_list) > 0) {
			$sql = SQL::get()->delete('products_categories')
				->where("`category_id` = '@category_id'")
				->where("`product_id` IN (@product_list)")
				->bind('category_id', $category_id)
				->bind('product_list', implode(',', $final_product_list));
			db_query($sql);
			$return['success'] = true;
		}

		echo json_encode($return);
		exit;
	}

	public function sellby() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		$product_list = $this->_getProducts();
		$V = new View('batch_sellby.php');
		$V->bind('PRODUCT_LIST', $product_list);
		$this->_setView($V);
	}

	public function margin() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		$product_list = $this->_getProducts();

		$product_ids = explode(' ', get_var('products'));
		$product_id_list = array();
		foreach($product_ids as $raw_id) {
			if(intval($raw_id) > 0) {
				$product_id_list[] = intval($raw_id);
			}
		}
		$sql = SQL::get()->select('MIN(markup) AS min_margin')
			->from('product_quantity_discounts')
			->where('product_id in (@product_id_list)')
			->bind('product_id_list', implode(',', $product_id_list));
		$query = db_query($sql);
		$max_margin = 100;
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$max_margin = $rec['min_margin'];
		}

		$V = new View('batch_margin.php');
		$V->bind('PRODUCT_LIST', $product_list);
		$V->bind('MAX_MARGIN', $max_margin);
		$this->_setView($V);
	}

	public function setMargin() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		$product_list = $this->_getProducts();
		$V = new View('batch_margin_set.php');
		$V->bind('PRODUCT_LIST', $product_list);
		$this->_setView($V);
	}

	public function setPrice() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('ajax.php'));
		$product_list = $this->_getProducts();
		$V = new View('batch_price_set.php');
		$V->bind('PRODUCT_LIST', $product_list);
		$this->_setView($V);
	}

	public function setPriceProcess() {
		$this->_requireAdmin();
		$MS = new Message_Stack();
		$product_list = post_var('product_id', array());
		$new_price = abs(floatval(post_var('new_price', 0)));
		if($new_price > 0) {
			foreach($product_list as $product_id) {
				$P = new Product($product_id);
				if(true == $P->exists()) {
					$P->base_price = $new_price;
					$P->write();
				}
			}
			$MS->add('batch', 'New price set.', MS_SUCCESS);
		} else {
			$MS->add('batch', 'New price not set. Bad price value.', MS_ERROR);
		}
		redirect('/admin/batch/?products=' . implode('+', $product_list));
		exit;
	}

	public function sellbyProcess() {
		$this->_requireAdmin();

		$product_list = post_var('product_id', array());
		$sellby_qty = intval(post_var('sell-by-quantity', 1));
		$unit_of_measure = post_var('sell-by-unit');
		$good_product_list = array();
		foreach($product_list as $product_id) {
			$P = new Product($product_id);
			if(true == $P->exists()) {
				$P->unit_measure = $unit_of_measure;
				$P->quantity = $sellby_qty;
				$P->write();
				$good_product_list[] = $P->ID;
			}
		}
		$message = "Updated " . count($good_product_list) . " products' sell by quantity and unit of measure.";
		$MS = new Message_Stack();
		$MS->add('batch', $message, MS_SUCCESS); 

		redirect('/admin/batch/?products=' . implode('+', $good_product_list));
	}

	public function processMargin() {
		$this->_requireAdmin();
		$MS = new Message_Stack();
		$product_list = post_var('product_id', array());
		$margin_adjustment = floatval(post_var('margin_adjustment'));
		$good_product_list = array();
		if(0 != $margin_adjustment) {
			foreach($product_list as $product_id) {
				$P = new Product($product_id);
				if(true == $P->exists()) {
					$price_list = $P->getPrices();
					foreach($price_list as $PQD) {
						$original_margin = $PQD->markup;
						$new_margin = $original_margin - ($margin_adjustment / 100);
						if($new_margin > 0) {
							$PQD->markup = $new_margin;
							$PQD->write();
						}
					}
					$good_product_list[] = $P->ID;
				}
			}
		}
		$message = "Adjusted margins for " . count($good_product_list) . " product(s).";
		$MS->add('batch', $message, MS_SUCCESS);
		redirect('/admin/batch/?products=' . implode('+', $good_product_list));
	}

	public function setMarginProcess() {
		$this->_requireAdmin();
		$MS = new Message_Stack();
		$product_list= post_var('product_id', array());
		$new_margin = floatval(post_var('new_margin', 0));
		if($new_margin > 0) {
			foreach($product_list as $product_id) {
				$P = new Product($product_id);
				if(true == $P->exists()) {
					foreach($P->getPrices() as $PQD) {
						$PQD->markup = $new_margin;
						$PQD->write();
					}
				}
			}
			$MS->add('batch', 'Margin successfully set to "' . $new_margin . '".');
		} else {
			$MS->add('batch', "Margin not set, bad value for new margin.", MS_ERROR);
		}
		redirect('/admin/batch/?products=' . implode('+', $product_list));
	}

	public function processPricing() {
		$this->_requireAdmin();
		$product_list = post_var('product_id', array());
		$min_quantity_list = post_var('min_quantity', array());
		$base_price_list = post_var('base_price', array());
		$markup_list = post_var('margin', array());
		$good_product_list = array();
		if(count($min_quantity_list) == count($markup_list) && count($markup_list) == count($base_price_list)) {
			foreach($product_list as $product_id) {
				$P = new Product($product_id);
				if(true == $P->exists()) {
					$good_product_list[] = $product_id;
					foreach($P->getPrices() as $PQD) {
						$PQD->delete();
					}
					foreach($min_quantity_list as $i => $min_qty) {
						$PQD = new Product_Quantity_Discount();
						$PQD->product_id = $P->ID;
						$PQD->actual_price = abs(floatval($base_price_list[$i]));
						$PQD->min_quantity = abs(intval($min_qty));
						$PQD->markup = abs(floatval($markup_list[$i]));
						$PQD->write();
					}
				}
			}
			$message = "Updated " . count($good_product_list) . " products with " . count($markup_list) . " pricing tiers.";
			$MS = new Message_Stack();
			$MS->add('batch', $message, MS_SUCCESS);
			redirect('/admin/batch/?products=' . implode('+', $good_product_list));
		} else {
			$MS = new Message_Stack();
			$MS->add('batch', 'Something went horribly wrong.', MS_ERROR);
			redirect('/admin/batch/?products=' . implode('+', $product_list));
		}
		exit;
	}

	private function _getProducts() {
		$product_list = array();
		$raw_products = get_var('products');
		$raw_list = explode(' ' , $raw_products);
		if(count($raw_list) > 0) {
			foreach($raw_list as $product_id) {
				$P = new Product($product_id);
				if(true == $P->exists()) {
					$product_list[] = $P;
				}
			}
		}

		return $product_list;
	}

	private function _setup() {
		$this->_setTemplate(new Template('batch_template.php'));
	}
}
?>