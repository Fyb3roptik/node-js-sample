<script type="text/javascript">
var address_book = <?php echo json_encode($ADDRESS_DUMP); ?>;
var cc_list = new Array();
<?php foreach($CC_DETAILS as $cc): ?>
cc_list[<?php echo $cc['cc_id']; ?>] = <?php echo json_encode($cc); ?>;
<?php endforeach; ?>

$(document).ready(function() {
	$("#billing_state_text").hide();
	$("#shipping_state_text").hide();

	$("#billing_country").change(function() {
		var country_code = $(this).val();
		var $billing_state_select = $("#billing_state");
		var $billing_state_text = $("#billing_state_text");
		if('USA' !== country_code) {
			//change the state selection from a drop-down
			$billing_state_select.hide();
			$billing_state_text.show();
		} else {
			$billing_state_select.show();
			$billing_state_text.hide();
		}
	});

	$("#shipping_country").change(function() {
		var country_code = $(this).val();
		var $shipping_state_select = $("#shipping_state");
		var $shipping_state_text = $("#shipping_state_text");
		if('USA' !== country_code) {
			//change the state selection from a drop-down
			$shipping_state_select.hide();
			$shipping_state_text.show();
		} else {
			$shipping_state_select.show();
			$shipping_state_text.hide();
		}
	});

	$("#billing_country").change();
	$("#shipping_country").change();

	$("#billing_info_form").submit(function() {
		$('[name="billing[state]"]:not(:visible)').remove();
		$('[name="shipping[state]"]:not(:visible)').remove();
	});

	$("#saved_billing_address").change(function() {
		setAddress('billing', address_book[$(this).val()]);
		copyBillingToShipping();
	});

	$("#saved_address").change(function() {
		setAddress('shipping', address_book[$(this).val()]);
	});

	$("#SameBilling").change(function() {
		if(true == $(this).attr('checked')) {
			setAddress('shipping', getAddress('billing'));
			$('[name^=shipping], #saved_address').each(function() {
				$(this).attr('disabled', true);
			});
		} else {
			$('[name^=shipping], #saved_address').each(function() {
				$(this).attr('disabled', false);
			});
		}
	});

	$("#SameBilling").click(function() {
		$(this).change();
	});

	$('label').click(function() {
		var selector = "#" . $(this).attr('for');
		$(selector).click();
	});

	$('#selected_cc').change(function() {
		var selected_option = parseInt($(this).val());
		if(0 == selected_option) {
			$('[name^=cc]').each(function() {
				$(this).val('').show().parent().children('span').remove();
			});
			$("#SaveCard").change();
		} else {
			$("#nickname_row").show();
			load_cc_info(selected_option);
		}
	});

	$("#SaveCard").change(function() {
		if(true == $(this).attr('checked')) {
			$("#nickname_row").show();
		} else {
			if(0 == $("#selected_cc").val()) {
				$("#nickname_row").hide();
			}
		}
	});

	$("#SaveCard").click(function() {
		$(this).change();
	});

	$('.tooltip').tooltip();

	$("#schedule_holder").hide();

	$("#numeric").numeric();
	$("#numeric2").numeric();
	$("#numeric3").numeric();
	$("#numeric4").numeric();

	var default_date = null;

	<?php if(false == is_null($CHECKOUT->getSchedule())): ?>
		//the checkout's already got a date scheduled, set that ish up
		default_date = new Date(
			<?php echo $CHECKOUT->getSchedule('Y'); ?>,
			<?php echo $CHECKOUT->getSchedule('n'); ?> - 1,
			<?php echo $CHECKOUT->getSchedule('j'); ?>,
			1);
	<?php endif; ?>

	$("#ship_date").datepicker({ dateFormat: 'mm/dd/yy'});
	$("#order_notes").textlimit('span.order_notes_counter',100);
	$("#sales_notes").textlimit('span.sales_notes_counter',100);

	$("textarea.notes").change(function() {
		var current_val = $(this).val();
		$(this).val(current_val.substring(0,100));
	});
});

function copyBillingToShipping() {
	if(true == $("#SameBilling").attr('checked')) {
		setAddress('shipping', getAddress('billing'));
	}
}

