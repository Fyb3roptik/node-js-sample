<script type="text/javascript">
$(document).ready(function() {
	$("#freight_form_holder").hide();

	$("a[href='/admin/fover/new/']").click(function() {
		new_override();
		return false;
	});
});

function new_override() {
	$("#freight_form_holder").load('/admin/fover/new/').show();
}

function edit_override(override_id) {
	$("#freight_form_holder").load('/admin/fover/edit/' + override_id).show();
}

function drop_override(override_id) {
	var confirm_drop = confirm("Are you sure you want to delete this freight override?");
	if(true == confirm_drop) {
		var post_data = { "freight_override_id" : override_id }
		$.post('/admin/fover/drop/', post_data, function(data) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}, "json");
	}
}
</script>
<h2>Freight Overrides</h2>
<p>Define freight overrides here. Click here to <a href="/admin/fover/new/">add new override</a>.</p>
<div id="freight_form_holder"></div>
<?php
if(0 == count($OVERRIDE_LIST)) {
?>
<p>No overrides found, please <a href="/admin/fover/new/">add one</a>.</p>
<?php
} else {
?>
<p>
</p>
<table>
	<thead>
		<tr>
			<th>Minimum Order Value</th>
			<th>Freight Value</th>
			<th>Calculation Type</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($OVERRIDE_LIST as $FO) {
	?>	
		<tr>
			<td><?php echo $FO->minimum_value; ?></td>
			<td><?php echo $FO->freight_value; ?></td>
			<td><?php echo $FO->freight_type; ?></td>
			<td>
				<a href="javascript:void(0);" onclick="edit_override(<?php echo $FO->ID; ?>)">edit</a>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="drop_override(<?php echo $FO->ID; ?>)">delete</a>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
}
?>
