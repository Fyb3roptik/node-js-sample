<form id="password_reset_form" action="" method="post">
	<fieldset>
		<legend>Reset Password</legend>
		<input type="hidden" name="action" value="process_password_reset" />
		<p>Enter your email address, new password, and password reset token below to reset your password.</p>
		<table>
			<tr>
				<td>Email:</td>
				<td><input type="text" name="email" value="" /></td>
			</tr>
			<tr>
				<td>New Password:</td>
				<td><input type="password" name="password" value="" /></td>
			</tr>
			<tr>
				<td>Confirm Password:</td>
				<td><input type="password" name="confirm_password" value="" /></td>
			</tr>
			<tr>
				<td>Token:</td>
				<td><input type="text" name="token" value="<?php echo sanitize_string(request_var('token')); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" value="Reset Password" /> or <a href="<?php echo LOC_LOGIN; ?>">Cancel</a>
				</td>
			</tr>
		</table>
	</fieldset>
</form>