<script type="text/javascript">
$(document).ready(function() {
	$("#print_order").click(function() {
		window.print();
		return false;
	});
});
</script>



<div id="ThanksMessage">
	<br />
	<h3>Thank you for your order!</h3>
	<div class="order_number_line">Your order number is <span class="order_number"><?php echo $ORDER_ID; ?></span></div>
	<p>Your order will typically be delivered in 5 to 7 business days unless you have selected a faster shipping method. Most orders ship the same day. In the case of a back order, we will notify you within 5 business days. Please contact our customer service department if you need your delivery expedited or if you have additional questions. We appreciate your business!</p>
	<p>You will receive a Fedex Tracking Number by e-mail when your package(s) ships.</p>
	<p><b>Please print this page for your records.</b></p>
</div>

<br clear="all" />

<div id="AddressHolder">
	<ul id="Addresses">
		<li>
			<div class="LeftSide">Payment method</div>
			<div class="RightSide">
				<?php echo $PAYMENT_INFO; ?>
			</div>
		</li>
		<li>
			<div class="LeftSide">Billing Address</div>
			<div class="RightSide">
				<?php echo $BILLING_ADDRESS->name; ?><br />
				<?php if(strlen($BILLING_ADDRESS->company) > 0): ?>
					<?php echo $BILLING_ADDRESS->company; ?><br />
				<?php endif; ?>
				<?php echo $BILLING_ADDRESS->address_1; ?>
   				<?php if(strlen($BILLING_ADDRESS->address_2) > 0): ?>
					<br /><?php echo trim($BILLING_ADDRESS->address_2); ?>
   				<?php endif; ?>
   				<?php if(strlen($BILLING_ADDRESS->address_3) > 0): ?>
					<br /><?php echo trim($BILLING_ADDRESS->address_3); ?>
   				<?php endif; ?>
				<br />
				<?php echo $BILLING_ADDRESS->city; ?>, <?php echo $BILLING_ADDRESS->state; ?> <?php echo $BILLING_ADDRESS->zip_code; ?>
			</div>
		</li>
		<li>
			<div class="LeftSide">Shipping Address</div>
			<div class="RightSide">
				<?php echo $SHIPPING_ADDRESS->name; ?><br />
				<?php if(strlen($SHIPPING_ADDRESS->company) > 0): ?>
					<?php echo $SHIPPING_ADDRESS->company; ?><br />
				<?php endif; ?>
				<?php echo $SHIPPING_ADDRESS->address_1; ?>
   				<?php if(strlen($SHIPPING_ADDRESS->address_2) > 0): ?>
					<br /><?php echo trim($SHIPPING_ADDRESS->address_2); ?>
   				<?php endif; ?>
   				<?php if(strlen($SHIPPING_ADDRESS->address_3) > 0): ?>
					<br /><?php echo trim($SHIPPING_ADDRESS->address_3); ?>
   				<?php endif; ?>
				<br />
				<?php echo $SHIPPING_ADDRESS->city; ?>, <?php echo $SHIPPING_ADDRESS->state; ?> <?php echo $SHIPPING_ADDRESS->zip_code; ?>
			</div>
		</li>
	</ul>
	<div class="ExtClearBoth"></div>
</div>

<br clear="all" />
<br />

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
		<?php foreach($PRODUCT_LIST as $i => $P): ?>
			<?php
			$product = new Product($P->getProductID());
			$price_list = $product->getPrices();
			?>
		<tr>
			<td></td>
			<td><?php echo $product->catalog_code; ?></td>
			<td><img src="<?php echo $product->getDefaultImage(41); ?>" alt="<?php echo $product->getDefaultImageAlt(); ?>" /></td>
			<td>
				<a href="<?php echo get_product_url($product); ?>"><?php echo $product->name; ?></a>
			</td>
			<td class="Right">
				<?php echo price_format($P->getFinalUnitPrice());?> <span class="unitOfMeasure"><?php echo $product->getUnitOfMeasure(); ?></span>
			</td>
			<td class="Center"><?php echo $P->getQuantity(); ?></td>
			<td class="Right"><?php echo price_format($P->getFinalUnitPrice() * $P->getQuantity()); ?></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
		<?php foreach($O->getMiscCharges() as $misc_data): ?>
		<?php $MC = new Misc_Charge($misc_data->name, 'comment_code'); ?>
		<tr>
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
	<strong>Notes:</strong>
	<br />
	<?php echo nl2br($O->note); ?>

	<?php if(true == is_a($USER, 'Sales_Rep')): ?>
	<br /><br />
	<strong>Sales Notes:</strong>
	<br />
	<?php echo nl2br($O->sales_note); ?>
	<?php endif; ?>
</div>

