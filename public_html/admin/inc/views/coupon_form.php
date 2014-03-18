<script type="text/javascript">
	$(document).ready(function() {
		var start_date = new Date();
		start_date.setFullYear(<?php echo $C->startDate('Y') . ',' . ($C->startDate('m') - 1) . ',' . $C->startDate('d'); ?>);

		var end_date = new Date();
		end_date.setFullYear(<?php echo $C->endDate('Y') . ',' . ($C->endDate('m') - 1) . ',' . $C->endDate('d'); ?>);

		$("#start_date").datepicker({
			defaultDate: start_date,
			showButtonPanel: true,
			showAnim: false,
			duration: '',
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			changeYear: true
		});

		$("#end_date").datepicker({
			defaultDate: end_date,
			showButtonPanel: true,
			showAnim: false,
			duration: '',
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			changeYear: true
		});

		$("#coupon_form").submit(function() {
			var valid_form = true;
			$('input:not(type="hidden"):not(type="radio"):not(type="hidden"):not(name="coupon_id")').each(function() {
				if("" == $(this).val()) {
					valid_form = false;
				}
			});
			if(false == valid_form) {
				$("#coupon_error").show();
			} else {
				$("#coupon_error").hide();
			}

			return valid_form;
		});

		var initial_cats = new Array();
		initial_cats[0] = new Array();
		initial_cats[0]['id'] = 1;
		initial_cats[0]['name'] = 'foobar';
		initial_cats[1] = new Array();
		initial_cats[1]['id'] = 2;
		initial_cats[1]['name'] = 'Asdf';
		//draw_category_products(initial_cats, new Array());

		draw_initial_triggers();

		$("input.catprod_selector").click(function() {
			window.open('/admin/category_selector.php', 'popup', "menubar=no,width=600,height=600,toolbar=no,scrollbars=yes");
		});

		$("a.delete_cat").live('click', function() {
			var $this = $(this);
			var $tr = $this.parents('td').parents('tr');
			var category_id = $tr.find('input[name="categories[]"]').val();
			var confirm_drop = confirm("Are you sure you want to remove this category from the Coupon?");
			if(true == confirm_drop) {
				var coupon_id = parseInt($("#coupon_id").val());
				if(coupon_id > 0) {
					var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
							"action" : "drop_coupon_category",
							"coupon_id" : coupon_id,
							"category_id" : category_id}
					$.post('/admin/coupons.http.php', data, function(data) {
						if(true == data['success']) {
							$tr.remove();
						}
					}, "json");
				} else {
					$tr.remove();
				}
			}
			return false;
		});

		$("a.delete_prod").live('click', function() {
			var $this = $(this);
			var $tr = $this.parents('td').parents('tr');
			var product_id = $tr.find('input[name="products[]"]').val();
			var confirm_drop = confirm("Are you sure you want to remove this product from the Coupon?");
			if(true == confirm_drop) {
				var coupon_id = parseInt($("#coupon_id").val());
				if(coupon_id > 0) {
					var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
							"action" : "drop_coupon_product",
							"coupon_id" : coupon_id,
							"product_id" : product_id}
					$.post('/admin/coupons.http.php', data, function(data) {
						if(true == data['success']) {
							$tr.remove();
						}
					}, "json");
				} else {
					$tr.remove();
				}
			}
			return false;
		});

		$("#category_table, #product_table").hide();

		$("select[name='coupon[all_products]']").change(function() {
			$("#catprod_holder").hide();
			if(0 == $(this).val()) {
				$("#catprod_holder").show();
			}
		}).change();
	});

	function draw_initial_triggers() {
		var coupon_id = parseInt($("#coupon_id").val());
		if(coupon_id > 0) {
			var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
					"action" : "get_coupon_triggers",
					"coupon_id" : coupon_id}
			$.post('/admin/coupons.http.php', data, function(data) {
				draw_category_products(data['cats'], data['products']);
			}, "json");
		}
	}

	function draw_category_products(categories, products) {
		var $cat_table = $("#category_table");
		if(categories.length > 0) {
			for(var cat_index in categories) {
				var cat = categories[cat_index];
				var $tr = $(document.createElement('tr'));
				var $td_1 = $(document.createElement('td'));
				var $cat_id = $(document.createElement('input'));
				$td_1.text(cat['name']);
				$cat_id.attr('type', 'hidden').attr('name', 'categories[]').val(cat['id']).appendTo($td_1);
				$td_1.appendTo($tr);

				var $td_2 = $(document.createElement('td'));
				var $delete_link = $(document.createElement('a'));
				$delete_link.addClass('delete_cat').attr('href', 'javascript:void(0);').text('delete').appendTo($td_2);
				$td_2.appendTo($tr);
				$tr.appendTo($cat_table.children('tbody'));
			}
			$cat_table.show();
		}

		var $prod_table = $("#product_table");
		if(products.length > 0) {
			for(var prod_index in products) {
				var prod = products[prod_index];
				var $tr = $(document.createElement('tr'));
				var $td_1 = $(document.createElement('td'));
				var $prod_id = $(document.createElement('input'));
				$td_1.text(prod['name']);
				$prod_id.attr('type', 'hidden').attr('name', 'products[]').val(prod['id']).appendTo($td_1);
				$td_1.appendTo($tr);

				var $td_2 = $(document.createElement('td'));
				var $delete_link = $(document.createElement('a'));
				$delete_link.addClass('delete_prod').attr('href', 'javascript:void(0);').text('delete').appendTo($td_2);
				$td_2.appendTo($tr);
				$tr.appendTo($prod_table.children('tbody'));
			}
			$prod_table.show();
		}
	}
