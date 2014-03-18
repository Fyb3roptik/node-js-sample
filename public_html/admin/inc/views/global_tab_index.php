<script type="text/javascript">
$(document).ready(function() {
	$("tbody").zebra();

	$("tbody").sortable({
		stop: function() {
			save_tab_sort();
		}
	});
});

function save_tab_sort() {
	var post_data = { }
	var index = 0;
	$("tbody tr").each(function() {
		$(this).find('input[name="tab_id[]"]').each(function() {
			var tab_id = $(this).val();
			var field_name = "tab[" + index + "]";
			post_data[field_name] = tab_id;
			index++;
		});
	});

	$.post('/admin/gtab/saveSort/', post_data, function(data) {
		if(true == data['success']) {
			$('tbody').zebra();
		}
	}, "json");
}

function drop_tab(tab_id) {
	var confirm_drop = confirm("Are you sure you want to delete this tab?");
	if(true == confirm_drop) {
		var post_data = { "global_tab_id" : tab_id }
		$.post('/admin/gtab/drop/', post_data, function(data) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}, "json");
	}
}
</script>
<h2>Manage Global Accordion Tabs</h2>
<p>Global tabs will show up in the accordion tab list for every product.</p>
<?php
if(count($TAB_LIST) > 0) {
?>
<a href="/admin/gtab/newTab/">Add new tab</a>
<table>
	<thead>
		<tr>
			<th>Title</th>
			<th>Type</th>
			<th>Default View</th>
		<tr>
	</thead>
	<tbody>
	<?php
	foreach($TAB_LIST as $TAB) {
	?>
		<tr>
			<td>
				<?php echo $TAB->title; ?>
				<input type="hidden" name="tab_id[]" value="<?php echo $TAB->ID; ?>" />
			</td>
			<td><?php echo $TAB->type; ?></td>
			<td>
				<?php
				$view = 'closed';
				if(Product_Tab::OPEN == $TAB->default_view) {
					$view = 'open';
				}
				echo $view;
				?>
			</td>
			<td>
				<a href="/admin/gtab/edit/<?php echo $TAB->ID; ?>/">edit</a>
			</td>
			<td><a href="javascript:void(0)" onclick="drop_tab(<?php echo $TAB->ID; ?>)">delete</a></td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
} else {
?>
<p>
	No global tabs have been defined yet. 
	You might want to <a href="/admin/gtab/newTab/">add one</a>?
</p>
<?php
}
?>
