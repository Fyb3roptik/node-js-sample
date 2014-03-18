<script type="text/javascript">
$(document).ready(function() {
	$("utility_mod_list").zebra();
	$("#mod_form_holder").hide();
	$('a.drop_mod').click(function() {
		var confirm_drop = confirm("Are you sure you want to delete this mod?");
		var mod_id = $(this).prev('input').val();
		var $parent = $(this).parent('td').parent('tr');
		if(true == confirm_drop) {
			var post_data = { "mod_id" : mod_id }
			$.post('/admin/ubd/dropMod/', post_data, function(data) {
				if(true == data['success']) {
					$parent.fadeOut(1000, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});

	$('a.drop_program').click(function() {
		var confirm_drop = confirm("Are you sure you want to delete this program?");
		var mod_id = $(this).prev('input').val();
		var $parent = $(this).parent('td').parent('tr');
		if(true == confirm_drop) {
			var post_data = { "program_id" : mod_id }
			$.post('/admin/ubd/dropProgram/', post_data, function(data) {
				if(true == data['success']) {
					$parent.fadeOut(1000, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});

	$('a.new_mod').click(function() {
		$("#mod_form_holder").load('/admin/ubd/newMod/', null, function() {
			$(this).show();
		});
		return false;
	});

	$('a.edit_mod, a.edit_program').click(function() {
		var url = $(this).attr('href');
		$("#mod_form_holder").load(url, null, function() {
			$(this).show();
		});
		return false;
	});

	$('a.new_program').click(function() {
		$('#mod_form_holder').load('/admin/ubd/newProgram/', null, function() {
			$(this).show();
		});
		return false;
	});

	$("#import_csv").click(function() {
		window.open('/admin/ubd/importModsForm/', 'mod_importer', "menubar=no,width=600,height=150,toolbar=no");
		return false;
	});
});
</script>
<h2>Utility Buydown Modifications</h2>
<p>
	<a href="#" class="new_program">Create New Mod Program</a> |
	<a href="#" class="new_mod">Create New Mod</a> |
	<a href="/admin/ubd/exportMods">Export Mods</a> |
	<a href="#" id="import_csv">Import Mods</a>
</p>
<div id="messages"><?php echo $MS->messages(); ?></div>
<div id="mod_form_holder">&nbsp;</div>
<div id="messages"><?php echo $MS->messages(); ?></div>

<h3>Program Details</h3>
<?php if(count($PROGRAM_LIST) > 0): ?>
<table>
	<thead>
		<tr>
			<th>Sponsor</th>
			<th>Program Code</th>
			<th>Savings Text</th>
			<th>Products</th>
			<th>Start Date</th>
			<th>End Date</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($PROGRAM_LIST as $ump): ?>
		<tr>
			<td><?php echo $ump->sponsor; ?></td>
			<td><?php echo $ump->program_code; ?></td>
			<td><?php echo $ump->savings_text; ?></td>
			<td><?php echo $ump->products; ?></td>
			<td><?php echo $ump->start_date; ?></td>
			<td><?php echo $ump->end_date; ?></td>
			<td>
				<a href="/admin/ubd/editProgram/<?php echo $ump->ID; ?>" class="edit_program">edit</a>
			</td>
			<td>
				<input type="hidden" name="ump_id[]" value="<?php echo $ump->ID; ?>" />
				<a href="#" class="drop_program">delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p>No programs have been defined. Please <a href="#" class="new_program">define a new program</a>.</p>
<?php endif; ?>

<h3>Mod Details</h3>
<?php if(count($MOD_LIST) > 0): ?>
<?php echo $PK_LINKS; ?>
<table id="utility_mod_list">
	<thead>
		<tr>
			<th>Zip Code</th>
			<th>Program</th>
			<th>Stock Code</th>
			<th>Mod Type</th>
			<th>Discount Price</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($MOD_LIST as $mod): ?>
		<tr>
			<td><?php echo $mod->zip_code; ?></td>
			<td><?php echo $mod->program_id; ?></td>
			<td><?php echo $mod->stock_code; ?></td>
			<td><?php echo $mod->mod_type; ?></td>
			<td><?php echo price_format($mod->price); ?></td>
			<td>
				<a class="edit_mod" href="/admin/ubd/editMod/<?php echo $mod->ID; ?>">edit</a>
			</td>
			<td>
				<input type="hidden" name="mod_id[]" value="<?php echo $mod->ID; ?>" />
				<a href="#" class="drop_mod">delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $PK_LINKS; ?>
<?php else: ?>
<p>No utility mods found. Please <a href="#" class="new_mod">add one</a>.</p>
<?php endif; ?>
