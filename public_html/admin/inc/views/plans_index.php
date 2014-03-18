<a href="/admin/plans/create/">Create New Plan</a>
<br />
<br />
<fieldset>
<legend>DJ Plans:</legend>
<br />
<br />
<table>
<thead>
	<tr>
		<th width="30%">Name</th>
		<th></th>
		<th>Price</th>
		<th>Active</th>
	</tr>
</thead>
<tbody>
<?php foreach($DJ_PLANS as $DP): ?>
<?php
$PLAN = $DP;
?>
<tr>
	<td><a href="/admin/plans/edit/<?php echo $PLAN->ID; ?>"><?php echo $PLAN->name; ?></a></td>
	<td><a href="/admin/plans/permissions/<?php echo $PLAN->ID; ?>">Edit Permissions</a></td>
	<td><?php echo price_format($PLAN->price); ?></td>
	<td><?php if(true == $PLAN->active): ?>Yes<?php else: ?>No<?php endif; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</fieldset>