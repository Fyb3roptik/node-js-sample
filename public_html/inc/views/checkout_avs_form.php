<script type="text/javascript">
var address_list = [];
<?php foreach($OPTION_LIST as $i => $address): ?>
address_list[<?php echo $i; ?>] = <?php echo json_encode($address); ?>

<?php endforeach; ?>
$(document).ready(function() {
	$("input[name='selected_address']").change(function() {
		var address_id = parseInt($(this).val());
		for(var field_name in address_list[address_id]) {
			var value = address_list[address_id][field_name];
			var input_name = 'shipping[' + field_name + ']';
			$("input[name='" + input_name + "']").val(value);

		}
		$('#avs_form').submit();
	});

	$("input[name='continue_address']").change(function() {
		window.location = '/checkout/shipping';
	});

	$("input[name='reenter_address']").change(function() {
		window.location = '/checkout/billing';
	});

});
</script>
<h2 class="greeting">Please verify your shipping address.</h2>
<br clear="all" />
<?php if($OPTION_LIST[0]['address_1'] != ""): ?><?php endif; ?>
 
<form id="avs_form" action="/checkout/processAvs/" method="post">

<?php foreach($BAD_ADDRESS->dump() as $field => $value): ?>
<input type="hidden" name="shipping[<?php echo $field; ?>]" value="<?php echo $value; ?>" />
<?php endforeach; ?>
<?php if($OPTION_LIST[0]['address_1'] != ""): ?>

<div class="address_option">
<?php foreach($OPTION_LIST as $i => $address): ?>
			<input id="select_address_<?php echo $i; ?>" type="radio" name="selected_address" align="absmiddle" value="<?php echo $i; ?>" />
			<label for="select_address_<?php echo $i; ?>">&nbsp;<strong>Use the corrected Address</strong></label><br />
			<div id="good_address">
			<?php echo $address['address_1']; ?>&nbsp;<?php echo $BAD_ADDRESS->address_2; ?>
			<br />
			<?php echo $address['city']; ?>, <?php echo $address['state']; ?>&nbsp;<?php echo $address['zip_code']; ?>
			</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<br />

<div class="address_option">
<input id="continue_address" type="radio" name="continue_address" align="absmiddle" value="" />
<label for="continue_address">&nbsp;<strong>Continue with Current Address</strong></label><br />
<div id="bad_address">
	<?php echo $BAD_ADDRESS->name; ?><br />
	<?php echo $BAD_ADDRESS->address_1 . ' ' . $BAD_ADDRESS->address_2; ?><br />
	<?php echo $BAD_ADDRESS->city; ?>, <?php echo $BAD_ADDRESS->state; ?>
	<?php echo $BAD_ADDRESS->zip_code; ?>
</div>
</div>

<br />
<div class="address_option">
<input id="reenter_address" type="radio" name="reenter_address" align="absmiddle" value="" />
<label for="reenter_address">&nbsp;<strong>Re-enter Shipping Address</strong></label><br />
</div>

</form>
<br clear="all" />
<br clear="all" />
<br clear="all" />
<p>Address validation powered by <img src="/images/16pxh_FedEx_logo.jpg" align="bottom" height="16" width="60"></p>
<p><strong>NOTICE:</strong><br>Fedex assumes no liability for the information provided by the address validation functionality.<br>The address validation functionality does not support the identification or verification of occupants at an address.</p>
