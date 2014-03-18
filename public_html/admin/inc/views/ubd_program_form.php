<script type="text/javascript">
$('a.cancel_program').click(function() {
	$("#mod_form_holder").empty();
	return false;
});
</script>
<form id="utility_program_form" action="/admin/ubd/processProgram/" method="post">
	<fieldset>
		<legend>Utility Program Details</legend>
		<input type="hidden" name="utility_mod_program_id" value="<?php echo $UMP->ID; ?>" />
		Program Sponsor:<br />
		<input type="text" name="ump[sponsor]" value="<?php echo $UMP->sponsor; ?>" /><br />
		Program Code:<br />
		<input type="text" name="ump[program_code]" value="<?php echo $UMP->program_code; ?>" />
		<br />
		Savings Text:<br />
		<input type="text" name="ump[savings_text]" value="<?php echo $UMP->savings_text; ?>" />
		<br />
		Products:<br />
		<input type="text" name="ump[products]" value="<?php echo $UMP->products; ?>" /><br />
		Description:<br />
		<input type="text" name="ump[description]" value="<?php echo $UMP->description; ?>" />
		<br />
		Start Date:<br />
		<input type="text" name="ump[start_date]" value="<?php echo $UMP->start_date; ?>" />
		<br />
		End Date:<br />
		<input type="text" name="ump[end_date]" value="<?php echo $UMP->end_date; ?>" />
		<br />
		<input type="submit" value="Save Program">
		or <a href="#" class="cancel_program">cancel</a>
	</fieldset>
</form>
