<script type="text/javascript">
$(document).ready(function() {
	$('a.cancel_option').click(function() {
		$("#shipping_form_holder").fadeOut(1000, function() {
			$(this).empty();
		});
		return false;
	});

	$("#customer_shipping_option_form").submit(function() {
		var valid = false;
		var shipping_option_id = parseFloat($('select[name="cso[custom_shipping_option_id]"]').val());
		if(shipping_option_id > 0) {
			valid = true;
		} else {
			alert(shipping_option_id);
		}
		return valid;
	});
});
</script>
<form id="customer_shipping_option_form" method="post" action="/customer_shipping/processOption/">
	<fieldset>
		<legend>Edit Customer Shipping Option</legend>
		<input type="hidden" name="cso[customer_id]" value="<?php echo $CSO->customer_id; ?>" />
		<input type="hidden" name="cso_id" value="<?php echo $CSO->ID; ?>" />
		Shipping Method:<br />
		<?php echo draw_select('cso[custom_shipping_option_id]', $OPTION_LIST, $CSO->custom_shipping_option_id); ?>
		<br />
		Account #:<br />
		<input type="text" name="cso[account_number]" value="<?php echo $CSO->account_number; ?>" /><br />
		<input type="submit" value="Save" />
		or <a href="#" class="cancel_option">cancel</a>
	</fieldset>
</form>
