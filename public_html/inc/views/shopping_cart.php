<div id="cart_holder">
	<?php
	$SCT = new Html_Template('inc/modules/shopping_cart_table.php');
	$SCT->bind('CUSTOMER', $CUSTOMER);
	$SCT->bind('PREV_PAGE', $PREV_PAGE);
	$SCT->bind('MISC_CHARGE_LIST', $MISC_CHARGE_LIST);
	$SCT->render();
	?>
<br clear="all" />
</div>
