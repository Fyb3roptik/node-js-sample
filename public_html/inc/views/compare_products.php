<script type="text/javascript">
$(document).ready(function() {
	$("#comparison_table tr:first th").text("");
	$("#comparison_table tr:last th").text("");
	$("#comparison_table tr:not(:first)").each(function() {
		var $row = $(this);
		var different = false;
		var prev_value = $row.children('td:first').text();
		$row.children('td').each(function() {
			if($(this).text() != prev_value) {
				different = true;
			}
		});

		if(true == different) {
			$row.children('td').addClass("differentAttribute");
		}
	});

	$("#comparison_table tr:last").children().css('border-bottom', 'none').css('text-align', 'center');
	$("#comparison_table tr:first").next().children().removeClass('differentAttribute');

	$("a.addCart").click(function() {
		$(this).parent('form').submit();
		return false;
	});

	$(".hideRow").click(function(){
		$(this).parents("tr").fadeOut();
	});
});

</script>
<h3 class="greeting">Compare Products
<div class="compareContinue">
	<?php if(false == is_null($PREV_PAGE)): ?>
	<a href="<?php echo $PREV_PAGE; ?>">&#60;&#60;&nbsp;&nbsp;Continue Shopping </a>
	<?php endif; ?>
</div>
</h3>

 <br />
<div class="contentbox">

	<table id="comparison_table" cellspacing="0" cellpadding="0" >
	<?php foreach($COMPARISON_TABLE as $attribute_name => $attribute): ?>
	<?php $short_attr_name = str_replace(" ", "", $attribute_name); ?>
	<tr class="hideTR">
		<th width="150" class="attribute_name" valign="middle">
			<span class="hideRow" style="float:left; cursor:pointer;"><img src="/images/site-assets/compare-remove_product.jpg" alt="remove product" /></span>
			<strong><?php echo $attribute_name; ?></strong>
		</th>
		<?php foreach($attribute as $product_id => $value): ?>
		<td class="attribute_value">
			<?php echo is_null($value) ? "X" : $value; ?>
		</td>
		<?php endforeach; ?>
	
	</tr>
	<?php endforeach; ?>
	</table>
</div>