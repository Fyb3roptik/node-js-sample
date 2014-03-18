<script type="text/javascript">
var ORIG_LANDED_COST = <?php echo $P->getSemiLandedCost(); ?>;
function recalculate_pqd_totals() {
	var $pqd_table = $("#pqd_table");

	$pqd_table.find('tr').each(function() {
		var $tr = $(this);
		var actual_price = ORIG_LANDED_COST;
		var admin_override = parseFloat($("#admin_override_base_cost").val());
		var fudge_value = parseFloat($("#fudge_value").val());
		var fudge_type = $("#fudge_type").val();
		if(fudge_value > 0) {
			if("<?php echo Product::FUDGE_DOLLAR; ?>" == fudge_type) {
				actual_price += fudge_value;
			} else {
				actual_price += parseFloat(actual_price * (fudge_value / 100));
			}
		}
		if(admin_override > 0) {
			actual_price = admin_override;
		}
		var markup = 0;
		$("td.landed_cost").text(actual_price.toFixed(2));

		$tr.find('input').each(function() {
			var field_name = $(this).attr("name");
			switch(field_name) {
				case 'pqd[markup][]':
					markup = parseFloat($(this).val());
					if(false == isNaN(markup)) {
						$(this).val(markup);
					}
					break;
				default:
					break;
			}
		});
		var price = (actual_price / markup);
		$tr.find('td:last').prev('td').text("$" + price.toFixed(2));
	});

	restripe();
}

