<script type="text/javascript">
$(document).ready(function() {
	$("#change_cat").click(function() {
		var cat_chooser_url = '/admin/category/chooser/0/';
		$("#category_chooser").load(cat_chooser_url);
		$("#category_chooser_fieldset").show();
	});
	$("#category_chooser_fieldset").hide();

	$("nav_item_form").submit(function() {
		return validate_form();
	});
});

function cancel_chooser() {
	$("#category_chooser_fieldset").hide();
}

function choose_category(category_id) {
	var $category_field = $("input[name='category_id']");
	$category_field.val(category_id);
	$("#category_chooser_fieldset").hide();
	$("#nav_item_form").submit();
}

function validate_form() {
	var category_id = parseInt($("input[name='category_id']").val());
	var good_data = false;
	if(cateogyr_id > 0) {
		good_data = true;
	}
	return good_data;
}
</script>
<form id="nav_item_form" action="/admin/nav/process/" method="post">
	<fieldset>
		<input type="hidden" name="nav_item_id" value="<?php echo $ITEM->ID; ?>" />
		<input type="hidden" name="category_id" value="<?php echo intval($ITEM->category_id); ?>" />
		Current Category: <?php echo $CURRENT_CAT->name; ?>
		(<a href="javascript:void(0)" id="change_cat">change</a>)
	</fieldset>
	<fieldset id="category_chooser_fieldset">
		<legend>Please Select A Category (<a href="javascript:void(0);" onclick="cancel_chooser()">cancel</a>)</legend>
		<p>Click on category name to choose.</p>
		<div id="category_chooser">&nbsp;</div>
	</fieldset>
</form>
