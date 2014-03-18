<script type="text/javascript">
/* <[CDATA[ */
$(document).ready(function() {
	$("#global_fedex_3day").change(function()
	{
        var ground = $("#global_fedex_ground").val();
		var percentage = $("#global_fedex_3day").val();

		$.post("/admin/shipping/updateShippingMinimums",
		{ ground: ground, percentage: percentage },
        function(value)
		{
        	$("#fedex_3day_base").html("$"+value).fadeIn();
			$("#global_fedex_3day_base").empty().val(value);
		});

	});
	$("#global_fedex_2day").change(function()
	{
        var ground = $("#global_fedex_ground").val();
		var percentage = $("#global_fedex_2day").val();

		$.post("/admin/shipping/updateShippingMinimums",
		{ ground: ground, percentage: percentage },
        function(value)
		{
        	$("#fedex_2day_base").html("$"+value).fadeIn();
			$("#global_fedex_2day_base").empty().val(value);
		});

	});
	$("#global_fedex_stover").change(function()
	{
        var ground = $("#global_fedex_ground").val();
		var percentage = $("#global_fedex_stover").val();

		$.post("/admin/shipping/updateShippingMinimums",
		{ ground: ground, percentage: percentage },
        function(value)
		{
        	$("#fedex_stover_base").html("$"+value).fadeIn();
			$("#global_fedex_stover_base").empty().val(value);
		});

	});
	$("#global_fedex_prover").change(function()
	{
        var ground = $("#global_fedex_ground").val();
		var percentage = $("#global_fedex_prover").val();

		$.post("/admin/shipping/updateShippingMinimums",
		{ ground: ground, percentage: percentage },
        function(value)
		{
        	$("#fedex_prover_base").html("$"+value).fadeIn();
			$("#global_fedex_prover_base").empty().val(value);
		});

	});

	$("#global_fedex_ground").change(function()
	{
		var arr = Array("global_fedex_3day", "global_fedex_2day", "global_fedex_stover", "global_fedex_prover");
        var arr2 = Array("fedex_3day_base", "fedex_2day_base", "fedex_stover_base", "fedex_prover_base");

		var ground = $("#global_fedex_ground").val();
		$("#fedex_ground_base").empty().html("$"+ground).fadeIn();

		$.each(arr, function (i) {

			var percentage = $("#"+arr[i]).val();
            $("#"+arr2[i]).fadeOut();

			$.post("/admin/shipping/updateShippingMinimums",
			{ ground: ground, percentage: percentage },
	        function(value)
			{
	        	$("#"+arr2[i]).empty().html("$"+value).fadeIn();
				$("#"+arr[i]+"_base").empty().val(value);
			});

		});





	});

});
</script>
<h2>Manage Shipping and Freight</h2>
<div class="shipping_nav">
	<a href="/admin/fover/">Freight Overrides</a> |
	<a href="/admin/excludes/">Freight Overrides Excludes</a> | 
	<a href="/admin/fbox/">Freight Boxes</a> |
	<a href="/admin/custom_shipping/">Custom Shipping Options</a>
</div>

<form id="global_variable_form" action="/admin/shipping/saveGlobals/" method="post">
	<fieldset>
		<legend>Global Shipping Variables</legend>
		Global Dunnage Factor:<br />
		<input type="text" name="global_dunnage" value="<?php echo Config::get()->value('global_dunnage'); ?>" size="4" /> (%)<br />
		Fudge Factor:<br />
		<input type="text" name="global_fudge" value="<?php echo Config::get()->value('fudge_factor'); ?>" size="4"/> (%)<br />
		<br />
		<br />
		<h3><u>Shipping Minimums</u></h3>
		Fedex Ground Minimum:<br />
		<input type="text" name="global_fedex_ground" id="global_fedex_ground" value="<?php echo Config::get()->value('fedex_ground_base'); ?>" size="6" />($)&nbsp;&nbsp;<span id="fedex_ground_base">$<?php echo Config::get()->value('fedex_ground_base'); ?></span><br /><br />
		Fedex 3Day Minimum:<br />
		<input type="text" name="global_fedex_3day" id="global_fedex_3day" value="<?php echo Config::get()->value('fedex_3day_percentage'); ?>" size="6" />(%)&nbsp;&nbsp;<span id="fedex_3day_base">$<?php echo Config::get()->value('fedex_3day_base'); ?></span><br /><br />
		Fedex 2Day Minimum:<br />
		<input type="text" name="global_fedex_2day" id="global_fedex_2day" value="<?php echo Config::get()->value('fedex_2day_percentage'); ?>" size="6" />(%)&nbsp;&nbsp;<span id="fedex_2day_base">$<?php echo Config::get()->value('fedex_2day_base'); ?></span><br /><br />
		Fedex Standard Overnight Minimum:<br />
		<input type="text" name="global_fedex_stover" id="global_fedex_stover" value="<?php echo Config::get()->value('fedex_stover_percentage'); ?>" size="6" />(%)&nbsp;&nbsp;<span id="fedex_stover_base">$<?php echo Config::get()->value('fedex_stover_base'); ?></span><br /><br />
		Fedex Priority Overnight Minimum:<br />
		<input type="text" name="global_fedex_prover" id="global_fedex_prover" value="<?php echo Config::get()->value('fedex_prover_percentage'); ?>" size="6" />(%)&nbsp;&nbsp;<span id="fedex_prover_base">$<?php echo Config::get()->value('fedex_prover_base'); ?></span><br /><br />
		<input type="submit" value="Save" />
	</fieldset>
	<input type="hidden" name="global_fedex_3day_base" id="global_fedex_3day_base" value="<?php echo Config::get()->value('fedex_3day_base'); ?>" />
	<input type="hidden" name="global_fedex_2day_base" id="global_fedex_2day_base" value="<?php echo Config::get()->value('fedex_2day_base'); ?>" />
	<input type="hidden" name="global_fedex_stover_base" id="global_fedex_stover_base" value="<?php echo Config::get()->value('fedex_stover_base'); ?>" />
	<input type="hidden" name="global_fedex_prover_base" id="global_fedex_prover_base" value="<?php echo Config::get()->value('fedex_prover_base'); ?>" />
</form>
