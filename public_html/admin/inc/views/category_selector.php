<script type="text/javascript">
	var category_lookup = new Array();
	var product_lookup = new Array();

	$(document).ready(function() {
		$("#save_selections").click(function() {
			var categories = new Array();
			$("input[name='category_id[]']").each(function() {
				if(true == $(this).attr('checked')) {
					var category_index = categories.length;
					categories[category_index] = new Array();
					categories[category_index]['id'] = $(this).val();
					categories[category_index]['name'] = category_lookup[parseInt($(this).val())];
				}
			});

			var products = new Array();
			$("input[name='product_id[]']").each(function() {
				if(true == $(this).attr('checked')) {
					var product_index = products.length;
					products[product_index] = new Array();
					products[product_index]['id'] = $(this).val();
					products[product_index]['name'] = product_lookup[parseInt($(this).val())];
				}
			});

			window.opener.draw_category_products(categories, products);
			self.close();
		});

		$(".expand_cat").live("click", function() {
			var parent_id = $(this).siblings("input[name='category_id[]']").val();
			get_categories(parent_id);
			$(this).removeClass('expand_cat').addClass('minimize_cat');
			$(this).text('-');
		});

		$(".minimize_cat").live("click", function() {
			var $parent = $(this).parent();
			$parent.children('div.sub_categories').empty();
			$parent.children('div.products').empty();
			$(this).removeClass('minimize_cat').addClass('expand_cat');
			$(this).text('+');
		});

		get_categories(0);

		$("input[type='checkbox'][name='category_id[]']").each(function() {
			if(0 == parseInt($(this).val())) {
				$(this).hide();
			}
		});
	});
	function get_categories(parent_id) {
		var parent_id = parseInt(parent_id);
		var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
				"action" : "get_categories",
				"parent_id" : parent_id}
		$.post('/admin/category_selector.http.php', data, function(data) {
			var $checkbox = $("input[name='category_id[]'][value='" + parent_id + "']");
			var $cat_container = $checkbox.parent(); //we can clone this! yay!

			if(data['subcats'].length > 0) {
				$cat_container.children("div.sub_categories").empty();
				var $good_clone = $cat_container.clone();
				$good_clone.children('input').show();
				$good_clone.children("div.products").empty();
				$good_clone.children("a.minimize_cat").removeClass('minimize_cat').addClass('expand_cat').text('+');
				for(var subcat_index in data['subcats']) {
					var subcat = data['subcats'][subcat_index];
					category_lookup[parseInt(subcat['id'])] = subcat['name'];
					var $subcat_container = $good_clone.clone();
					$subcat_container.children("input[name='category_id[]']").val(subcat['id']);
					$subcat_container.children("span.cat_name").text(subcat['name']);
					$subcat_container.appendTo($cat_container.children("div.sub_categories"));
				}
			}

			if(data['products'].length > 0) {
				var $product_container = $cat_container.find('div.products');
				for(var prod_index in data['products']) {
					var $product_checkbox = $(document.createElement('input'));
					var $product_span = $(document.createElement('span'));
					var prod = data['products'][prod_index];
					product_lookup[parseInt(prod['id'])] = prod['name'];
					$product_span.text(prod['name']);
					$product_checkbox.attr('type', 'checkbox').attr('name', 'product_id[]').val(prod['id']);
					$product_checkbox.appendTo($product_container);
					$product_span.appendTo($product_container);
					$(document.createElement('br')).appendTo($product_container);
				}
			}
		}, "json");
	}
</script>
<form id="category_select_form" action="" method="post">
	<div class="nav">
		<input type="button" id="save_selections" value="Save Selections" />
	</div>
<div class="category">
	<a href="javascript:void(0)" class="expand_cat">+</a>
	<input type="checkbox" name="category_id[]" value="0" /><span class="cat_name">All Categories/Products</span>
	<div class="products"></div>
	<div class="sub_categories"></div>
</div>
</form>