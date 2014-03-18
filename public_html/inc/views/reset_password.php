<script type="text/javascript">
	$(document).ready(function() {
		$("#reset_password").submit(function() {
			var password = $("#new_password").val();
			var confirm_password = $("#confirm_new_password").val();
			if(password !== confirm_password || 0 == password.length) {
				$("#password_error").text("Passwords do not match.").css("color", "red").css("padding-left", "5px");
				return false;
			}
		});
	});
</script>
<h3 class="greeting">Reset Your Password</h3>
<div class="contentbox">
	<?php if($MS->count('reset_password') > 0) { ?>
	<div class="messagestack">
		<?php echo $MS->messages('reset_password'); ?>
	</div>
	<br />
	<?php } ?>
	<form id="reset_password" name="reset_password" action="" method="post">
		<fieldset>
		<input type="hidden" name="action" value="process_password_reset" />
		<input type="hidden" name="token" value="<?php echo $TOKEN; ?>" />
		<table>
			<tr>
				<td>Email Address:</td>
				<td><input type="text" name="email" /></td>
			</tr>
			<tr>
				<td>New Password:</td>
				<td><input type="password" id="new_password" name="new_password" /><span id="password_error">&nbsp;</span></td>
			</tr>
			<tr>
				<td>Confirm Password:</td>
				<td><input type="password" id="confirm_new_password" name="confirm_new_password" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Reset Password" /></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>