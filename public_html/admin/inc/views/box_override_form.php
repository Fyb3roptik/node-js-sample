<script type="text/javascript">
$(document).ready(function() {
	$("#cancel_bover").click(function() {
		$("#bover_form").empty();
		return false;
	});

	/**
	 * Submit the form via AJAX and refresh the page.
	 */
	$("#submit_bover").click(function() {
		var post_data = { }
		var alert_me = "";
		$("#bover_field").children('input').each(function() {
			var $field = $(this);
			//alert($field.attr('name'));
			post_data[$field.attr('name')] = $field.val();
			alert_me += $field.attr('name') + "\t" + $field.val() + "\n";
		});
		$.post('/admin/bover/processBox/', post_data, function(data) {
			if(true == data['success']) {
				window.location.href = window.location.href;
				window.location.reload(false);
			}
		}, "json");
	});
});
</script>
<div id="bover_form">
<fieldset id="bover_field">
	<!-- not unlike Cloverfield, but w/o the shaky camera -->
	<legend>Edit Box Override</legend>
	<input type="hidden" name="box_override_id" value="<?php echo $BO->ID; ?>" />
	<input type="hidden" name="box[product_id]" value="<?php echo $BO->product_id; ?>" />
	Quantity Range (min - max)<br />
	<input type="text" name="box[min_quantity]" value="<?php echo $BO->min_quantity; ?>" size="3" />
	 -
	<input type="text" name="box[max_quantity]" value="<?php echo $BO->max_quantity; ?>" size="3" />
	<br />
	Dimensions (L x W x H)<br />
	<input type="text" name="box[length]" value="<?php echo $BO->length; ?>" size="3" /> x
	<input type="text" name="box[width]" value="<?php echo $BO->width; ?>" size="3" /> x 
	<input type="text" name="box[height]" value="<?php echo $BO->height; ?>" size="3" />
	<br />
	Weight (lbs)<br />
	<input type="text" name="box[weight]" value="<?php echo $BO->weight; ?>" size="3" /><br />
	<input type="button" name="submit" id="submit_bover" value="Save Box" />
	 or <a id="cancel_bover" href="#">Cancel</a>
</fieldset>
</div>
