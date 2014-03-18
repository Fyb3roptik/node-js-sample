<script type="text/javascript">
$(document).ready(function() {
	$("input[name='default_shipping'],input[name='default_billing']").change(function() {
		save_default_addresses();
	});
});

function save_default_addresses() {
	var default_shipping = 0;
	var default_billing = 0;

	default_shipping = parseInt($("input[name='default_shipping'][checked='true']").val());
	default_billing = parseInt($("input[name='default_billing'][checked='true']").val());

	var data = {"action" : "save_addresses",
			"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
			"default_shipping" : default_shipping,
			"default_billing" : default_billing}
	$.post('/address_book.http.php', data, function(data) {

	});
}
</script>
<div class="account_header"><span class="RedText2">My Address Book</span></div>

<div class="contentbox">
	<?php
	if(count($address_book) > 0) {
	?>
	<table width="100%">
		<thead>
			<tr>
				<th>Default<br />Shipping</th>
				<th>Default<br />Billing</th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach($address_book as $address) {
	?>
		<tr>
			<td align="center">
				<?php echo draw_radio('default_shipping', $address['address_id'], (intval($address['address_id']) == intval($CUSTOMER->default_shipping))); ?>
			</td>
			<td align="center">
				<?php echo draw_radio('default_billing', $address['address_id'], (intval($address['address_id']) == intval($CUSTOMER->default_billing))); ?>
			</td>
			<td><?php echo $address['nickname']; ?></td>
			<td><?php echo $address['address_1'] . " " . $address['address_2']; ?></td>
			<td><?php echo $address['city']; ?>, <?php echo $address['state']; ?> <?php echo $address['zip_code']; ?></td>
			<td><a href="/myaccount/addressbook/edit/<?php echo $address['address_id']; ?>/<?php echo convert_for_url($address['nickname']); ?>">edit</a></td>
		</tr>
	<?php
		}
	?>
		</tbody>
	</table>
	<a href="<?php echo LOC_ADDRESS_BOOK_NEW; ?>">Add New Address</a>
	<?php
	} else {
	?>
	You don't have any addresses saved. <a href="<?php echo LOC_ADDRESS_BOOK_NEW; ?>">New Address</a>.
	<?php
	}
	?>
</div>