<script type="text/javascript">
var PRODUCT_ID = <?php echo $P->ID; ?>;
function remove_category(category_id) {
	var data = {
		"category_id" : category_id,
		"product_id" : PRODUCT_ID
	}
	$.post('/admin/product/removeCategory/', data, function(data) {
		if(true == data['success']) {
			$("#product_form").submit();
		}
	}, "json");
}

function choose_category(category_id) {
	var data = {
		"category_id" : category_id,
		"product_id" : PRODUCT_ID
	}
	$.post('/admin/product/addCategory/', data, function(data) {
		if(true == data['success']) {
			$("#product_form").submit();
		}
	}, "json");
}

$(document).ready(function() {
	$("#cat_choose").hide();
	$('a.add_cat').click(function() {
		$("#cat_choose").show();
		$("#cat_chooser").load('/admin/category/chooser/');
		return false;
	});

	$("a.cancel_add_cat").click(function() {
		$("#cat_choose").hide();
		return false;
	});
});
</script>
<fieldset>
	<legend>Categories (<a href="#" class="add_cat">add</a>)</legend>
	<?php
$cat_list = $P->getCategories();
if(count($cat_list) > 0) {
?>
<table>
	<thead>
		<tr>
			<th>Category</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($cat_list as $CAT) {
	?>
		<tr>
			<td><?php echo $CAT->url; ?></td>
			<td>
				<a href="javascript:void(0)" onclick="remove_category(<?php echo $CAT->ID; ?>)">remove</a>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
} else {
?>
<p>This product hasn't been associated with any products yet.</p>
<?php
}
?>
</fieldset>
<fieldset id="cat_choose">
	<legend>Add New Category (<a href="#" class="cancel_add_cat">cancel</a>)</legend>
	<div id="cat_chooser"><img src="/images/ajax-loader.gif" alt="loading..." /> Loading...</div>
</fieldset>
