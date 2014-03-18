<script type="text/javascript">
$(document).ready(function() {
	$("#freight_form_holder").hide();

	$("a[href='/admin/excludes/new/']").click(function() {
		new_override();
		return false;
	});
});

function new_override() {
	$("#freight_form_holder").load('/admin/excludes/new/').show();
}

function edit_override(overrides_excludes_id) {
	$("#freight_form_holder").load('/admin/excludes/edit/' + overrides_excludes_id).show();
}

function drop_override(overrides_excludes_id) {
	var confirm_drop = confirm("Are you sure you want to delete this freight override?");
	if(true == confirm_drop) {
		var post_data = { "freight_overrides_excludes_id" : overrides_excludes_id }
		$.post('/admin/excludes/drop/', post_data, function(data) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}, "json");
	}
}
</script>
<h2>Freight Overrides Excludes</h2>
<p>Define freight override excludes here. Click here to <a href="/admin/excludes/new/">add new override</a>.</p>
<div id="freight_form_holder"></div>
<?php
if(0 == count($FOE)) {
?>
<p>No override excludes found, please <a href="/admin/excludes/new/">add one</a>.</p>
<?php
} else {
?>
<p>
</p>
<table>
	<thead>
		<tr>
			<th>Excluded State</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($FOE as $F) {
	?>
		<tr>
			<td><?php echo $F->excluded_state; ?></td>
			<td>
				<a href="javascript:void(0);" onclick="edit_override(<?php echo $F->ID; ?>)">edit</a>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="drop_override(<?php echo $F->ID; ?>)">delete</a>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
}
?>
