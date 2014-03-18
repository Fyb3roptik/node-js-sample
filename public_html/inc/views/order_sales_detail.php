<?php if(1 == $O->syspro_status || 2 == $O->syspro_status || '' == $O->syspro_status): ?>
	<?php if(1 == intval($CUSTOMER->cancel_permission)): ?>
		<a id="cancel_order" href="/order/cancel/<?php echo $O->ID; ?>">Cancel Order</a>
	<?php endif; ?>
<?php endif; ?>

<?php if(1 == $O->syspro_status || 2 == $O->syspro_status): ?>
	<?php if(1 == intval($CUSTOMER->change_permission)): ?>
| <a id="change_order" href="/order/change/<?php echo $O->ID; ?>">Change Order</a>
	<?php endif; ?>
<?php endif; ?>

<?php if(true == $OH->holdable()): ?>
| <a id="hold_order" href="/order/hold/<?php echo $O->ID; ?>">Hold Order</a>
<?php endif; ?>

<?php if(true == $OH->unholdable()): ?>
| <a id="unhold_order" href="/order/unhold/<?php echo $O->ID; ?>">Unhold Order</a>
<?php endif; ?>
