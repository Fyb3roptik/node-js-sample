<script type="text/javascript">
/* <[CDATA[ */
$(document).ready(function() {
	$("a.edit_rate").click(function() {
		var $tr = $(this).parent('td').parent('tr');
		if(false == $(this).hasClass("selected")) {
			$tr.attr("class", "selected");
			$tr.css("background-color", "#999");
			$tr.find('div.sales_tax').hide();
			$tr.find('div.sales_tax_field').show();
			$tr.find("input[name='sales_tax[]']").focus().select();
			$tr.find("a.edit_rate").hide();
			$tr.find("a.cancel_edit").show();
		} else {
			return false;
		}
	});

	$("#state_table tr").mouseover(function() {
		if(false == $(this).hasClass("selected") && $(this).children('th').length == 0) {
			$(this).css("background", "#CCC");
		}
	});

	$("#state_table tr:odd").mouseout(function() {
		if(false == $(this).hasClass("selected") && $(this).children('th').length == 0) {
			$(this).css("background", "#DDD");
		}
	});

	$("#state_table tr:even").mouseout(function() {
		if(false == $(this).hasClass("selected") && $(this).children('th').length == 0) {
			$(this).css("background", "#FFF");
		}
	});

	$("input[type='text'][name='sales_tax[]']")
		.keypress(function(e) {
			if(13 == e.which) {
				var tax_rate = $(this).val();
				var state_id = parseInt($(this).siblings("input[name='state_id[]']").val());
				var $this = $(this);
				var data = {
						"action" : "save_tax_rate",
						"state_id" : parseInt(state_id),
						"tax_rate" : parseFloat(tax_rate),
						"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>" }
				$.post('/admin/sales_tax.http.php', data,
					function(data) {
						tax_rate = data.sales_tax;
						var $parent = $this.parents("div[class='sales_tax_field']");
						$parent.hide();
						$parent.siblings('div').text(parseFloat(tax_rate).toFixed(4) + "%").show();
						var $tr = $parent.parent('td').parent('tr');
						$tr.find('a.edit_rate').show();
						$tr.find('a.cancel_edit').hide();
						stripe_state_table();
					}, "json");
			}
		});

	$("a.cancel_edit").click(function() {
		var $tr = $(this).parent('td').parent('tr');
		var $parent = $tr.find('div.sales_tax_field');
		var $tax = $tr.find('div.sales_tax');
		$tr.find('a.edit_rate').show();
		$tr.find('a.cancel_edit').hide();
		$parent.hide();
		$tax.show();
		stripe_state_table();
	});

	$("a.cancel_edit").hide();
	$(".sales_tax_field").hide();
	stripe_state_table();

	$("div.sales_tax").click(function() {
		$(this).parent('td').parent('tr').find('a.edit_rate').click();
	});
});

function stripe_state_table() {
	$("#state_table tr:odd").css('background-color', '#DDD');
	$("#state_table tr:even").css('background-color', '#FFF');
	$("#state_table tr").removeClass("selected");
}
/* ]]/> */
</script>
<h2>Manage Sales Tax</h2>
<form id="sales_tax_form" action="" method="post">
	<fieldset>
		<table id="state_table" width="40%" cellpadding="0" cellspacing="0">
			<tr>
				<th>State Abbr</th>
				<th>State</th>
				<th>Sales Tax</th>
				<th>Action</th>
			</tr>
			<?php
			foreach($STATE_LIST as $i => $state) {
			?>
			<tr>
				<td><?php echo $state->abbr; ?></td>
				<td><?php echo $state->state; ?></td>
				<td style="text-align: center;">
				<div class="sales_tax"><?php echo $state->sales_tax . "%"; ?></div>
				<div class="sales_tax_field">
					<input type="hidden" name="state_id[]" value="<?php echo $state->ID; ?>" />
					<input type="text" name="sales_tax[]" value="<?php echo $state->sales_tax; ?>" size="4" /> %
				</div>
				</td>
				<td style="text-align: center">
					<a href="#" class="edit_rate">[edit]</a>
					<a href="#" class="cancel_edit">[cancel]</a>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
	</fieldset>
</form>