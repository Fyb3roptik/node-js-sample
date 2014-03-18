<fieldset>
	<input type="hidden" name="action" value="process_product" />
	<input type="hidden" name="product_id" value="<?php echo $P->ID; ?>" />
	<input type="hidden" name="form_hash" value="" id="form_hash" />
	<legend>Product Details</legend>
	<table>
		<tr>
			<td>Product Name:</td>
			<td><input type="text" class="extra_long" name="product[name]" value="<?php echo $P->name; ?>" /></td>
		</tr>
		<tr>
			<td>Legacy ID:</td>
			<td><input type="text" name="product[legacy_id]" value="<?php echo $P->legacy_id; ?>" /></td>
		</tr>
		<tr>
			<td>Catalog Code:</td>
			<td><input type="text" name="product[catalog_code]" value="<?php echo $P->catalog_code; ?>" /></td>
		</tr>
		<tr>
			<td>Title Tag:</td>
			<td><input type="text" name="product[title]" value="<?php echo $P->title; ?>" /></td>
		</tr>
		<tr>
			<td>Active:</td>
			<td>
				<?php echo draw_select('product[active]', array(0 => 'inactive', 1 => 'active'), intval($P->active)); ?>
			</td>
		</tr>
		<tr>
			<td>Orderable:</td>
			<td>
				<?php echo draw_select('product[orderable]', array(0 => 'Not Orderable', 1 => 'Orderable'), intval($P->orderable)); ?>
			</td>
		</tr>
		<tr>
			<td>Weight:</td>
			<td><input type="text" name="product[weight]" value="<?php echo floatval($P->weight); ?>" /></td>
		</tr>
		<tr>
			<td>Length:</td>
			<td><input type="text" name="product[length]" value="<?php echo floatval($P->length); ?>" /></td>
		</tr>
		<tr>
			<td>Width:</td>
			<td><input type="text" name="product[width]" value="<?php echo floatval($P->width); ?>" /></td>
		</tr>
		<tr>
			<td>Height:</td>
			<td><input type="text" name="product[height]" value="<?php echo floatval($P->height); ?>" /></td>
		</tr>
		<tr>
			<td>Dunnage Override:</td>
			<td><input type="text" name="product[dunnage_override]" value="<?php echo floatval($P->dunnage_override); ?>" /> (%)</td>
		</tr>
		<tr>
			<td>Sales Only:</td>
			<td>
				<?php echo draw_checkbox('product[sales_only]', 1, (1 == $P->sales_only)); ?>
			</td>
		</tr>
		<tr>
			<td valign="top">Description:</td>
			<td><textarea id="product_description" name="product[description]" cols="80" rows="10"><?php echo htmlentities($P->description); ?></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Save Product" /> or <a href="<?php echo LOC_PRODUCTS; ?>">Cancel</a></td>
		</tr>
	</table>
</fieldset>
