<?php
$MS = new Message_Stack();
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#generating_message").hide();
	$("#price_gen_message").hide();
	$("#attr_gen_message").hide();
	$("#pc_gen_message").hide();

	$("#download_products").click(function() {
		$.post('/admin/exporter/generateProducts/', {}, function(data) {
			if(true == data['success']) {
				window.location = '/admin/exporter/download/products';
			}
		}, 'json');
		return false;
	});

	$("#download_product_tabs").click(function() {
		$.post('/admin/exporter/generateProductTabs/', {}, function(data) {
			if(true == data['success']) {
				window.location = '/admin/exporter/download/product_tabs';
			}
		}, 'json');
		return false;
	});

	$("#generate_attr").click(function() {
		$("#attr_links").hide();
		$("#attr_gen_message").show();
		data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>"}
		$.post('/admin/exporter/generateAttr/', data, function(data) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}, 'json');
	});

	$("#generateFeed").click(function() {
		$("#shopping_feed_links").hide();
		$("#generating_message").show();
		data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>"}
		$.post('/admin/exporter/generateFeed/', data, function(data) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}, 'json');
	});

	$("#generatePrice").click(function() {
		$("#pricing_links").hide();
		$("#price_gen_message").show();
		var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>"}
		$.post('/admin/exporter/generatePrices/', data, function(data) {
			if(true == data['success']) {
				window.location = window.location;
			}
		}, 'json');
	});

	$("#generate_product_category").click(function() {
		$("#pc_links").hide();
		$("#pc_gen_message").show();
		var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>"}
		$.post('/admin/exporter/generateProdCat/', data, function(data) {
			if(true == data['success']) {
				window.location.reload(false);
			}
		}, "json");
	});

	$("#attr_instructions").hide();
	$("#attr_csv_instructions").toggle(
		function() {
			$(this).text('close');
			$("#attr_instructions").slideDown();
		},
		function() {
			$(this).text('open');
			$("#attr_instructions").slideUp();
		}
	);
});
</script>
<h2>Product Exporter</h2>
<fieldset>
	<legend>Shopping Feed Export</legend>
	<input type="button" id="generateFeed" value="Generate Shopping Feed" />
<?php
if(true == $SHOW_LINKS) {
?>
<div id="shopping_feed_links">
<p>The Shopping Feed Export was last generated <?php echo $LAST_GENERATED; ?>.</p>
<p><a href="/admin/exporter/download/shopping">Download Shopping Feed</a></p>
</div>
<?php
}
?>
<div id="generating_message">
	<p>
		<img src="/images/ajax-loader.gif" alt="loading" />
		<strong>Generating the new shopping feed... don't touch anything.</strong>
	</p>
	<p>This may take a while, you might want to get some coffee, play solitaire, something.</p>
</div>
</fieldset>
<fieldset>
	<legend>Pricing Export</legend>
	<input type="button" id="generatePrice" value="Generate Pricing CSV" />
	<?php
if(true == $SHOW_PRICE_LINKS) {
?>
	<div id="pricing_links">
		<p>The Pricing CSV was last generated <?php echo date('Y-m-d H:i:s', $PRICE_GENERATED); ?></p>
		<p><a href="/admin/exporter/download/pricing">Download Pricing CSV</a></p>
	</div>
	<?php
	}
	?>
	<div id="price_gen_message">
		<p>
			<img src="/images/ajax-loader.gif" alt="loading" />
			<strong>Generating the pricing CSV... please don't touch anything.</strong>
		</p>
		<p>This shouldn't take <em>too</em> long.</p>
	</div>
</fieldset>
<form id="pricing_importer" action="/admin/exporter/processPricing/" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Pricing Import</legend>
		<div class="messagestack"><?php echo $MS->messages('price_import'); ?></div>	
		<p>Please select the pricing CSV file.</p>
		<input type="file" name="import_file" />
		<input type="submit" value="Upload Pricing CSV" />
	</fieldset>
