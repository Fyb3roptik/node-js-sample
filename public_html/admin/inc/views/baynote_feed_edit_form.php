<script type="text/javascript">
$(document).ready(function() {
	$('a.cancel_attribute').click(function() {
		$("#attributes_form_holder").empty();
		return false;
	});

	$("#attribute_form").submit(function() {
		var valid = true;
		var baynote_attribute_id = $("select[name='attribute[baynote_attribute_id]']").val();
		if("null" == syspro_rep_id) {
			alert("You must select a valid Baynote Attribute.");
			valid = false;
		}
		return valid;
	});

	$("#baynote_attribute").change(function() {
    	var selected_val = $('select[id=baynote_attribute] option:selected').attr('name');
		$("#display_name").val(selected_val);
	});

});
</script>
<form id="attribute_form" method="post" action="/admin/baynote/processAttribute/">
	<fieldset>
		<legend>Baynote Attribute</legend>
		<input type="hidden" name="baynote_feed_id" value="" />
		<input type="hidden" name="attribute[category_id]" value="<?php echo $CATEGORY_ID; ?>" />
		Attribute:<br />
		<?php echo draw_select('attribute[baynote_attribute_id]', $ATTRIBUTES, $FEED['baynote_attribute_id'], "id='baynote_attribute'", "Select Attribute"); ?><br />
		Display Name:<br />
		<input type="text" name="attribute[display_name]" id="display_name" value="<?php echo $FEED['display_name']; ?>" /><br />
        Range:
		<br />
		<input type="submit" value="Save Attribute" /> or <a href="#" class="cancel_attribute">cancel</a>
	</fieldset>
</form>
