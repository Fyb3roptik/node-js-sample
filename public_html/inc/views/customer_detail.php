<script type="text/javascript">
$(document).ready(function() {
	$("a.new_shipping").click(function() {
		$("#shipping_form_holder").load('/customer_shipping/newOption/<?php echo $C->ID; ?>').show();
		return false;
	});

	$("a.edit_option").click(function() {
		$("#shipping_form_holder")
			.load($(this).attr('href'))
			.show();
		return false;
	});

	$('a.delete_option').click(function() {
		var confirm_drop = confirm("Are you sure you want to remove this shipping option?");
		var row = $(this).parents('tr');
		if(true == confirm_drop) {
			var option_id = $(this).prev('input').val();
			var post_data = { "option_id" : option_id }
			$.post('/customer_shipping/dropOption/', post_data, function(data) {
				if(true == data['success']) {
					row.fadeOut(1000, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});
});
</script>

<div class="account_header">
    <span class="RedText2">Customer Detail for "<?php echo $C->name; ?>" (<a href="<?php echo LOC_SALES; ?>?action=login_as&amp;customer=<?php echo $C->ID; ?>">Login As</a> or <a href="<?php echo LOC_SALES; ?>?action=edit_customer&amp;customer=<?php echo $C->ID; ?>">edit</a>)
    </span>
<!-- end account_header --></div>

<br clear="all" />


<div class="messages"><?php echo $MS->messages(); ?></div>

<div class="account_info_container">
	<div class="account_info_label">Name:</div> 
	<div class="account_info_info"><?php echo $C->name; ?></div>
	<br clear="all" />
	<div class="account_info_label">Email:</div> 
	<div class="account_info_info"><?php echo $C->email; ?></div>
	<br clear="all" />
	<div class="account_info_label">Credit:</div> 
	<div class="account_info_info"><?php echo price_format($C->credit_limit); ?></div>
	<br clear="all" />
</div>

<br clear="all" />

<script type="text/javascript">
$(document).ready(function() {
	$(function() {
		$("#tabs").tabs();

	});
});
</script>

<div class="account_tabs">
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Orders</a></li>
		<li><a href="#tabs-2">Quotes</a></li>
		<li><a href="#tabs-3">Favorites</a></li>
		<li><a href="#tabs-4">Shipping</a></li>
		<li><a href="#tabs-5">Addresses</a></li>
    </ul>
    
	<div id="tabs-1">
		<div class="tab_wrap">
		        <div class="order_head"><span class="RedText2">Orders</span></div>
        <div class="searchBox_form">
            <form name="order_search" id="order_search" method="get" action="/orders.php">
                <input type="hidden" name="action" value="view" />
				<a href="/report">View All</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;
                 <label>
    			<input type="text" name="order" id="order" value="Search by Order number"/>
 		 		</label>
                <input type="image" src="/images/go_btn.jpg" id="search_orders" align="absmiddle" height="18" width="18" alt="" />
            </form>
        </div>
        <br clear="all" />
			<table width="100%" border="0" cellspacing="0" cellpadding="0" id="favouriteTable">
				<thead>
					<tr class="tableheader">
                        <th class="FirstColumn"></th>
						<th>Number</th>
						<th>Sales Rep</th>
						<th>Status</th>
						<th>Last Update</th>
						<th>Total</th>
						<th class="LastColumn"><div class="RightCorner"></div></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$orders = $C->getOrders(5);
					$order_count = 0;
					$SYS = new Syspro_API();
					foreach($orders as $O) {
					?>
					<tr>
						<td>&nbsp;</td>
						<td><a href="/orders.php?action=view&amp;order=<?php echo $O->getID(); ?>"><?php echo $O->getID(); ?></a></td>
						<td><?php echo $O->getSalesRep(); ?></td>
						<td><?php echo Order_Status_Lookup::lookup($SYS->getOrderStatus($O->ID)); ?></td>
						<td><?php echo $O->getDatePurchased('m/d/y'); ?></td>
						<td><?php echo price_format($O->getTotal()); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		<br clear="all" />
		<!-- end tab_wrap --></div>
	</div>
	
	
	<div id="tabs-2">
	<div class="tab_wrap">
		
		<?php
	    $quote_list = $C->getQuotes();
	    if(count($quote_list) > 0):
	        $quote_count = 1; ?>
	    <!--quotes table-->
	   
	        <div class="order_head"><span class="RedText2">Quotes</span></div>
	        
	        <div class="searchBox_form">
	            <form name="" method="" action="" onsubmit="">
	                <a href="/myquotes">View All</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;
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
	                <?php foreach($quote_list as $quote): ?>
	                <tr>
	                    <td></td>
	                    <td>
	                        <a class="order_id_link" href="/quote/edit/<?php echo $quote->ID; ?>"><?php echo $quote->ID; ?></a>
	                    </td>
	                    <td><?php echo $quote->getExpires('m/d/Y'); ?></td>
	                    <td><?php echo $C->name; ?></td>
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
	    <!--/quotes table-->
	    <?php endif; ?>
	<br clear="all" />
	<!-- end tab_wrap --></div>
	</div>
	
	
	<div id="tabs-3">
		<div class="tab_wrap">
		<div class="order_head"><span class="RedText2">My Favorites</span></div>
		<br clear="all" />
			<?php
			$W = new Wishlist();
			$wishlists = $C->getWishlists();
			$wishlist_thumb = new Html_Template('inc/widgets/wishlist_thumb.php');
			if(count($wishlists) > 0) {
				$max_wishlists = 4;
				$wishlist_count = 0;
				foreach($wishlists as $W) {
					$wishlist_count++;
					$wishlist_thumb->bind('W', $W, false);
					$wishlist_thumb->render();
					if($wishlist_count >= $max_wishlists) {
						//break out of here
						break;
					}
				}
			}
			?>
		</div>
		<br clear="all" />
	</div>
	
	
	<div id="tabs-4">
		<div class="tab_wrap">
		<div class="order_head"><span class="RedText2">Custom Shipping Options </span>(<a href="#" class="new_shipping">add new</a>)</div>
	<br clear="all" />
		<div id="shipping_form_holder"></div>
		<?php
		$CUSTOM_OPTIONS = $C->getShippingOptions();
		if(count($CUSTOM_OPTIONS) > 0): ?>
		<table>
			<thead>
				<tr>
					<th>Shipping Option</th>
					<th>Account Number</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($CUSTOM_OPTIONS as $opt): ?>
				<?php $CSO = new Custom_Shipping_Option($opt->custom_shipping_option_id); ?>
				<tr>
					<td><?php echo $CSO->name; ?></td>
					<td><?php echo $opt->account_number; ?></td>
					<td><a href="/customer_shipping/editOption/<?php echo $opt->ID; ?>" class="edit_option">edit</a></td>
					<td>
						<input type="hidden" name="customer_option_id" value="<?php echo $opt->ID; ?>" />
						<a href="#" class="delete_option">delete</a>
					</td>
				</tr>
				<?php endforeach; ?>
			<tbody>
		</table>
		<?php else: ?>
		<p>This customer has no custom shipping options. <a href="#" class="new_shipping">Add new shipping option?</a></p>
		<?php endif; ?>
	   </div>
	  <br clear="all" />
	</div>
	
	<div id="tabs-5">
	  <div class="tab_wrap">
		<div class="order_head"><span class="RedText2">Address Book</span> <a href="<?php echo LOC_SALES; ?>?action=new_address&amp;customer=<?php echo $C->ID; ?>">(add new)</a></div>
		<br clear="all" />
			<table id="address_table" width="100%">
				<thead>
					<tr>
						<th>Nickname</th>
						<th>Company</th>
						<th>Address 1</th>
						<th>Address 2</th>
						<th>Address 3</th>
						<th>City</th>
						<th>State</th>
						<th>Zip</th>
						<th>Phone</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$ADDRESS_LIST = $C->getAddressBook(false);
					foreach($ADDRESS_LIST as $A) {
					?>
					<tr>
						<td>
							<a href="<?php echo LOC_SALES; ?>?action=edit_address&amp;address=<?php echo $A->ID; ?>">
							<?php echo $A->nickname; ?>
							</a>
						</td>
						<td><?php echo $A->company; ?></td>
						<td><?php echo $A->address_1; ?></td>
						<td><?php echo $A->address_2; ?></td>
						<td><?php echo $A->address_3; ?></td>
						<td><?php echo $A->city; ?></td>
						<td><?php echo $A->state; ?></td>
						<td><?php echo $A->zip_code; ?></td>
						<td><?php echo $A->phone; ?></td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	  <br clear="all" />
    </div>
	
<br clear="all" />
<!-- end tabs --></div>
<!-- end account_tabs --></div>