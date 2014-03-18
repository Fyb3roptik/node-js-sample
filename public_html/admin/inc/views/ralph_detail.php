<script type="text/javascript">
$(document).ready(function() {
	$('tbody tr th').attr('valign', 'top').css('text-align', 'right');
	$('#order_history').zebra({odd: "#ddd"});
});
</script>
<h2>Order #<?php echo $ORDER->ID; ?></h2>

<h3>Order Details</h3>
<div id="order_details">
	<table>
		<tbody>
			<tr>
				<th>Bill To:</th>
				<td>
					<?php
					echo html_entity_decode($ORDER->billing_name) . " / " .
						html_entity_decode($ORDER->billing_company) . "<br />" .
						$ORDER->billing_address_1 . ' ' .
						$ORDER->billing_address_2 . '<br />' .
						$ORDER->billing_city . ', ' .
						$ORDER->billing_state . ' ' . 
						$ORDER->billing_zip_code; 
					?>
				</td>
			</tr>
			<tr>
				<th>Ship To:</th>
				<td>
					<?php
					echo html_entity_decode($ORDER->shipping_name) . " / " .
						html_entity_decode($ORDER->shipping_company) . "<br />" .
						$ORDER->shipping_address_1 . ' ' .
						$ORDER->shipping_address_2 . '<br />' .
						$ORDER->shipping_city . ', ' . 
						$ORDER->shipping_state . ' ' . 
						$ORDER->shipping_zip_code; 
					?>
				</td>
			</tr>
			<tr>
				<th>Date Purchased:</th>
				<td><?php echo $ORDER->date_purchased; ?></td>
			</tr>
			<tr>
				<th>Note:</th>
				<td><?php echo $ORDER->note; ?></td>
			</tr>
			<tr>
				<th>Sales Note:</th>
				<td><?php echo $ORDER->sales_note; ?></td>
			</tr>
		</tbody>
	</table>
</div>

<h3>Products</h3>
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Quantity</th>
			<th>Price</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($ORDER->getProducts() as $product): ?>
		<tr>
			<td><?php echo $product->name; ?></td>
			<td><?php echo $product->getQuantity(); ?></td>
			<td><?php echo price_format($product->final_price); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h3>Change History</h3>
<div id="order_history">
<?php if(count($HISTORY) > 0): ?>
	<?php 
	foreach($HISTORY as $change): 
	$SR = new Sales_Rep($change->sales_rep);
	?>
	<div class="order_history">
		<table>
			<tbody>
				<tr>
					<th>Sales Rep</th>
					<td><?php echo $SR->name; ?></td>
				</tr>
				<tr>
					<th>Timestamp</th>
					<td><?php echo $change->timestamp; ?></td>
				</tr>
				<tr>
					<th>Change Type</th>
					<td><?php echo $change->change_type; ?></td>
				</tr>
				<tr>
					<th>Description</th>
					<td><?php echo nl2br($change->description); ?></td>
				</tr>
				<?php if(Order_Change_History::CHANGE == $change->change_type): ?>
				<tr>
					<th>Line Changes</th>
					<td>
						<?php if(count($change->getItems()) > 0): ?>
						<table>
							<thead>
								<tr>
									<th>Stock Code</th>
									<th>Change Type</th>
									<th>New Value/<br />Cancel Code</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($change->getItems() as $line): ?>
							<tr>
								<td><?php echo $line->stock_code; ?></td>
								<td><?php echo $line->change_type; ?></td>
								<td><?php echo $line->value; ?></td>
							</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<?php endif; ?>
					</td>
				<tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php endforeach; ?>
<?php else: ?>
No history here.
<?php endif; ?>
</div>
