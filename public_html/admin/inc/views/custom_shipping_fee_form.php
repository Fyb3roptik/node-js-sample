<script type="text/javascript">
$(document).ready(function() {
	$('a.cancel_fee').click(function() {
		$("#custom_fee_form_holder").empty();
		return false;
	});
});
</script>
<form id="custom_shipping_fee_form" method="post" action="/admin/custom_handling/processFee/">
	<fieldset>
		<legend>Edit Custom Handling Fee</legend>
		<input type="hidden" name="custom_fee_id" value="<?php echo $CSF->ID; ?>" />
		Minimum Order Total:<br />
		<input type="text" name="fee[minimum_cost]" value="<?php echo $CSF->minimum_cost; ?>" /><br />
		Handling Fee:<br />
		<input type="text" name="fee[handling_fee]" value="<?php echo $CSF->handling_fee; ?>" />
		<br />
		<input type="submit" value="Save Fee" /> or <a href="#" class="cancel_fee">cancel</a>
	</fieldset>
</form>