</form>
<fieldset>
	<legend>Attribute Export</legend>
	<p>This export will generate a CSV file you can use to batch edit the attributes.</p>
	<input type="button" id="generate_attr" value="Generate Attribute CSV" />
	<?php if(true == $SHOW_ATTR_LINKS) { ?>
	<div id="attr_links">
		<p>The Attribute CSV was last generated <?php echo date('Y-m-d H:i:s', $ATTR_GENERATED); ?></p>
		<p><a href="/admin/exporter/download/attributes">Download Attributes CSV</a></p>
	</div>
	<?php } ?>

	<div id="attr_gen_message">
		<p>
			<img src="/images/ajax-loader.gif" alt="loading" />
			<strong>Generating the attribute CSV...</strong>
		</p>
	</div>
	<p>Attribute CSV Instructions (<a href="#" id="attr_csv_instructions">open</a>)</p>
	<div id="attr_instructions">
		<p><strong>Deleting Attributes:</strong> To remove an attribute from a product, just delete the value from that column.</p>
		<p><strong>Creating Attrubutes:</strong> Just add a column and prepend the attribute name with "a:". i.e. An attribute named "Super Duper" would become "a:Super Duper".</p>
		<p><strong>Renaming Products:</strong> The product name field <strong>WILL</strong> rename the product.</p>
	</div>
</fieldset>
<form id="attribute_importer" action="/admin/exporter/processAttributes/" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Attribute Import</legend>
		<div class="messagestack"><?php echo $MS->messages('attr_import'); ?></div>	
		<p>Please select the attribute CSV file.</p>
		<p><strong>Note:</strong> Uploading and processing this file will take a few minutes.</p>
		<input type="file" name="import_file" />
		<input type="submit" value="Upload Attribute CSV" />
	</fieldset>
</form>
<fieldset>
	<legend>Product/Category Export</legend>
	<input type="button" id="generate_product_category"  value="Generate Product/Category CSV" />
	<?php if(true == $SHOW_PC_LINKS) { ?>
		<?php $PCE = new Product_Category_Exporter(); ?>
	<div id="pc_links">
		<p>The Product/Category CSV was last generated <?php echo date('Y-m-d H:i:s', $PCE->getLastExported()); ?></p>
		<p><a href="/admin/exporter/download/product_category">Download Product/Category CSV</a></p>
	</div>
	<?php } ?>
	<div id="pc_gen_message">
		<p>
			<img src="/images/ajax-loader.gif" alt="loading" />
			<strong>Generating the Product/Attribute CSV...</strong>
		</p>
	</div>
</fieldset>
<form id="pc_importer" action="/admin/exporter/processProdCat/" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Product/Category Import</legend>
		<div class="messagestack"><?php echo $MS->messages('pc_import'); ?></div>
		<p>Please select the attribute CSV file.</p>
		<p><strong>Note:</strong> Uploading and processing this file will take a few minutes.</p>
		<input type="file" name="import_file" />
		<input type="submit" value="Upload Category CSV" />
	</fieldset>
</form>

<form id="product_importer" action="/admin/exporter/processProducts/" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Product Import/Export</legend>
		<div class="messagestack"><?php echo $MS->messages('products'); ?></div>
		<a id="download_products" href="#">Download Products CSV</a>
		<p>Please select the product detail CSV file.</p>
		<input type="file" name="import_file" />
		<input type="submit" value="Upload Product Detail CSV" />
	</fieldset>
</form>

<form id="product_importer" action="/admin/exporter/processProductTabs/" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Product Tab Import/Export</legend>
		<div class="messagestack"><?php echo $MS->messages('product_tabs'); ?></div>
		<a id="download_product_tabs" href="#">Download Product Tab CSV</a>
		<p>Please select the product detail CSV file.</p>
		<input type="file" name="import_file" />
		<input type="submit" value="Upload Product Tab CSV" />
	</fieldset>
</form>
