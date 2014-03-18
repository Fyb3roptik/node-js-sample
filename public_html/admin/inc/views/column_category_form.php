<script type="text/javascript">
$(document).ready(function() {
	style_subcats();

	$("input[name='subcat_active[]']").change(function() {
		style_subcats();
		var category_id = $(this).val();
		var active = $(this).attr('checked');
		var active_int = 0;
		if(true == active) {
			active_int = 1;
		}
		change_category_active(category_id, active_int);
	});

	$(".subcat_table tbody").sortable({
		stop: function(event, ui) {
			save_column_sort_order();
		},
			placeholder: "sortable-subcat-placeholder",
			forcePlaceholderSize: true,
			cursor: "move"
	});

	$("#column_list").sortable({
		stop: function(event, ui) {
			save_column_sort_order();
		},
			handle: ".cat_name",
			placeholder: "sortable-cat-placeholder",
			forcePlaceholderSize: true,
			cursor: "move"
	});
});

function delete_column_category(category_id) {
	var confirm_drop = confirm("Are you sure you want to remove this category from the column?");
	var post_data = {"category_id" : category_id}
	$.ajax({
		url: '/admin/nav/columnDropCategory/',
		data: post_data,
		dataType: "json",
		type: "POST",
		async: false,
		success: function(data, message) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}
	});
	return false;
}

function change_category_active(category_id, active) {
	var post_data = {"category_id" : category_id,
			 "active" : active}
	$.post('/admin/category/changeNavActive/', post_data, function(data) {
		//do nothing.
	}, "json");
}

function style_subcats() {
	$(".subcat_table tbody tr").each(function() {
		var is_active = $(this).find("input[name='subcat_active[]']").attr('checked');
		if(true == is_active) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('inactive');
		}
	});
}

function field_name(field, index) {
	return field + "[" + index + "]";
}

function save_column_sort_order() {
	var nav_sort_array = {};
	$(".column_category").each(function() {
		var category_id = $(this).find("input[name='category_id[]']").val();
		nav_sort_array[field_name('category', category_id)] = 1;

		$(this).find("input[name='subcat_active[]']").each(function() {
			var active = 0;
			var category_id = $(this).val();
			if(true == $(this).attr('checked')) {
				active = 1;
			}
			nav_sort_array[field_name('category', category_id)] = active;
		});
	});

	$.post('/admin/nav/columnSaveSort/', nav_sort_array, function(data) {
		//do nada.
	}, "json");
}
</script>
<?php
if(count($CATEGORY_LIST) > 0) {
?>
<strong>Current Categories in Column</strong>
<p>You can drag and drop categories and sub-categories to sort them. Unchecking sub-categories will disable them on the front-end, they will be replaced with a "see all" link that links to the top level category.</p>
<div id="column_list">
<?php
	foreach($CATEGORY_LIST as $i => $category) {
	?>
	<div class="column_category">
		<input type="hidden" name="category_id[]" value="<?php echo $category->ID; ?>" />
		<strong class="cat_name"><?php echo $category->name; ?></strong>
		(<a href="javascript:void(0)" onclick="delete_column_category(<?php echo $category->ID; ?>)">delete</a>)
		<?php
		$subcats = $category->getSubcategories('nav_sort_order');
		if(count($subcats) > 0) {
		?>
		<table width="100%" class="subcat_table">
			<tbody>
			<?php
			foreach($subcats as $kitten) {
			?>
				<tr>
					<td>
					<?php
					echo draw_checkbox('subcat_active[]', $kitten->ID, (1 == $kitten->nav));
					?> 
					<?php echo $kitten->name; ?></td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<?php
		}
		?>
	</div>
	<?php
	}
?>
</div>
<?php
} else {
?>
<p>No categories for this column. Please <a href="#" class="new_category_link">add one</a>.</p>
<?php
}
?>
