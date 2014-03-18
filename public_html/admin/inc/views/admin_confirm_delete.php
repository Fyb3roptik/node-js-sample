<h2>Confirm Account Removal</h2>
<p>Are you sure you want to permantently remove the administrative account for <strong><?php echo $DROP_ADMIN->name; ?></strong>?</p>
<form id="drop_admin" action="/admin/admin/dropAdmin/" method="post">
	<input type="hidden" name="admin_id" value="<?php echo $DROP_ADMIN->ID; ?>" />
	<input type="submit" value="Delete Account" />
	or <a href="<?php echo LOC_MANAGE_ADMIN; ?>">cancel</a>
</form>
