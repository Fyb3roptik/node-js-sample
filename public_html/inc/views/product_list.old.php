<script type="text/javascript">
$(document).ready(function() {
	$(".Desc div.ul_holder ul.first_list").each(function() {
		var $first_list = $(this);
		var $second_list = $(this).parent('.ul_holder').next('.ul_holder').children('ul.second_list');
		var move_count = parseInt($first_list.children('li').length / 2);
		for(var i = 0; i < move_count; i++) {
			if($second_list.children().length == 0) {
				$first_list.children('li:last').appendTo($second_list);
			} else {
				$first_list.children('li:last').insertBefore($second_list.children(':first'));
			}
		}
	});

	$("input.go_button").click(function() {
		var $checked_boxes = $('input[name=compare_box[]]:checked');
		var comparison_products = "";
		var product_count = $checked_boxes.length;
		$checked_boxes.each(function() {
			comparison_products += "+" + $(this).val();
		});

		if(comparison_products != "" && product_count > 1) {
			window.location = '<?php echo LOC_COMPARE_PRODUCTS; ?>?compare=' + comparison_products;
		} else {
			alert("You must select at least 2 products for comparison.");
		}

		return false;
	});

});

function addProduct(ID, image, name)
{
    var quantity = $("#quantity_"+ID
	).val();

    var html = '<span id="impromptu_item_title">Item Added to Cart</span><br /><div id="impromptu_item_info_container"><div id="impromptu_item_img"><img src="'+image+'"></div><div id="impromptu_item_info">'+name+'</div><div id="impromptu_item_quantity">Quantity: '+quantity+'</div></div>';

    $.prompt(html, {
      buttons:{"Continue Shopping":true, Checkout:false},
      callback: function(v,m,f) {

            if(v == true)
            {
				var url = window.location;            	
				$("input[name='redirect']").setValue("return");
				$("input[name='redirect_url']").setValue(url);
            } else {
              $("#redirect").val("");
            }
            $("#inline_product_form"+ID).submit();
      }
    });


}

</script>

<?php if(false == is_null($BREADCRUMB) && true == is_array($BREADCRUMB)): ?>
<div class="BreadCrumb">
	<?php
	$crumb_links = array();
	foreach($BREADCRUMB as $crumb) {
		$crumb_links[] = '<span>' . get_category_link($crumb) . '</span>';
	}
	echo implode(' &gt; ', $crumb_links);
	?>
</div>
<?php endif; ?>

<?php if(true == isset($PAGE_TITLE)): ?>
<div class="prodList_header">
<span class="RedText2"><?php echo $PAGE_TITLE; ?></span>
<!-- end prodList_header --></div>
<?php endif; ?>

<?php echo empty($HTML_HEADER) ? null : $HTML_HEADER; ?>

<div class="Paging">
	<?php echo $PK_LINKS; ?>
</div>
<div class="Sorting">
	Sort by:
	<a href="<?php echo $PRICE_LOW_LINK; ?>">lowest price</a> |
	<a href="<?php echo $PRICE_HIGH_LINK; ?>">highest price</a><!-- |

    <a href="<?php echo $NAME_LOW_LINK; ?>">name (a-z)</a> |
	<a href="<?php echo $NAME_HIGH_LINK; ?>">name (z-a)</a>
    -->
</div>
<?php $UM = new Utility_Modifier(new Utility_Mod_Finder());?>
<?php foreach($PRODUCT_LIST as $product_id): ?>
<?php if($product_id['no_results'] == true): ?>
<div class="CatList <?php echo $extra_class; ?>">
Search returned no results
</div>
<?php else: ?>
	<?php if($product_id['product_id'] != ""): ?>
	<?php
		$P = Object_Factory::OF()->newObject('Product', $product_id['product_id']);
		$UM->modify($P, session_var('ubd_zip', null));
		$product_url = get_product_url($P);
		$extra_class = null;
		if(intval($P->sales_only) > 0) {
			$extra_class = "SalesOnly";
		}
	?>
