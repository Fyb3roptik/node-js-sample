<h2 class="greeting">Cancel Order #<?php echo $O->ID; ?></h2>
<form id="order_cancel_form" action="/order/processCancel" method="post">
	<fieldset>
		<input type="hidden" name="order_id" value="<?php echo $O->ID; ?>" />
		Cancel Code:<br />
		<?php echo draw_select('cancel_code', $CANCEL_REASON_LIST); ?>
		<br />
		Reason:<br />
		<textarea name="cancel_reason" cols="80" rows="8"></textarea>
		<br /><br />
		<input type="submit" value="Cancel Order" />
	</fieldset>
</form>
