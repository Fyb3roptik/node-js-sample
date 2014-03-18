<form id="freight_box_form" action="/admin/fbox/processBox/" method="post">
	<fieldset>
		<legend>Edit Box</legend>
		<input type="hidden" name="freight_box_id" value="<?php echo $BOX->ID; ?>" />
		Length:<br />
		<input type="text" name="box[length]" value="<?php echo $BOX->length; ?>" />
		<br />
		Width:<br />
		<input type="text" name="box[width]" value="<?php echo $BOX->width; ?>" />
		<br />
		Height:<br />
		<input type="text" name="box[height]" value="<?php echo $BOX->height; ?>" />
		<br />
		Weight:<br />
		<input type="text" name="box[dim_weight]" value="<?php echo $BOX->dim_weight; ?>" />
		<br />
		<?php echo draw_checkbox('custom_box', 1, (intval($BOX->custom) > 0)); ?> Custom / Oversized?
		<br />
		<input type="submit" value="Save Box" />
		or <a href="/admin/fbox/">Cancel</a>
	</fieldset>
</form>
