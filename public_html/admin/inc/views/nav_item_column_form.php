<?php
$nav_item = new Category_Nav_Item($COLUMN->nav_item_id);
$CATEGORY = new Category($nav_item->category_id);
?>
<script type="text/javascript">
var ITEM_ID = <?php echo $COLUMN->ID; ?>;
var PARENT_ID = <?php echo $COLUMN->getTopCategory(); ?>;

$(document).ready(function() {
	$(".new_category_link").live('click', function() {
		$("#category_chooser").load('/admin/category/chooser/' + PARENT_ID);
		$("#category_chooser_fieldset").show();
		return false;
	});
	$("#category_chooser_fieldset").hide();

	$("#current_items").load('/admin/nav/getColumnCategories/' + ITEM_ID);
});

function choose_category(category_id) {
	var post_data = {"category_id" : category_id}
	var post_url = '/admin/nav/columnAddCategory/' + ITEM_ID;
	$.ajax({
		async: false,
		url: post_url,
		data: post_data,
		dataType: "json",
		type: "POST",
		success: function(data, message) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}
	});
	$("#category_chooser_fieldset").hide();
	window.location = window.location;
}
</script>
	<h2>Editing Column on nav item "<a href="/admin/nav/edit/<?php echo $COLUMN->nav_item_id; ?>/"><?php echo $CATEGORY->name; ?></a>"</h2>
<form id="category_nav_item_column_form" action="" method="post">
	<p><a href="#" class="new_category_link">Add New Category</a></p>
	<div id="current_items"></div>
	<fieldset id="category_chooser_fieldset">
		<legend>Choose A New Category</legend>
		<div id="category_chooser"></div>
	</fieldset>
</form>
