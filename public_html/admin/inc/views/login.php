<script type="text/javascript">
$(document).ready(function() {
	$('input[name="email"]').focus();
});
</script>
<div class="messagestack">
	<?php echo $MS->messages(); ?>
</div>
<form id="admin_login" action="" method="post">
	<fieldset>
	<legend>login</legend>
	<input type="hidden" name="action" value="login" />
	<table>
		<tr>
			<td>email:</td>
			<td><input type="text" name="email" /></td>
		</tr>
		<tr>
			<td>password:</td>
			<td><input type="password" name="password" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" value="login" />
				<br />
				<a href="login.php?action=recover_password">Forgot your password?</a>
			</td>
		</tr>
	</table>
	</fieldset>
</form>