<div class="messagestack">
	<?php echo $MS->messages(); ?>
</div>
<form id="password_recover_form" action="" method="post">
	<fieldset>
		<legend>Recover Password</legend>
		<input type="hidden" name="action" value="process_recover_password" />
		<p>
		Input your email address below and a password reset token will be emailed to you.
		</p>
		<table>
			<tr>
				<td>email:</td>
				<td><input type="text" name="email" value="" /></td>
				<td><input type="submit" value="Recover Password" /></td>
			</tr>
		</table>
	</fieldset>
</form>