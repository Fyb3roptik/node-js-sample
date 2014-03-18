<?php
/* Yeah, I'm a jerk so this gets to be here instead of in the controller. o_0 */
$O = $CC->checkout->createOrder();
$order_totals = $O->getTotals();
$product_list = $O->getProducts();
$cc = $CC->checkout->getCardInfo();
$billing_address = $CC->checkout->getBillingAddress();
$shipping_address = $CC->checkout->getShippingAddress();
?>
<script type="text/javascript">
   var bnOrderId = '123';
   var bnOrderTotal = '2100.99';
   var bnOrderDetails = new Array();
   // Product ID 1001 x 2 @ $50.00 each (productid:quantity:price)
   bnOrderDetails[0] = "1001:2:50.00";
   // Product ID 1002 x 1 @ $2000.99 each
   bnOrderDetails[1] = "1002:1:2000.99";

$(document).ready(function() {
	$("#order_review_form").submit(function() {
		$(this).submit(function() {
			//all subsequent submissions should return false. :D
			return false;
		});
	});
	$("#complete_bttn").click(function() {
		$("#complete_bttn").hide();
        $("#processing_bttn").show(); 
	});
});
</script>

<br />
<div>
	<?php echo $MS->messages('checkout_review'); ?>
	<ul id="Addresses">
		<li>
			<div class="LeftSide">
				Payment method<br />
				<a class="Red" href="/checkout/billing/">change</a>
			</div>
			<div class="RightSide">
			<?php if('cc' == $CC->checkout->payment_term): ?>
				<?php echo $cc['name']; ?><br />
				Card Ending: <?php echo substr($cc['number'], -4); ?><br />
				Exp: <?php echo $cc['exp_month']; ?> / <?php echo $cc['exp_year']; ?>
			<?php else: ?>
				<?php $term = new Payment_Term($CC->checkout->payment_term); ?>
				Invoice: <?php echo $term->name; ?>
			<?php endif; ?>
			</div>
		</li>
		<li>
			<div class="LeftSide">
				Billing Address<br />
				<a class="Red" href="/checkout/billing/">change</a>
			</div>
			<div class="RightSide">
				<?php echo $billing_address->name; ?><br />
				<?php if(strlen($billing_address->company) > 0): ?>
					<?php echo $billing_address->company; ?><br />
				<?php endif; ?>
				<?php echo $billing_address->address_1; ?>
   				<?php if(strlen($billing_address->address_2) > 0): ?>
					<br /><?php echo trim($billing_address->address_2); ?>
   				<?php endif; ?>
   				<?php if(strlen($billing_address->address_3) > 0): ?>
					<br /><?php echo trim($billing_address->address_3); ?>
   				<?php endif; ?>
				<br />
				<?php echo $billing_address->city; ?>, <?php echo $billing_address->state; ?> <?php echo $billing_address->zip_code; ?>
				<br />
				<?php echo $billing_address->country; ?>
			</div>
		</li>
		<li>
			<div class="LeftSide">
				Shipping Address<br />
				<a class="Red" href="/checkout/billing/">change</a>
			</div>
			<div class="RightSide">
				<?php echo $shipping_address->name; ?><br />
				<?php if(strlen($shipping_address->company) > 0): ?>
					<?php echo $shipping_address->company; ?><br />
				<?php endif; ?>
				<?php echo $shipping_address->address_1; ?>
   				<?php if(strlen($shipping_address->address_2) > 0): ?>
					<br /><?php echo trim($shipping_address->address_2); ?>
   				<?php endif; ?>
   				<?php if(strlen($shipping_address->address_3) > 0): ?>
					<br /><?php echo trim($shipping_address->address_3); ?>
   				<?php endif; ?>
				<br />
				<?php echo $shipping_address->city; ?>, <?php echo $shipping_address->state; ?> <?php echo $shipping_address->zip_code; ?>
				<br />
				<?php echo $shipping_address->country; ?>
			</div>
		</li>
	</ul>
	<div class="ExtClearBoth"></div>
</div>
<br clear="all" />
<br />
<form id="order_review_form" action="/checkout/processOrder/" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="PriceGrid">
	<thead>
		<tr>
			<th><div class="LeftCorner"></div></th>
			<th width="80">Catalog code</th>
			<th width="50"></th>
			<th>Product</th>
			<th width="120" class="Right">Unit Price</th>
			<th width="40" class="Center">Qty</th>
			<th width="50" class="Right">Cost</th>
			<th><div class="RightCorner"></div></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($product_list as $i => $P): ?>
			<?php
			$product = new Product($P->getProductID());
			$price_list = $product->getPrices();
			?>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo $product->catalog_code; ?></td>
			<td><img src="<?php echo $product->getDefaultImage(41); ?>" alt="" /></td>
			<td><?php echo $product->name; ?></td>
			<td class="Right">
				<?php echo price_format($P->getFinalUnitPrice());?> <span class="unitOfMeasure"><?php echo $product->getUnitOfMeasure(); ?></span>
			</td>
			<td class="Center"><?php echo $P->getQuantity(); ?></td>
			<td class="Right"><?php echo price_format($P->getFinalUnitPrice() * $P->getQuantity()); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php endforeach; ?>
		<?php foreach($O->getMiscCharges() as $misc_data): ?>
		<?php $MC = new Misc_Charge($misc_data->name, 'comment_code'); ?>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><?php echo $MC->description; ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="Right"><?php echo price_format($misc_data->unit_price); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td><div class="LeftCornerTfoot"></div></td>
			<td>&nbsp;</td>
			<td colspan="4"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><div class="RightCornerTfoot"></div></td>
		</tr>
	</tfoot>
</table>
<div id="checkout_notes">
	<br />
	<strong>Ship Date:</strong> <?php echo $CC->checkout->getSchedule('m/d/Y'); ?>
	<br /><br />

	<strong>Notes:</strong>
	<br />
	<?php echo nl2br($CC->checkout->note); ?>

	<?php if(true == is_a($USER, 'Sales_Rep')): ?>
	<br /><br />
	<strong>Sales Notes:</strong>
	<br />
	<?php echo nl2br($CC->checkout->sales_note); ?>
	<?php endif; ?>
</div>

<div id="PriceSummary">
	<br />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<?php foreach($order_totals as $total): ?>
		<tr>
			<td><?php echo $total['name']; ?></td>
			<td width="20%"><?php echo price_format($total['value']); ?></td>
		</tr>
		<?php endforeach; ?>
		<tfoot>
			<tr>
				<td colspan="2">
					<input type="image" id="complete_bttn" src="/images/complete_order_big.png" alt="" />
					<img src="/images/processing_bttn.png" id="processing_bttn" style="display:none;" />
				</td>
			</tr>
		</tfoot>
	</table>
</div>
</form>
<br clear="all" />
<br />
<br />