<script type="text/javascript">
$(document).ready(function() {
	$('input[name="email"]').focus();
});
</script>
<div class="messagestack">
	<?php echo $MS->messages(); ?>
</div>
<form id="admin_login" action="" method="post">
	<input type="hidden" name="action" value="login" />
	<div class="form-group">
    	<label for="email">Email address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
	</div>
	<div class="form-group">
	    <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
	</div>
	<button type="submit" class="btn btn-success pull-right">Login</button>
	<span class="pull-left"><a href="login.php?action=recover_password">Forgot your password?</a></span>
	<div class="clearfix"></div>
</form>