<fieldset>
	<legend>Manage Images</legend>
	<table>
		<tr>
			<td>Upload New Image:</td>
			<td><input type="file" name="new_image" /></td>
			<td><input type="submit" value="Save" /></td>
		</tr>
	</table>
	<?php
	$product_images = $P->getImages();
	if(count($product_images) > 0) {
	?>
	<hr />
	<strong>Current Images</strong>
	<table style="width: 50%">
		<tr>
			<th>Thumbnail</th>
			<th>Default</th>
			<th>Alt Tag</th>
			<th>Delete</th>
		</tr>
		<?php
		foreach($product_images as $i => $image) {
		?>
		<tr>
			<td style="text-align: center;"><img src="/<?php echo $image->getThumb(); ?>" alt="thumb" /></td>
			<td style="text-align: center;"><?php echo draw_radio('product[default_image]', $image->ID, ($image->ID == $P->default_image)); ?></td>
			<td>
				<input type="text" name="product_image_alt[<?php echo $image->ID; ?>]" value="<?php echo $image->alt; ?>" />
			</td>
			<td style="text-align: center;"><input type="checkbox" name="delete_image[]" value="<?php echo $image->ID; ?>" /></td>
		</tr>
	<?php
		}
	?>
	</table>
	<?php
	}
	?>
</fieldset>
