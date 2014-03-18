<script type="text/javascript">
/* <[CDATA[ */
function drop_page(page_id) {
	page_id = parseInt(page_id);
	if(page_id > 0) {
		var drop_confirm = confirm("Are you sure you want to delete this page?");
		if(true == drop_confirm) {
			var data = { 	'page_id' : page_id,
					'<?php echo get_xsrf_field_name(); ?>' : '<?php echo get_xsrf_field_value(); ?>' }
			$.post('/admin/page/drop/', data, function(data) {
				if(true == data.status) {
					window.location = '<?php echo LOC_PAGES; ?>';
				}
			}, "json");
		}
	}
}

$(document).ready(function() {
	$("#new_page_button").click(function() {
		window.location = '/admin/pages/new/';
	});
});
/* ]]/> */
</script>
<h2>Manage Pages</h2>
<form id="new_page_form" action="" method="post">
	<div>
		<input type="button" id="new_page_button" value="New Page" />
	</div>
</form>
<table id="page_list_table">
	<tr>
		<th>ID</th>
		<th>Title</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
	<?php
	foreach($PAGE_LIST as $i => $P) {
	?>
	<tr>
		<td><?php echo $P->ID; ?></td>
		<td><?php echo $P->title; ?></td>
		<td><a href="/admin/page/edit/<?php echo $P->ID; ?>">edit</a></td>
		<td><a href="javascript:void(0);" class="delete_page_link" onclick="drop_page(<?php echo $P->ID; ?>)">delete</a></td>
	</tr>
	<?php
	}
	if(0 == count($PAGE_LIST)) {
	?>
	<tr>
		<td colspan="4" align="center">No pages here... maybe you could <a href="?action=new">add one</a>?</td>
	</tr>
	<?php
	}
	?>
</table>
