<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function() {
	$(".numeric3").numeric();
	$(".numeric4").numeric();
	$("#name").focus();
	$("form#customer_form input[type='radio']").change(function() {
		$("#company_name").toggle();
    });

});
/* ]]> */
</script>

<div><span class="RedText2">Create New Account</span></div>
<?php if(true == ($MS instanceof Message_Stack)): ?>
<div class="messages"><?php echo $MS->messages(); ?></div>
<?php endif; ?>
<br clear="all" />

	<div class="salesCreate">
	<form id="customer_form" action="" method="post">
		<input type="hidden" name="customer_id" value="<?php echo $C->ID; ?>" />
		<input type="hidden" id="customer_sales_rep" name="sales_rep" value="<?php echo $CUSTOMER->ID; ?>" />
		<input type="hidden" name="action" value="add_customer" />
		<input type="hidden" name="redirect" value="false" />
			<table>
				<tr>
				<td><input id="residential" type="radio" name="type" align="absmiddle" value="" checked />
				<label for="residential">&nbsp;<strong>Residential</strong></label>
				</td>
				<td width="25"></td>
				<td><input id="commerical" type="radio" name="type" align="absmiddle" value="" />
				<label for="commerical">&nbsp;<strong>Commercial</strong></label>
				</td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr id="company_name" style="display:none;">
					<td height="25" align="right">Company Name</td>
					<td width="25"></td>
					<td><input type="text" id="customer_company" name="customer_company" value="<?php echo $C->company; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td height="25" align="right">Contact Name</td>
					<td width="25"></td>
					<td><input type="text" id="customer_name" class="textfield" name="customer_name" value="<?php echo $C->name; ?>" /></td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td height="25" align="right">Street Address</td>
					<td width="25"></td>
					<td><input type="text" id="customer_address1" name="customer_address_1" value="<?php echo $C->address_1; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td height="25" align="right">Address 2</td>
					<td width="25"></td>
					<td><input type="text" id="customer_address2" name="customer_address_2" value="<?php echo $C->address_2; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td height="25" align="right">Address 3</td>
					<td width="25"></td>
					<td><input type="text" id="customer_address3" name="customer_address_3" value="<?php echo $C->address_3; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td height="25" align="right">City</td>
					<td width="25"></td>
					<td><input type="text" id="customer_city" name="customer_city" value="<?php echo $C->city; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td height="25" align="right">State</td>
					<td width="25"></td>
					<td>
						<?php echo draw_select('customer_state', get_states(), $C->state, 'id="customer_state" class="textfield"'); ?>
					</td>
				</tr>
				<tr>
					<td height="25" align="right">Zip Code</td>
					<td width="25"></td>
					<td><input type="text" id="customer_zipcode" name="customer_zip_code" size="9" value="<?php echo $C->zip_code; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td height="25" align="right">Phone</td>
					<td width="25"></td>
					<td><input type="text" id="customer_phone" class="textfield" size="12" name="customer_phone" value="<?php echo $C->phone; ?>"/></td>
				</tr>
				<tr>
					<td height="25" align="right">Ext</td>
					<td width="25"></td>
					<td><input type="text" class="textfield" id="customer_ext" class="numeric4" size="6" name="customer_ext" value="<?php echo $C->ext; ?>" size="5"/></td>
				</tr>
				<tr>
					<td height="25" align="right">Email:</td>
					<td width="25"></td>
					<td><input type="text" id="customer_email" class="textfield" name="customer_email" value="<?php echo $C->email; ?>" /></td>
				</tr>
				<tr>
					<td height="25" align="right">Secondary Email</td>
					<td width="25"></td>
					<td><input type="text" id="customer_email2" class="textfield" name="customer_secondary_email" value="<?php echo $C->secondary_email; ?>" /></td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td colspan="3" align="center">
					<a href="<?php echo LOC_SALES; ?>"><img src="/images/cancel_bttn.png" width="50" height="22"></a>&nbsp;&nbsp;<input type="image" id="create" name="create" src="/images/save_bttn.png" width="60" height="22" />
					</td>
				</tr>
			</table>
	</form>
</div>
