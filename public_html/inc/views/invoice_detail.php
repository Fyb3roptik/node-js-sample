<div id="TopNavContainer">
	<div id="TopNavContainerL"></div>
	<div id="TopNavContainerR">
	
	<ul>
		<li class="title"><span class="RedText2">Invoice #&nbsp;<?php echo $INV->syspro_id; ?></span></li>
		<li class="invoice"><strong>Invoice Date:</strong><?php echo date('m/d/Y', strtotime($INV->date)); ?></li>
		<?php if(strlen($INV->po_number) > 0): ?>
		<li class="invoice"><strong>P.O. #:</strong> <?php echo $INV->po_number; ?></li>
		<?php endif; ?>
		<li class="invoice"><strong>Terms:</strong> <?php echo $TERMS_LOOKUP[$INV->terms_code]; ?></li>
	</div>
</div>
<!-- /Top nav container -->

<!-- Top nav container2 -->
<div id="TopNavContainer2">
	<div id="TopNavContainer2L"></div>
	<div id="TopNavContainer2R">
		<ul id="nav_item_list">
			<li class="top_item"><strong>Order #:</strong> <?php echo $O->ID; ?></li>
			<li class="top_item"><strong>Order Date:</strong> <?php echo date('m/d/Y', strtotime($O->date_purchased)); ?></li>
			<li class="top_item"><strong>Customer No.:</strong> <?php echo $O->customer_id; ?></li>
			<li class="Latest"></li>
		</ul>
	</div>
</div>

<div class="invoice_wrap">
<div class="invoice_info">
<?php if(true == ($SALES_REP instanceof Sales_Rep)): ?>
<table width="245" id="bill_ship">
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
	<tr>	
		<td class="invoice_lable" width="65"></td>
		<td width="20"></td>
		<td width="165"></td>
	</tr>
</table>
<?php endif; ?>
<!--end invoice_info--></div>

<div class="invoice_info">
<table width="245" id="bill_ship">
	<tr>
		<th colspan="3" align="left">Bill To:</th>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Contact:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILL->name; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILL->address_1; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address 2:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILL->address_2; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address 3:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILL->address_3; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">City:</td>	
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILL->city; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">State:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILL->state; ?> </td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Zip Code:</td>
		<td width="15">&nbsp;</td>
		<td width="165"><?php echo $BILL->zip_code; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Phone:</td>
		<td width="20"></td>
		<td width="165"><?php echo $BILL->phone; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Email:</td>
		<td width="20"></td>
		<td width="165"><?php echo $BILL->email; ?></td>
	</tr>
</table>		
<!--end invoice_info--></div>

<div class="invoice_info">
<table width="245"  id="bill_ship">
	<tr>
		<th colspan="3" align="left">Ship To:</th>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Contact:</td>
		<td width="20"></td>
		<td><?php echo $SHIP->name; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIP->address_1; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address 2:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIP->address_2; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Address 3:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIP->address_3; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">City:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIP->city; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">State:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SHIP->state; ?></td>
	</tr>
	<tr>
		<td class="invoice_lable" width="65">Zip Code:</td>	
		<td width="20"></td>
		<td width="165"><?php echo $SHIP->zip_code; ?></td>
	</tr>
		<tr>	
		<td class="invoice_lable" width="65">Phone:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SHIP->phone; ?></td>
	</tr>
	<tr>	
		<td class="invoice_lable" width="65">Email:</td>
		<td width="20"></td>
		<td width="165"><?php echo $SHIP->email; ?></td>
	</tr>
</table>
<!--end invoice_info--></div>

<!--end invoice_wrap--></div>
<br clear="all" />
<?php if(count($INV->getDetails()) > 0): ?>
<table width="955" border="0" cellpadding="0" cellspacing="0" class="PriceGrid">
	<thead>
		<tr>
			<th><div class="LeftCorner"></div></th>
			<th align="left" width="130">Stock Code</th>
			<th align="left" width="570">Description</th>
			<th align="left" width="70">Price</th>
			<th align="left" width="50">Ordered</th>
			<th align="left" width="50">Shipped</th>
			<th align="left" width="85">Cost</th>
			<th><div class="RightCorner"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($INV->getDetails() as $detail): ?>
		<tr>
			<td></td>
			<td class="borderRight"><?php echo $detail->stock_code; ?></td>
			<td class="borderRight"><?php echo $detail->getDescription(); ?></td>
			<td class="borderRight"><?php echo price_format($detail->getUnitPrice()); ?></td>
			<td class="borderRight"><?php echo intval($detail->qty_ordered); ?></td>
			<td class="borderRight"><?php echo intval($detail->qty_invoiced); ?></td>
			<td class="borderRight0"><?php echo price_format($detail->net_sales_value); ?></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>
<br clear="all" />
<br clear="all" />
<div id="InvoiceSummary">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="right" class="subtotal">Net Invoice:</td>
		<td width="20"></td>
		<td align="left" width="80" class="subtotal_value"><?php echo price_format($INV->merchandise_value); ?></td>
		<td width="4">&nbsp;</td>
	</tr>
	<?php if($INV->getMiscTotal() > 0): ?>
	<tr>
		<td align="right" class="misc">Misc Total:</td>
		<td width="20"></td>
		<td align="left"><?php echo price_format($INV->getMiscTotal()); ?></td>
		<td width="4">&nbsp;</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td align="right" id="est_shipping_text" class="subtotal shipping_total">Freight:</td>
		<td width="20"></td>
		<td align="left" id="est_shipping" width="25%" class="shipping_est_value shipping_total"><?php echo price_format($INV->freight_value); ?></td>
		<td width="4">&nbsp;</td>
	</tr>
	<tr>
		<td align="right" id="handling_fee_text" class="subtotal">Sales Tax:</td>
		<td width="20"></td>
		<td align="left" id="handling_fee" width="25%" class="handling_fee_value"><?php echo price_format($INV->tax_value); ?></td>
		<td width="4">&nbsp;</td>
	</tr>
	<tr>
		<td align="right" class="FinalTotal">Invoice Total:</td>
		<td width="20"></td>
		<td align="left" class="FinalTotal"><?php echo price_format($INV->currency_value); ?></td>
		<td width="4">&nbsp;</td>
	</tr>
</table>
</div>
<br clear="all" />
<br clear="all" />
<?php if('01' == $INV->terms_code): ?>
<div id="paid">PAID BY CC</div>
<?php endif; ?>
<br clear="all" />
<div id="paid_disclaimer">All invoices paid 15 days past due date will be subject to a 5% finance fee. This does not include invoices paid with a credit card.</div>
<script type="text/javascript">
$(document).ready(function() {
	$("td.invoice_lable").each(function() {
		var $value_node = $(this).next().next();
		if("" == $value_node.text()) {
			$(this).parent().hide();
		}
	});
});
</script>
