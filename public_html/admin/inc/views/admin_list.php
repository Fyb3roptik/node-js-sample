<h2>Manage Administrators</h2>
<div class="messages"><?php echo $MS->messages('admin'); ?></div>
<p><a href="<?php echo LOC_ADMIN_MANAGE; ?>newAdmin/">Add new Admin</a></p>
<?php
if(count($ADMIN_LIST) > 0) {
?>
<table>
	<tr>
		<th>Name</th>
	</tr>
	<?php
	foreach($ADMIN_LIST as $i => $A) {
	?>
	<tr>
		<td>
			<a href="/admin/admin/edit/<?php echo $A->ID; ?>"><?php echo $A->name; ?></a>
		</td>
		<td>
			<a href="/admin/admin/editPermissions/<?php echo $A->ID; ?>">edit permissions</a>
		</td>
		<td>
			<a href="/admin/admin/confirmDelete/<?php echo $A->ID; ?>">delete</a>
		</td>
	</tr>
	<?php
	}
	?>
</table>
<?php
}
?>