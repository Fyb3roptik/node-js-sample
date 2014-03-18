<script type="text/javascript">
$(document).ready(function() {
	$("#nav_items_table tbody").sortable({
		stop : function(event, ui) {
			$("#save_sort").show();	
		},
		handle : 'td:first'
	});

	$("#save_sort").hide();
	$("#save_sort").click(function() {
		save_nav_item_sort();
	});
	$(".nav_bullet").css('font-size', '28px');
});

function save_nav_item_sort() {
	var sort_data = { }
	var index = 0;
	$("input[name='nav_id[]']").each(function() {
		var field_key = 'nav[' + index + ']';
		sort_data[field_key] = $(this).val();
		index++;
	});
	$.ajax({
		type: "POST",
		url: "/admin/nav/saveSort/",
		data : sort_data,
		dataType : "json",
		async: false,
		success: function(data, message) {
			if(true == data['success']) {
				window.location = window.location; //refresh
			}
		}
	});
}

function delete_nav_item(nav_item_id) {
	post_data = {"nav_item_id" : nav_item_id}
	if(true == confirm("Are you sure you want to delete this nav item?")) {
		$.ajax({
			async: false,
			url: "/admin/nav/dropItem/",
			data: post_data,
			dataType: "json",
			type: "POST",
			success: function(data, message) {
				if(true == data['success']) {
					window.location = window.location; //refresh
				}
			}
		});
	}
}
</script>
<h2>Manage Category Navigation</h2>
<?php if($NAV_CACHE_RAW > 0): ?>
<p>The header nav was last cached on <?php echo $NAV_CACHE_TIME; ?>. <a href="/admin/nav/clearCache/">Click here</a> to clear the cached file and make any changes you've made since then live.</p>
<?php else: ?>
<p>No cached file exists. The next time someone loads the site, the cache will be reset.</p>
<?php endif; ?>
<?php
if(0 == count($NAV_LIST)) {
?>
<p>It looks like you haven't defined any top level nav items. Please <a href="/admin/nav/newItem/">add one</a>.</p>
<?php
} else {
?>
<strong>Current Nav Items</strong> (<a href="/admin/nav/newItem/">add new</a>)
<p>These are the categories that will show up as "top-level" categories on the front-end.</p>
<p>Drag and drop nav items by the <span class="nav_bullet">&bull;</span> to change the sort order.</p>
<input type="button" id="save_sort" value="Save New Order" />
<br />
<table id="nav_items_table">
	<thead>
		<tr>
			<th colspan="2" align="left">Category</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($NAV_LIST as $i => $nav) {
	?>
	<tr>
		<td align="center"><span class="nav_bullet">&bull;</span></td>
		<td>
			<a href="/admin/nav/edit/<?php echo $nav->ID; ?>"><?php echo $nav->getName(); ?></a>
			<input type="hidden" name="nav_id[]" value="<?php echo $nav->ID; ?>" />
		</td>
		<td>
			<a href="javascript:void(0)" onclick="delete_nav_item(<?php echo $nav->ID; ?>)">delete</a>
		</td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
}
?>
