<script type="text/javascript">
$(document).ready(function() {
	$("#existing_categories tbody").zebra().truncate(10);

	$("a.remove_category").click(function() {
		var product_count = $(this).parents('tr').children('.product_count').text();
		var confirm_remove = confirm("Are you sure you want to remove this category from " + product_count + " product(s)?");
		var $link = $(this);
		if(true == confirm_remove) {
			var category_id = $(this).parents('tr').find('input[name="category_id[]"]').val();
			var post_data = { "category_id" : category_id }
			var counter = 0;
			$('input[name="product_id[]"]').each(function() {
				var field_name = "product_id[" + counter + "]";
				post_data[field_name] = $(this).val();
				counter++;
			});
			$.post('/admin/batch/removeCategory/', post_data, function(data) {
				if(true == data['success']) {
					$link.parents('tr').remove();
				}
			}, "json");
		}

		return false;
	});

	load_category_select($("select:first[name='new_category[]']"), 0);

	//$('select[name="new_category[]"]').live('change', function() {
	$('select[name="new_category[]"]').change(function() {
		load_next_select($(this));
	});

	$('#add_category').click(function() {
		var category_id = 'select';
		var $select = $("#select_holder").find('select:last');

		while('select' == category_id) {
			category_id = $select.val();
			$select = $select.parent().parent().find('select');
		}

		if(parseInt(category_id) > 0) {
			var confirm_add = confirm("Are you sure you want to add all of these products to this category?");
			if(true == confirm_add) {
				var post_data = { "category_id" : category_id }
				var counter = 0;
				$('input[name="product_id[]"]').each(function() {
					var field_name = "product_id[" + counter + "]";
					post_data[field_name] = $(this).val();
					counter++;
				});
				$.post('/admin/batch/addCategory/', post_data, function(data) {
					if(true == data['success']) {
						$("#select_holder div div").remove();
						$("#select_holder").find("select:first").val(0).change();
						$("#cat_add_message").text(data['message']).fadeIn(1500);
					} else {
						alert("Something went wrong. Please try again in a few minutes.");
					}
				}, "json");
			}
		} else {
			alert("You must select a category.");
		}
	});
});

function load_category_select(select, parent_id) {
	var url = '/admin/category/getChildren/' + parent_id;
	$.get(url, null, function(data) {
		for(var i in data['children']) {
			var child = data['children'][i];
			var $option = $(document.createElement('option'));
			$option.attr('value', child['id']).appendTo(select);
			$option.text(child['name']);
		}
	}, "json");
}

function load_next_select($current_select) {
	var parent_id = parseInt($current_select.val());
	if(parent_id > 0 && false == isNaN(parent_id)) {
		//remove any selects after this one!
		$current_select.nextAll('div').remove();
		var $new_div = $(document.createElement('div'));
		var $arrow_img = $(document.createElement('img'))
			.attr('src', '/images/icons/arrow_turn_right.png')
			.attr('alt', 'arrrrrroooooow')
			.appendTo($new_div);
		var $new_select = $(document.createElement('select')).appendTo($new_div);
		$new_select.attr('name', 'new_category[]');

		var $select_option = $(document.createElement('option'));
		$select_option.attr('value', 'select').text('Select Parent').appendTo($new_select);
		$new_div.appendTo($current_select.parent());
		load_category_select($new_select, parent_id);
		$new_select.change(function() {
			load_next_select($(this));
		});
	}
}
</script>
<fieldset id="product_list" style="display: none;">
	<?php foreach($PRODUCT_LIST as $P): ?>
	<input type="hidden" name="product_id[]" value="<?php echo $P->ID; ?>" />
	<?php endforeach; ?>
</fieldset>
<fieldset>
	<legend>Existing Categories</legend>
	<?php if(count($CATEGORY_COUNTS) > 0): ?>
	<table id="existing_categories">
		<thead>
			<tr>
				<th style="text-align: left;">Category</th>
				<th>Product Count</th>
			</tr>
		<thead>
		<tbody>
		<?php foreach($CATEGORY_COUNTS as $category_id => $count): ?>
			<?php $C = $CATEGORY_LIST[$category_id]; ?>
			<tr>
				<td>
					<input type="hidden" name="category_id[]" value="<?php echo $C->ID; ?>" />
					<?php echo $C->name; ?>
				</td>
				<td class="product_count" style="text-align: center;"><?php echo $count; ?></td>
				<td><a class="remove_category" href="#">remove</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p><strong>No categories are associated with these products.</strong></p>
	<?php endif; ?>
</fieldset>

<fieldset>
	<legend>Add New Category</legend>
	<div id="cat_add_message"></div>
	<strong>Select new category:</strong>
	<div id="select_holder">
		<div>
		<select name="new_category[]">
			<option value="0">Select Category</option>
		</select>
		</div>
	</div>
	<input type="button" id="add_category" value="Add Category" />
</fieldset>
<img src="/images/icons/arrow_turn_right.png" alt="pre-loaded-arrow" style="display: none;" />