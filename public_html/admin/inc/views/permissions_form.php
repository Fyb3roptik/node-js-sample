<h2>Permissions for "<?php echo $ADMIN->name; ?>"</h2>

<form id="admin_permissions_form" action="/admin/admin/processPermissions/" method="post">
	<input type="hidden" name="admin_id" value="<?php echo $ADMIN->ID; ?>" />
<?php foreach($REG as $controller => $controller_data): ?>
<fieldset>
	<legend><?php echo $controller; ?></legend>
	<?php foreach($controller_data as $data): ?>
	<div class="action">
		<p>
		<?php if(false == empty($data['description'])): ?>
			<?php echo $data['description']; ?>
		<?php else: ?>
			<?php echo $data['code']; ?>
		<?php endif; ?>
		</p>
		<?php
		echo draw_radio('perm[' . $data['code'] . ']', 1, (true == $ADMIN->hasPermission($data['code'])), 'id="' . $data['code'] . '_on"');
		?>
			<label for="<?php echo $data['code']; ?>_on">yes</label>
		<?php
		echo draw_radio('perm[' . $data['code'] . ']', 0, (false == $ADMIN->hasPermission($data['code'])), 'id="' . $data['code'] . '_off"');
		?>
			<label for="<?php echo $data['code']; ?>_off">no</label>
	</div>
	<?php endforeach; ?>
</fieldset>
<?php endforeach; ?>
	<input type="submit" value="Save Permissions" /> or <a href="/admin/admin/">cancel</a>
</form>

<?php /* ?>
<hr />
<?php pprint_r($REG); ?>
<?php //*/ ?>
