<script type="text/javascript">
var product_list = '?products=<?php echo $PRODUCT_ID_LIST; ?>';

$(document).ready(function() {
	$("#batch_menu a").click(function() {
		$("#batch-stage").load($(this).attr('href') + product_list);
	       	return false;
	});

	$('#product_list').zebra();
});
</script>
<div id="batch_menu">
	<strong>Batch Edit:</strong>
	<a href="/admin/batch/sellby/">Unit of Measure</a> |
	<a href="/admin/batch/pricing/">Pricing Tiers</a> |
	<a href="/admin/batch/margin/">Margin Adjustment</a> |
	<a href="/admin/batch/setMargin/">Set Margin</a> |
	<a href="/admin/batch/setPrice/">Set Price</a> |
	<a href="/admin/batch/category/">Manage Categories</a>
</div>
<hr />
<div id="message_stack">
	<?php echo $MS->messages(); ?>
</div>
<div id="product_list">
<?php if(count($PRODUCT_LIST) > 0): ?>
	<strong>Selected Products</strong>
	<?php foreach($PRODUCT_LIST as $P): ?>
	<div>
		<?php echo $P->catalog_code; ?>
	</div>
	<?php endforeach; ?>
<?php else: ?>
<p>No products selected.</p>
<?php endif; ?>
</div>
<div id="batch-stage">&nbsp;</div>
