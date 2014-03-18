<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td aling="center" height="25" style=" font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#999999;">Notice: This automated email is not monitored for replies.</td>
  </tr>
  <tr>
    <td>
        <table id="Table_01" width="600" height="89" border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td colspan="3">
		<table id="Table_01" width="600" height="55" border="0" cellpadding="0" cellspacing="0">
    	<tr>
		<td width="331" align="left"><a href="http://www.siing.co"><img src="http://www.siing.co/images/confemail/OrderConf_top_01.jpg" alt="siing.co" width="331" height="55" border="0"></a></td>
		<td width="269" align="left">
			<img src="http://www.siing.co/images/confemail/OrderConf_top_02.jpg" alt="1-800-624-4488" width="269" height="55" border="0"></td>
     	</tr>
		</table>
    </td>      
	</tr>
	<tr>
		<td><a href="http://www.siing.co"><img src="http://www.siing.co/images/confemail/OrderConf_top_03.jpg" alt="Go to siing.co" width="166" height="33" border="0"></a></td>
		<td><a href="http://www.siing.co/pages/Customer-Service.html"><img src="http://www.siing.co/images/confemail/OrderConf_top_04.jpg" alt="Customer Service" width="231" height="33" border="0"></a></td>
		<td><a href="http://www.siing.co/pages/Customer-Service.html#returns"><img src="http://www.siing.co/images/confemail/OrderConf_top_05.jpg" alt="Return Policy" width="203" height="33" border="0"></a></td>
	</tr>
	
