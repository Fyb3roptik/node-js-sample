<script type="text/javascript">
	function delete_widget(widget_id) {
		var confirm_delete = confirm("Are you sure you want to delete this Widget?");

		if(true == confirm_delete) {
			var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
					"action" : "delete_widget",
					"widget_id" : parseInt(widget_id) }
			$.post('/admin/widgets.http.php', data, function(data) {
				var widget_id = parseInt(data.widget_id);
				if(widget_id > 0) {
					var widget_row_id = "#widget_row_" + widget_id;
					$(widget_row_id).hide();
				}
			}, "json");
		}
	}
</script>
<h2>Widgets!</h2>
<a href="/admin/widget/manageGlobalWidgets/">Manage Global Widgets</a> |
<a href="/admin/widget/manageAccountWidgets/">Manage Account Widgets</a>
<form id="new_widget_form" action="/admin/widget/configureNewWidget/" method="post">
	<fieldset>
		<legend>Configure New Widget</legend>
		<?php echo draw_xsrf_field(); ?>
		<input type="hidden" name="action" value="configure_new_widget" />
		<select name="widget_type">
			<option value="0">-Select Widget-</option>
			<option value="HTML_Widget">HTML Widget</option>
			<option value="Product_Widget">Product Widget</option>
		</select>
		<input type="submit" value="Create New Widget" />
	</fieldset>
</form>
<?php
if(count($WIDGET_LIST) > 0) {
?>
<table>
	<tr>
		<th>Widget Nickname</th>
		<th>Widget Type</th>
		<th>Edit</th>
	</tr>
	<?php
	foreach($WIDGET_LIST as $i => $W) {
	?>
	<tr id="widget_row_<?php echo $W->ID; ?>">
		<td><?php echo $W->nickname; ?></td>
		<td><?php echo $W->widget_class; ?></td>
		<td>
			<a href="/admin/widget/edit/<?php echo $W->ID; ?>">[edit]</a>
		</td>
		<td>
			<a href="javascript:void(0)" onclick="delete_widget(<?php echo $W->ID; ?>);">[delete]</a>
		</td>
	</tr>
	<?php
	}
	?>
</table>
<?php
}
?>