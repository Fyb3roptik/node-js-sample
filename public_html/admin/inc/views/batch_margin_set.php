<script type="text/javascript">
$(document).ready(function() {
	$("#batch_product_margin_set").submit(function() {
		var new_margin = $("input[name='new_margin']").val();
		var good_data = true;
		if(true == isNaN(parseFloat(new_margin)) || parseFloat(new_margin) <= 0) {
			alert("Margin must be a positive non-zero number.");
			good_data = false;
		}

		if(true == good_data) {
			good_data = confirm("Are you sure you want to set the margin for all " + $("input[name='product_id[]']").length + " products to '" + new_margin + "'?");
		}
		return good_data;
	});

});
</script>
<form id="batch_product_margin_set" action="/admin/batch/setMarginProcess/" method="post">
	<fieldset>
		<legend>Set Margin</legend>
		<?php foreach($PRODUCT_LIST as $P): ?>
		<input type="hidden" name="product_id[]" value="<?php echo $P->ID; ?>" />
		<?php endforeach; ?>
		<strong>New Margin:</strong>
		<input type="text" name="new_margin" value="" size="4" />
		<input type="submit" value="Set Margin" />
	</fieldset>
</form>
