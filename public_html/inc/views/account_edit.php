<script type="text/javascript">
	$(document).ready(function() {
		$(".contentbox table tr td:even").css("text-align", "right").css('font-weight', 'bold');
	});
</script>

<div class="contentbox">
	<form id="customer_form" action="/myaccount" method="post">
	<fieldset>
	<input type="hidden" name="action" value="process_updates" />
	<div class="account_info_label">Name:</div>
	<div class="account_info_info"><input type="text" name="customer_name" value="<?php echo $CUSTOMER->name; ?>" /></div>
     <br clear="all" />
    <div class="account_info_label">Stage Name:</div>
	<div class="account_info_info"><input type="text" name="customer_stage_name" value="<?php echo $CUSTOMER->stage_name; ?>" /></div>
     <br clear="all" />
    <div class="account_info_label">Email:</div>
	<div class="account_info_info"><input type="text" name="customer_email" value="<?php echo $CUSTOMER->email; ?>" /></div>
    <br clear="all" />
    <div class="account_info_label">Username:</div>
	<div class="account_info_info"><input type="text" name="customer_username" value="<?php echo $CUSTOMER->username; ?>" /></div>
     <br clear="all" />
     <br clear="all" />
     <br clear="all" />
    <div class="account_info_info"><u>Change Password</u></div>
     <br clear="all" />
    <div class="account_info_label">Current Password:</div>
	<div class="account_info_info"><input type="password" name="current_password" /></div>
     <br clear="all" />
    <div class="account_info_label">New Password:</div>
	<div class="account_info_info"><input type="password" name="new_password" /></div>
     <br clear="all" />
    <div class="account_info_label">Confirm Password:</div>
	<div class="account_info_info"><input type="password" name="confirm_password" /></div>
     <br clear="all" />
     <br clear="all" />
	<input type="submit" value="Update Account" /> or <a class="cancel" href="<?php echo LOC_ACCOUNT_HOME; ?>">Cancel</a>
	</form>
</div>