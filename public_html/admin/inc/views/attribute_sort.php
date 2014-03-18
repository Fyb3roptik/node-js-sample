<script type="text/javascript">
$(document).ready(function() {
	$("#attribute_table tbody")
		.zebra()
		.sortable({
			stop: function() { 
				$("#return_message").hide();
				save_attribute_sort();
				$(this).zebra();
			}
		});

	$('#return_message').hide();
});

function save_attribute_sort() {
	var post_data = { }
	var index = 0;
	$("input[name='attribute_id[]']").each(function() {
		var field_name = 'attribute_id[' + index + ']';
		post_data[field_name] = $(this).val();
		index++;
	});

	$.post('/admin/attribute/saveSort/', post_data, function(data) {
		if(true == data['success']) {
			$("#return_message")
				.text(data['message'])
				.fadeIn(2000);
		}
	}, "json");
};
</script>
<h2>Manage Attribute Sort Order</h2>
<p>Drag and drop attributes to rearrange the order in which they're displayed.</p>
<div id="return_message"></div>
<table id="attribute_table">
	<tbody>
	<?php foreach($ATTR_LIST as $A): ?>
		<tr>
			<td>
			<input type="hidden" name="attribute_id[]" value="<?php echo $A->ID; ?>" />
			<?php echo $A->name; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
