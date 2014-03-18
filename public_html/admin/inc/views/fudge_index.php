<form id="fudge_factor_form" action="/admin/fudge/process" method="post">
	<strong>GLOBAL PRODUCT OVERHEAD</strong>
	<br />
	<input type="text" name="global_product_overhead" value="<?php echo $PRODUCT_OVERHEAD; ?>" id="product_fudge" /><br />
<p>This percentage will be added to the base cost of <em>every</em> product in the system for price calculation.</p>
	<input type="submit" value="Update Global Product Overhead" />
</form>
