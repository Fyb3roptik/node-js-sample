<script type="text/javascript">
$(document).ready(function() {
	$("#edit_holder").hide();
	$('a.new_box').click(function() {
		$("#edit_holder").load('/admin/fbox/new/').show();
		return false;
	});
	$('tbody').zebra();

	$('tbody').sortable({
		stop: function() {
			save_box_sort_order();
			$(this).zebra();
		}
	});
});

function save_box_sort_order() {
	var index = 0;
	var post_data = { }
	$('input[name="box_id[]"]').each(function() {
		post_data["box_list[" + index + "]"] = $(this).val();
		index++;
	});
	$.post('/admin/fbox/saveSort/', post_data, 
		function(data) { 
			if(true == data['success']) {
				/* do nothing (unless you need to debug) */
			}
       		}, "json");
}

function edit_box(box_id) {
	$("#edit_holder").load('/admin/fbox/edit/' + box_id).show();
	return false;
}

function delete_box(box_id) {
	var confirm_drop = confirm("Are you sure you want to delete this box?");
	if(true == confirm_drop) {
		var post_data = { "box_id" : box_id }
		$.post('/admin/fbox/drop/', post_data, function(data) {
			if(true == data['success']) {
				window.location.href = window.location.href;
			}
		}, "json");
	}
}
</script>
<h2>Freight Boxes</h2>
<div id="edit_holder">&nbsp;</div>
<p>
	<strong>Current Boxes</strong>
	(<a href="#" class="new_box">Add new box</a>)
</p>
<?php
if(count($BOX_LIST) > 0) {
?>
<p>Drag / drop boxes to rearrange recommendation priority.</p>
<table cellspacing="0">
	<thead>
		<tr>
			<th>Priority</th>
			<th>Length</th>
			<th>Width</th>
			<th>Height</th>
			<th>Cubic Inches</th>
			<!--<th>Dim lbs</th>-->
			<th>Custom/Oversized</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$box_index = 1;
	foreach($BOX_LIST as $box) {
	?>
		<tr>
			<td><?php echo $box_index; ?></td>
			<td>
				<input type="hidden" name="box_id[]" value="<?php echo $box->ID; ?>" />
				<?php echo $box->length; ?>
			</td>
			<td><?php echo $box->width; ?></td>
			<td><?php echo $box->height; ?></td>
			<td><?php echo $box->getCubicInches(); ?></td>
			<!--<td>--><?php //echo $box->dim_weight; ?><!--</td>-->
			<td align="center">
				<?php echo (intval($box->custom) > 0) ? 'Y' : 'N'; ?>
			</td>
			<td>
				<a href="javascript:void(0);" class="edit_box" onclick="edit_box(<?php echo $box->ID; ?>)">edit</a>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="delete_box(<?php echo $box->ID; ?>)">delete</a>
			</td>
		</tr>
	<?php
		$box_index++;
	}
	?>
	</tbody>
</table>
<?php
} else {
?>
<p>No boxes found. Please add a <a href="#" class="new_box">new box</a>.</p>
<?php
}
?>
