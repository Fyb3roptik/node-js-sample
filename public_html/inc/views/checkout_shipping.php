<script type="text/javascript">
$(document).ready(function() {

	<?php if(false == is_null($SHIPPING_HASH)): ?>
	$("#option_<?php echo $SHIPPING_HASH; ?>").attr('checked', 'checked');
	<?php else: ?>
	$("input[name='shipping_option']:first").attr('checked', 'checked');
	<?php endif; ?>

	$("#checkout_shipping_selection").submit(function() {
		var valid = true;
		var selected_option = null;
		$("input[name='shipping_option']").each(function() {
			if(true == $(this).attr('checked')) {
				selected_option = $(this).val();
			}
		});

		if('dropship' == selected_option) {
			var dropship_price = parseFloat($("input[name='dropship_price']").val());
			if(true == isNaN(dropship_price) || parseFloat(dropship_price) <= 0) {
				alert("Dropship price must be a non-negative number.");
				valid = false;
			}
		}
		return valid;
	});
});
</script>
<br clear="all" />
<div class="shipping_logo_container">
  <div class="shipper_logo"></div>
  <div class="shipper_logo_motto"></div>
</div>
<form id="checkout_shipping_selection" action="/checkout/selectShipping/" method="post">
	<fieldset>
    <?php if("freight" == $OPTION_LIST['89c5bf53d8abe1d5066cad761e0b83f897eb689a']['code']): ?>
     <div class="shipping_option_container">
        <div class="shipping_option_container_left"></div>
        <div class="shipping_option_container_middle">
            <div class="shipping_option_title">
            GROUND
            </div>
            <div class="shipping_option_days">1-7 business days</div>
            <div class="shipping_option_quote"><?php echo price_format($OPTION_LIST['89c5bf53d8abe1d5066cad761e0b83f897eb689a']['cost']); ?>&nbsp;<input type="radio" name="shipping_option" value="89c5bf53d8abe1d5066cad761e0b83f897eb689a" id="option_89c5bf53d8abe1d5066cad761e0b83f897eb689a" /> </div>
            <div class="shipping_option_animal_rabbit"></div>
        </div>
        <div class="shipping_option_container_right"></div>
    </div>
    <?php endif; ?>
    <?php if("freight" != $OPTION_LIST['89c5bf53d8abe1d5066cad761e0b83f897eb689a']['code']): ?>
    <div class="shipping_option_container">
        <div class="shipping_option_container_left"></div>
        <div class="shipping_option_container_middle">
            <div class="shipping_option_title">
            GROUND
            </div>
            <div class="shipping_option_days">1-7 business days</div>
            <br clear="all" />
            <div class="shipping_option_quote"><?php echo price_format($OPTION_LIST['f1c37023448a729d91760a6d28071199bf39b3b9']['cost']); ?>&nbsp;<input type="radio" name="shipping_option" value="f1c37023448a729d91760a6d28071199bf39b3b9" id="option_f1c37023448a729d91760a6d28071199bf39b3b9" /></div>
            <div class="shipping_option_animal_rabbit"></div>
        </div>
        <div class="shipping_option_container_right"></div>
    </div>
    <div class="shipping_option_container_express">
        <div class="shipping_option_container_left"></div>
        <div class="shipping_option_container_middle_express">

            <div class="shipping_option_title_left">
            EXPRESS SAVER
            </div>
            <div class="shipping_option_title_right">
            2 DAY
            </div>
            <br clear="all" />
            <div class="shipping_option_days_left">3 business days</div>
            <div class="shipping_option_days_right">2 business days</div>
            <br clear="all" />
            <div class="shipping_option_quote_left"><?php if($OPTION_LIST['27113a65933876b19f62244f3b2f24ac4c2508e5']['cost'] != ""): ?><?php echo price_format($OPTION_LIST['27113a65933876b19f62244f3b2f24ac4c2508e5']['cost']); ?>&nbsp;<input type="radio" name="shipping_option" value="27113a65933876b19f62244f3b2f24ac4c2508e5" id="option_27113a65933876b19f62244f3b2f24ac4c2508e5" /><?php else: ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?></div>
            <div class="shipping_option_quote_right"><?php if($OPTION_LIST['750a1e13ac0ab489a9ab32ab62a5261c50b021dd']['cost'] != ""): ?><?php echo price_format($OPTION_LIST['750a1e13ac0ab489a9ab32ab62a5261c50b021dd']['cost']); ?>&nbsp;<input type="radio" name="shipping_option" value="750a1e13ac0ab489a9ab32ab62a5261c50b021dd" id="option_750a1e13ac0ab489a9ab32ab62a5261c50b021dd" /><?php else: ?><?php endif; ?></div>
            <div class="shipping_option_separator"></div>

            <br clear="all" />
            <div class="shipping_option_animal_horse"></div>
        </div>
        <div class="shipping_option_container_right"></div>
    </div>
    <div class="shipping_option_container_overnight">
        <div class="shipping_option_container_left"></div>
        <div class="shipping_option_container_middle_overnight">
            <div class="shipping_option_title_overnight">
            OVERNIGHT
            </div>
			<?php if($OPTION_LIST['25e62712a8a3cbdbb557b198c661fdca6af711d1']['cost'] != ""): ?>
            <div class="shipping_option_days_overnight"><strong>Standard</strong><br />Next business afternoon</div>
            <div class="shipping_option_quote_overnight"><?php echo price_format($OPTION_LIST['25e62712a8a3cbdbb557b198c661fdca6af711d1']['cost']); ?>&nbsp;<input type="radio" name="shipping_option" value="25e62712a8a3cbdbb557b198c661fdca6af711d1" id="option_25e62712a8a3cbdbb557b198c661fdca6af711d1" /></div>
            <br clear="all" />
			<?php endif; ?>
			<?php if($OPTION_LIST['acee1d0ea8578c882ae85233e636a43d019e361c']['cost'] != ""): ?>
            <div class="shipping_option_days_overnight"><strong>Priority</strong><br />Next business morning</div>
            <div class="shipping_option_quote_overnight"><?php echo price_format($OPTION_LIST['acee1d0ea8578c882ae85233e636a43d019e361c']['cost']); ?>&nbsp;<input type="radio" name="shipping_option" value="acee1d0ea8578c882ae85233e636a43d019e361c" id="option_acee1d0ea8578c882ae85233e636a43d019e361c" /></div>
            <br clear="all" />
			<?php endif; ?>
            <div class="shipping_option_animal_cheetah"></div>
        </div>
        <div class="shipping_option_container_right"></div>
    </div>
    <?php endif; ?>
    <br clear="all" />
    <br clear="all" />
		<?php if(true == is_a($USER, 'Sales_Rep')): ?>
		<table>
			<?php foreach($CUSTOMER->getShippingOptions() as $opt): ?>
				<?php $CSO = new Custom_Shipping_Option($opt->custom_shipping_option_id); ?>
			<tr>
				<td>
				<input type="radio" name="shipping_option" value="<?php echo $opt->ID; ?>" id="option_<?php echo $opt->ID; ?>" />
				</td>
				<td>
					<label for="option_<?php echo $opt->ID; ?>"><?php echo $CSO->name; ?></label>
					<span class="customer_shipping_account">(shipped on customer account: #<?php echo $opt->account_number; ?>)</span>
				</td>
				<td><?php echo price_format($HANDLING_FEE); ?></td>
			</tr>
			<?php endforeach; ?>
			<?php foreach($SHIP_VIA_LIST as $option_id => $name): ?>
			<tr>
				<td>
				<input type="radio" name="shipping_option" value="sv_<?php echo $option_id; ?>" id="sv_<?php echo $option_id; ?>" />
				</td>
				<td>
					<label for="sv_<?php echo $option_id; ?>"><?php echo $name; ?></label>
				</td>
				<td>
					$<input size="4" type="text" name="ship_via_cost_<?php echo $option_id; ?>" value="<?php echo number_format($HANDLING_FEE, 2, '.', ''); ?>" />
				</td>
			</tr>
			<?php endforeach; ?>
			<tr>
				<td>
					<input type="radio" id="dropship_option" name="shipping_option" value="dropship" />
				</td>
				<td>
					<label for="dropship_option">Dropship</label>
				</td>
				<td>
					$<input size="4" type="text" name="dropship_price" value="<?php echo number_format($HANDLING_FEE, 2, '.', ''); ?>" />
				</td>
			</tr>
		</table>
		<?php endif; ?>
		<input type="submit" value="" class="select_shipping_btn" />
	</fieldset>
</form>
 <br clear="all" />
 <div class="shipping_order_arrive">
 <p><strong><font size="4">When will my order arrive?</font></strong></p>
 <br />
 <p style="margin: -12px 0 0 0">Shipping estimates do not include 2 business day processing time for in stock items. Business days do not include weekends or holidays</p>
 </div>
 <table border="0" cellpadding="0" cellspacing="8" class="RegTable_shipping">
		<thead>
			<tr>
				<th colspan="3"></th>
                <th colspan="3" class="th_left">DAY 1</th>
                <th colspan="3" class="th_middle">2</th>
                <th colspan="3" class="th_middle">3</th>
                <th colspan="3" class="th_middle">4</th>
                <th colspan="3" class="th_middle">5</th>
                <th colspan="3" class="th_middle">6</th>
                <th colspan="3" class="th_middle">7</th>
                <th colspan="3" class="th_right">DAY 8</th>
			</tr>
		</thead>

		<tbody>
            <tr>
                <td colspan="3" class="name_td">Ground</td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="3" class="name_td">Express Saver</td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="3" class="name_td">2 Day</td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="3" class="name_td">Overnight Standard</td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="red_td"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="3" class="name_td">Overnight Priority</td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="gray_td"></td>
                <td colspan="3" class="red_td_half"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
                <td colspan="3"></td>
            </tr>

		</tbody>
	</table>
