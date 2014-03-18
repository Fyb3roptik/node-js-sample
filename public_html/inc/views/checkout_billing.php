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
		if('new' == $(this).val()) {
			$("input[name='billing[nickname]']").defaultValue('New Address Name');
		}
		copyBillingToShipping();
	});

	$("#saved_address").change(function() {
		setAddress('shipping', address_book[$(this).val()]);
		if('new' == $(this).val()) {
			$("input[name='shipping[nickname]']").defaultValue('New Address Name');
		}
	});

	$("#cc_nickname").focus(function() {
		$("#cc_nickname").val("");
	});

	$("#cc_nickname").blur(function() {
		if($("#cc_nickname").val() == "") {
			$("#cc_nickname").val("Default Credit Card");
		}
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
	$("#numeric_cc").numeric();

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


<div class="billing_wrap">

<div class="billing_wrap_right">
<!-- Credit Card info -->
<div class="billing_right">
<div class="creditCard_top"><img src="/images/creditCard_back_top.png"></div>
<div class="creditCard_mid">
<div class="creditCard_form">

<input type="hidden" name="payment_terms" value="cc" />
<table width="270" border="0" cellpadding="0" cellspacing="0" class="BillTable">
    <?php if(true == ($USER instanceof Sales_Rep)): ?>
	<?php if("all" == $INVOICE_ALLOWED || "limited" == $INVOICE_ALLOWED): ?>
	<thead>
		<tr>
			<th colspan="2" class="bill_title">
				<?php echo draw_radio('payment_type', 'invoice', false, 'id="inv_payment"'); ?>
				<label for="inv_payment">Payment Option</label>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="leftField">Credit Limit:</td>
			<td class="rightField"><?php echo price_format($CREDIT_LIMIT); ?></td>
		</tr>
		<tr>
			<td class="leftField">Invoice type:</td>
			<td class="rightField"><?php echo draw_select('invoice_term', $INVOICE_OPTIONS); ?></td>
		</tr>
	</tbody>
	<?php endif; ?>
	<?php endif; ?>
	<thead>
		<tr>
			<th colspan="2" class="bill_title">
                <?php if(true == ($USER instanceof Sales_Rep)): ?>
				<?php if("all" == $INVOICE_ALLOWED || "limited" == $INVOICE_ALLOWED): ?>
					<?php echo draw_radio('payment_type', 'cc', true, 'id="cc_payment"'); ?>
				<?php endif; ?>
				<?php endif; ?>
				<label for="cc_payment">Credit Card</label>
			</th>
		</tr>
	</thead>
	<tbody>
		
		<tr>
			<th class="leftField">Saved Card </th>
			<td class="rightField">
				<?php echo draw_select('selected_cc', $CC_OPTIONS, 0, 'id="selected_cc" class="textfield2"'); ?>
			</td>
		</tr>
		<tr>
			<th class="leftField">Card Type&nbsp;<span class="required">*</span></th>
			<td class="rightField">
				<select name="cc[type]" class="textfield2">
					<option selected="selected">Visa</option>
					<option>Master card</option>
					<option>American express</option>
				</select>
			</td>
		</tr>
		<tr>
			<th class="leftField">Card Number&nbsp;<span class="required">*</span></th>
			<td class="rightField"><input type="text" id="numeric_cc" name="cc[number]" class="textfield" /></td>
		</tr>
		<tr>
			<th class="leftField">Name on card&nbsp;<span class="required">*</span></th>
			<td class="rightField"><input type="text" name="cc[name]"  class="textfield"/></td>
		</tr>
		<tr>
			<th class="leftField">Security Code&nbsp;<span class="required">*</span></th>
			<td class="rightField"><input type="text" name="cc[ccv]" size="5" class="normal" />
			<img align="absmiddle" src="/images/question_mark.jpg" width="14" height="14" alt="3 digit code from back of card" title="3 digit code from back of card" class="tooltip" /></td>
		</tr>
		<tr>
			<th class="leftField">Expiration&nbsp;<span class="required">*</span></th>
			<td class="rightField">
				<?php
				$months = array('0' => 'Month', '1' => '01','2' => '02','3' => '03','4' => '04','5' => '05','6' => '06','7' => '07','8' => '08','9' => '09','10' => '10','11' => '11','12' => '12');
				echo draw_select('cc[exp_month]', $months, null, 'id="cc_exp_month" class="textfield2"') . ' / ';
				$year_range = range(date('Y'), date('Y')+7);
				$year_options = array();
				foreach($year_range as $year) {
					$year_options[$year] = $year;
				}
				echo draw_select('cc[exp_year]', $year_options, null, 'id="cc_exp_year" class="textfield2"');
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bigButton">
				<input id="SaveCard" name="save_cc" type="checkbox" value="1" />
				<label for="SaveCard">Save Card For Future Purchase?</label>
			</td>
		</tr>
		
		<tr id="nickname_row">
			<th class="leftField">Card Save Name </th>
			<td><input type="text" id="cc_nickname" name="cc[nickname]" class="textfield" value="Default Credit Card" /></td>
		</tr>
	</tbody>
</table>
<!-- endcreditCard_form--></div>
<!--end creditCard_mid--></div>
<div class="creditCard_top"><img src="/images/creditCard_back_bttm.png"></div>
<!-- end billing_right --></div>
<br clear="all" />

<!-- Big Button -->
<div class="billing_right">
<div class ="bigButton"><input type="image" name="Submit" value="Submit" src="/images/order_preview_btn.jpg" /></div>
<!-- end billing_right --></div>
<br clear="all" />

<?php if(true == ($USER instanceof Sales_Rep)): ?>
<!-- ship complete-->
<div class="billing_right">
<input id="ShipComplete" type="checkbox" checked="checked" name="ship_complete" value="1" />
<label for="ShipComplete"><strong>&nbsp;&nbsp;Ship Complete</strong></label>
<!-- end billing_right --></div>
<br clear="all" />
<?php endif; ?>

<!-- PO Number -->
<div class="billing_right">
<div><strong>PO Number</strong> (optional)<br>
<input type="text" class="textfield_e" name="billing_po_number" value="<?php echo $PO_NUMBER; ?>" /></div>
<!-- end billing_right --></div>
<br clear="all" />

<!-- Order Notes -->
<div class="billing_right">
<strong>Special Instructions</strong> <span class="order_notes_counter"></span>
<br />
<textarea cols="36" rows="6" id="order_notes" name="order_notes" class="notes"><?php echo htmlspecialchars(nl2br(stripslashes($CHECKOUT->note))); ?></textarea>
<?php if(true == ($USER instanceof Sales_Rep)): ?>
<br /><br />
<strong>Sales Notes</strong> <span class="sales_notes_counter"></span>
<br />
<textarea cols="36" rows="6" id="sales_notes" name="sales_notes" class="notes"><?php echo htmlspecialchars(nl2br(stripslashes($CHECKOUT->sales_note))); ?></textarea>
<?php endif; ?>

<!-- end billing_right --></div>
<!-- end billing_wrap_right --></div>





<div class="billing_wrap_left">

<!-- Billing Address -->
<div class="billing_left">

<div class="billing_bar">
<div class="billing_bar_left">Billing Addresses</div>
<div class="billing_bar_right"><input type="text" class="textfield" name="billing[nickname]" value="<?php echo $billing['nickname'];?>" />&nbsp;<img align="absmiddle" src="/images/question_mark.jpg" width="14" height="14" alt="Create a name for this address to help remember on future checkouts." title="Create a name for this address to help remember on future checkouts." class="tooltip" /></div>
<div class="billing_bar_right"><?php echo draw_select('billing[saved_billing_address]', $ADDRESS_OPTIONS, $billing['address_id'], 'id="saved_billing_address" class="textfield2"', 'Saved Address'); ?></div>
<!-- end billing_bar --></div>
<br clear="all" />
<div class="billing_form_wrap">
<div class="billing_form">
Name<span class="required">*</span><br><input type="text" name="billing[name]" class="textfield" value="<?php echo $billing['name']; ?>" />
<!-- end billing_form --></div>
<div class="billing_form">
Company Name<br><input type="text" name="billing[company]" class="textfield" value="<?php echo $billing['company']; ?>"/>
<!-- end billing_form --></div>
<div class="billing_form">
Phone<span class="required">*</span><br><input type="text" id="numeric" name="billing[phone]" class="textfield" value="<?php echo $billing['phone']; ?>" size="12"/>
<!-- end billing_form --></div>
<div class="billing_form0">
Ext<br><input type="text" id="numeric2" name="billing[ext]" class="textfield" value="<?php echo $billing['ext']; ?>" size="4"/>
<!-- end billing_form --></div>
<!-- end billing_form_wrap --></div>
<br clear="all" />

<div class="billing_form_wrap">
<div class="billing_form">
Street Address 1<span class="required">*</span><br><input type="text" name="billing[address_1]" class="textfield" value="<?php echo $billing['address_1']; ?>"/>
<!-- end billing_form --></div>
<div class="billing_form">
Address 2<br><input type="text" name="billing[address_2]" class="textfield" value="<?php echo $billing['address_2']; ?>"/>
<!-- end billing_form --></div>
<div class="billing_form0">
Address 3<br><input type="text" name="billing[address_3]" class="textfield" value="<?php echo $billing['address_3']; ?>"/>
<!-- end billing_form --></div>
<!-- end billing_form_wrap --></div>
<br clear="all" />

<div class="billing_form_wrap">
<div class="billing_form">
City<span class="required">*</span><br><input type="text" name="billing[city]" class="textfield" value="<?php echo $billing['city']; ?>" />
<!-- end billing_form --></div>
<div class="billing_form">
State<span class="required">*</span><br><input type="text" name="billing[state]" class="textfield" value="<?php echo $billing['state']; ?>" id="billing_state_text" />
<?php echo draw_select('billing[state]', get_states(), $billing['state'], 'id="billing_state" class="textfield2"'); ?>
<!-- end billing_form --></div>
<div class="billing_form">
Zip Code<span class="required">*</span><br><input type="text" name="billing[zip_code]" class="textfield" value="<?php echo $billing['zip_code']; ?>" size="6"/>
<!-- end billing_form --></div>
<div class="billing_form0">
Country<span class="required">*</span><br><?php echo draw_select('billing[country]', $COUNTRY_LIST, $billing['country'], 'id="billing_country" class="textfield2"'); ?>
<!-- end billing_form --></div>
<!-- end billing_form_wrap --></div>
<br clear="all" />


<!-- end billing_left --></div>
<br clear="all" />

<!-- Shipping Address -->
<div class="billing_left">
<div class="billing_bar">
<div class="billing_bar_left">Shipping Addresses</div>
<div class="billing_bar_right"><input type="text" class="textfield" name="shipping[nickname]" value="<?php echo $shipping['nickname'];?>" />&nbsp;<img align="absmiddle" src="/images/question_mark.jpg" width="14" height="14" alt="Create a name for this address to help remember on future checkouts." title="Create a name for this address to help remember on future checkouts." class="tooltip" /></div>
<div class="billing_bar_right"><?php echo draw_select('shipping[saved_shipping_address]', $ADDRESS_OPTIONS, $shipping['address_id'], 'id="saved_address" class="textfield2"', 'Saved Address'); ?></div>
<!-- end billing_bar --></div>
<br clear="all" />

<div class="billing_form_wrap">
<div class="billing_form">
<input id="SameBilling" name="billing_is_shipping" type="checkbox" value="1" />
<label for="SameBilling"><strong>Same as billing</strong></label>
<!-- end billing_form --></div>
<!-- end billing_form_wrap --></div>
<br clear="all" />

<div id="shipping_hide">

<div class="billing_form_wrap">
<div class="billing_form">
Name<span class="required">*</span><br><input type="text" name="shipping[name]" class="textfield" value="<?php echo $shipping['name']; ?>" />
<!-- end billing_form --></div>
<div class="billing_form">
Company Name<br><input type="text" name="shipping[company]" class="textfield" value="<?php echo $shipping['company']; ?>"/>
<!-- end billing_form --></div>
<div class="billing_form">
Phone<span class="required">*</span><br><input type="text" id="numeric" name="shipping[phone]" class="textfield" value="<?php echo $shipping['phone']; ?>" size="12"/>
<!-- end billing_form --></div>
<div class="billing_form0">
Ext<br><input type="text" id="numeric2" name="shipping[ext]" class="textfield" value="<?php echo $shipping['ext']; ?>" size="4"/>
<!-- end billing_form --></div>
<!-- end billing_form_wrap --></div>
<br clear="all" />

<div class="billing_form_wrap">
<div class="billing_form">
Street Address 1<span class="required">*</span><br><input type="text" name="shipping[address_1]" class="textfield" value="<?php echo $shipping['address_1']; ?>"/>
<!-- end billing_form --></div>
<div class="billing_form">
Address 2<br><input type="text" name="shipping[address_2]" class="textfield" value="<?php echo $shipping['address_2']; ?>"/>
<!-- end billing_form --></div>
<div class="billing_form0">
Address 3<br><input type="text" name="shipping[address_3]" class="textfield" value="<?php echo $shipping['address_3']; ?>"/>
<!-- end billing_form --></div>
<!-- end billing_form_wrap --></div>
<br clear="all" />

<div class="billing_form_wrap">
<div class="billing_form">
City<span class="required">*</span><br><input type="text" name="shipping[city]" class="textfield" value="<?php echo $shipping['city']; ?>" />
<!-- end billing_form --></div>
<div class="billing_form">
State<span class="required">*</span><br><input type="text" name="shipping[state]" class="textfield" value="<?php echo $shipping['state']; ?>" id="shipping_state_text" />
<?php echo draw_select('shipping[state]', get_states(), $shipping['state'], 'id="shipping_state" class="textfield2"'); ?>
<!-- end billing_form --></div>
<div class="billing_form">
Zip Code<span class="required">*</span><br><input type="text" name="shipping[zip_code]" class="textfield" value="<?php echo $shipping['zip_code']; ?>" size="6"/>
<!-- end billing_form --></div>
<div class="billing_form0">
Country<span class="required">*</span><br><?php echo draw_select('shipping[country]', $COUNTRY_LIST, $shipping['country'], 'id="shipping_country" class="textfield2"'); ?>
<!-- end billing_form --></div>
<!-- end billing_form_wrap --></div>
<br clear="all" />

<div class="billing_form_wrap">
<div class="billing_form">
Shipping Email Address<br><input type="text" name="shipping[email_address]" class="textfield_e" value="<?php echo $CHECKOUT->shipping_email; ?>" />
<!-- end billing_form --></div>
<!-- end billing_form_wrap --></div>

<!--end shipping_hide--></div>
<br clear="all" />


<!-- end billing_wrap_left --></div>

</form>
<br clear="all" />
<div class="required_text"> fields marked <span class="required">*</span> are required</div>
<!-- end billing_wrap--></div>
