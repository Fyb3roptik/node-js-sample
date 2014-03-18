<script type="text/javascript">
$(document).ready(function() {
	$('a.new_custom_option').click(function() {
		$("#custom_shipping_form_holder").load('/admin/custom_shipping/newOption/');
		return false;
	});

	$("a.delete_option").click(function() {
		var option_id = $(this).prev('input').val();
		var confirm_delete = confirm("Are you sure you want to delete this option?");
		var row = $(this).parents('tr');
		if(true == confirm_delete) {
			var post_data = { "option_id" : option_id }
			$.post('/admin/custom_shipping/dropOption/', post_data, function(data) {
				if(true == data['success']) {
					row.fadeOut(1500, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});

	$('a.new_custom_fee').click(function() {
		$("#custom_fee_form_holder").load('/admin/custom_handling/newFee/').show();
		return false;
	});

	$('a.new_shipping_discount').click(function() {
		$("#shipping_discount_form_holder").load('/admin/shipping_discount/newDiscount/').show();
		return false;
	});

	$('a.edit_fee').click(function() {
		$("#custom_fee_form_holder").load($(this).attr('href')).show();
		return false;
	});

	$('a.edit_discount').click(function() {
		$("#shipping_discount_form_holder").load($(this).attr('href')).show();
		return false;
	});

	$('a.edit_option').click(function() {
		$("#custom_shipping_form_holder").load($(this).attr('href'));
		return false;
	});

	$('a.delete_fee').click(function() {
		var $row = $(this).parents('tr');
		var custom_fee_id = $(this).prev('input').val();
		if(true == confirm("Are you sure you want to delete this fee tier?")) {
			var post_data = { "custom_fee_id" : custom_fee_id }
			$.post('/admin/custom_handling/deleteFee/', post_data, function(data) {
				if(true == data['success']) {
					$row.fadeOut(1000, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});

	$('a.delete_discount').click(function() {
		var $row = $(this).parents('tr');
		var shipping_discount_id = $(this).prev('input').val();
		if(true == confirm("Are you sure you want to delete this discount?")) {
			var post_data = { "shipping_discount_id" : shipping_discount_id }
			$.post('/admin/shipping_discount/deleteDiscount/', post_data, function(data) {
				if(true == data['success']) {
					$row.fadeOut(1000, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});
});
</script>
<h2>Custom Shipping Options</h2>
<p>Manage custom shipping options here. Sales reps will be able to assign these options to customer accounts.</p>

<h3>Current Shipping Options [<a href="#" class="new_custom_option">add new</a>]</h3>
<div id="custom_shipping_form_holder"></div>
<?php if(count($OPTION_LIST) > 0): ?>
<table>
	<thead>
		<tr>
			<th>Option Name</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($OPTION_LIST as $opt): ?>
		<tr>
			<td><?php echo $opt->name; ?></td>
			<td>
				<a class="edit_option" href="/admin/custom_shipping/editOption/<?php echo $opt->ID; ?>">edit</a>
			</td>
			<td>
				<input type="hidden" name="custom_shipping_option_id" value="<?php echo $opt->ID; ?>" />
				<a href="#" class="delete_option">delete</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p>
	No custom shipping options have been defined.
	<a href="#" class="new_custom_option">Add new shipping option.</a>
</p>
<?php endif; ?>

<h3>Custom Handling Fees [<a href="#" class="new_custom_fee">add new</a>]</h3>
<div id="custom_fee_form_holder"></div>
<?php if(count($HANDLING_FEE_LIST) > 0): ?>
<table>
	<thead>
		<tr>
			<th>Minimum Total</th>
			<th>Handling Fee</th>
		<tr>
	</thead>
	<tbody>
	<?php foreach($HANDLING_FEE_LIST as $fee): ?>
		<tr>
			<td><?php echo price_format($fee->minimum_cost); ?></td>
			<td><?php echo price_format($fee->handling_fee); ?></td>
			<td>
				<a href="/admin/custom_handling/editfee/<?php echo $fee->ID; ?>" class="edit_fee">edit</a>
			</td>
			<td>
				<input type="hidden" name="custom_fee_id" value="<?php echo $fee->ID; ?>" />
				<a href="#" class="delete_fee">delete</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p>No custom shipping fees have been found. <a href="#" class="new_custom_fee">Add new fee?</a></p>
<?php endif; ?>

<h3>Custom Shipping Discount [<a href="#" class="new_shipping_discount">add new</a>]</h3>
<div id="shipping_discount_form_holder"></div>
<?php if(count($SHIPPING_DISCOUNT_LIST) > 0): ?>
<table>
	<thead>
		<tr>
			<th>Zip Code</th>
			<th>Discount Percentage</th>
		<tr>
	</thead>
	<tbody>
	<?php foreach($SHIPPING_DISCOUNT_LIST as $discount): ?>
		<tr>
			<td><?php echo $discount->zip_code; ?></td>
			<td><?php echo $discount->discount_percentage; ?>%</td>
			<td>
				<a href="/admin/shipping_discount/editDiscount/<?php echo $discount->ID; ?>" class="edit_discount">edit</a>
			</td>
			<td>
				<input type="hidden" name="shipping_discount_id" value="<?php echo $discount->ID; ?>" />
				<a href="#" class="delete_discount">delete</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p>No custom shipping discounts have been found. <a href="#" class="new_shipping_discount">Add new discount?</a></p>
<?php endif; ?>

<?php if(count($Fedex_LIST) > 0): ?>
<h3>Fedex/Syspro Shipping Association</h3>
<form id="fedex_custom_form" method="post" action="/admin/custom_shipping/processFedex/">
	<fieldset>
		<table>
			<thead>
				<tr>
					<th>Fedex Option</th>
					<th>Syspro Option</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($Fedex_LIST as $name => $opt): ?>
				<tr>
					<td><?php echo $name; ?></td>
					<td>
						<?php
						$field_name = 'fedex_option[' . $opt->fedex_code . ']';
						echo draw_select($field_name, $SYSPRO_LIST, $opt->syspro_code);
						?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<input type="submit" value="Save Options" />
	</fieldset>
</form>
<?php else: ?>
<p>No Fedex options have been found.</p>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function() {
	$("a.new_shipvia").click(function() {
		$("#shipvia_form_holder").load('/admin/custom_shipping/newShipVia');
		return false;
	});

	$("a.edit_shipvia").click(function() {
		$("#shipvia_form_holder").load($(this).attr('href'));
		return false;
	});

	$("a.delete_shipvia").click(function() {
		var confirm_drop = confirm("Are you sure you want to delete this option?");
		if(true == confirm_drop) {
			var $tr = $(this).parent().parent();
			var svo_id = $(this).parent().find('input:first').val();
			var data = {
				"svo_id" : svo_id
			};

			$.post('/admin/custom_shipping/dropShipVia/', data, function(data) {
				if(true == data['success']) {
					$tr.fadeOut(function() {
						$(this).remove();
					});
				}
			}, 'json');
		}
		return false;
	});
});
</script>

<h3>"Ship Via" Options [<a href="#" class="new_shipvia">new option</a>]</h3>
<div id="shipvia_form_holder"></div>
<?php if(count($SHIP_VIA_LIST) > 0): ?>
<table>
	<thead>
		<tr>
			<th>Ship Via Option</th>
			<th>SysPro Option</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($SHIP_VIA_LIST as $option): ?>
		<tr>
			<td><?php echo $option->option_name; ?></td>
			<td><?php echo $SYSPRO_LIST[$option->syspro_option]; ?></td>
			<td>
				<a class="edit_shipvia" href="/admin/custom_shipping/editShipVia/<?php echo $option->ID; ?>">edit</a>
			</td>
			<td>
				<input type="hidden" name="svo_id[]" value="<?php echo $option->ID; ?>" />
				<a href="#" class="delete_shipvia">delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p>No "ship via" options have been defined.</p>
<?php endif; ?>
