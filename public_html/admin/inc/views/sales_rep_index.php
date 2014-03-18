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
<h2>Manage Sales Reps</h2>
<a href="/admin/salesrep/newRep/">Create New Sales Rep</a>
<table>
	<thead>
		<tr>
			<th>Sales Rep</th>
			<th>Status</th>
		</tr>
	</thead>
	<?php
	if(count($REP_LIST) > 0) {
	?>
	<tbody>
		<?php
		foreach($REP_LIST as $i => $REP) {
		?>
		<tr id="rep_row_<?php echo $REP->ID; ?>">
			<td><?php echo $REP->name; ?></td>
			<td><?php echo $REP->status; ?></td>
			<td>
				<a href="/admin/salesrep/edit/<?php echo $REP->ID; ?>">[edit]</a>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="drop_sales_rep(<?php echo $REP->ID; ?>);">[delete]</a>
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