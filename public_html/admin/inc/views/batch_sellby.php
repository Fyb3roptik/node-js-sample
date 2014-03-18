<form id="batch_product_sellby" action="/admin/batch/sellbyProcess/" method="post">
	<fieldset>
		<legend>Edit Batch Sell-by Quantities</legend>
		<?php foreach($PRODUCT_LIST as $P): ?>
		<input type="hidden" name="product_id[]" value="<?php echo $P->ID; ?>" />
		<?php endforeach; ?>
		<strong>Sell-By Quantity:</strong><br />
		<input type="text" name="sell-by-quantity" value="1" /><br />
		<br />
		<strong>Unit of Measure</strong><br />
		<input type="text" name="sell-by-unit" value="Each" /><br />
		<span class="helper">"Each", "Box", "Barrel", "Crate"...</span>
		<br />
		<input type="submit" value="Save Changes" />
	</fieldset>
</form>
