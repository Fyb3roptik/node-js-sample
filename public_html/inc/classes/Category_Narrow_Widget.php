<?php
require_once 'Widget.php';

/**
 * Widget that allows the user to narrow/drill-down by Category.
 */
class Category_Narrow_Widget extends Widget {
	private $_category;

	protected function _load($cat_url) {
		$this->_category = new Category($cat_url, 'url');
	}

	public function render() {
		$parent = $this->_category;
		if(true == $parent->exists()) {
			$subcategory_list = $parent->getSubcategories();
			if(count($subcategory_list) > 0) {
			?>
			<div class="leftcolheader">
				<h3>Narrow by Category</h3>
			</div>
			<ul id="category_list">
			<?php
				foreach($subcategory_list as $i => $subcat) {
				?>
				<li>
					<span id="category_<?php echo $subcat->ID; ?>" class="category">
						<a href="/category/<?php echo $subcat->url; ?>/"><?php echo $subcat->name; ?></a></span>
				</li>
				<?php
				}
			?>
			</ul>
			<?php
			}
		}
	}
}
?>