<script type="text/javascript">
$(document).ready(function() {
   	$("#sales_rep_form").submit(function() {
		var valid = true;
		var syspro_rep_id = $("select[name='sales_rep[syspro_id]']").val();
		if("null" == syspro_rep_id) {
			alert("You must select a valid Syspro Sales Rep.");
			valid = false;
		}
		return valid;
	});

	$('a.baynote_attributes').click(function() {
		$("#attributes_form_holder").load('/admin/baynote/newAttribute/<?php echo $CATEGORY_ID; ?>').show();
		return false;
	});

	$('a.edit_attribute').click(function() {
		$("#attributes_form_holder").load($(this).attr('href')).show();
		return false;
	});

	$('a.delete_attribute').click(function() {
		var baynote_feed_id = $(this).prev('input').val();
		var $row = $("tr#attribute_"+baynote_feed_id);
		if(true == confirm("Are you sure you want to delete this attribute?")) {
			var post_data = { "baynote_feed_id" : baynote_feed_id }
			$.post('/admin/baynote/deleteAttribute/', post_data, function(data) {
				if(true == data['success']) {
					$row.fadeOut(1000, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});

	$("#updateAttributes").click(function() {
		if(confirm("Are you sure you want to update the Category Attributes?"))
		{
            var category_id = $("#category_id").val();
			$.post('/admin/baynote/updateCatAttributes', { category_id: category_id }, function(data) {
            	alert(data);
				location.reload();
			});
		}
	});
});
</script>
<h2>Edit Baynote Feed</h2>
<form id="baynote_feed_form" action="/admin/baynote/processFeed/" method="post">
	<fieldset>
		<legend>Details</legend>
		<input type="hidden" name="category_id" id="category_id" value="<?php echo $CATEGORY_ID; ?>" />
		<a href="#" id="updateAttributes">Update Category Attributes</a>
		<table>
			<tr>
				<td>
	            	<h3>Attributes [<a href="#" class="baynote_attributes">add new</a>]</h3>
					<div id="attributes_form_holder"></div>
					<?php if(count($FEEDS) > 0): ?>
					<table>
						<thead>
							<tr>
								<th>Attribute Name</th>
							<tr>
						</thead>
						<tbody>
						<?php foreach($FEEDS as $f): ?>
							<tr id="attribute_<?php echo $f['baynote_feed_id']; ?>">
								<td><?php echo $f['display_name']; ?></td>
								<td></td>
								<td>
									<a href="/admin/baynote/editAttribute/<?php echo $f['baynote_feed_id']; ?>" class="edit_attribute">edit</a>
								</td>
								<td>
									<input type="hidden" name="baynote_feed_id" id="baynote_feed_id" value="<?php echo $f['baynote_feed_id']; ?>" />
									<a href="#" class="delete_attribute">delete</a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<?php else: ?>
					<p>No attributes have been found. <a href="#" class="baynote_attributes">Add new goal?</a></p>
					<?php endif; ?>
				</td>
			</tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" value="Save" />
					or <a href="/admin/baynote">Cancel</a>
				</td>
			</tr>
		</table>
	</fieldset>
</form>