$(document).ready(function() {
	$("a.cancel_pricing").click(function() {
		window.location.href = '/admin/product/edit/<?php echo $P->ID; ?>/';
		return false;
	});
	$("#fudge_value").change(function() {
		recalculate_pqd_totals();
		$("td.fudge").text($(this).val());
	});

	$("#fudge_type").change(function() {
		recalculate_pqd_totals();
	});

	$('input.pqd_price_markup').change(function() {
		recalculate_pqd_totals();
	});
	restripe();

	$("#hidden_row").hide();

	$("#save_pricing").click(function() {
		$('#hidden_row').remove();
	});

	$("#new_pqd").click(function() {
		var $last_pqd_tr = $("#pqd_table tbody tr:last").prev();
		var $template_tr = $('#hidden_row');
		var $new_pqd_tr = $template_tr.clone();
		$new_pqd_tr.attr("id", null);

		var new_values = new Array();
		$last_pqd_tr.children('td').children('input').each(function() {
			var $field = $(this);
			new_values[$field.attr("name")] = $field.val();
		});

		for(var key in new_values) {
			value = new_values[key];
			switch(key) {
				case 'pqd[discount_id][]':
					new_values[key] = "";
					break;
				case 'pqd[min_quantity][]':
					new_values[key] = parseInt(value) + 10;
					break;
				case 'pqd[markup][]':
					new_values[key] = parseFloat(value) + .1;
					break;
				default:
					new_values[key] = value;
					break;
			}
		}

		$new_pqd_tr.children('td').children('input').each(function() {
			var field_name = $(this).attr("name");
			$(this).val(new_values[field_name]);
		});

		$new_pqd_tr.insertBefore($template_tr).show();
		$new_pqd_tr.find('input').change(function() {
			recalculate_pqd_totals();
		});

		recalculate_pqd_totals();
		restripe();
	});

	$("#admin_override_change").click(function() {
		var new_amount = parseFloat(prompt("New admin override amount?", $("#admin_override_base_cost").val()));
		if(false == isNaN(new_amount)) {
			$("#admin_override_base_cost").val(new_amount);
			var formatted_price = "$" + new_amount.toFixed(2);
			$("#admin_override_change").text(formatted_price);
			if(new_amount > 0) {
				$('td.landed_cost').text(new_amount);
			}
		}
		recalculate_pqd_totals();
		return false;
	});

	$("a.delete_tier").live('click', function() {
		var $parent_row = $(this).parent().parent();
		var pqd_id = $parent_row.find('input[name="pqd[discount_id][]"]').val();
		if(true == confirm("Are you sure you want to drop this pricing tier?")) {
			var post_data = { "pqd_id" : pqd_id }
			$.post('/admin/product/dropTier/', post_data, function(data) {
				if(true == data['success'] || 0 == pqd_id) {
					$parent_row.fadeOut(1000, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});
});
</script>

<fieldset>
	<legend>Pricing Info</legend>
	<table width="100%">
		<tr>
			<td>Qty Per Unit:</td>
			<td><input type="text" name="product[quantity]" value="<?php echo $P->quantity; ?>" /></td>
		</tr>
		<tr>
			<td>Unit of Measure:</td>
			<td>
				<input type="text" name="product[unit_measure]" value="<?php echo $P->unit_measure; ?>" />
				(i.e. "Case", "Barrel", "Truckload"...)
			</td>
		</tr>
		<tr>
			<td>Sales Rep Cost Override:</td>
			<td>
			<?php
			$BCL = new Base_Cost_Lookup($P->ID);
			$base_cost = $BCL->admin_override;
			?>
			<input type="hidden" id="admin_override_base_cost" name="admin_override_base_cost" value="<?php echo $BCL->admin_override; ?>" />
			<a href="#" id="admin_override_change"><?php echo price_format($BCL->admin_override); ?></a>
			</td>
		</tr>
		<tr>
			<td>Fudge Factor:</td>
			<td>
			<input type="text" name="product[fudge_factor]" value="<?php echo floatval($P->fudge_factor); ?>" size="2" id="fudge_value" />
			<?php
			$type_select = array(
				Product::FUDGE_DOLLAR => '$',
				Product::FUDGE_PERCENT => '%');
			echo draw_select('product[fudge_type]', $type_select, $P->fudge_type, 'id="fudge_type"');
			?>
			</td>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				Quantity Discounts
				(<a href="javascript:void(0);" id="new_pqd">new</a>):
			</td>
		</tr>
		<tr>
			<td id="quantity_discounts" colspan="2">
				<table id="pqd_table" cellspacing="0" cellpadding="0" width="100%">
					<thead>
						<tr>
							<th>Minimum Quantity</th>
							<th>Base Cost</th>
							<th>Global Overhead</th>
							<th>Fudge</th>
							<th>Landed Cost</th>
							<th>Margin</th>
							<th>Actual Price</th>
						</tr>
					</thead>
					<tbody>
				<?php
				$price_list = $P->getPrices();
				$BCL = new Base_Cost_Lookup($P->ID);
				$base_cost = $BCL->getActualCost();
				foreach($price_list as $i => $price) {
				?>
					<tr>
						<td>
							<input type="hidden" name="pqd[discount_id][]" value="<?php echo $price->ID; ?>" />
							<input type="text" name="pqd[min_quantity][]" value="<?php echo $price->min_quantity; ?>" />
						</td>
						<td><?php echo price_format($base_cost); ?></td>
						<td><?php echo floatval(Config::get()->value('global_product_overhead')); ?>%</td>
						<td class="fudge"><?php echo number_format(floatval($P->fudge_factor), 2, '.', ''); ?></td>
						<td class="landed_cost">
							<?php echo price_format($P->getLandedCost()); ?>
						</td>
						<td>
							<input class="pqd_price_markup" type="text" name="pqd[markup][]" value="<?php echo $price->markup; ?>" />
						</td>
						<td class="pqd_price">
							<?php echo price_format($price->getPrice()); ?>
						</td>
						<td>
							<a href="#" class="delete_tier">delete tier</a>
						</td>
					</tr>
				<?php
				}
				?>
					<tr id="hidden_row">
						<td>
							<input type="hidden" name="pqd[discount_id][]" value="" />
							<input type="text" name="pqd[min_quantity][]" value="1" />
						</td>
						<td><?php echo price_format($base_cost); ?></td>
						<td><?php echo floatval(Config::get()->value('global_product_overhead')); ?>%</td>
						<td class="fudge"><?php echo number_format(floatval($P->fudge_factor), 2, '.', ''); ?></td>
						<td class="landed_cost">
							<?php echo price_format($P->getLandedCost()); ?>
						</td>
						<td>
							<input class="pqd_price_markup" type="text" name="pqd[markup][]" value="1" />
						</td>
						<td class="pqd_price"><?php echo price_format($P->base_price); ?></td>
						<td>
							<a href="#" class="delete_tier">delete tier</a>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	<input id="save_pricing" type="submit" value="Submit" /> or <a href="#" class="cancel_pricing">Cancel</a>
</fieldset>
