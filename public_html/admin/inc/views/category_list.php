<script type="text/javascript">
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

<h2>"<?php echo $C->name; ?>"</h2>

<?php
if(count($SUBCATEGORY_LIST) > 0) {
?>
<fieldset>
	<a href="/admin/category/new/<?php echo $C->ID; ?>">Create New</a>
	<legend>Subcategories for <em><?php echo $C->name; ?></em></legend>
	<table>
		<thead>
			<tr>
				<th>Category</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach($SUBCATEGORY_LIST as $i => $category) {
			?>
			<tr>
				<td>
					<a href="<?php echo LOC_CATEGORIES; ?>?category=<?php echo $category->ID; ?>">
						<?php echo $category->name; ?>
					</a>
				</td>
				<td>
					<a href="/admin/category/edit/<?php echo $category->ID; ?>">
						edit
					</a>
				</td>
				<td>
					<a href="javascript:void(0);" onclick="delete_category(<?php echo $category->ID; ?>)">delete</a>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
</fieldset>
<?php
}
?>