<form id="attribute_value_form" action="/admin/attribute/processValue/" method="post">
	<fieldset>
		<input type="hidden" name="attribute_value_id" value="<?php echo $AV->ID; ?>" />
		Value:<br />
		<input type="text" name="value[value]" value="<?php echo $AV->value; ?>" /><br />
		Alt:<br />
		<input type="text" name="value[alt]" size="100" value="<?php echo $AV->alt; ?>" /><br />
		<input type="submit" value="Save Value" />
		or <a href="/admin/attribute/edit/<?php echo $AV->attribute_id; ?>/">Cancel</a>
	</fieldset>
</form>
