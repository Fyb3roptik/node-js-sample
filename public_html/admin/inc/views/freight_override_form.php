<form id="freight_override_form" action="/admin/fover/process/" method="post">
	<fieldset>
		<legend>Freight Override</legend>
		<input type="hidden" name="freight_override_id" value="<?php echo $FO->ID; ?>" />
		Minimum Order Value:<br />
		<input type="text" value="<?php echo $FO->minimum_value; ?>" name="fo[minimum_value]" /><br />
		Freight Value:<br />
		<input type="text" value="<?php echo $FO->freight_value; ?>" name="fo[freight_value]" />
<br />
		Freight Calculation:<br />
		<?php
		$options = array(
			Freight_Override::TYPE_FLAT => 'Flat Charge',
			Freight_Override::TYPE_PERCENT => 'Percent of Order'
		);
		echo draw_select('fo[freight_type]', $options, $FO->freight_type);
		?>
		<br />
		<input type="submit" value="Save" /> or <a href="/admin/fover/">Cancel</a>
	</fieldset>
</form>
