<script type="text/javascript">
$(document).ready(function() {
	$('.charge_holder').sortable({
		cursor: "move",
		connectWith: ".charge_holder",
		forcePlaceholderSize: true,
		placeholder: 'sortable-holder'
	});

	$("#misc_charge_form").submit(function() {
		$("#unselected_charges").find('.hidden-charge').val("0");
		$("#selected_charges").find('.hidden-charge').val("1");
		return true;
	});
});
</script>
<h2>Misc Charges</h2>
<p>Drag misc charges to the right column to make them available for use by the sales team.</p>
<div class="messages"><?php echo $MS->messages('misc_charges'); ?></div>
<form id="misc_charge_form" action="/admin/misc_charges/save" method="post">
	<input type="submit" value="Save" /> or
	<a href="">Cancel</a>
	<br />
	<div id="unselected_charges" class="charge_column">
		<strong>Inactive Misc Charges</strong>
		<div class="charge_holder">
			<?php foreach($MISC_CHARGE_LIST['unselected'] as $charge): ?>
			<div class="charge">
				<?php echo $charge->description; ?>
				<input type="hidden" class="hidden-charge" name="charge_active[<?php echo $charge->ID; ?>]" value="0" />
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div id="selected_charges" class="charge_column">
		<strong>Active Misc Charges</strong>
		<div class="charge_holder">
			<?php foreach($MISC_CHARGE_LIST['selected'] as $charge): ?>
			<div class="charge">
				<?php echo $charge->description; ?>
				<input type="hidden" class="hidden-charge" name="charge_active[<?php echo $charge->ID; ?>]" value="1" />
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</form>
