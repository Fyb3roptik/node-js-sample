<script type="text/javascript">
$(document).ready(function() {
	$('#product_table tbody').zebra();
	$('select[name="cat_option"]').change(function() {
		var cat_id = parseInt($(this).val());
		if(cat_id > 0) {
			$("input[name='cat']").val(cat_id);
			$("#product_search_form").submit();
		}
	});

	//This is for the sake of shitty, shitty IE7/8. :-/
	$("#global_check").click(function() {
		$(this).change();
	});

	$("#global_check").change(function() {
		var checked_value = $(this).attr('checked');
		$('input.product_checkbox').attr('checked', checked_value);
	});

	$("#make_active").click(function() {
		make_active();
	});

	$("#make_inactive").click(function() {
		make_inactive();
	});

	$("#make_orderable").click(function() {
		make_orderable();
	});

	$("#make_nonorderable").click(function() {
		make_nonorderable();
	});

	$("#delete_products").click(function() {
		delete_products();
	});

	$("#advanced_edit").click(function() {
		var selected_products = get_checked_products();
		if(selected_products.length > 0) {
			var url = '/admin/batch/';
			var products = '';
			for(var i in selected_products) {
				var product_id = selected_products[i];
				products += product_id + "+";
			}
			url += "?products=" + products;
			window.open(url, 'batch_edit', 'width=800,height=600,location=0,menubar=0,toolbar=0,scrollbars=1');
		} else {
			alert("You need to select some products first.");
		}
	});
});

function delete_products() {
	var selected_products = get_checked_products();
	if(selected_products.length > 0) {
		if(true == confirm("Are you sure you want to PERMANENTLY delete these products?")) {
			var post_data = { }
			for(var i in selected_products) {
				var field_name = "product_id[" + i + "]";
				post_data[field_name] = selected_products[i];
			}
			$.post('/admin/product/batchDelete/', post_data, function(data) {
				if(true == data['success']) {
					window.location.reload();
				} else {
					alert("Something went wrong with the deletion.");
				}
			}, "json");
		}
	} else {
		alert("You must select some products to delete.");
	}
}

function make_orderable() {
	var selected_products = get_checked_products();
	if(selected_products.length > 0) {
		if(true == confirm("Are you sue you want to set these products to 'orderable' status?")) {
			batch_set_orderable(selected_products, 1);
		}
	} else {
		alert("Please select some products first.");
	}
}

function make_nonorderable() {
	var selected_products = get_checked_products();
	if(selected_products.length > 0) {
		if(true == confirm("Are you sue you want to set these products to 'unorderable' status?")) {
			batch_set_orderable(selected_products, 0);
		}
	} else {
		alert("Please select some products first.");
	}

}


function get_checked_products() {
	var products = [];
	var index = 0;
	$('input.product_checkbox').each(function() {
		if(true == $(this).attr('checked')) {
			products[index] = $(this).val();
			index++;
		}
	});

	return products;
}

function make_active() {
	var selected_products = get_checked_products();
	if(selected_products.length > 0) {
		var confirm_active = confirm("Are you sure you want to set all of these products to 'active' status?");
		if(true == confirm_active) {
			batch_set_active(selected_products, 1);
		}
	} else {
		alert("Please select some products first.");
	}
}


function make_inactive() {
	var selected_products = get_checked_products();
	if(selected_products.length > 0) {
		var confirm_active = confirm("Are you sure you want to set all of these products to 'inactive' status?");
		if(true == confirm_active) {
			batch_set_active(selected_products, 0);
		}
	} else {
		alert("Please select some products first.");
	}
}

function batch_set_active(product_list, active) {
	var post_data = { "active" : active }
	for(var i in product_list) {
		var field_name = "product_id[" + i + "]";
		var field_value = product_list[i];
		post_data[field_name] = field_value;
	}
	$.post('/admin/product/batchActivate/', post_data, function(data) {
		if(true == data['success']) {
			window.location.reload();
		} else {
			alert('OH NOS');
		}
	}, "json");
}

function batch_set_orderable(selected_products, orderable) {
	var post_data = {"orderable" : orderable}
	for(var i in selected_products) {
		var field_name = "product_id[" + i + "]";
		var field_value = selected_products[i];
		post_data[field_name] = field_value;
	}
	$.post('/admin/product/batchOrderable', post_data, function(data) {
		if(true == data['success']) {
			window.location.reload();
		} else {
			alert("Drat, that didn't go as planned.");
		}
	}, "json");
}

