<script type="text/javascript">
$(document).ready(function() {
	$("#is_manager").change(function() {
		if(true == $(this).attr('checked')) {
			$('#manager_select').hide();
		} else {
			$('#manager_select').show();
		}
	});

	$('#is_manager').change();

	$("#sales_rep_form").submit(function() {
		var valid = true;
		var syspro_rep_id = $("select[name='sales_rep[syspro_id]']").val();
		if("null" == syspro_rep_id) {
			alert("You must select a valid Syspro Sales Rep.");
			valid = false;
		}
		return valid;
	});

	$('a.sales_rep_goals').click(function() {
		$("#goal_form_holder").load('/admin/sales_rep_goals/newGoal/<?php echo $SR->ID; ?>').show();
		return false;
	});

	$('a.edit_goal').click(function() {
		$("#goal_form_holder").load($(this).attr('href')).show();
		return false;
	});

	$('a.delete_goal').click(function() {
		var $row = $(this).parents('tr');
		var sales_rep_goal_id = $(this).prev('input').val();
		if(true == confirm("Are you sure you want to delete this goal?")) {
			var post_data = { "sales_rep_goal_id" : sales_rep_goal_id }
			$.post('/admin/sales_rep_goals/deleteGoal/', post_data, function(data) {
				if(true == data['success']) {
					$row.fadeOut(1000, function() {
						$(this).remove();
					});
				}
			}, "json");
		}
		return false;
	});
});
</script>
<h2>Edit Sales Rep</h2>
<form id="sales_rep_form" action="/admin/salesrep/processRep/" method="post">
	<fieldset>
		<legend>Details</legend>
		<input type="hidden" name="sales_rep_id" value="<?php echo $SR->ID; ?>" />
		<table>
			<tr>
				<td>Name</td>
				<td><input type="text" name="sales_rep[name]" value="<?php echo $SR->name; ?>" /></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input type="text" name="sales_rep[email]" value="<?php echo $SR->email; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				<?php echo draw_checkbox('is_manager', 1, (1 == intval($SR->manager)), 'id="is_manager"'); ?> Is manager?
				</td>
			</tr>
			<tr>
				<td>Syspro Sales Rep:</td>
				<td>
					<?php echo draw_select('sales_rep[syspro_id]', $SYSPRO_REPS, $SR->syspro_id); ?>
				</td>
			</tr>
			<tr id="manager_select">
				<td>Manager:</td>
				<td>
					<?php echo draw_select('sales_rep[manager_id]', $MANAGER_LIST, $SR->manager_id); ?>
				</td>
			</tr>
			<tr>
				<td>Max Payment Term:</td>
				<td>
					<?php echo draw_select('sales_rep[max_payment_term]', $TERM_LIST, $SR->max_payment_term); ?>
				</td>
			</tr>
			<tr>
				<td>Phone</td>
				<td><input type="text" name="sales_rep[phone]" value="<?php echo $SR->phone; ?>" /></td>
			</tr>
			<tr>
				<td>Fax</td>
				<td><input type="text" name="sales_rep[fax]" value="<?php echo $SR->fax; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
<?php
echo draw_checkbox('cancel_permission', 1, (1 == intval($SR->cancel_permission)), 'id="cancel_permission"');
?> <label for="cancel_permission">Can Cancel Orders?</label>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
<?php
echo draw_checkbox('change_permission', 1, (1 == intval($SR->change_permission)), 'id="change_permission"');
?> <label for="change_permission">Can Change Orders?</label>
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>
					<?php echo draw_radio('sales_rep[status]', Sales_Rep::STATUS_ACTIVE, (Sales_Rep::STATUS_ACTIVE == $SR->status), 'id="status_active"'); ?>
					<label for="status_active">Active</label>
					<?php echo draw_radio('sales_rep[status]', Sales_Rep::STATUS_INACTIVE, (Sales_Rep::STATUS_INACTIVE == $SR->status), 'id="status_inactive"'); ?>
					<label for="status_inactive">Inactive</label>
				</td>
			</tr>
			<tr>
				<td>Margin Cap</td>
				<td><input type="text" name="sales_rep[margin_cap]" value="<?php echo floatval($SR->margin_cap); ?>" /></td>
			<tr>
			<?php if(false == $SR->exists()): ?>
			<tr>
				<td>Password</td>
				<td><input type="password" name="new_password" value="" /></td>
			</tr>
			<?php endif; ?>
			<tr>
            	<td>&nbsp;</td>
			</tr>
			<tr>
            	<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
	            	<h3>Goals [<a href="#" class="sales_rep_goals">add new</a>]</h3>
					<div id="goal_form_holder"></div>
					<?php if(count($GOAL) > 0): ?>
					<table>
						<thead>
							<tr>
								<th>Month</th>
								<th>Year</th>
							<tr>
						</thead>
						<tbody>
						<?php foreach($GOAL as $g): ?>
							<tr>
								<td><?php echo date("F", strtotime("01-".$g['month']."-".$g['year'])); ?></td>
								<td><?php echo $g['year']; ?></td>
								<td>
									<a href="/admin/sales_rep_goals/editGoal/<?php echo $g['sales_rep_goal_id']; ?>" class="edit_goal">edit</a>
								</td>
								<td>
									<input type="hidden" name="sales_rep_goal_id" id="sales_rep_goal_id" value="<?php echo $g['sales_rep_goal_id']; ?>" />
									<a href="#" class="delete_goal">delete</a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<?php else: ?>
					<p>No goals have been found. <a href="#" class="new_goal">Add new goal?</a></p>
					<?php endif; ?>
				</td>
			</tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" value="Save" />
					or <a href="<?php echo LOC_SALES_REPS; ?>">Cancel</a>
				</td>
			</tr>
		</table>
	</fieldset>
</form>
