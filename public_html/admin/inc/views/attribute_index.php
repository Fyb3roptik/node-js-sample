<script type="text/javascript">
$(document).ready(function() {
	$('tbody').zebra();
	$("table").css('font-size', '12px');
});

function delete_attribute(attribute_id) {
	var confirm_drop = confirm("Are you sure you want to delete this Attribute?");
	if(true == confirm_drop) {
		var data = {"attribute_id" : attribute_id}
		$.post('/admin/attribute/drop/', data, function(data) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}, "json");
	}
}
</script>
<h2>Manage Attributes</h2>
<p>
	Click here to <a href="/admin/attribute/newAttribute/">add a new attribute</a>.<br />
	Click here to <a href="/admin/attributes.php">manage attribute value images.</a><br />
	Click here to <a href="/admin/attribute/editSort/">edit attribute sort order.</a>
</p>
<?php
if(count($ATTR_LIST) > 0) {
?>
<div class="pk_links">
	<?php echo $PK_LINKS; ?>
</div>
<table>
	<thead>
		<tr>
			<th>Attribute</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($ATTR_LIST as $ATTR) {
	?>
		<tr>
			<td><?php echo $ATTR->name; ?></td>
			<td>
				<a href="/admin/attribute/edit/<?php echo $ATTR->ID; ?>/">edit</a>
			</td>
			<td>
				<a href="javascript:void(0)" onclick="delete_attribute(<?php echo $ATTR->ID; ?>)">delete</a>
			</td>
		</tr>
	<?php
	}
	?>
</tbody>
</table>
<div class="pk_links">
	<?php echo $PK_LINKS; ?>
</div>
<?php
}
?>
