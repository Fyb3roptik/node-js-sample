<script type="text/javascript">
var MASTER_CATEGORY_ID = <?php echo intval($CATEGORY->ID); ?>;

$(document).ready(function() {

	$('.category_list').load('/admin/category/listCategories/<?php echo $CATEGORY->ID; ?>/');

	$('a.cat_expand').live('click', function() {
		var category_id = $(this).siblings("input[name='category_id[]']").val();
		var $subcat_list = $(this).siblings('div.category_list');
		$subcat_list.load('/admin/category/listCategories/' + category_id);
	});
	$('.category_list').sortable({
		connectWith: 'div',
		placeholder: 'cat-placeholder',
		forcePlaceholderSize: true,
		containment: 'body',
		handle: 'strong',
		curor: 'move',
		items: '.category',
		stop: function() {
			restripe_category();
			save_category_sort();
		}
	});

	$('a.delete').live('click', function() {
		var category_id = $(this).parent().siblings('input[name="category_id[]"]').val();
		var $link = $(this);
		if(category_id > 0) {
			var drop_confirm = confirm("Are you sure you want to delete this category? All subcategories will be deleted as well.");
			if(true == drop_confirm) {
				var post_data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
						"category_id" : category_id}
				$.post('/admin/category/drop/', post_data, function(data) {
					if(true == data["deleted"]) {
						$link.parent().parent('.category').remove();
						restripe_category();
					}
				}, "json");
			}
		}
	});

	restripe_category();

	$('.category_list:first').css('width', '50%');

	$('.category a.indent').live('click', function() {
		var $category = $(this).parent('.category');
		if(1 == $category.prev('.category').length) {
			var $new_parent = $category.prev('.category');
			var parent_id = $new_parent.children('input[name="category_id[]"]').val();

			var post_data = {"parent_id" : parent_id, 
					"category_id" : $category.children('input[name="category_id[]"]').val()}
			$.post('/admin/category/changeParent/', post_data, function(data) {
				if(true == data['success']) {
					$category.hide('slow');
					$category.remove();
					$new_parent.children('.category_list').load('/admin/category/listCategories/' + parent_id);
					setTimeout(function() {
						save_category_sort();
					}, 10000);
				} else {
					alert("Whoops! Seems we couldn't change the parent of this category.");
				}
			}, "json");
		} else {
			alert("No category to append to!");
		}
	});
});

function save_category_sort() {
	var sort_data = { }
	var sort_index = 0;
	$('.category_list').each(function() {
		var parent_id = MASTER_CATEGORY_ID;
		if(1 == $(this).parent('.category').length) {
			parent_id = $(this).parent('.category')
					.children('input[name="category_id[]"]').val();
		}
		$(this).children('.category').each(function() {
			var category_id = $(this).children('input[name="category_id[]"]').val();
			var field_name = 'category[' + parent_id + '][' + category_id + ']';
			sort_data[field_name] = sort_index;
			sort_index += 100;
		});
	});

	$.post('/admin/category/saveSort/', sort_data, function(data) {
		if(true == data['success']) {
			//do nothing.
		}
	}, "json");
}

function restripe_category() {
	$('.category').css('background-color', '#fff');
	$('.category:odd').css('background-color', '#ccc');
}

function delete_category(category_id) {
	category_id = parseInt(category_id);
	if(category_id > 0) {
		var drop_confirm = confirm("Are you sure you want to delete this category? All subcategories will be deleted as well.");
		if(true == drop_confirm) {
			var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
					"category_id" : category_id}
			$.post("/admin/category/drop/", data, function(data) {
				if(true == data.deleted) {
					window.location = window.location;
				}
			}, "json");
		}
	}
}
</script>

<h2>Category "<?php echo $CATEGORY->name; ?>"</h2>
<?php
if(true == $CATEGORY->exists()) {
?>
<p><a href="?category=<?php echo $CATEGORY->parent_id; ?>">Up one level</a></p>
<?php
}
?>
<p><a href="/admin/category/new/<?php echo $CATEGORY->ID; ?>/">Create New Category</a></p>
<p>You can drag and drop categories to reorganize them. You can also use the <strong>&gt;&gt;</strong> links to indent a category, making it a subcategory of the category directly above it.</p>
<div class="category_list"></div>
