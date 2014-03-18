<script type="text/javascript">
$(document).ready(function() {
	$(".striped-table:odd").each(function() {
		$(this).css("background-color", "#CCC").nextUntil(".striped-table").css("background-color", "#CCC");
	});
	$(".striped-table:even").each(function() {
		$(this).css("background-color", "#FEFEDC").nextUntil(".striped-table").css("background-color", "#FEFEDC");
	});
	$(".striped-table").mouseover(function() {
		$(this).css("background-color", "#898989").nextUntil(".striped-table").css("background-color", "#898989");
	});
	$(".striped-table").mouseout(function() {
		$(".striped-table:odd").each(function() {
			$(this).css("background-color", "#CCC").nextUntil(".striped-table").css("background-color", "#CCC");
		});
		$(".striped-table:even").each(function() {
			$(this).css("background-color", "#FEFEDC").nextUntil(".striped-table").css("background-color", "#FEFEDC");
		});
	});
	$(".remove-customer").click(function() {
		var customer_id = this.id;
		
		if(confirm("Are you sure you want to delete this customer?")) {
			var post_data = {"customer_id" : customer_id}
			$.post("/admin/customer/remove/", post_data, function(data) {
				if(true == data['success']) {
					$("#customer-"+data['customer_id']).fadeOut();
				}
			}, "json");
		}
	});
	$("#date_range_min").datepicker({ dateFormat:"yy-mm-dd" });
	$("#date_range_max").datepicker({ dateFormat:"yy-mm-dd" });
});
</script>
<?php echo $MS->messages('customer'); ?> 
<h2>Manage Customers</h2>
<?php
if(count($CUSTOMER_LIST) > 0) { 
?>
<form id="customer_search_form" action="" method="get">
	<fieldset>
		<table>
			<tr>
				<td>
					Customer Email:
					<br />
					<input type="text" name="customer_email" value="<?php echo get_var('customer_email'); ?>" />
				</td>
				<td>
					Customer Name:
					<br />
					<input type="text" name="customer_name" value="<?php echo get_var('customer_name'); ?>" />
				</td>
				<td>
					Date Range:
					<br />
					<input type="text" name="date_range_min" id="date_range_min" value="<?php if(get_var('date_range_min') != ""): ?><?php echo urldecode(get_var('date_range_min')); ?><?php else: ?>From...<?php endif; ?>" />&nbsp;&nbsp;&nbsp;<input type="text" name="date_range_max" id="date_range_max" value="<?php if(get_var('date_range_max') != ""): ?><?php echo urldecode(get_var('date_range_max')); ?><?php else: ?>To...<?php endif; ?>" />
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Search Customers" /></td>
			</tr>
		</table>
	</fieldset>
</form>
<div class="totals">
	<strong><?php echo $DJS; ?> DJ's &nbsp;&nbsp; <?php echo $SINGERS; ?> Singers &nbsp;&nbsp;<?php echo $TOTAL; ?> Customers Total</strong>&nbsp;&nbsp;<i>(<?php echo $TOTAL_SEARCH; ?> Customers in this search)</i>
</div>
<div class="pk_links">
	<?php echo $PK_LINKS; ?>
</div>
<table>
	<thead>
		<tr>
			<th><a href="/admin/customer/?page=<?php echo $page; ?>&sort=name&dir=<?php echo $new_dir; ?>">Name</a></th>
			<th><a href="/admin/customer/?page=<?php echo $page; ?>&sort=stage_name&dir=<?php echo $new_dir; ?>">Stage Name</a></th>
			<th><a href="/admin/customer/?page=<?php echo $page; ?>&sort=email&dir=<?php echo $new_dir; ?>">Email</a></th>
			<th><a href="/admin/customer/?page=<?php echo $page; ?>&sort=username&dir=<?php echo $new_dir; ?>">DJ Name</a></th>
			<th><a href="/admin/customer/?page=<?php echo $page; ?>&sort=has_playlist&dir=<?php echo $new_dir; ?>">Songbook</a></th>
			<th><a href="/admin/customer/?page=<?php echo $page; ?>&sort=user_type&dir=<?php echo $new_dir; ?>">Account Type</a></th>
			<th><a href="/admin/customer/?page=<?php echo $page; ?>&sort=date_registered&dir=<?php echo $new_dir; ?>">Date Registered</a></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($CUSTOMER_LIST as $customer_id) {
		$C = new Customer($customer_id['customer_id']);
?>
		<tr class="striped-table" id="customer-<?php echo $C->ID; ?>">
			<td>
				<a href="/admin/customer/edit/<?php echo $C->ID; ?>"><?php echo $C->name; ?></a>
			</td>
			<td><?php echo $C->stage_name; ?></td>
			<td><?php echo $C->email; ?></td>
			<td><?php echo $C->username; ?></td>
			<td><?php if($C->has_playlist == "0"): ?>No<?php else: ?>Yes<?php endif; ?></td>
			<td><?php echo ucwords($C->user_type); ?></td>
			<td><?php echo date("M j, Y g:i A", strtotime($C->date_registered)); ?></td>
			<td><a class="remove-customer" id="<?php echo $C->ID; ?>" href="#">Remove</a></td>
		</tr>
<?php
	}
	?>
	</tbody>
</table>
<div class="pk_links">
	<?php echo $PK_LINKS; ?>
</div>
<?php
} else {
?>
<p>No customers here. Strange, that.</p>
<?php
}
?>
