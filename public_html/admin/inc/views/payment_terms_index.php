<script type="text/javascript">
$(document).ready(function() {
	$("#save_field").hide();
	$("#payment_terms_table tbody")
		.zebra()
		.sortable({
			stop: function() {
				$("#save_field").fadeIn(2000);
				$(this).zebra();
			}
		});
	$("#save_sort").click(function() {
		var confirm_save = confirm("Are you sure you want to alter this sort order?");
		if(true == confirm_save) {
			var post_data = { };
			var index = 0;
			$("input[name='term_id[]']").each(function() {
				var field_name = "term_id[" + index + "]";
				post_data[field_name] = $(this).val();
				index++;
			});

			$.post('/admin/terms/saveSort/', post_data, function(data) {
				$("#sort_save_message").text(data['message']);
			}, "json");
		}
	});
});
</script>
<h2>Manage Payment Terms</h2>
<p>
	<strong>Instructions:</strong>
	Drag and drop payment terms to reorder them.
	Terms at the top of the list are less restricted than terms at the bottom of the list.
</p>
<p>
	Example: <em>If a sales rep has access to payment term at the bottom of the list, then they can "downgrade" an order's payment terms to any term listed above their "maximum" allowed term.</em>
</p>
<?php if(count($TERM_LIST) > 0): ?>
<div id="save_field">
	<input type="button" id="save_sort" value="Save New Sort Order" />
	<div id="sort_save_message"></div>
</div>
<table id="payment_terms_table">
	<thead>
		<tr>
			<th>Term Code</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($TERM_LIST as $TERM): ?>
		<tr>
			<td>
				<input type="hidden" name="term_id[]" value="<?php echo $TERM->ID; ?>" />
				<?php echo $TERM->syspro_code; ?>
			</td>
			<td><?php echo $TERM->name; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p>No payment terms have been found. Perhaps the import script isn't working correctly?</p>
<?php endif; ?>
