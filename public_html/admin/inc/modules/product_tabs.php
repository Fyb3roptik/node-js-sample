<fieldset>
	<legend>"Accordion" Tabs (<a href="/admin/tab/new/<?php echo $P->ID; ?>">new</a>)</legend>
	<?php
	$tab_list = $P->getTabs();
	if(count($tab_list) > 0) {
?>
	<strong>Current Tabs</strong>
	<table cellspacing="0" cellpadding="4">
		<thead>
			<tr>
				<th>Title</th>
				<th>Type</th>
				<th>Default View</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach($tab_list as $i => $TAB) {
		?>
			<tr>
				<td class="tab_title">
					<input type="hidden" name="tab_id[]" value="<?php echo $TAB->ID; ?>" />
					"<?php echo $TAB->title; ?>"
				</td>
				<td><?php echo $TAB->type; ?></td>
				<td>
					<?php
					$view = 'closed';
					if(Product_Tab::OPEN == $TAB->default_view) {
						$view = 'open';
					}
					echo $view;
					?>
				</td>
				<td><a href="/admin/tab/edit/<?php echo $TAB->ID; ?>">edit</a></td>
				<td><a href="javascript:void(0);" onclick="delete_tab(<?php echo $TAB->ID; ?>)">delete</a></td>
			</tr>
		<?php
		} ?> </tbody>
	</table>
	<?
	} else {
	?>
	<p>No tabs have been associated with this product. Please add a <a href="/admin/tab/new/<?php echo $P->ID; ?>">new tab</a>.</p>
	<?php
	}
	?>
</fieldset>
