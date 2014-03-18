<script type="text/javascript">
function drop_sales_rep(sales_rep_id) {
	var ID = parseInt(sales_rep_id);
	if(ID > 0) {
		var confirm_drop = confirm("Are you sure you want to delete this Sales Rep?");
		if(true == confirm_drop) {
			var data = {"action": "drop_sales_rep",
					"sales_rep_id" : ID,
					"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>"}
			$.post('/admin/salesrep/drop/', data, function(data) {
				if(true == data.success) {
					var row_id = "#rep_row_" + ID;
					$(row_id).remove();
				} else {
					alert("There was an error deleting this sales rep. Please try again later.");
				}
			}, "json");
		}
	}
}
</script>
<h2>Manage Baynote Feed</h2>
<table>
	<thead>
		<tr>
			<th>Category</th>
		</tr>
	</thead>
	<?php
	if(count($CATS) > 0) {
	?>
	<tbody>
		<?php
		foreach($CATS as $i => $CAT) {
		?>
		<tr id="rep_row_<?php echo $CAT['category_id']; ?>">
			<td><?php echo $CAT['name']; ?></td>
			<td>
				<a href="/admin/baynote/edit/<?php echo $CAT['category_id']; ?>">[edit]</a>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="drop_sales_rep(<?php echo $CAT['category_id']; ?>);">[delete]</a>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
	<?php
	}
	?>
</table>