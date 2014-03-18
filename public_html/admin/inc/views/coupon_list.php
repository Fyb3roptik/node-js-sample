<script type="text/javascript">
	$(document).ready(function() {

	});

	function drop_coupon(coupon) {
		var coupon_id = parseInt(coupon);

		if(coupon > 0) {
			var confirm_drop = confirm("Are you sure you want to delete this coupon?");
			if(true == confirm_drop) {
				var data = {"coupon_id" : coupon_id,
						"action" : "drop_coupon",
						"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>"}
				$.post('/admin/coupons.http.php', data, function(data) {
					if(true == data.success) {
						window.location = window.location;
					}
				}, "json");
			}
		}
	}
</script>
<h2>Manage Coupon Codes</h2>
<form id="coupon_list" action="" method="post">
	<a href="<?php echo LOC_COUPONS; ?>?action=new">Add New Coupon</a>
	<?php
	if(count($COUPON_LIST) > 0) {
	?>
	<fieldset>
		<legend>Current Coupons</legend>
		<table>
			<thead>
				<tr>
					<th>Nickname</th>
					<th>Code</th>
					<th>Web Link</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($COUPON_LIST as $C) {
			?>
				<tr>
					<td><?php echo $C->nickname; ?></td>
					<td><?php echo $C->code; ?></td>
					<td>cc=<?php echo $C->code; ?></td>
					<td>
						<a href="<?php echo LOC_COUPONS; ?>?action=edit&amp;coupon=<?php echo $C->ID; ?>">[edit]</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="drop_coupon(<?php echo $C->ID; ?>)">[delete]</a>
					</td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
	</fieldset>
	<?php
	}
	?>
</form>
