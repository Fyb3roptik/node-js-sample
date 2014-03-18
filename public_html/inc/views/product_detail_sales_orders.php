<script type="text/javascript">
$(document).ready(function() {
	$("#sor_table").hide();

	$("#toggle_sor_table").toggle(
		function() {
			$(this).text('Hide');
			$("#sor_table").show();
		},
		function() {
			$(this).text('Show');
			$("#sor_table").hide();
		}
	);
	$("#sor_table tbody tr td").css('font-size', '12px');
	$("#sor_table tbody tr:odd").css('background', '#DDD');
});
</script>
<div>
<?php
if(false == isset($ERROR)) {
	if(count($SOR_LIST) > 0) {
?>
	Found <?php echo count($SOR_LIST); ?> Sales Order(s)
	[<a href="javascript:void(0);" id="toggle_sor_table">Show</a>]
	<table id="sor_table" width="50%">
		<thead>
			<tr>
				<th>Sales Order</th>
				<th>Status</th>
				<th>Order Quantity</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach($SOR_LIST as $sor_data) {
		?>
			<tr>
				<td><?php echo $sor_data['sales_order_number']; ?></td>
				<td><?php echo $sor_data['status']; ?></td>
				<td><?php echo $sor_data['qty']; ?></td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<?php
	} else {
	?>
	<p>No sales orders found for this product.</p>
	<?php
	}
} else {
	echo '<p>' . $ERROR . '</p>';
}
?>
</div>
