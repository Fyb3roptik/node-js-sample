<form id="item_cancel_form_<?php echo $STOCK_CODE; ?>" action="/order/changeCancel/" method="post">
	<fieldset>
		<input type="hidden" name="order_id" value="<?php echo $ORDER_ID; ?>" />
		<input type="hidden" name="stock_code" value="<?php echo $STOCK_CODE; ?>" />
		<input type="hidden" name="type" value="<?php echo $ITEM_TYPE; ?>" />
		<?php
		echo draw_select('cancel_code', $REASON_LIST);
?>
		<input type="submit" value="go" />
		[<a href="/order/change/<?php echo $ORDER_ID; ?>">cancel</a>]
		

	</fieldset>
</form>
