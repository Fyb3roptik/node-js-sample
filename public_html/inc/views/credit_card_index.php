<script type="text/javascript">
$(document).ready(function() {
	$("a.edit_cc, a.new_cc").click(function() {
		$("#payment_info_holder").load($(this).attr('href'));
		return false;
	});
});
function delete_card(card_id) {
	var confirm_delete = confirm("Are you sure you want to delete this credit card?");
	if(true == confirm_delete) {
		var post_data = {
			"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
			"credit_card_id" : card_id
		}

		$.ajax({
			type: "POST",
			url: '/credit_card/drop/',
			async: false,
			data : post_data,
			dataType: "json",
			success : function(data, status) {
				if(true == data['success']) {
					$("#payment_info_holder").load('/myaccount/payment_info/');
				}
			}
		});
	}
	return false;
}
</script>

<div class="contentbox">
<?php if(count($CC_LIST) > 0): ?>
<div class="add_new_cc"><a href="/myaccount/payment_info/new/" class="new_cc">Add New Credit Card</a></div>
	<?php foreach($CC_LIST as $CC): ?>
	<div class="credit_card">
		<span class="functions">
			<a href="/myaccount/payment_info/edit/<?php echo $CC->ID; ?>" class="edit_cc">edit</a> |
			<a href="#" onclick="delete_card(<?php echo $CC->ID; ?>)">remove</a>
		</span>
		<span class="cc_nickname"><?php echo $CC->nickname; ?></span>
		<div class="cc_details">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td><?php echo $CC->name; ?></td>
				</tr>
				<tr>
					<td><?php echo obfuscate_cc_number($CC->getPlainNumber()); ?></td>
				</tr>
				<tr>
					<td><?php echo $CC->getPlainMonth() . ' / ' . $CC->getPlainYear(); ?></td>
				</tr>
			</table>
		</div>
	</div>
	<?php endforeach; ?>
<?php else: ?>
	<div>
		You don't have any payment information saved. Maybe you would like to 
		<a class="new_cc" href="/myaccount/payment_info/new/">add some?</a>
	</div>
<?php endif; ?>
</div>
