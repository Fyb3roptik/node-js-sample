<script type="text/javascript">
$(document).ready(function() {
	$("#column_table tr").sortable({
		stop: function() {
			save_column_category_sort();
		},
			handle: ".column_name",
			cursor: "move"
	});

	$(".column_category_list").sortable({
		stop: function() {
			save_column_category_sort();
		},
		cursor: "move",
			connectWith: ".column_category_list",
			placeholder: "sortable-cat-placeholder",
			forcePlaceholderSize: true
	});
});

function save_column_category_sort() {
	var post_data = { }
	$("#column_table tr td").each(function() {
		var column_id = $(this).find("input[name='column_id[]']").val();
		$(this).find(".column_category_list").children('.column_category').each(function() {
			var category_id = $(this).find('input[name="category_id[]"]').val();
			var field_name = "sort_data[" + column_id + "][" + category_id + "]";
			post_data[field_name] = 1; //just needs a value, we'll look at the keys later.
		});
	});
	$.post("/admin/nav/saveItemSortOrder/<?php echo $ITEM->ID; ?>/", post_data, function(data) {
		//do nothing
	}, "json");
}

function drop_column(column_id) {
	post_data = {"column_id" : column_id}
	if(true == confirm("Are you sure you want to delete this column?")) {
		$.ajax({
			async: false,
			url: '/admin/nav/dropColumn/',
			data: post_data,
			type: "POST",
			dataType: "json",
			success: function(data) {
				if(true == data['success']) {
					window.location = window.location;
				}
			}
		});
	}
}
</script>
<h2>Editing Nav Item for "<?php echo $ITEM->getName(); ?>"</h2>
<p><a href="/admin/nav/">Back to all nav items.</a></p>
<div id="nav_columns">
<?php
if(0 == count($ITEM->getColumns())) {
?>
	<p>No columns yet. Please <a href="/admin/nav/newColumn/<?php echo $ITEM->ID; ?>/">add one</a>.</p>
<?php 
} else {
?>
<strong>Current Columns</strong> (<a href="/admin/nav/newColumn/<?php echo $ITEM->ID; ?>/">add new</a>)
<table id="column_table">
	<tr>
<?php

	foreach($ITEM->getColumns() as $col_index => $COL) {
		$cat_list = $COL->getCategories();
	?>
	<td class="category" valign="top">
		<input type="hidden" name="column_id[]" value="<?php echo $COL->ID; ?>" />
		<strong class="column_name">Column <?php echo $col_index+1; ?></strong>
		(<a href="/admin/nav/editColumn/<?php echo $COL->ID; ?>/">edit</a> |
		<a href="javascript:void(0)" onclick="drop_column(<?php echo $COL->ID; ?>)">delete</a>)
		<div class="column_category_list">
		<?php
		foreach($cat_list as $CAT) {
		?>
		<div class="column_category">
			<input type="hidden" name="category_id[]" value="<?php echo $CAT->ID; ?>" />
			<strong><?php echo $CAT->name; ?></strong>
			<?php
			$subcat_list = $CAT->getNavCategories();
			if(count($subcat_list) > 0) {
			?>
			<div class="subcat_list">
			<?php
			foreach($subcat_list as $subcat_index => $subcat) {
			?>
				<div class="subcat"><?php echo $subcat->name; ?></div>
			<?php
			}
			?>
			</div>
			<?php
			}
			?>
		</div>
		<?php
		}
		?>
		</div>	
	</td>
	<?php
	}
?>
	</tr>
</table>
<?php
}
?>
</div>
