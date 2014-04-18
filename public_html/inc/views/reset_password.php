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
<h3 class="page-header">Reset Your Password</h3>
<div class="contentbox">
	<?php if($MS->count('reset_password') > 0) { ?>
	<div class="messagestack">
		<?php echo $MS->messages('reset_password'); ?>
	</div>
	<br />
	<?php } ?>
	<form id="reset_password" name="reset_password" action="" method="post">
		
		<div class="col-lg-6 col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Beast Franchise Reset Password</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="" method="post">
                        <input type="hidden" name="action" value="process_password_reset" />
                        <input type="hidden" name="token" value="<?php echo $TOKEN; ?>" />
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address">
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter New Password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_new_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm New Password">
                        </div>
                        <button type="submit" class="btn btn-success pull-right">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
	</form>
</div>