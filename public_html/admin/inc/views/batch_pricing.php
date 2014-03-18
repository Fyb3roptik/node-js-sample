<script type="text/javascript">
$(document).ready(function() {
	$("#table_template").hide();
	$("#new_tier").click(function() {
		var $new_row = $("#table_template tr:first").clone();
		$new_row.appendTo("#pricing_tiers tbody");
		if($new_row.prev('tr').length > 0) {
			var $prev_row = $new_row.prev('tr');
			var prev_base_price = $prev_row.find('input[name="base_price[]"]').val();
			$new_row.find('input[name="base_price[]"]').val(prev_base_price);
		}
	});

	$("a.remove_row").live('click', function() {
		$(this).parents('tr').remove();
		return false;
	});

	$("#batch_product_pricing").submit(function() {
		var errors = false;
		var $pricing_table = $(this).find('#pricing_tiers');
		var show_message = false;
		if($pricing_table.children('tbody').children().length > 0) {
			$pricing_table.children('tbody').children().each(function() {
				var input_count = $(this).find('input').length;
				$(this).find('input').each(function() {
					var input_val = $(this).val();
					if(false == isNaN(input_val) && "" != input_val) {
						input_count -= 1;
					}
				});
				if(input_count > 0) {
					$(this).css('background-color', 'yellow');
					errors = true;
					show_message = true;
				} else {
					$(this).css('background-color', '#fff');
				}
			});
		} else {
			alert("You must add at least 1 pricing tier.");
			errors = true;
		}
		if(true == show_message) {
			alert("All fields are required.");
		}
		return !errors;
	});

	$('input[name="base_price[]"], input[name="margin[]"]').live("change", function() {
		var $parent_row = $(this).parents('tr');
		var base_price = parseFloat($parent_row.find('input[name="base_price[]"]').val());
		var margin = parseFloat($parent_row.find('input[name="margin[]"]').val());
		var price = 0.00;
		if(false == isNaN(base_price) && false == isNaN(margin)) {
			price = (base_price / margin);
		}

		$parent_row.find('.tier_price').text("$" + price.toFixed(2));
	});
});
</script>
<form id="batch_product_pricing" action="/admin/batch/processPricing" method="post">
	<fieldset>
		<legend>Batch Edit Pricing Tiers</legend>
		<?php foreach($PRODUCT_LIST as $P): ?>
		<input type="hidden" name="product_id[]" value="<?php echo $P->ID; ?>" />
		<?php endforeach; ?>
		<p><strong>Note:</strong> All pricing for these products will be overwritten with the enw tiers if saved.</p>
		<input type="button" id="new_tier" value="Add Tier" />
		<hr />	
		<table id="pricing_tiers" width="100%">
			<thead>
				<th>Min Qty</th>
				<th>Base Price</th>
				<th>Markup</th>
				<th>Price</th>
			</thead>
			<tbody>
				<!-- we'll fill this in with jQuery. :] -->
			</tbody>
		</table>
		<input type="submit" value="Save Pricing" />
	</fieldset>
</form>
<!-- hidden table is hidden, we use this as a holder for new rows. -->
<table id="table_template">
	<tr>
		<td><input type="text" name="min_quantity[]" size="4" /></td>
		<td><input type="text" name="base_price[]" size="4" /></td>
		<td><input type="text" name="margin[]" size="4" /></td>
		<td class="tier_price">$0.00</td>
		<td><a href="#" class="remove_row">remove</a></td>
	</tr>
</table>
