<h2>Permissions for "<?php echo $PLAN->name; ?>" Plan</h2>

<form id="admin_permissions_form" action="/admin/plans/processPermissions/" method="post">
	<input type="hidden" name="plan_id" value="<?php echo $PLAN->ID; ?>" />
<?php foreach($REG as $user_type => $controller): ?>
<fieldset>
	<legend><?php echo $user_type; ?> Permissions</legend>
	<?php foreach($controller as $controller_data): ?>
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
		echo draw_radio('perm[' . $data['code'] . ']', 1, (true == $PLAN->hasPermission($data['code'])), 'id="' . $data['code'] . '_on"');
		?>
			<label for="<?php echo $data['code']; ?>_on">yes</label>
		<?php
		echo draw_radio('perm[' . $data['code'] . ']', 0, (false == $PLAN->hasPermission($data['code'])), 'id="' . $data['code'] . '_off"');
		?>
			<label for="<?php echo $data['code']; ?>_off">no</label>
	</div>
	<?php endforeach; ?>
	<?php endforeach; ?>
</fieldset>
<?php endforeach; ?>
	<input type="submit" value="Save Permissions" /> or <a href="/admin/plans/">cancel</a>
</form>

<?php /* ?>
<hr />
<?php pprint_r($REG); ?>
<?php //*/ ?>
