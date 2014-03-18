<div class="BreadCrumb">
	<a href="/">home</a> >
	<a href="<?php echo LOC_ACCOUNT_HOME; ?>">My Account</a> &gt;
	<a href="<?php echo LOC_ORDER_HISTORY; ?>">Order history</a> &gt;
	Order #<?php echo $ORDER_ID; ?>
</div>
<br clear="all" />
<div class="messages">
	<?php echo $MS->messages(); ?>
</div>

<div class="order_head">
	<span class="RedText2">Order #<?php echo $ORDER_ID; ?></span>&nbsp;&nbsp;Order Date <?php echo date('m/d/Y', strtotime($O->date_purchased)); ?>
</div>



<div class="invoice_wrap">

<div class="invoice_info">
<?php if(true == ($SALES_REP instanceof Sales_Rep) && true == $SALES_REP->exists()): ?>
<table width="245" id="order_info">
	<tr>
		<th colspan="3" align="left">Sales Person:</th>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Name:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SALES_REP->name; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Phone:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SALES_REP->phone; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Fax:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SALES_REP->fax; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Email:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SALES_REP->email; ?></td>
	</tr>
</table>
<?php endif; ?>

<table width="245" id="order_info">
	<tr>
		<th align="left">Payment Method:</th>
	</tr>
	<tr>
		<td><?php echo $PAYMENT_INFO; ?></td>
	</tr>
</table>

<?php if(strlen($SHIPPING_METHOD) > 0): ?>
<table width="245" id="order_info">
	<tr>
		<th align="left">Shipping Information:</th>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Ship Via:</td>
	</tr>
	<tr>	
		<td><?php echo $SHIPPING_METHOD; ?></td>
	</tr>
<?php if(count($O->getTrackingNumbers()) > 0): ?>
	<tr>	
		<td class="invoice_lable">Tracking Numbers:</td>
	</tr>
	<tr>	
		<td>
		<?php
		$number_list = array();
		foreach($O->getTrackingNumbers() as $number) {
		$number_list[] = $number->getTrackingLink();
		}
		echo implode(', ', $number_list);
		?>
		</td>
	</tr>
<?php endif; ?>
</table>
<?php endif; ?>

<?php if(count($INVOICE_LIST) > 0): ?>
<table width="245" id="order_info">
	<tr>
		<th colspan="3" align="left">Invoices:</th>
	</tr>
	<?php foreach($INVOICE_LIST as $I): ?>
	<tr>
		<td class="invoice_lable" width="65">Invoice #:</td>
		<td width="20"></td>
		<td width="165"><a href="/invoice/view/<?php echo $O->ID; ?>/?invoice=<?php echo $I->ID; ?>"><?php echo $I->syspro_id; ?></a></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Date:</td>
		<td width="20"></td>
		<td width="165"><?php echo date('m/d/Y', strtotime($I->date)); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>
<br clear="all" />
<!--end invoice_info--></div>

<div class="invoice_info">
<table width="245" id="order_info">
	<tr>
		<th colspan="3" align="left">Bill To:</th>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Contact:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILLING_ADDRESS->name; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILLING_ADDRESS->address_1; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address 2:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILLING_ADDRESS->address_2; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address 3:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILLING_ADDRESS->address_3; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">City:</td>	
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILLING_ADDRESS->city; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">State:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILLING_ADDRESS->state; ?> </td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Zip Code:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILLING_ADDRESS->zip_code; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Phone:</td>
		<td width="20"></td>
		<td width="165"><?php echo $BILLING_ADDRESS->phone; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Email:</td>
		<td width="20"></td>
		<td width="165"><?php echo $BILLING_ADDRESS->email; ?></td>
	</tr>
</table>		
<!--end invoice_info--></div>

<div class="invoice_info">
<table width="245"  id="order_info">
	<tr>
		<th colspan="3" align="left">Ship To:</th>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Contact:</td>
		<td width="20"></td>
		<td><?php echo $SHIPPING_ADDRESS->name; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIPPING_ADDRESS->address_1; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address 2:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIPPING_ADDRESS->address_2; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address 3:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIPPING_ADDRESS->address_3; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">City:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIPPING_ADDRESS->city; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">State:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SHIPPING_ADDRESS->state; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Zip Code:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIPPING_ADDRESS->zip_code; ?></td>
	</tr>
		<tr>	
		<td class="invoice_lable" width="65">Phone:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SHIPPING_ADDRESS->phone; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Email:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SHIPPING_ADDRESS->email; ?></td>
	</tr>
</table>
<!--end invoice_info--></div>

<!--end invoice_wrap--></div>

<br clear="all" />
<br clear="all" />
<br clear="all" />


<table width="100%" border="0" cellpadding="0" cellspacing="0" class="PriceGrid">
	<thead>
		<tr>
			<th><div class="LeftCorner"></div></th>
			<th align="left" width="80">Catalog code</th>
			<th width="50"></th>
			<th align="left" width="535">Product</th>
			<th align="left">Unit Price</th>
			<th align="left" width="40">Qty</th>
			<th align="left">Cost</th>
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
			<td>&nbsp;</td>
			<td class="borderRight"><?php echo $product->catalog_code; ?></td>
			<td class="borderRight0"><img src="<?php echo $product->getDefaultImage(41); ?>" alt="<?php echo $product->getDefaultImageAlt(); ?>" /></td>
			<td class="borderRight">
				<a href="<?php echo get_product_url($product); ?>"><?php echo $product->name; ?></a>
			</td>
			<td class="borderRight">
				<?php echo price_format($P->getFinalUnitPrice());?> <span class="unitOfMeasure"><?php echo $product->getUnitOfMeasure(); ?></span>
			</td>
			<td class="borderRight"><?php echo $P->getQuantity(); ?></td>
			<td class="borderRight0"><?php echo price_format($P->getFinalUnitPrice() * $P->getQuantity()); ?></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
		<?php foreach($O->getMiscCharges() as $misc_data): ?>
		<?php $MC = new Misc_Charge($misc_data->name, 'comment_code'); ?>
		<tr>
			<td>&nbsp;</td>
			<td class="borderRight">&nbsp;</td>
			<td class="borderRight0">&nbsp;</td>
			<td class="borderRight"><?php echo $MC->description; ?></td>
			<td class="borderRight">&nbsp;</td>
			<td class="borderRight">&nbsp;</td>
			<td class="borderRight0"><?php echo price_format($misc_data->unit_price); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<br clear="all" />
<div class="summarySpace"></div>
<div id="PriceSummary">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<?php foreach($ORDER_TOTALS as $total): ?>
		<tr>
			<td><?php echo $total['name']; ?></td>
			<td width="20%"><?php echo price_format($total['value']); ?></td>
		</tr>
		<?php endforeach; ?>
		<tfoot>
			<tr><td></td></tr>
			<tr>
				<td colspan="2">
					<a href="#" id="print_order"><img src="/images/print_btn.jpg" width="82" height="22" alt="" /></a>
				</td>
			</tr>
		</tfoot>
	</table>
</div>


<?php if(true == is_a($CUSTOMER, 'Sales_Rep')): ?>
<?php require_once dirname(__FILE__) . '/order_sales_detail.php'; ?>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function() {
	$("#print_order").click(function() {
		window.print();
		return false;
	});

	$("td.invoice_lable").each(function() {
		var $value_node = $(this).next().next();
		if("" == $value_node.text()) {
			$(this).parent().hide();
		}
	});
});
</script>