</script>
<h2>Edit Coupon "<?php echo $C->nickname; ?>"</h2>
<form id="coupon_form" action="" method="post">
	<fieldset>
		<legend>Coupon Details</legend>
		<input type="hidden" name="action" value="process_coupon" />
		<input type="hidden" id="coupon_id" name="coupon_id" value="<?php echo $C->ID; ?>" />
		<div id="coupon_error">
			All fields required.
		</div>
		<table>
			<tr>
				<td>Nickname:</td>
				<td><input type="text" name="coupon[nickname]" value="<?php echo $C->nickname; ?>" /></td>
			</tr>
			<tr>
				<td>Code:</td>
				<td><input type="text" name="coupon[code]" value="<?php echo $C->code; ?>" /></td>
			</tr>
			<tr>
				<td>Description:</td>
				<td>
					<input type="text" name="coupon[description]" value="<?php echo $C->description; ?>" />
					Shown to customers when coupon is successfully applied to their order.
				</td>
			</tr>
			<tr>
				<td>Discount Type:</td>
				<td>
					<?php echo draw_radio('coupon[discount_type]', Coupon::DISCOUNT_PERCENT, (Coupon::DISCOUNT_PERCENT == $C->discount_type), 'id="discount_percent"'); ?>
					<label for="discount_percent">Percent</label>
					<?php echo draw_radio('coupon[discount_type]', Coupon::DISCOUNT_DOLLAR, (Coupon::DISCOUNT_DOLLAR == $C->discount_type), 'id="discount_dollar"'); ?>
					<label for="discount_dollar">Dollar Amount</label>
				</td>
			</tr>
			<tr>
				<td>Discount Value:</td>
				<td>
					<input type="text" size="4" name="coupon[discount_value]" value="<?php echo $C->discount_value; ?>" />
				</td>
			</tr>
			<tr>
				<td>Start Date:</td>
				<td>
					<input type="text" id="start_date" name="coupon[start_date]" value="<?php echo $C->start_date; ?>" />
				</td>
			</tr>
			<tr>
				<td>End Date:</td>
				<td>
					<input type="text" id="end_date" name="coupon[end_date]" value="<?php echo $C->end_date; ?>" />
				</td>
			</tr>
			<tr>
				<td>Applies To:</td>
				<td>
					<?php echo draw_select('coupon[all_products]', array(1 => 'All Products', 0 => 'Select Products/Categories'), $C->all_products); ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" value="Save Coupon" /> or <a href="<?php echo LOC_COUPONS; ?>">Cancel</a>
				</td>
			</tr>
		</table>
	</fieldset>
	<div id="catprod_holder">
		<fieldset>
			<legend>Categories</legend>
			<p>Any product in these categories will be affected by the coupon.</p>
			<input type="button" class="catprod_selector" value="Add Categories" />
			<table id="category_table">
				<thead>
					<tr>
						<th>Category</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody><tr><td>&nbsp;</td><td>&nbsp;</td></tr></tbody>
			</table>
		</fieldset>
		<fieldset>
			<legend>Products</legend>
			<p>These products will be affected by the coupon.</p>
			<input type="button" class="catprod_selector" value="Add Product" />
			<table id="product_table">
				<thead>
					<tr>
						<th>Product</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody><tr><td>&nbsp;</td><td>&nbsp;</td></tr></tbody>
			</table>
		</fieldset>
	</div>
</form>