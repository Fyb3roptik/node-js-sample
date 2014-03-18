<script type="text/javascript">
$(document).ready(function() {
	$("#batch_product_price_set").submit(function() {
		var valid = true;
		var new_price = $("input[name='new_price']").val();
		if(true == isNaN(parseFloat(new_price)) || parseFloat(new_price) <= 0) {
			alert("Price must be a positive, non-zero number.");
			valid = false;
		}
		return valid;
	});
});
</script>
<form id="batch_product_price_set" action="/admin/batch/setPriceProcess/" method="post">
	<fieldset>
		<legend>Set Price</legend>
		<?php foreach($PRODUCT_LIST as $P): ?>
		<input type="hidden" name="product_id[]" value="<?php echo $P->ID; ?>" />
		<?php endforeach; ?>
		<p>Note: Products with tiered pricing will be unaffected.</p>
		<strong>New Price</strong>
		<input type="text" name="new_price" size="4" />
		<input type="submit" value="Apply New Price" />
	</fieldset>
</form>