<script type="text/javascript">
var ATTRIBUTE_ID = <?php echo intval($A->ID); ?>;
$(document).ready(function() {
	$("#attribute_values tbody").zebra();

	$("#add_value").toggle(
		function() {
			if(0 == ATTRIBUTE_ID) {
				alert("I'm sorry, Dave. I can't do that.\n\nThis Attribute must be saved first.");
			} else {
				$("#new_value_form").slideDown();
				$(this).text('cancel add');
				$("#new_value").defaultValue("new value");
			}
			return false;
		},
		function() {
			$("#new_value_form").slideUp();
			$(this).text('add');
			return false;
		}
	);
	$("#av_holder").load('/admin/attribute/valueTable/' + ATTRIBUTE_ID);
	$("#new_value_form").hide();
	$("#save_new_value").click(function() {
		var data = { "attribute_id" : ATTRIBUTE_ID,
			"value[value]" : $("#new_value").val(),
			"attribute_value_id" : 0 }
		$.post('/admin/attribute/processValueAjax/', data, function(data) {
			$("#asdf").html(data);
			if(true == data['success']) {
				$("#add_value").click();
				$("#av_holder").load('/admin/attribute/valueTable/' + ATTRIBUTE_ID);
			}
		}, "json");
	});
});
function edit_value(value_id) {
	$("#av_holder").load('/admin/attribute/editValue/' + value_id);
}

function drop_value(value_id) {
	var confirm_drop = confirm("Are you sure you want to delete this value?");
	if(true == confirm_drop) {
		var post_data = { "value_id" : value_id }
		$.post('/admin/attribute/dropValue/', post_data, function(data) {
			if(true == data['success']) {
				$("#av_holder").load('/admin/attribute/valueTable/' + ATTRIBUTE_ID);
			}
		}, "json");
	}
}
</script>
<h2>Editing Attribute "<?php echo $A->name; ?>"</h2>
<form id="attribute_form" action="/admin/attribute/process/" method="post">
	<fieldset>
		<legend>Attribute Details</legend>
		<input type="hidden" name="attribute_id" value="<?php echo $A->ID; ?>" />
		Name:<br />
		<input type="text" name="attribute[name]" value="<?php echo $A->name; ?>" /><br />
		Display Type:<br />
		<?php
		$display_options = array(
					Attribute::NORMAL => 'Attribute and Value',
					Attribute::VAL_ONLY => 'Value Only');
		echo draw_select('attribute[display]', $display_options, $A->display);
		?><br />
		Alt:<br />
		<input type="text" name="attribute[alt]" size="100" value="<?php echo $A->alt; ?>"/> (alt text information about this attribute)<br />
		Key:<br />
		<input type="text" name="attribute[key]" value="<?php echo $A->key; ?>" /> (used to identify the attribute for different stuff, must be unique)<br />
		<input type="submit" value="Save" />
		or <a href="/admin/attribute/">Cancel</a>
	</fieldset>
</form>
<fieldset>
	<legend>Attribute Values (<a href="#" id="add_value">add</a>)</legend>
	<div id="new_value_form">
		<input id="new_value" name="new_value" />
		<input type="button" id="save_new_value" value="Add Value" />
	</div>
	<div id="av_holder"><img src="/images/ajax-loader.gif" alt="loading" /> Loading...</div>
</fieldset>
