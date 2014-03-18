<script type="text/javascript">
$(document).ready(function() {
	$("#empty_values").hide();
	if(0 == $("#attribute_values tbody").children().length) {
		$("#attribute_values").hide();
		$("#empty_values").show();
	}
	$("#attribute_values tbody").zebra();
});
</script>
<?php if(true == $A->exists()) { ?>
<table id="attribute_values">
	<thead>
		<tr>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$value_list = $A->getValues();
	foreach($value_list as $V) {
	?>
		<tr>
			<td><?php echo $V->value; ?></td>
			<td><a href="javascript:void(0)" onclick="edit_value(<?php echo $V->ID; ?>);">edit</a></td>
			<td><a href="javascript:void(0)" onclick="drop_value(<?php echo $V->ID; ?>);">delete</a></td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<div id="empty_values"><strong>Whoops!</strong> Looks like there aren't any values for this Attribute yet.</div>
<?php } else { ?>
<p><strong>Notice:</strong> The attribute must be saved before you can add values to it!</p>
<?php } ?>