<div id="PriceSummary"><br />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">

		<?php foreach($ORDER_TOTALS as $total): ?>
		<tr>
			<td><?php echo $total['name']; ?></td>
			<td width="20%"><?php echo price_format($total['value']); ?></td>
		</tr>
		<?php endforeach; ?>
		<tfoot>
			<tr>
				<td colspan="2">
					<a href="#" id="print_order"><img src="/images/print_btn.jpg" width="82" height="22" alt="" /></a>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

<br clear="all" />
<br />
<br />
<!-- URCHIN E-Comm Module -->
    <script language=.javascript.>__utmSetVar(.Customer.);</script>
    <form style="display:none;" name="utmform">
        <textarea id="utmtrans">
            UTM:T|<?php echo $ORDER_ID; ?>|INTERNAL|<?php echo substr($ORDER_TOTALS["3"]["value"], 0, -2); ?><?php echo $SHIPPING_ADDRESS->city; ?>|<?php echo $SHIPPING_ADDRESS->state; ?>|USA<br />
            <?php foreach($PRODUCT_LIST as $i => $P): ?>
			<?php $product = new Product($P->getProductID()); ?>
            UTM:I|<?php echo $ORDER_ID; ?>|<?php echo $product->catalog_code; ?>|<?php echo $product->name; ?>|<?php echo $product->getProductID(); ?>|<?php echo $P->getFinalUnitPrice(); ?>|<?php echo $P->getQuantity(); ?><br />
            <?php endforeach; ?>
        </textarea>
    </form>
<script language="Javascript">
    urchinTracker("/cart_done.php")
</script>
<script type="text/javascript">

   var bnOrderId = '<?php echo $ORDER_ID; ?>';
   var bnOrderTotal = '<?php echo substr($ORDER_TOTALS["3"]["value"], 0, -2); ?>';
   var bnOrderDetails = new Array();
   <?php echo $baynote_list ?>
</script>

<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '<?php echo GOOGLE_ANALYTICS_CODE; ?>']);
_gaq.push(['_trackPageview']);
_gaq.push(['_addTrans',
	'<?php echo $O->ID; ?>',
	'siing.co',
	'<?php echo $O->getTotal(); ?>'
]);

<?php foreach($PRODUCT_LIST as $i => $P): ?>
<?php $product = new Product($P->getProductID()); ?>
_gaq.push(['_addItem',
	'<?php echo $O->ID; ?>',			// order ID - required
	'<?php echo $product->catalog_code; ?>',	// SKU/code
	'<?php echo $product->name; ?>',		// product name
	'<?php echo $P->getFinalUnitPrice(); ?>',	// unit price - required
	'<?php echo $P->getQuantity(); ?>'		// quantity - required
]);

<?php endforeach; ?>

_gaq.push(['_trackTrans']); //submits transaction to the Analytics servers

(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
})();
</script>
<!-- Google Code for Purchase Conversion Page -->
<script language="JavaScript" type="text/javascript">
<!--
var google_conversion_id = '<?php echo GOOGLE_ANALYTICS_CODE; ?>';
var google_conversion_language = "en_US";
var google_conversion_format = "1";
var google_conversion_color = "FFFFFF";
var google_conversion_label = "Purchase";
//-->
if (<?php echo $O->getSubTotal(); ?>) {
var google_conversion_value = <?php echo $O->getSubTotal(); ?>;
}
</script>
<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<img height=1 width=1 border=0
src="http://www.googleadservices.com/pagead/conversion/UA-30857-8/?value=<?php echo $O->getSubTotal(); ?>&label=Purchase&script=0">
</noscript>
<!--  Yahoo tracker-->
<SCRIPT language="JavaScript" type="text/javascript">
<!-- Yahoo! Inc.
window.ysm_customData = new Object();
window.ysm_customData.conversion = "transId=<?php echo $O->ID; ?>,currency=US,amount=<?php echo $P->getFinalUnitPrice(); ?>";
var ysm_accountid = "1MLGHH1JAULPIUTG3VORTH36HS0";
document.write("<SCR" + "IPT language='JavaScript' type='text/javascript' " + "SRC=//" + "srv1.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid + "></SCR" + "IPT>");
// -->
</SCRIPT>
<img src="https://clickserve.cc-dt.com/link/order?vid=K160024&oid=<?php echo $ORDER_ID; ?>&amt=<?php echo $O->getSubTotal(); ?>&btzip=<?php echo $SHIPPING_ADDRESS->zip_code; ?>&prdsku=[<?php echo $catalog_codes; ?>]&prdnm=[<?php echo $product_names; ?>]&prdqn=[<?php echo $product_quantities; ?>]&prdpr=[<?php echo $product_final_prices; ?>]&FXSrc=USD" width=1 height=1 />