</table>
    </td>
  </tr>
  <tr>
    <td><table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr><td height="15" colspan="3"></td></tr>
      <tr>
        <td valign="top" <?php if($O->sales_rep == ""):?>colspan="2"<?php endif; ?>>
        <p><span style=" font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; color:#000000;">Thank you for your order! - </span>
        <span style=" font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#990000;">You will receive a Fedex Tracking Number by e-mail when your package(s) ships.</span></p>
		<p style=" font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#000000;">Your order will typically be delivered in 5 to 7 business days. In the case of a back order, we will notify you within 5 business days. Please contact our customer service department if you have additional questions. We appreciate your business!</p>
		</td>
		<?php if($O->sales_rep_id != ""):?>
        <td width="20">&nbsp;</td>
        <td width="234"><table width="234" border="0" cellspacing="0" cellpadding="0" bgcolor="#f6f6f6">
          <tr>
            <td height="5" colspan="3"><img src="http://www.siing.co/images/confemail/OrderConf_sales_top.gif" width="234" height="6" /></td>
          </tr>
          <tr>
            <td width="5"><img src="http://www.siing.co/images/confemail/OrderConf_sales_side1.gif" width="5" height="100" /></td>
            <td align="center" width="224" bgcolor="#f6f6f6"><table width="214" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="70" style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;">Salesperson:</td>
                <td width="10">&nbsp;</td>
                <td style=" font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;"><?php echo $SALES_REP->name; ?></td>
              </tr>
              <tr>
                <td width="70" style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;">Phone:</td>
                <td width="10">&nbsp;</td>
                <td style=" font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;"><?php echo $SALES_REP->phone; ?></td>
              </tr>
              <tr>
                <td width="70" style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;">E-mail:</td>
                <td width="10">&nbsp;</td>
                <td style=" font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;"><?php echo $SALES_REP->email; ?></td>
              </tr>
            </table></td>
            <td width="5"><img src="http://www.siing.co/images/confemail/OrderConf_sales_side2.gif" width="5" height="100" /></td>
          </tr>
          <tr>
            <td height="5" colspan="3"><img src="http://www.siing.co/images/confemail/OrderConf_sales_bttm.gif" width="234" height="6" /></td>
          </tr>
        </table></td>
		<?php endif; ?>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="558" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr><td height="20" colspan="5"></td></tr>
      <tr>
        <td colspan="5">
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:19px; color:#990000;">Order # <?php echo $ORDER_ID; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000000;"><strong>Order Date: </strong><?php echo date('m/d/Y', strtotime($O->date_purchased)); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000000;"><strong>Customer #: </strong><?php echo $O->customer_id; ?></span>
        </td>
      </tr>
      <tr><td height="15" colspan="5"></td></tr>
      <tr>
        <td width="174" align="left" valign="top">
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#990000;">Payment Method</span><br />
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;">
        <?php echo $PAYMENT_INFO; ?>
        </span>
        </td>
        <td width="20">&nbsp;</td>
        <td align="left" width="172">
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#990000;">Bill To</span><br />
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;">
        <?php echo $BILLING_ADDRESS->name; ?>
		<br />
		<?php echo $BILLING_ADDRESS->address_1; ?>
		<br />
		<?php if($BILLING_ADDRESS->address_2 != ""): ?>
        <?php echo $BILLING_ADDRESS->address_2; ?>
		<br />
		<?php endif; ?>
		<?php if($BILLING_ADDRESS->address_3 != ""): ?>
        <?php echo $BILLING_ADDRESS->address_3; ?>
        <br />
		<?php endif; ?>
		<?php echo $BILLING_ADDRESS->city; ?>, <?php echo $BILLING_ADDRESS->state; ?> <?php echo $BILLING_ADDRESS->zip_code; ?>
		<br />
		<?php echo $BILLING_ADDRESS->phone; ?>
		<br />
		<?php if($BILLING_ADDRESS->email != ""): ?>
		<?php echo $BILLING_ADDRESS->email; ?>
		<br />
		<?php endif; ?>
        </span>
        </td>
        <td width="20">&nbsp;</td>
        <td align="left" width="172">
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#990000;">Ship To</span><br />
        <span style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333;">
        <?php echo $SHIPPING_ADDRESS->name; ?>
		<br />
		<?php echo $SHIPPING_ADDRESS->address_1; ?>
		<br />
		<?php if($SHIPPING_ADDRESS->address_2 != ""): ?>
        <?php echo $SHIPPING_ADDRESS->address_2; ?>
		<br />
		<?php endif; ?>
		<?php if($SHIPPING_ADDRESS->address_3 != ""): ?>
        <?php echo $SHIPPING_ADDRESS->address_3; ?>
        <br />
		<?php endif; ?>
		<?php echo $SHIPPING_ADDRESS->city; ?>, <?php echo $SHIPPING_ADDRESS->state; ?> <?php echo $SHIPPING_ADDRESS->zip_code; ?>
		<br />
		<?php echo $SHIPPING_ADDRESS->phone; ?>
		<br />
		<?php if($SHIPPING_ADDRESS->email != ""): ?>
		<?php echo $SHIPPING_ADDRESS->email; ?>
		<br />
		<?php endif; ?>
        </span>
        </td>
      </tr>
      <tr><td height="15" colspan="5"></td></tr>
    </table>
    </td>
  </tr>
  <tr>
    <td><table width="578" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="5" colspan="3"><img src="http://www.siing.co/images/confemail/OrderConf_table_top.gif" width="578" height="5" /></td>
      </tr>
      <tr>
        <td width="5" height="19"><img src="http://www.siing.co/images/confemail/OrderConf_table_side1.gif" width="5" height="19" /></td>
        <td width="568" height="19" align="center" bgcolor="#f6f6f6" >
        <table width="558" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; color:#990000;">
          <tr>
            <td width="100">Catalog Code</td>
            <td width="278">Product</td>
            <td width="85">Price</td>
            <td width="30">Qty</td>
            <td width="65">Cost</td>
          </tr>
        </table></td>
        <td width="5" height="19"><img src="http://www.siing.co/images/confemail/OrderConf_table_side2.gif" width="5" height="19" /></td>
      </tr>
      <tr>
        <td height="5" colspan="3"><img src="http://www.siing.co/images/confemail/OrderConf_table_bttm.gif" width="578" height="5" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center">
    <table width="558" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#333333;">
      
	  <?php foreach($PRODUCT_LIST as $i => $P): ?>
			<?php
			$product = new Product($P->getProductID());
			$price_list = $product->getPrices();
			?>
      <tr><td colspan="5" height="10"></td></tr>
      <tr>
        <td width="100"><?php echo $product->catalog_code; ?></td>
        <td width="278"><?php echo $product->name; ?></td>
        <td width="85"><?php echo price_format($P->getFinalUnitPrice());?> <span class="unitOfMeasure"><?php echo $product->getUnitOfMeasure(); ?></span></td>
        <td width="70"><?php echo $P->getQuantity(); ?></td>
        <td width="65"><?php echo price_format($P->getFinalUnitPrice() * $P->getQuantity()); ?></td>
      </tr>
	  <?php endforeach; ?>
	  <tr><td colspan="5" height="10"></td></tr>
		<?php foreach($O->getMiscCharges() as $misc_data): ?>
		<?php $MC = new Misc_Charge($misc_data->name, 'comment_code'); ?>
		<tr>
			<td width="100">&nbsp;</td>
			<td width="278"><strong><?php echo $MC->description; ?></strong></td>
			<td width="85">&nbsp;</td>
			<td width="70">&nbsp;</td>
			<td width="65"><strong><?php echo price_format($misc_data->unit_price); ?></strong></td>
		</tr>
		<?php endforeach; ?>
    </table>
	</td>
  </tr>
  <tr><td height="20">&nbsp;</td></tr>
  <tr>
    <td>
    <table width="558" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="326">
	<?php if($O->note != ""): ?>
    <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000000; font-weight:bold;">Notes</span>
    <p style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;">
	<?php echo nl2br($O->note); ?>
    </p>
	<?php endif; ?>
    </td>
    <td width="232" align="right">
	<table border="0" cellpadding="0" cellspacing="0">

	<?php foreach($ORDER_TOTALS as $total): ?>
	<tr>
		<td align="right" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; <?php if($total['name'] == "Total"): ?>color:#990000<?php else: ?>color:#000000<?php endif; ?>; font-weight:bold;"><?php echo $total['name']; ?></td>
        <td width="15">&nbsp;</td>
		<td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; <?php if($total['name'] == "Total"): ?>color:#990000<?php else: ?>color:#000000<?php endif; ?>; font-weight:bold;"><?php echo price_format($total['value']); ?></td>
	</tr>
	<?php endforeach; ?>
	</table></td>
  </tr>
</table>
    </td>
  </tr>
  <tr><td height="30">&nbsp;</td></tr>
  <tr>
    <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#999999;">siing.co 2140 Merritt Dr., Garland, TX 75041</td>
  </tr>
</table>
</body>
</html>