function getAddress(prefix) {
	var address = { }
	var regex = new RegExp(prefix + '\\[(.+)+\\]');
	$("[name^=" + prefix + "]:visible").each(function() {
		var field_name = $(this).attr('name');
		var matches = regex.exec(field_name);
		if(null !== matches) {
			var field = matches[1];
			address[field] = $(this).val();
		}
	});
	return address;
}

function setAddress(prefix, address) {
	for(var x in address) {
		setAddressField(prefix, x, address[x]);
	}
}

function setAddressField(prefix, field, value) {
	var field_name = prefix + "[" + field + "]";
	var selector = '[name=' + field_name + ']';
	$(selector).val(value).change();
}

function load_cc_info(option_id) {
	cc_info = cc_list[option_id];
	draw_cc_info(cc_info);
}


function draw_cc_info(card) {
	for(var field_name in card) {
		var value = card[field_name];
		var selector = "[name=cc[" + field_name + "]]";
		var $span = $(document.createElement('span'));
		var $input_field = $(selector);
		if('input' == $input_field.tagName()) {
			$(selector).parent().children('span').remove();
			$span.html(value).insertAfter($(selector));
			$input_field.hide();
		} else {
			$input_field.val(value);
		}
	}
}
</script>
<?php echo $MS->messages('checkout_billing'); ?>
<form id="billing_info_form" action="/checkout/processBilling/" method="post">
<input type="hidden" name="payment_terms" value="cc" />
<table border="0" cellpadding="0" cellspacing="0" class="BillTable">
    <?php if(true == is_a($USER, 'Sales_Rep')): ?>
	<?php if("all" == $INVOICE_ALLOWED || "limited" == $INVOICE_ALLOWED): ?>
	<thead>
		<tr>
			<th colspan="2" class="bill_title">
				<?php echo draw_radio('payment_type', 'invoice', false, 'id="inv_payment"'); ?>
				<label for="inv_payment">INVOICE</label>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="left">Credit Limit:</td>
			<td><?php echo price_format($CREDIT_LIMIT); ?></td>
		</tr>
		<tr>
			<td>Invoice type:</td>
			<td><?php echo draw_select('invoice_term', $INVOICE_OPTIONS); ?></td>
		</tr>
	</tbody>
	<?php endif; ?>
	<?php endif; ?>
	<thead>
		<tr>
			<th colspan="2" class="bill_title">
                <?php if(true == is_a($USER, 'Sales_Rep')): ?>
				<?php if("all" == $INVOICE_ALLOWED || "limited" == $INVOICE_ALLOWED): ?>
					<?php echo draw_radio('payment_type', 'cc', true, 'id="cc_payment"'); ?>
				<?php endif; ?>
				<?php endif; ?>
				<label for="cc_payment">CREDIT CARD</label>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2" class="left">
				<input id="SaveCard" name="save_cc" type="checkbox" value="1" />
				<label for="SaveCard">Save Card For Future Purchase?</label>
			</td>
		</tr>
		<tr>
			<th width="100">Saved Card </th>
			<td>
				<?php echo draw_select('selected_cc', $CC_OPTIONS, 0, 'id="selected_cc" class="textfield"'); ?>
			</td>
		</tr>
		<tr id="nickname_row">
			<th>Card Save Name </th>
			<td><input type="text" name="cc[nickname]"  class="textfield"/></td>
		</tr>
		<tr>
			<th>Card Type </th>
			<td>
				<select name="cc[type]" class="textfield">
					<option selected="selected">Visa</option>
					<option>Master card</option>
					<option>American express</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Card Number</th>
			<td><input type="text" name="cc[number]" class="textfield" /></td>
		</tr>
		<tr>
			<th>Name on card </th>
			<td><input type="text" name="cc[name]"  class="textfield"/></td>
		</tr>
		<tr>
			<th>Security Code </th>
			<td><input type="text" name="cc[ccv]" size="5" class="normal" />
			<img align="absmiddle" src="/images/question_mark.jpg" width="14" height="14" alt="3 digit code from back of card" title="3 digit code from back of card" class="tooltip" /></td>
		</tr>
		<tr>
			<th>Expiration</th>
			<td>
				<?php
				$months = array('0' => 'Month', '1' => '01','2' => '02','3' => '03','4' => '04','5' => '05','6' => '06','7' => '07','8' => '08','9' => '09','10' => '10','11' => '11','12' => '12');
				echo draw_select('cc[exp_month]', $months, null, 'id="cc_exp_month" class="normal"') . ' / ';
				$year_range = range(date('Y'), date('Y')+7);
				$year_options = array();
				foreach($year_range as $year) {
					$year_options[$year] = $year;
				}
				echo draw_select('cc[exp_year]', $year_options, null, 'id="cc_exp_year" class="normal"');
				?>
			</td>
		</tr>
	</tbody>
