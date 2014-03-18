<div class="BreadCrumb">
<a href="/">home</a> >
	<a href="/myaccount">My Account</a> &gt;
	<a href="#">My Quotes</a>
</div>

<div class="table_layout">
	<div class="order_head">
		<span class="RedText2">My Quotes</span>
	</div>
	
<!--quotes table-->	
<div class="table_layout">
        <div class="searchBox_form">
            <form name="" method="" action="" onsubmit="">
                Search&nbsp;&nbsp;
                <input type="text" name="textfield" id="textfield" value="Order by Quote number"/>
                <input type="image" src="/images/go_btn.jpg" align="absmiddle" height="18" width="18" alt="" />
            </form>
        </div>	
 <br clear="all" />
        
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="favouriteTable">
		<thead>
			<tr>
				<th class="FirstColumn"></th>
				<th>Number</th>
				<th>Expires</th>
				<th>Customer</th>
				<th>Total</th>
				<th class="LastColumn"><div class="RightCorner"></div></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($QUOTE_LIST as $quote): ?>
			<tr>
				<td></td>
				<td>
					<input type="hidden" name="quote_id[]" value="<?php echo $quote->ID; ?>" />
					<a class="quote_detail" href="/quote/view/<?php echo $quote->ID; ?>"><?php echo $quote->ID; ?></a>
				</td>
				<td><?php echo $quote->getExpires('m/d/Y'); ?></td>
				<td><?php echo $CUSTOMER->name; ?></td>
				<td><?php echo price_format($quote->getSubtotal()); ?></td>
			</tr>
			<?php $quote_count++;
			if($quote_count > 5) {
				break;
			}
			?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<!--/quotes table-->
<br clear="all" />
<div id="quote_details"></div>
