<?php echo $MS->messages('customer_form'); ?>
<form id="customer_form" action="/admin/customer/process/" method="post">
	<fieldset>
		<legend>Customer Details</legend>
		<input type="hidden" name="customer_id" value="<?php echo $C->ID; ?>" /><br />
		Name:<br />
		<input type="text" name="customer[name]" value="<?php echo $C->name; ?>" /><br />
		Email:<br />
		<input type="text" name="customer[email]" value="<?php echo $C->email; ?>" /><br />
		DJ Name:<br />
		<input type="text" name="username" value="<?php echo $C->username; ?>" /><br />
		Stage Name:<br />
		<input type="text" name="customer[stage_name]" value="<?php echo $C->stage_name; ?>" /><br />
		<br />
		Plan:<br />
		<?php echo draw_select('customer[plan_id]', $PLANS, $C->plan_id); ?>
		<br />
		<br />
		<br />
		<u>Reset Customer's Password:</u>
        <br />
		<br />
		New Password: <input type="password" name="new_password" />
		<br />
		Confirm Password: <input type="password" name="confirm_password" />
		<br />
		<input type="submit" value="Save Customer" /> or <a href="/admin/customer/">Cancel</a>
	</fieldset>
</form>
