<form id="freight_overrides_excludes_form" action="/admin/excludes/process/" method="post">
	<fieldset>
		<legend>Freight Override Excludes</legend>
		<input type="hidden" name="freight_overrides_excludes_id" value="<?php echo $FOE->ID; ?>" />
		State to Exclude:
		<br />
		<?php echo draw_select('foe[excluded_state]', get_states(), $FOE->excluded_state, 'id="excluded_state" class="textfield2"'); ?>
		<br />
		<input type="submit" value="Save" /> or <a href="/admin/excludes/">Cancel</a>
	</fieldset>
</form>
