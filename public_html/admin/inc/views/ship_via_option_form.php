<script type="text/javascript">
$(document).ready(function() {
	$("#shipvia_cancel").click(function() {
		$("#ship_via_option_form").hide();
		return false;
	});

	$("#ship_via_option_form").submit(function() {
		var valid = true;
		var syspro_option_id = $(this).find('select').val();
		if("null" == syspro_option_id) {
			alert("Please select a valid SysPro option.");
			valid = false;
		}
		return valid;
	});
});
</script>
<form id="ship_via_option_form" action="/admin/custom_shipping/processShipVia/" method="post">
	<fieldset>
		<legend>Ship Via Option</legend>
		<input type="hidden" name="svo_id" value="<?php echo $SVO->ID; ?>" />
		Option Name:<br />
		<input type="text" name="svo[option_name]" value="<?php echo $SVO->option_name; ?>" />
		<br />
		Syspro Option:<br />
		<?php echo draw_select('svo[syspro_option]', $SYSPRO_LIST, $SVO->syspro_option); ?>
		<br />
		<input type="submit" value="Save" />
		or
		<a id="shipvia_cancel" href="#">Cancel</a>
	</fieldset>
</form>
