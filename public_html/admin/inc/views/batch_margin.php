<script type="text/javascript">
var max_margin = <?php echo $MAX_MARGIN; ?>;

$(document).ready(function() {
	$("#example_table tbody").zebra();

	$("#margin_adjustment").keyup(function() {
		$(this).change();
	});

	$("#margin_adjustment").change(function() {
		var margin_adjustment = parseFloat($(this).val());
		if(false == isNaN(margin_adjustment)) {
			$("#example_table tbody tr").each(function() {
				var example_base = parseFloat($(this).find('.example_base').text());
				var example_margin = parseFloat($(this).find('.example_margin').text()); 
				var new_margin = example_margin - (parseFloat(margin_adjustment) / 100);
				$(this).find('.new_margin').text(new_margin.toFixed(2));

				var new_price = parseFloat(example_base / new_margin);
				$(this).find('.new_price').text(new_price.toFixed(2));
			});
		}
	});

	$("#batch_product_margin").submit(function() {
		var margin_adjustment = parseFloat($("#margin_adjustment").val());
		var valid_data = true;
		if(true == isNaN(margin_adjustment)) {
			valid_data = false;
			alert("Adjusted margin must be a number.");
		}

		if(true == valid_data) {
			if(margin_adjustment >= (max_margin * 100)) {
				alert("For this set of products, the margin point adjustment must be less than:  '" + (max_margin * 100).toFixed(2) + "'.");
				valid_data = false;
			}
		}

		return valid_data;
	});
});
</script>
<form id="batch_product_margin" action="/admin/batch/processMargin/" method="post">
	<fieldset>
		<legend>Increase/Decrease Margin</legend>
		<?php foreach($PRODUCT_LIST as $P): ?>
		<input type="hidden" name="product_id[]" value="<?php echo $P->ID; ?>" />
		<?php endforeach; ?>
		<strong>Margin Adjustment (points)</strong>
		<input type="text" name="margin_adjustment" id="margin_adjustment" value="0" size="4" />
		<span class="helper">"20", "-50"...</span>
		<div id="example_adjustments">
			<p><strong>Example Adjustments (randomly generated):</strong></p>
			<table id="example_table" width="100%">
				<thead>
					<tr>
						<th>Base Price</th>
						<th>Old Margin</th>
						<th>New Margin</th>
						<th>Old Price</th>
						<th>New Price</th>
					<tr>
				</thead>
				<tbody>
					<?php for($i = 0; $i < 3; $i++): ?>
						<?php
						$base_price = rand(1,99) . '.' . rand(0, 99);
						$margin = rand(1, 99) / 100;
						?>
					<tr>
						<td class="example_base"><?php echo number_format($base_price, 2); ?></td>
						<td class="example_margin"><?php echo $margin; ?></td>
						<td class="new_margin"><?php echo $margin; ?></td>
						<td class="old_price"><?php echo number_format(($base_price/$margin), 2, '.', ''); ?></td>
						<td class="new_price"><?php echo number_format(($base_price/$margin), 2, '.', ''); ?></td>

					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>
		<input type="submit" value="Adjust Margin" />
	</fieldset>
</form>