<!-- CatList -->
<div class="CatList <?php echo $extra_class; ?>">
	<h2><a href="<?php echo $product_url; ?>"><?php echo $P->name; ?></a></h2>
	<br />

	<div class="Image">
		<a href="<?php echo $product_url; ?>"><img src="<?php echo $P->getDefaultImage(150); ?>" alt="<?php echo $P->getDefaultImageAlt(); ?>" /></a>
	</div>

	<div class="Desc">
		<?php foreach($P->getAttributeImages() as $image): ?>
		<img src="<?php echo $image; ?>" alt="" />
		<?php endforeach; ?>
		<?php $attributes = $P->getAttributes();?>
		<div class="clear"></div>
		<div class="ul_holder">
		<ul class="first_list">
			<?php foreach($attributes as $a_index => $A): ?>
			<li>
				<?php if(Attribute::NORMAL == $A->attribute()->display): ?>
					<?php echo $A->getName(true); ?> :
				<?php endif; ?>
				<?php echo $A->getValue(true); ?>
			</li>
			<?php endforeach; ?>
		</ul>
		</div>
		<div class="ul_holder">
			<ul class="second_list"></ul>
		</div>
		<br clear="all" />
	</div>

	<div class="AddToCart">
		<div class="Box">
			<img src="/images/small_box_top.jpg" class="block" width="152" height="6" alt="" />
			<div class="Cont">
				<form name="inline_product_form<?php echo $P->ID; ?>" id="inline_product_form<?php echo $P->ID; ?>" method="post" action="<?php echo LOC_CART; ?>">
					<input type="hidden" name="product_id" value="<?php echo $P->ID; ?>" />
					<input type="hidden" name="action" value="add_product" />
                    <input type="hidden" name="redirect" id="redirect" value="" />
                    <input type="hidden" name="redirect_url" id="redirect_url" value="" />
					<?php echo draw_xsrf_field(); ?>
					<?php
					$PL = new HTML_Template('inc/modules/product_price_table.php');
					$PL->bind('P', $P);
					$PL->render();
					?>
					<?php if(intval($P->orderable) > 0): ?>
                  <table class="detailAdd_sm">
                  <tr>
                    <td valign="middle" width="68px">Qty <input value="1" id="quantity_<?php echo $P->ID; ?>" name="quantity" type="text" size="2" style="text-align:right" /></td>
                    <td valign="bottom"><img src="/images/add_btn.jpg" width="59" height="22" align="absmiddle" onclick="javascript:addProduct(<?php echo $P->ID; ?>, '<?php echo $P->getDefaultImage(150); ?>', '<?php echo $P->name; ?>');" style="cursor:pointer" /></td>
                  </tr>
                </table>
					<?php else: ?>
					<p>(not available to order)</p>
					<?php endif; ?>

				</form>
			</div>
			<img src="/images/small_box_bottom.jpg" class="block" width="152" height="6" alt="" /> </div>
			<p>
                <div class="moreNav"><a href="/wishlist.php?action=add_product&amp;product=<?php echo $P->getID(); ?>"><img src="/images/add-to-bulb-closet-button.png" width="145" height="22" /></a></div>
                <div class="moreNav"><a href="<?php echo $product_url; ?>"><img src="/images/more_info_btn.png" width="145" height="22" /></a></div>
			</p>
		</div>
        <div class="comapareBar">
		<p class="CatCode">Catalog Code: <?php echo $P->catalog_code; ?></p>
        <p>
            <input id="compare_box_<?php echo $P->ID; ?>" type="checkbox" name="compare_box[]" value="<?php echo $P->ID; ?>" />
            <input type="image" class="go_button" src="/images/compare_prod_bttn.gif" width="90" height="11" />
        </p>

			</div>
		<div class="CatListFoot">
			<div class="CatListFootL"></div>
			<div class="CatListFootR">

		</div>
	</div>
</div>
<!-- /CatList -->
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>

<div class="Paging">
	<?php echo $PK_LINKS; ?>
</div>
<div class="Sorting">
	Sort by:
	<a href="<?php echo $PRICE_LOW_LINK; ?>">lowest price</a> |
	<a href="<?php echo $PRICE_HIGH_LINK; ?>">highest price</a> <!-- |
	<a href="<?php echo $NAME_LOW_LINK; ?>">name (a-z)</a> |
	<a href="<?php echo $NAME_HIGH_LINK; ?>">name (z-a)</a>          -->
</div>

<?php echo empty($HTML_FOOTER) ? null : $HTML_FOOTER; ?>

<br />
<br />
