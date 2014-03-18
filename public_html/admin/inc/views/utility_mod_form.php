<script type="text/javascript">
$(document).ready(function() {
	$('a.cancel_mod').click(function() {
		$("#mod_form_holder").empty();
		return false;
	});
});
</script>
<form id="utility_mod_form" action="/admin/ubd/processMod/" method="post">
<fieldset>
	<legend>Utility Mod</legend>
	<input type="hidden" name="mod_id" value="<?php echo $MOD->ID; ?>" />
	Zip Code:<br />
	<input type="text" name="mod[zip_code]" value="<?php echo $MOD->zip_code; ?>" size="5" /><br />
	Program ID:<br />
	<input type="text" name="mod[program_id]" value="<?php echo $MOD->program_id; ?>" /><br />
	Stock Code:<br />
	<input type="text" name="mod[stock_code]" value="<?php echo $MOD->stock_code; ?>" /><br />
	Mod Type:<br />
	<?php echo draw_select('mod[mod_type]', $MOD_TYPE_LIST, $MOD->mod_type); ?><br />
	Discount Price:<br />
	<input type="text" name="mod[price]" value="<?php echo $MOD->price; ?>" size="5" /><br />
	<input type="submit" value="Save Mod" />
	or <a href="#" class="cancel_mod">cancel</a>
</fieldset>
</form>
