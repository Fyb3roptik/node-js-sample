<h2>Edit Admin Details</h2>
<form id="admin_edit_form" action="/admin/admin/process/" method="post">
	<fieldset>
		<input type="hidden" name="admin_id" value="<?php echo $A->ID; ?>" />
		<table>
			<tr>
				<td>Name</td>
				<td><input type="text" name="admin[name]" value="<?php echo $A->name; ?>" /></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input type="text" name="admin[email]" value="<?php echo $A->email; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<u>Reset Admin's Password:</u>
        <br />
		<br />
		New Password: <input type="password" name="new_password" />
		<br />
		Confirm Password: <input type="password" name="confirm_password" />
		<br />
		<input type="submit" value="Save Admin" />
	</fieldset>
</form>