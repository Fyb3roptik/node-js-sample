<script type="text/javascript">
$(document).ready(function() {
	$("#credit_card_form a.cancel_edit").click(function() {
		$("#payment_info_holder").load($(this).attr('href'));
		return false;
	});

	$("a.edit_number").click(function() {
		$("#cc_number_holder").hide();
		$("#cc_number_form_holder").show();
		return false;
	});

	$("a.cancel_number").click(function() {
		$("input[name='credit_card[number]']").val("");
		$("#cc_number_holder").show();
		$("#cc_number_form_holder").hide();
		return false;
	});

	$("#cc_number_form_holder").hide();

	$("#credit_card_form").submit(function() {
		var cc_data ={};
		$(this).find('input[type="text"], select, input[type="hidden"]').each(function() {
			var name = $(this).attr('name');
			var value = $(this).val();
			cc_data[name] = value;
		});

		$.ajax({
			async: false,
			url: $(this).attr('action'),
			type: "post",
			data: cc_data,
			dataType: "json",
			success: function(data) {
				$("#payment_info_holder").load(data['redir_loc']);
			}
		});
		return false;
	});
});
</script>
<h3 class="greeting">Editing "<?php echo $CC->nickname; ?>"</h3>
<div class="contentbox">
	<form id="credit_card_form" action="/myaccount/payment_info/process/" method="post">
		<fieldset>
			<input type="hidden" class="textfield" name="credit_card_id" value="<?php echo $CC->ID; ?>" />
			<label>Nickname:</label> 
			<input type="text" class="textfield" name="credit_card[nickname]" value="<?php echo $CC->nickname; ?>" />
			<span class="example">i.e. "My Card" or "Corporate Visa"</span>
			<label>Name on Card:</label>
			<input type="text" class="textfield" name="credit_card[name]" value="<?php echo $CC->name; ?>" />
			<label>Credit Card Number:</label>
			<span id="cc_number_holder">
				<?php echo obfuscate_cc_number($CC->getPlainNumber()); ?>
				<a href="#" class="edit_number">edit</a>
			</span>
			<span id="cc_number_form_holder">
				<input type="text" class="textfield" name="credit_card[number]" value="" />
				<a href="#" class="cancel_number">cancel</a>
				<br />
				<span class="example">Numbers only! No hyphens or spaces, please.</span>
			</span>
			<label>Expiration Date:</label>
			<?php echo draw_expires_month_select($CC->getPlainMonth()); ?> / 
			<?php echo draw_expires_year_select($CC->getPlainYear()); ?>
			<br />
			<br />
			 <a class="cancel_edit" href="/myaccount/payment_info/"><img src="/images/cancel_bttn.png" height="22" width="50"></a>&nbsp;&nbsp;<input type="image" src="/images/save_creditCard.png" height="22" width="122"/>
		</fieldset>
	</form>
</div>

