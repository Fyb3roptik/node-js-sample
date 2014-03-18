<?php
if(true == is_a($CUSTOMER, 'Customer')) {
?>
<div id="breadcrumb">
	<a href="/">home</a> &gt;
	<a href="<?php echo LOC_ACCOUNT_HOME; ?>">my account</a> &gt;
	<a href="<?php echo LOC_ADDRESS_BOOK; ?>">my address book</a> &gt;
	<?php echo strtolower($CA->getNickname()); ?>
</div>
<?php
}
?>

<h4 class="greeting">Editing "<?php echo $CA->getNickname(); ?>"</h4>

<div class="contentbox">
	<div class="messagestack"><?php echo $MS->messages('address_book'); ?></div>
	<form id="address_book_form" action="" method="post">
	<fieldset>
	<input type="hidden" name="action" value="process_address" />
	<input type="hidden" name="address_id" value="<?php echo $address['address_id']; ?>" />
	<input type="hidden" name="customer_id" value="<?php echo $CA->customer_id; ?>" />
	<table>
		<tr>
			<td class="form_label">Nickname:</td>
			<td>
				<input type="text" name="nickname" value="<?php echo $address['nickname']; ?>" />
				<span class="form_help">e.g. "My House"</span>
			</td>
		</tr>
		<tr>
			<td class="form_label">Name:</td>
			<td>
				<input type="text" name="name" value="<?php echo $address['name']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="form_label">Company:</td>
			<td>
				<input type="text" name="company" value="<?php echo $address['company']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="form_label">Address 1:</td>
			<td><input type="text" name="address_1" value="<?php echo $address['address_1']; ?>" /></td>
		</tr>
		<tr>
			<td class="form_label">Address 2:</td>
			<td><input type="text" name="address_2" value="<?php echo $address['address_2']; ?>" /></td>
		</tr>
		<tr>
			<td class="form_label">Address 3:</td>
			<td><input type="text" name="address_3" value="<?php echo $address['address_3']; ?>" /></td>
		</tr>
		<tr>
			<td class="form_label">City:</td>
			<td><input type="text" name="city" value="<?php echo $address['city']; ?>" /></td>
		</tr>
		<tr>
			<td class="form_label">State:</td>
			<td>
			<?php
			echo draw_select("state", get_states(), $address['state']);
			?>
			</td>
		</tr>
		<tr>
			<td class="form_label">Zip Code:</td>
			<td><input type="text" name="zip_code" value="<?php echo $address['zip_code']; ?>" /></td>
		</tr>
		<tr>
			<td class="form_label">Country:</td>
			<td>
				<select name="country">
					<option value="country" selected="selected">United States</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="form_label">Phone:</td>
			<td><input type="text" name="phone" value="<?php echo $address['phone']; ?>" /></td>
		</tr>
		<tr>
			<td class="form_label">Ext:</td>
			<td><input type="text" name="ext" value="<?php echo $address['ext']; ?>" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Save Address" /> or <a href="<?php echo LOC_ADDRESS_BOOK; ?>">cancel</a></td>
		</tr>
	</table>
	</fieldset>
	</form>
</div>