</table>

<table border="0" cellpadding="0" cellspacing="0" class="BillTable">
	<thead>
		<tr>
			<th colspan="2" class="bill_title">Billing Addresses</th>

		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2">
				<input id="RecieveEmail" name="email_subscribe" checked="checked" type="checkbox" value="1" />
				<label for="RecieveEmail">Receive email promotions and coupons?</label>
			</td>
		</tr>
		<tr>
			<th width="100">Saved Address</th>
            <input type="hidden" name="billing[address_id]" value="<?php echo $billing['address_id'];?>" />
			<td>
			<?php echo draw_select('billing[saved_billing_address]', $ADDRESS_OPTIONS, $billing['address_id'], 'id="saved_billing_address" class="textfield"', 'Please Select Address'); ?>
			</td>
		</tr>
        <tr style="border-bottom: 1px solid #BBB;">
            <th>Address Nickname</th>
            <td><input type="text" name="billing[nickname]" value="<?php echo $billing['nickname'];?>" /></td>
        </tr>
        <tr>
        <td style="margin: 0 20px 0 0; padding: 0 0 0 30px;">
            <div style="margin: 20px 0 0 0; border-top: 1px solid #BBB;">&nbsp</div>
        </td>
        <td style="padding: 0 34px 0 0;">
            <div style="margin: 20px 0 0 0; border-top: 1px solid #BBB;">&nbsp</div>
        </td>
        </tr>
        <tr>
			<th>Name</th>
			<td><input type="text" name="billing[name]" value="<?php echo $billing['name']; ?>"  class="textfield"/></td>
		</tr>
		<tr>
			<th>Company Name</th>
			<td><input type="text" name="billing[company]" value="<?php echo $billing['company']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>Street Address 1</th>
			<td><input type="text" name="billing[address_1]" value="<?php echo $billing['address_1']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>       Address 2</th>
			<td><input type="text" name="billing[address_2]" value="<?php echo $billing['address_2']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>       Address 3</th>
			<td><input type="text" name="billing[address_3]" value="<?php echo $billing['address_3']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>City</th>
			<td><input type="text" name="billing[city]" value="<?php echo $billing['city']; ?>"  class="textfield"/></td>
		</tr>
		<tr>
			<th>State</th>
			<td>
				<input type="text" name="billing[state]" value="<?php echo $billing['state']; ?>" class="textfield" id="billing_state_text" />
				<?php echo draw_select('billing[state]', get_states(), $billing['state'], 'id="billing_state" class="textfield"'); ?>
			</td>
		</tr>
		<tr>
			<th>Zip Code</th>
			<td><input type="text" name="billing[zip_code]" value="<?php echo $billing['zip_code']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>Country</th>
			<td>
				<?php
				echo draw_select('billing[country]', $COUNTRY_LIST, $billing['country'], 'id="billing_country" class="textfield"');
				?>
			</td>
		</tr>
		<tr>
			<th>Phone</th>
			<td><input type="text" id="numeric" name="billing[phone]" value="<?php echo $billing['phone']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>Ext</th>
			<td><input type="text" id="numeric2" name="billing[ext]" value="<?php echo $billing['ext']; ?>" size="5"/></td>
		</tr>
		<tr>
			<th>PO Number (optional)</th>
			<td><input type="text" name="billing_po_number" value="<?php echo $PO_NUMBER; ?>" /></td>
		</tr>
	</tbody>
</table>

