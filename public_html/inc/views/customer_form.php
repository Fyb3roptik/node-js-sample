<script type="text/javascript">
$(document).ready(function() {
	$("#validation_message").hide();
	$("#customer_form").submit(function() {
		//get our validation on!
		$("#validation_message").hide();
		var valid = true;
		$("input.required").each(function() {
			if("" == $(this).val()) {
				$("#validation_message").show();
				$(this).css('background-color', '#fcff9e');
				valid = false;
			} else {
				$(this).css('background-color', '#FFF');
			}
		});
		return valid;
	});
});
</script>
<h3 class="greeting">Editing Customer "<?php echo $C->name; ?>"</h3>
<div class="contentbox">
	<form id="customer_form" action="" method="post">
		<input type="hidden" name="customer_id" value="<?php echo $C->ID; ?>" />
		<input type="hidden" name="customer[sales_rep]" value="<?php echo $C->sales_rep; ?>" />
		<input type="hidden" name="action" value="process_customer" />
		<fieldset>
			<div id="validation_message">
				<p><strong>Please fill out all required fields.</strong></p>
			</div>
			<table>
				<tr>
					<td>Name:</td>
					<td><input type="text" name="customer[name]" class="required" value="" /></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><input type="text" name="customer[email]" class="required" value="<?php echo $C->email; ?>" /></td>
				</tr>
				<tr>
					<td>Secondary Email:</td>
					<td><input type="text" name="customer[secondary_email]" value="<?php echo $C->secondary_email; ?>" /></td>
				</tr>
				<?php if(false == $C->exists()): ?>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="new_password" class="required" value="" /></td>
				</tr>
				<?php endif; ?>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Save" /> or <a href="<?php echo LOC_SALES; ?>">Cancel</a></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>