function drop_product(product_id) {
	var confirm_drop = confirm("Are you sure you want to delete this product?");
	if(true == confirm_drop) {
		var post_data = {
			"product_id" : product_id
		}
		$.post('/admin/product/drop/', post_data, function(data) {
			if(true == data['success']) {
				$("#product_" + product_id).remove();
				$('#product_table tbody').zebra();
			}
		}, "json");
	}
	return false;
}
</script>
<h2>Manage Products</h2>
<p>
	<a href="/admin/product/new/">Add New Product</a> |
	<a href="/admin/search_export/export/<?php echo $PRODUCT_ID_SORT_LINK; ?>">Export CSVs for current search</a>
</p>
<form id="product_search_form" action="" method="get">
	<fieldset>
		<table>
			<tr>
				<td>
					Stock Code:
					<br />
					<input type="text" name="stock_code" value="<?php echo get_var('stock_code'); ?>" />
				</td>
				<td>
					Title/Description:
					<br />
					<input type="text" name="q" value="<?php echo get_var('q', null); ?>" />
				</td>
				<td>
					<input type="hidden" name="cat" value="<?php echo $C->ID; ?>" />
					Category:
					<?php
					if(count($breadcrumb) > 0) {
					?>
						<span id="search_crumbs">(<?php echo implode(' > ', $breadcrumb); ?>)</span>
					<?php
					}
					?>
					<br />
					<?php
					$subcats = $C->find('parent_id', $C->ID);
					if(count($subcats) > 0) {
						$subcat_options[0] = 'Select Category';
						foreach($subcats as $i => $sub) {
							$subcat_options[$sub->ID] = $sub->name;
						}
						echo draw_select('cat_option', $subcat_options, 0);
					} else { 
					?>
					No subcategories found.
					<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Search Products" /></td>
			</tr>
		</table>
	</fieldset>
</form>
<div class="pk_links">
	<?php echo $PK_LINKS; ?>
</div>
<div id="with_checked">
	With Selected:
	<input type="button" id="make_active" value="Make Active" />
	<input type="button" id="make_inactive" value="Make Inactive" />
	<input type="button" id="make_orderable" value="Make Orderable" />
	<input type="button" id="make_nonorderable" value="Make Unorderable" />
	<input type="button" id="delete_products" value="Delete" />
	<input type="button" id="advanced_edit" value="Advanced Edit" />
</div>
<table id="product_table">
	<thead>
		<tr>
			<th>
				<input type="checkbox" name="global_check" id="global_check" value="1" />
			</th>
			<th>
				<a href="<?php echo $PRODUCT_ID_SORT_LINK; ?>">Product ID</a>
			</th>
			<th>
				<a href="<?php echo $CAT_SORT_LINK; ?>">Stock Code</a>
			</th>
			<th>
				Thumb
			</th>
			<th>
				<a href="<?php echo $NAME_SORT_LINK; ?>">Product</a>
			</th>
			<th>
				<a href="<?php echo $ACTIVE_SORT_LINK; ?>">Active</a>
			</th>
			<th>
				<a href="<?php echo $ORDERABLE_SORT_LINK; ?>">Orderable</a>
			</th>
			<th>
				<a href="<?php echo $PRICE_SORT_LINK; ?>">Price</a>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($PRODUCT_LIST as $i => $product_id) {
		$P = new Product($product_id['product_id']);
		?>
		<tr id="product_<?php echo $P->ID; ?>">
			<td>
				<input type="checkbox" name="product_checkbox_<?php echo $P->ID; ?>" class="product_checkbox" value="<?php echo $P->ID; ?>" />
			</td>
			<td><?php echo $P->ID; ?></td>
			<td><?php echo $P->catalog_code; ?></td>
			<td>
				<a href="<?php echo product_edit_url($P); ?>"><img src="<?php echo $P->getDefaultImage(50); ?>" /></a>
			</td>
			<td><a href="<?php echo product_edit_url($P); ?>"><?php echo $P->name; ?></a></td>
			<td><?php echo ($P->active > 0) ? "active" : "inactive"; ?></td>
			<td><?php echo ($P->orderable > 0) ? "orderable" : "unorderable"; ?></td>
			<td><?php echo price_format($P->getPrice(1)); ?></td>
			<td>
				<a href="/admin/product/cloneProduct/<?php echo $P->ID; ?>">clone</a>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="drop_product(<?php echo $P->ID; ?>)">delete</a>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
<div class="pk_links">
	<?php echo $PK_LINKS; ?>
</div>