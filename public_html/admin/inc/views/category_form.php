<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="/admin/js/widget_list.js.php"></script>
<script type="text/javascript">
/* <[CDATA[ */
$(document).ready(function() {
	var Category_Widget_List = new Widget_List(parseInt($("#category_id").val()), 'Category_Widget', '<?php echo get_xsrf_field_name(); ?>', '<?php echo get_xsrf_field_value(); ?>');
	Category_Widget_List.refresh_widget_table('/admin/widgets.http.php', 'get_category_widgets');

	$("#new_widget_button").click(function() {
		var widget_id = parseInt($("#new_widget_id").val());
		if(widget_id > 0) {
			var category_id = parseInt($("#category_id").val());

			var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
					"action": "add_category_widget",
					"category_id" : category_id,
					"widget_id" : widget_id}
			$.post('/admin/widgets.http.php', data, function(data) {
				var new_widget_id = parseInt(data.widget_id);
				if(new_widget_id > 0) {
					Category_Widget_List.refresh_widget_table('/admin/widgets.http.php', 'get_category_widgets');
				}
			}, "json");
		}
		$("#new_widget_id").val(0);
	});
});
</script>
<form id="category_form" action="/admin/category/processCategory/" method="post">
	<h2>Editing Category "<?php echo $C->name; ?>"</h2>
	<?php echo $MS->messages(); ?>
	<fieldset>
		<input type="hidden" id="category_id" name="category_id" value="<?php echo intval($C->ID); ?>" />
		<input type="hidden" name="parent_id" value="<?php echo intval($C->parent_id); ?>" />
		<table>
			<tr>
				<td>Category Name</td>
				<td><input type="text" name="category[name]" value="<?php echo $C->name; ?>" /></td>
			</tr>
			<tr>
				<td>Category List Name</td>
				<td><input type="text" name="category[list_name]" value="<?php echo $C->list_name; ?>" /></td>
			</tr>
			<tr>
				<td>Long Name</td>
				<td><input type="text" name="category[long_name]" value="<?php echo $C->long_name; ?>" /></td>
			</tr>
			<tr>
				<td>Title Override</td>
				<td><input type="text" name="category[title_override]" value="<?php echo $C->title_override; ?>" /></td>
			</tr>
			<tr>
				<td style="text-align: right">
					<?php echo draw_checkbox('category_show_name', 1, ($C->show_name > 0)); ?>
				</td>
				<td>Display Name on listing page?</td>
			</tr>
			<tr>
				<td>Description Line 2</td>
				<td><input type="text" name="category[desc_2]" value="<?php echo $C->desc_2; ?>" /></td>
			</tr>
			<tr>
				<td>Description Line 3</td>
				<td><input type="text" name="category[desc_3]" value="<?php echo $C->desc_3; ?>" /></td>
			</tr>
			<tr>
				<td>URL</td>
				<td>
					<input type="text" name="category[url]" value="<?php echo $C->url; ?>" />
					(not really a good idea to change this)
				</td>
			</tr>
			<tr>
				<td>View</td>
				<td>
					<?php
					$view_options = array(Category::VIEW_NORMAL => "normal", Category::VIEW_MULTI => "multi");
					echo draw_select('category[view]', $view_options, $C->view);
					?>
				</td>
			</tr>
			<tr>
				<td>Active</td>
				<td>
					<?php
					echo draw_checkbox('category_active', 1, (1 == $C->getActive(true)));
					?>
					(checking this box toggles the display on the live site)
				</td>
			</tr>
			<tr>
				<td valign="top">Header</td>
				<td>
					<textarea id="category_header" name="category[header]" cols="80" rows="6"><?php echo htmlentities($C->header); ?></textarea>
				</td>
			</tr>
			<tr>
				<td valign="top">Footer</td>
				<td>
					<textarea id="category_footer" name="category[footer]"  cols="80" rows="6"><?php echo htmlentities($C->footer); ?></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" value="Save Category" /> or
					<a href="<?php echo LOC_CATEGORIES; ?>?category=<?php echo $C->parent_id; ?>">Cancel</a>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend>Internal Notes</legend>
		<p>Used for internal purposes only. Will not be shown to customers.</p>
		<textarea name="category[admin_note]" cols="100" rows="6"><?php echo htmlentities($C->admin_note); ?></textarea>
	</fieldset>
	<?php if(true == $C->exists()): ?>
	<div id="category_widgets">
		<?php require 'modules/widget_table.php'; ?>
	</div>
	<fieldset>
		<legend>Meta Tags</legend>
		<table>
			<tr>
				<th>Keywords</th>
				<td><textarea name="category[meta_keywords]" cols="80" rows="6"><?php echo htmlspecialchars($C->meta_keywords); ?></textarea></td>
			</tr>
			<tr>
				<th>Description</th>
				<td><textarea name="category[meta_description]" cols="80" rows="6"><?php echo htmlspecialchars($C->meta_description); ?></textarea></td>
			</tr>
		</table>
	</fieldset>
	<?php endif; ?>
</form>
<?php if(true == $C->exists()): ?>
<form id="category_image_form" action="/admin/category/processImage/" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Category Image</legend>
		<?php if(0 == strlen($C->image)): ?>
			<p>No image has been uploaded for this category yet.</p>
		<?php else: ?>
		<div id="category_image_preview">
			Current Image: <br />
			<img src="/images/categories/<?php echo $C->image; ?>" alt="category_image" />
		</div>
		<?php endif; ?>
		<input type="hidden" name="category_id" value="<?php echo $C->ID; ?>" />
		<input type="file" name="category_image" />
		<input type="submit" value="Upload Image" />
	</fieldset>
</form>
<?php endif; ?>