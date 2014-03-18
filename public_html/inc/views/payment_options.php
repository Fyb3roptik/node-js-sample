<script type="text/javascript">
$(document).ready(function() {
	$("#payment_options").change(function() {
		var new_option = parseInt($(this).val());
		if(0 == new_option) {
			$("#new_card").show();
			$("#existing_card").hide();
		} else {
			draw_payment_option(new_option);
		}
	});

	$("input[name='save_new_cc']").change(function() {
		var checked = $(this).attr('checked');
		var $saved_card_field = $("#save_card_nickname");
		if(true == checked) {
			$saved_card_field.show();
		} else {
			$saved_card_field.hide();
		}
	});

	$("input[name='save_new_cc']").click(function() {
		$(this).change();
	});

	$("#payment_options").change();
	$("#save_card_nickname").hide();
});

function draw_payment_option(option_id) {
	$("#new_card").hide();
	var cc_info = get_cc_info(option_id);
	$("#card_number").html(cc_info['number']);
	var card_expires = cc_info['expires_month'] + " / " + cc_info['expires_year'];
	$("#card_expires").text(card_expires);
	$("#existing_card").show();
	$("#card_name").text(cc_info['name']);
	$("input[name='old_card[ccv]']").val("");
}

function get_cc_info(option_id) {
	var post_data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
		"credit_card_id" : option_id}
	var cc_info = null;
	$.ajax({
		async : false,
		url : "/credit_card/jsonDetails/",
		data : post_data,
		type : "POST",
		dataType : "json",
		success : function(data, message) {
			cc_info = data;
		}
	});
	return cc_info;
}
</script>
<?php if(count($SALES_REP_OPTIONS) > 0): ?>
<script type="text/javascript">
$(document).ready(function() {
	$("#cc_container").hide();

	$("#payment_terms").change(function() {
		var new_val = $(this).val();
		if('cc' == new_val) {
			$("#cc_container").show();
		} else {
			$("#cc_container").hide();
		}
	});

	$("#payment_terms").change();
});
</script>
<fieldset>
	<?php echo draw_select('payment_terms', $SALES_REP_OPTIONS, 'cc', 'id="payment_terms"'); ?>
	<br />
</fieldset>
<?php endif; ?>
<div id="cc_container">
<fieldset>
Choose one:
<?php echo draw_select('payment_options', $CC_OPTIONS, 0, 'id="payment_options"'); ?>
</fieldset>
<br />
<div id="cc_details">
<div id="existing_card">
	<table>
		<tr>
			<td>Name on Card</td>
			<td id="card_name"></td>
		</tr>
		<tr>
			<td>Number</td>
			<td id="card_number"></td>
		</tr>
		<tr>
			<td>Expires</td>
			<td id="card_expires"></td>
		</tr>
		<tr>
			<td>Security Code</td>
			<td><input type="text" size="3" name="old_card[ccv]" value="" /></td>
		</tr>
	</table>
</div>
<table width="100%" id="new_card">
	<tr>
		<td>
			<label for="cc_name">Cardholder's Name</label>
		</td>
		<td>
			<input type="text" id="cc_name" name="cc[name]" />
		</td>
	</tr>
	<tr>
		<td>
			<label for="cc_number">Credit Card Number</label>
		</td>
		<td>
			<input type="text" id="cc_number" name="cc[number]" value="" />
		</td>
	</tr>
	<tr>
		<td>
			<label for="cc_ccv">Security Code</label>
		</td>
		<td>
			<input type="text" id="cc_ccv" size="3" name="cc[ccv]" />
		</td>
	</tr>
	<tr>
		<td>Exp. Date</td>
		<td>
			<?php
			$months = array('0' => 'Month', '1' => '01','2' => '02','3' => '03','4' => '04','5' => '05','6' => '06','7' => '07','8' => '08','9' => '09','10' => '10','11' => '11','12' => '12');
			echo draw_select('cc[exp_month]', $months, null, 'id="cc_exp_month"') . ' / ';
			$year_range = range(date('Y'), date('Y')+7);
			$year_options = array();
			foreach($year_range as $year) {
				$year_options[$year] = $year;
			}
			echo draw_select('cc[exp_year]', $year_options, null, 'id="cc_exp_year"');
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="checkbox" name="save_new_cc" value="1" />
			Save card?
		</td>
	</tr>
	<tr id="save_card_nickname">
		<td>Nickname</td>
		<td><input type="text" name="new_cc_nickname" value="" /></td>
	</tr>
</table>
</div>
</div>
