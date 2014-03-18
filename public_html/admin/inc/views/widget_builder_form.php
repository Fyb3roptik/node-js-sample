<form id="widget_builder_form" action="/admin/widget/processWidget/" method="post">
	<fieldset>
		<legend>Widget Details</legend>
		<input type="hidden" name="action" value="process_widget_builder" />
		<input type="hidden" name="widget_id" value="<?php echo $WB->ID; ?>" />
		<input type="hidden" name="widget[widget_class]" value="<?php echo $WB->widget_class; ?>" />
		<?php echo draw_xsrf_field(); ?>
		<table>
			<tr>
				<td>Nickname</td>
				<td><input type="text" name="widget[nickname]" value="<?php echo $WB->nickname; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Save Widget" /> or <a href="<?php echo LOC_MANAGE_WIDGETS; ?>">Cancel</a></td>
			</tr>
		</table>
	</fieldset>
	<?php
	$widget_form_file_name = DIR_ROOT . 'admin/inc/widget_forms/' . $WB->widget_class . '.php';
	if(true == file_exists($widget_form_file_name)) {
	?>
	<fieldset>
		<legend>Widget Configuration</legend>
		<?php require_once($widget_form_file_name); ?>
	</fieldset>
	<?php
	}
	?>
</form>