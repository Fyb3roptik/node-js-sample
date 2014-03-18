<script type="text/javascript">
$(document).ready(function() {
	var warehouse_data = <?php echo json_encode(Product_Controller::lookupWarehouseData($P->catalog_code)); ?>;
	$("#wh_on_hand").text(warehouse_data['qty_on_hand']);
	$("#wh_allocated").text(warehouse_data['qty_allocated']);
	$("#wh_available").text(warehouse_data['qty_available']);
	$("#wh_safety_stock").text(warehouse_data['safety_stock_qty']);
	$("#wh_on_order").text(warehouse_data['qty_on_order']);
	$("#wh_default_bin").text(warehouse_data['default_bin']);
});
</script>
<br clear="all" />
<div class="contentbox">
	<div id="warehouse_data">
		<strong>Warehouse Data:</strong>
		<table>
			<tr>
				<td>On Hand:</td>
				<td id="wh_on_hand"></td>
			</tr>
			<tr>
				<td>Allocated:</td>
				<td id="wh_allocated"></td>
			</tr>
			<tr>
				<td>Available:</td>
				<td id="wh_available"></td>
			</tr>
			<tr>
				<td>Safety Stock:</td>
				<td id="wh_safety_stock"></td>
			</tr>
			<tr>
				<td>On Order:</td>
				<td id="wh_on_order"></td>
			</tr>
			<tr>
				<td>Default Bin:</td>
				<td id="wh_default_bin"></td>
			</tr>
		</table>
	</div>
</div>