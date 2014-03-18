<script type="text/javascript">
var ORDER_ID = <?php echo $O->ID; ?>;
var default_vals = { }
$(document).ready(function() {
	//capture the default values
	$('input[type="text"], textarea').each(function() {
		default_vals[$(this).attr('name')] = $(this).val();
	});

	$('tbody:not(:last)').zebra({"odd" : "#eee"});
	$('#process_table').find('input[type="button"]').css('width', '150px');

	$('#save_change').click(function() {
		save_products();
	});

	$("#process_change").click(function() {
		var changes = false;
		$('input[type="text"], textarea').each(function() {
			if($(this).val() != default_vals[$(this).attr('name')]) {
				changes = true;
			}
		});
		if(true == changes) {
			alert("You must save your changes before continuing.");
		} else {
			var data = { "order_id" : ORDER_ID }
			$.post('/order/processChanges/', data, function(data) {
				if(true == data['success']) {
					window.location = '/orders.php?action=view&order=' + ORDER_ID;
				}
			}, "json");
		}
	});

	$("#cancel_change").click(function() {

	});

	$('a.cancel_item').click(function() {
		var href = $(this).attr('href');
		$(this).parent().load(href);
		return false;
	});
});

function save_products() {
	var data = {
		"order_id" : ORDER_ID
	}

	$("#product_table").find('input[type="text"]').each(function() {
		data[$(this).attr('name')] = $(this).val();
	});

	data['sales_note'] = $("textarea[name='sales_note']").val();

	$.post('/order/saveChanges/', data, function(data) {
		if(true == data['success']) {
			window.location.reload();
		}
	}, "json");
}

function cancel_item(stock_code) {
	var data = {
		"stock_code" : stock_code,
		"order_id" : ORDER_ID
	}
	$.post('/order/changeCancel/', data, function(data) {
		if(true == data['success']) {
			window.location.reload();
		}
	}, "json");
}
</script>
<h3 class="greeting">Editing Order #<?php echo $O->ID; ?></h3>

<?php if(count($PRODUCT_LIST) > 0): ?>
<strong>Change Prices / Quantities</strong>
<p>You can edit existing prices / quantities here.</p>
<table cellspacing="0" cellpadding="0" width="100%" id="product_table">
	<thead class="section-header">
		<tr>
			<th>Stock Code</th>
			<th>Orignal Qty</th>
			<th>New Qty</th>
			<th>Price</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($PRODUCT_LIST as $stock_code => $OP): ?>
		<tr>
			<td><?php echo $stock_code; ?></td>
			<td><?php echo $OP->quantity; ?></td>
			<td>
			<input type="text" name="new_quantity[<?php echo $stock_code; ?>]" value="<?php echo $NEW_QTY[$stock_code]; ?>" size="2"/>
			</td>
			<td><?php echo $OP->getFinalUnitPrice(); ?></td>
			<td>
				<a href="/order/cancelItem/<?php echo $O->ID; ?>/?item=<?php echo $stock_code; ?>" class="cancel_item">Cancel Item</a>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php foreach($MISC_LIST as $charge): ?>
			<?php $MC = new Misc_Charge($charge->name); ?>
		<tr>
			<td>Misc: <?php echo $MC->description; ?></td>
			<td>1</td>
			<td>&nbsp;</td>
			<td><?php echo price_format($charge->unit_price); ?></td>
			<td>&nbsp;</td>
			<td><a href="/order/cancelItem/<?php echo $O->ID; ?>/?item=<?php echo $charge->ID; ?>&amp;type=misc" class="cancel_item">Cancel Misc</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>

<?php if(count($CANCEL_LIST) > 0 || count($MISC_CANCEL_LIST) > 0): ?>
<br />
<strong>Canceled Items</strong>
<p>These items will be canceled with you submit these changes for processing.</p>
<table cellpadding="0" cellspacing="0" width="100%">
	<thead class="section-header">
		<tr>
			<th>Stock Code</th>
			<th>Cancel Code</th>
			<th>Cancel Reason</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($CANCEL_LIST as $stock_code => $reason_code): ?>
		<tr>
			<td><?php echo $stock_code; ?></td>
			<td><?php echo $reason_code; ?></td>
			<td><?php echo $REASON_LIST[$reason_code]; ?></td>
<td>
<a href="/order/uncancelItem/<?php echo $O->ID; ?>/?item=<?php echo $stock_code; ?>">uncancel item</a>
</td>
		</tr>
		<?php endforeach; ?>
		<?php foreach($MISC_CANCEL_LIST as $oli_id => $reason_code): ?>
			<?php
			$OLI = new Order_Line_Item($oli_id);
			$MC = new Misc_Charge($OLI->name);
			?>
		<tr>
			<td>Misc: <?php echo $MC->description; ?></td>
			<td><?php echo $reason_code; ?></td>
			<td><?php echo $REASON_LIST[$reason_code]; ?></td>
			<td><a href="/order/uncancelItem/<?php echo $O->ID; ?>/?item=<?php echo $oli_id; ?>&amp;type=misc">uncancel misc charge</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>
<br />
<strong>Notes</strong>
<p>Please explain why you're changing this order:</p>
<textarea name="sales_note" cols="60" rows="6"><?php echo htmlentities($OC->getNote()); ?></textarea>
<br /><br />
<table id="process_table">
	<tr>
		<td><input type="button" id="save_change" value="Save Changes" /></td>
		<td>Save changes temporarily while you're logged in.</td>
	</tr>
	<tr>
		<td><input type="button" id="process_change" value="Process Changes" /></td>
		<td>Make these changes permanent.</td>
	</tr>
	<tr>
		<td><input type="button" id="cancel_change" value="Cancel Changes" /></td>
		<td>Cancel these changes all together.</td>
	</tr>
</table>
