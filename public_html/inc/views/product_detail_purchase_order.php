<script type="text/javascript">
$(document).ready(function() {
	$("#por_table").hide();

	$("#toggle_por_table").toggle(
		function() {
			$(this).text('Hide');
			$("#por_table").show();
		},
		function() {
			$(this).text('Show');
			$("#por_table").hide();
		}
	);
	$("#por_table tbody tr td").css('font-size', '12px');
	$("#por_table tbody tr:odd").css('background', '#DDD');
});
</script>
<?php
if(false == isset($ERROR)) {
	if(count($POR_LIST) > 0) {
	?>
	Found <?php echo count($POR_LIST); ?> Purchase Order(s)
	[<a href="javascript:void(0);" id="toggle_por_table">show</a>]
	<table id="por_table">
		<thead>
			<tr>
				<th>Purchase Order</th>
				<th>Status</th>
				<th>Due Date</th>
				<th>Entry Date</th>
				<th>Order Quantity</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach($POR_LIST as $por) {
		?>
			<tr>
				<td><?php echo $por['purchase_order_id']; ?></td>
				<td><?php echo $por['order_status']; ?></td>
				<td><?php echo $por['due_date']; ?></td>
				<td><?php echo $por['entry_date']; ?></td>
				<td><?php echo $por['order_qty']; ?></td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<?php
	} else {
	?>
	<p>No purchase orders found for this product.</p>
	<?php
	}
} else {
	echo '<p>' . $ERROR . '</p>';
}
?>
