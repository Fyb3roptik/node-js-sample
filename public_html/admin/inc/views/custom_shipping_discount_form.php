<script type="text/javascript">
$(document).ready(function() {
	$('a.cancel_discount').click(function() {
		$("#custom_discount_form_holder").empty();
		return false;
	});
});
</script>
<form id="custom_shipping_discount_form" method="post" action="/admin/shipping_discount/processDiscount/">
	<fieldset>
		<legend>Edit Custom Shipping Discount</legend>
		<input type="hidden" name="custom_shipping_discount_id" value="<?php echo $CSD->ID; ?>" />
		Zip Code:<br />
		<input type="text" name="discount[zip_code]" value="<?php echo $CSD->zip_code; ?>" /><br />
		Percentage:<br />
		<input type="text" name="discount[discount_percentage]" value="<?php echo $CSD->discount_percentage; ?>" />
		<br />
		<input type="submit" value="Save Discount" /> or <a href="#" class="cancel_discount">cancel</a>
	</fieldset>
</form>
