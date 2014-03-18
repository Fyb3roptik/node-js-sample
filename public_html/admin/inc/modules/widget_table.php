<script type="text/javascript">
$(document).ready(function() {
	if(1 == $("#widget_table tr").length) {
		$("#widget_table").hide();
	}
});
</script>
<fieldset id="widgets">
	<legend>Manage Widgets</legend>
	<?php echo draw_widget_select('new_widget_id', 0, 'id="new_widget_id"'); ?>
	<input type="button" id="new_widget_button" value="Add New Widget" />
	<table id="widget_table" style="width: 50%;">
		<thead>
		<tr>
			<th>Widget</th>
			<th>Class</th>
			<th>Delete</th>
		</tr>
		</thead>
		<tbody>
			<tr><td>(spacer)</td></tr>
		</tbody>
	</table>
</fieldset>