<table border="0" cellpadding="0" cellspacing="0" class="BillTable">
	<thead>
		<tr>
			<th colspan="2" class="bill_title">Shipping Addresses</th>
            <input type="hidden" name="shipping[address_id]" value="<?php echo $shipping['address_id'] ?>" />
		</tr>
	</thead>
	<tbody>
		<tr>
			<td height="27" colspan="2" valign="top" >
				<input id="SameBilling" name="billing_is_shipping" type="checkbox" value="1" />
				<label for="SameBilling">Same as billing</label>
			</td>
		</tr>
		<tr>
			<th width="100">Saved Address </th>
			<td>
				<?php echo draw_select('shipping[saved_shipping_address]', $ADDRESS_OPTIONS, $shipping['address_id'], 'id="saved_address" class="textfield"', 'Please Select Address'); ?>
			</td>
		</tr>
        <tr style="border-bottom: 1px solid #BBB;">
            <th>Address Nickname</th>
            <td><input type="text" name="shipping[nickname]" value="<?php echo $shipping['nickname'];?>" /></td>
        </tr>
        <tr>
        <td style="margin: 0 20px 0 0; padding: 0 0 0 30px;">
            <div style="margin: 20px 0 0 0; border-top: 1px solid #BBB;">&nbsp</div>
        </td>
        <td style="padding: 0 34px 0 0;">
            <div style="margin: 20px 0 0 0; border-top: 1px solid #BBB;">&nbsp</div>
        </td>
        </tr>
		<tr>
			<th>Name</th>
			<td><input type="text" name="shipping[name]" value="<?php echo $shipping['name']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<td>Company Name </td>
			<td><input type="text" name="shipping[company]" value="<?php echo $shipping['company']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>Street Address</th>
			<td><input type="text" name="shipping[address_1]" value="<?php echo $shipping['address_1']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>       Address 2</th>
			<td><input type="text" name="shipping[address_2]" value="<?php echo $shipping['address_2']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>       Address 3</th>
			<td><input type="text" name="shipping[address_3]" value="<?php echo $shipping['address_3']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>City</th>
			<td><input type="text" name="shipping[city]" value="<?php echo $shipping['city']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>State</th>
			<td>
				<input type="text" name="shipping[state]" value="<?php echo $billing['state']; ?>" class="textfield" id="shipping_state_text" />
				<?php echo draw_select('shipping[state]', get_states(), $shipping['state'], 'id="shipping_state" class="textfield"'); ?>
			</td>
		</tr>
		<tr>
			<th>Zip Code</th>
			<td><input type="text" name="shipping[zip_code]" value="<?php echo $shipping['zip_code']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>Country</th>
			<td>
				<?php
				echo draw_select('shipping[country]', $COUNTRY_LIST, $shipping['country'], 'id="shipping_country" class="textfield"');
				?>
			</td>
		</tr>
		<tr>
			<th>Email</th>
			<td><input type="text" name="shipping_email" value="<?php echo $CHECKOUT->shipping_email; ?>" class="textfield" /></td>
		</tr>
		<tr>
			<th>Phone</th>
			<td><input type="text" id="numeric3" name="shipping[phone]" value="<?php echo $shipping['phone']; ?>" class="textfield"/></td>
		</tr>
		<tr>
			<th>Ext</th>
			<td><input type="text" id="numeric4" name="shipping[ext]" value="<?php echo $shipping['ext']; ?>" size="5"/></td>
		</tr>
		<?php if(true == is_a($USER, 'Sales_Rep')): ?>
		<tr>
			<th>Ship Date</th>
			<td>
			<input type="text" name="ship_date" id="ship_date" value="<?php echo $CHECKOUT->getSchedule(); ?>" class="textfield" />
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th></th>
			<td><input type="image" name="Submit" value="Submit" src="/images/order_preview_btn.jpg" /></td>
		</tr>
	</tbody>
</table>
<strong>Order Notes</strong> <span class="order_notes_counter"></span>
<br />
<textarea cols="40" rows="6" id="order_notes" name="order_notes" class="notes"><?php echo htmlspecialchars(nl2br(stripslashes($CHECKOUT->note))); ?></textarea>
<?php if(true == is_a($USER, 'Sales_Rep')): ?>
<br /><br />
<strong>Sales Notes</strong> <span class="sales_notes_counter"></span>
<br />
<textarea cols="40" rows="6" id="sales_notes" name="sales_notes" class="notes"><?php echo htmlspecialchars(nl2br(stripslashes($CHECKOUT->sales_note))); ?></textarea>
<?php endif; ?>
</form>

<br clear="all" />
<br />
