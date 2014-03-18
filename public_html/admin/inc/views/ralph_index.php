<script type="text/javascript">
$(document).ready(function() {
	$('tbody').zebra();
});
</script>
<h2>SO Change History</h2>
<?php if(count($ORDER_CHANGE_LIST) > 0): ?>
<div class="pk_links">
	<?php echo $PK_LINKS; ?>
</div>
<table cellspacing="0" cellpadding="3">
	<thead>
		<tr>
			<th>Order #</th>
			<th>Sales Rep</th>
			<th>Change Type</th>
			<th>Timestamp</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($ORDER_CHANGE_LIST as $O):
			$SR = new Sales_Rep($O->sales_rep);
		?>
		<tr>
			<td>
				<a href="/admin/ralph/view/<?php echo $O->order_id; ?>/"><?php echo $O->order_id; ?></a>
			</td>
			<td><?php echo html_entity_decode($SR->name); ?></td>
			<td><?php echo $O->change_type; ?></td>
			<td><?php echo $O->timestamp; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<div class="pk_links">
	<?php echo $PK_LINKS; ?>
</div>
<?php else: ?>
<div>No orders here.</div>
<?php endif; ?>
