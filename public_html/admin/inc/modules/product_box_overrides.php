<script type="text/javascript">
$(document).ready(function() {
	$('#bover_form_holder');
	$('a.new_bover').live('click', function() {
		$('#bover_form_holder').load('/admin/bover/new/<?php echo $P->ID; ?>').show();
		return false;
	});
});

function edit_bover(box_id) {
	var url = '/admin/bover/edit/' + box_id;
	$("#bover_form_holder").load(url).show();
}

function drop_bover(box_id) {
	var confirm_drop = confirm("Are you sure you want to delete this box?");
	if(true == confirm_drop) {
		var post_data = { "box_override_id" : box_id }
		$.post('/admin/bover/drop/', post_data, function(data) {
			if(true == data['success']) {
				window.location.reload(false);
			}
		}, "json");
	}
}
</script>
<fieldset>
	<legend>Freight Override</legend>
	<p>These fields allow you to exempt orders of certain dollar amounts of this product from the shipping estimator.</p>
	<table>
		<tr>
			<td>&nbsp;</td>
			<td>
				<?php echo draw_checkbox('product[freight_override]', 1, (1 == $P->freight_override)); ?> Freight Override?
			</td>
		</tr>
		<tr>
			<th>Trigger Value ($)</th>
			<td>
				<input type="text" name="product[freight_override_value]" value="<?php echo floatval($P->freight_override_value); ?>" />
			<td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Save" /></td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<legend>Shipping Box Overrides</legend>
	<p>These can be used to assign special boxes for use when shipping this product in certain quantities.</p>
	<?php
	$box_list = $P->getBoxOverrides();
	if(count($box_list) > 0) {
	?>
	<p>Click here to add <a href="#" class="new_bover">a new box override</a>.</p>
	<table>
		<thead>
			<tr>
				<th>Min Qty</th>
				<th>Max Qty</th>
				<th>Length</th>
				<th>Width</th>
				<th>Height</th>
				<th>Weight</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($box_list as $box) {
			?>
			<tr>
				<td><?php echo $box->min_quantity; ?></td>
				<td><?php echo $box->max_quantity; ?></td>
				<td><?php echo $box->length; ?></td>
				<td><?php echo $box->width; ?></td>
				<td><?php echo $box->height; ?></td>
				<td><?php echo $box->weight; ?></td>
				<td>
					<a href="javascript:void(0)" onclick="edit_bover(<?php echo $box->ID; ?>)">edit</a>
				</td>
				<td>
					<a href="javascript:void(0)" onclick="drop_bover(<?php echo $box->ID; ?>)">delete</a>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<?php
	} else {
	?>
	<p>There are currently no box overrides associated with this product. Click here to add <a href="#" class="new_bover">a new box override</a>.</p>
	<?php
	}
	?>
</fieldset>
<div id="bover_form_holder">&nbsp;</div>
