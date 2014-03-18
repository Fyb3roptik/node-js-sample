<script type="text/javascript">
$(document).ready(function() {
	$('.savedAddress a').click(function() {
		$("#address_book_holder").load($(this).attr('href'));
		return false;
	});

	$("#address_edit_form").submit(function() {
		var address_data = {};
		$(this).find('input[type="text"], input[type="hidden"], input[type="checkbox"]:checked, select').each(function() {
			var name = $(this).attr('name');
			var value = $(this).val();
			address_data[name] = value;
		});

		$.ajax({
			async: false,
			url: $(this).attr('action'),
			type: "post",
			data: address_data,
			dataType: "json",
			success: function(data) {
				$("#address_book_holder").load(data['redir_loc']);
			}
		});
		return false;
	});

	$("input[name='address[phone]']").numeric();

	$("a.cancel_address_edit").click(function() {
		$("#address_book_holder").load('/myaccount/addressbook/');
		return false;
	});

	$("a.drop_address").click(function() {
		var address_data = {};
		var address_id = $("input[name='address_id']").val();
		if(true == confirm("Are you sure you want to delete this address?")) {
			address_data['address_id'] = address_id;
			$.ajax({
				async: false,
				url: '/address_book/drop/',
				type: "post",
				data: address_data,
				dataType: "json",
				success: function(data) {
					$("#address_book_holder").load(data['redir_loc']);
				}
			});
		}
		return false;
	});
});
</script>

<div class="address_wrap">
<div class="messages"><?php echo $MS->messages(); ?></div>



<div class="address_right">

<form id="address_edit_form" action="/address_book/process/" method="post">
<input type="hidden" name="address_id" value="<?php echo $FORM_ADDRESS->ID; ?>" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="favouriteTable">
        <thead>
			<tr>
                <th class="FirstColumn"></th>
				<?php if(false == $FORM_ADDRESS->exists()): ?>
				<th>&nbsp;&nbsp;Create New Adresses</th>
				<?php else: ?>
				<th>&nbsp;&nbsp;Edit Address</th>
				<?php endif; ?>
                <th class="LastColumn"><div class="RightCorner"></div></th>
            </tr>
		</thead>
</table>


	
<div class="addressForm_wrap">
<div class="addressForm">
Address Name<br><input type="text" name="address[nickname]" class="textfield" value="<?php echo $FORM_ADDRESS->nickname; ?>" />
</div>
<div class="addressForm0"><br>
<span class="example">e.g. South Campus, Receiving, Home</span>
</div>
<br clear="all" />
</div>


<div class="addressForm_wrap">
<div class="addressForm">
Name<br><input type="text" name="address[name]" class="textfield" value="<?php echo $FORM_ADDRESS->name; ?>" />
</div>
<div class="addressForm0">
Company<br><input type="text" name="address[company]" class="textfield" value="<?php echo $FORM_ADDRESS->company; ?>" />
</div>
<br clear="all" />
</div>

	
<div class="addressForm_wrap">
<div class="addressForm">
Address 1<br><input type="text" name="address[address_1]" value="<?php echo $FORM_ADDRESS->address_1; ?>" class="textfield" />
</div>
<div class="addressForm0">
Address 2<br><input type="text" name="address[address_2]" value="<?php echo $FORM_ADDRESS->address_2; ?>" class="textfield" />
</div>
<br clear="all" />
</div>

							
<div class="addressForm_wrap">
<div class="addressForm">
Address 3<br><input type="text" name="address[address_3]" value="<?php echo $FORM_ADDRESS->address_3; ?>" class="textfield" />
</div>
<br clear="all" />
</div>


<div class="addressForm_wrap">
<div class="addressForm">
City<br><input type="text" name="address[city]" value="<?php echo $FORM_ADDRESS->city; ?>" class="textfield" />
</div>
<div class="addressForm">
State<br><?php echo draw_select('address[state]', get_states(), $FORM_ADDRESS->state, 'class="textfield2"'); ?>
</div>
<div class="addressForm0">
Zip Code<br><input type="text" size="8" name="address[zip_code]" value="<?php echo $FORM_ADDRESS->zip_code; ?>" class="textfield" />
</div>
<br clear="all" />
</div>

