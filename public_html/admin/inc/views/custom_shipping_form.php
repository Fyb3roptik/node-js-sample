<script type="text/javascript">
$(document).ready(function() {
	$("a.cancel_option").click(function() {
		$("#custom_shipping_form_holder").empty();
		return false;
	});

	$('#new_custom_shipping_form').submit(function() {
		var valid = true;
		var syspro_type = $('select[name="shipping_option[syspro_type]"]').val();
		if("null" == syspro_type) {
			valid = false;
			alert("You must select a Syspro shipping option.");
		}
		return valid;
	});
});
</script>
<form id="new_custom_shipping_form" method="post" action="/admin/custom_shipping/processOption/">
	<fieldset>
		<input type="hidden" name="custom_shipping_option_id" value="<?php echo $CSO->ID; ?>" />
		<legend>Custom Shipping Option Form</legend>
		<strong>Shipping Option Name</strong><br />
		<?php echo draw_select('shipping_option[syspro_type]', $SYSPRO_LIST, $CSO->syspro_type); ?>
		<input type="text" name="shipping_option[name]" value="<?php echo $CSO->name; ?>" />
		<input type="submit" value="Save" />
		or <a href="#" class="cancel_option">Cancel</a>
	</fieldset>
</form>