<div class="addressForm_wrap">
<div class="addressForm">
Country<br><select name="address[country]" class="textfield2"><option value="usa">United States of America</option></select>
</div>
<br clear="all" />
</div>
				
<div class="addressForm_wrap">
<div class="addressForm">
Phone<br><input type="text" name="address[phone]" value="<?php echo $FORM_ADDRESS->phone; ?>" class="textfield" />
</div>
<div class="addressForm0">
Ext<br><input type="text" name="address[ext]" value="<?php echo $FORM_ADDRESS->ext; ?>" size="5" />
</div>
<br clear="all" />
</div>
				
<div class="addressForm_wrap">
<div class="addressForm">
Make default
</div>
<div class="addressForm0">
<?php echo draw_checkbox('default_shipping', 1, ($FORM_ADDRESS->ID == $CUSTOMER->default_shipping), 'id="MakeDefaultYes"'); ?>
<label for="MakeDefaultYes">&nbsp;Shipping</label>&nbsp;&nbsp;&nbsp;
<?php echo draw_checkbox('default_billing', 1, ($FORM_ADDRESS->ID == $CUSTOMER->default_billing), 'id="MakeDefaultNo"'); ?>
<label for="MakeDefaultNo">&nbsp;Billing</label>
</div>
<br clear="all" />
</div>				
<?php if(false == $FORM_ADDRESS->exists()): ?>
<div class="bigButton"><input type="image" name="Submit" value="Submit" src="/images/create_btn.jpg" /></div>
<?php else: ?>
<div class="bigButton">
	<a href="#" class="drop_address"><img alt="Drop Address" src="/images/delete_bttn.png" /></a>
	<a href="#" class="cancel_address_edit"><img alt="Cancel" src="/images/cancel_bttn.png" /></a>
	<input type="image" name="Submit" value="Submit" src="/images/update_btn.jpg" />
</div>
<?php endif; ?>
</form>
<br clear="all" />
<!-- end address right--></div>


<div class="address_left">

<div class="savedAddress_wrap">
	<div><img src="/images/savedAddress_back_top.png"></div>
	<h2>Saved Addresses</h2>
	<?php if(count($ADDRESS_BOOK) > 0): ?>

		<?php if(true == $DEFAULT_SHIPPING->exists()): ?>
		<div class="savedAddress">
			Default shipping address:<br>
			<span class="RedText">
				<a class="default_link" href="/address_book/edit/<?php echo $DEFAULT_SHIPPING->ID; ?>"><?php echo $DEFAULT_SHIPPING->nickname; ?></a>
			</span>
		</div>
		<?php endif; ?>

		<?php if(true == $DEFAULT_BILLING->exists()): ?>
		<div class="savedAddress">
			Default billing address:<br>
			<span class="RedText">
				<a class="default_link" href="/address_book/edit/<?php echo $DEFAULT_BILLING->ID; ?>"><?php echo $DEFAULT_BILLING->nickname; ?></a>
			</span>
		</div>
		<?php endif; ?>


		<?php foreach($ADDRESS_BOOK as $address): ?>
		<div class="savedAddress">
			Alternate Address:<br>
			<span>
				  <a href="/address_book/edit/<?php echo $address->ID; ?>"><?php echo $address->nickname; ?></a>
			</span>
		</div>
		<?php endforeach; ?>
	<?php else : ?>
		<div class="savedAddress"><p>You don't have any saved addresses.</p></div>
	<?php endif; ?>

	<div><img src="/images/savedAddress_back_bottom.png"></div>
</div>
<br clear="all" />
<!-- end address left--></div>
<br clear="all" />
<!--end address wrap --></div>