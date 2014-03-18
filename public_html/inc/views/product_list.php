<script type="text/javascript">
$(document).ready(function() {
	$("td#add-btn-noscript").hide();

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

	$(".add-to-cart").click(function() {
		var ID = this.id;
		var product_id = $("#product_id"+ID).val();
		var product_image = $("#product_image"+ID).val();
		var product_name = $("#product_name"+ID).val();
		addProduct(product_id, product_image, product_name);
	});

});

function addProduct(ID, image, name)
{
    var quantity = $("#quantity_"+ID).val();
	var redirect = 'return';
	var data = { "action" : "add_product", "redirect" : redirect, "product_id" : ID, "quantity" : quantity, '<?php echo get_xsrf_field_name(); ?>' : '<?php echo get_xsrf_field_value(); ?>' }
	$.post('/cart.php', data, function(data) {
		$("#shopping-cart-total").html(data);
		var html = '<span id="impromptu_item_title">Item Added to Cart</span><br /><div id="impromptu_item_info_container"><div id="impromptu_item_img"><img src="'+image+'"></div><div id="impromptu_item_info">'+name+'</div><div id="impromptu_item_quantity">Quantity: '+quantity+'</div></div>';
	    $.prompt(html, {
	      buttons:{"Continue Shopping":false, Checkout:true},
	      callback: function(v,m,f) {
			if(v == true)
			{
				window.location = '/cart.php';
			}
	      }
	    });
	});



}

function addFavorites(url) {
        <?php if(false == $CUSTOMER->exists()): ?>
		window.location = url;
		<?php else: ?>
		$.ajax({
               url: url,
               async: false,
               dataType: 'html',
               success: function(data){
               var html = data;
                       $.prompt(html, {
                               buttons:{"Add Product":true, Cancel:false},
                               submit: function(v,m,f) {
                                       if(v) {
                               $("#add_product_to_wishlist_form").submit();
                       }
                               }
                       });
               }});
		<?php endif; ?>

 }

</script>
<noscript>
<style type="text/css">
td#add-btn-script {
	display: none;
}


</style>
</noscript>
<?php if(false == is_null($BREADCRUMB) && true == is_array($BREADCRUMB)): ?>
<div class="BreadCrumb">
	<a href="/">home</a> >
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
<?php if($PRODUCT_LIST[0]['no_results'] != true): ?>
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
<?php endif; ?>
<?php $UM = new Utility_Modifier(new Utility_Mod_Finder());?>
<?php foreach($PRODUCT_LIST as $product_id): ?>
<?php if($product_id['no_results'] == true): ?>
<div class="CatList <?php echo $extra_class; ?>">
Search returned no results
</div>
<?php else: ?>
	<?php if($product_id['product_id'] != ""): ?>
	<?php
		$P = new Product($product_id['product_id']);
		$UM->modify($P, session_var('ubd_zip', null));
		$product_url = get_product_url($P);
		$extra_class = null;
		if(intval($P->sales_only) > 0) {
			$extra_class = "SalesOnly";
		}

	?>
<!-- CatList -->
<div class="CatList <?php echo $extra_class; ?>">

<div class="listLeft">
<div class="CatCode">Stock Code:&nbsp;<?php echo $P->catalog_code; ?></div>	
    <h2><a href="<?php echo $product_url; ?>"  baynote_bnrank="<?php echo $product_id['baynote_bnrank'] ?>" baynote_irrank="<?php echo $product_id['baynote_irrank'] ?>"><?php echo $P->name; ?></a></h2>
	<br />

	<div class="Image">
		<a href="<?php echo $product_url; ?>" baynote_bnrank="<?php echo $product_id['baynote_bnrank'] ?>" baynote_irrank="<?php echo $product_id['baynote_irrank'] ?>"><img src="<?php echo $P->getDefaultImage(150); ?>" alt="<?php echo $P->getDefaultImageAlt(); ?>" /></a>
	</div>

	<div class="Desc">
		<?php foreach($P->getAttributeImages() as $image): ?>
		<img src="<?php echo $image; ?>" alt="" />
		<?php endforeach; ?>
		<?php $attributes = $P->getAttributes();?>
		<div class="clear"></div>
		<div class="ul_holder">
		<ul class="first_list">
			<?php if(false == is_null($attributes)): ?>
			<?php foreach($attributes as $a_index => $A): ?>
			<li>
				<?php if(Attribute::NORMAL == $A->attribute()->display): ?>
					<?php echo $A->getName(true); ?> :
				<?php endif; ?>
				<?php echo $A->getValue(true); ?>
			</li>
			<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		</div>
		<div class="ul_holder">
			<ul class="second_list"></ul>
		</div>
		<br clear="all" />
	</div> 
<!-- end listLeft --></div>

<div class="listRight">
	<div class="AddToCart">
		<div class="Box">
			<img src="/images/addcart_box_top_sm.jpg" class="block" width="168" height="7" alt="" />
			<div class="Cont">
				<form name="inline_product_form<?php echo $P->ID; ?>" id="inline_product_form<?php echo $P->ID; ?>" method="post" action="<?php echo LOC_CART; ?>">
					<input type="hidden" name="product_id" id="product_id<?php echo $P->ID; ?>" value="<?php echo $P->ID; ?>" />
					<input type="hidden" name="product_image" id="product_image<?php echo $P->ID; ?>" value="<?php echo $P->getDefaultImage(150); ?>" />
                    <input type="hidden" name="action" value="add_product_noscript" />
					<input type="hidden" name="product_name" id="product_name<?php echo $P->ID; ?>" value="<?php echo $P->name; ?>" />
					<div class="add_middle_sm">
					<?php echo draw_xsrf_field(); ?>
					<?php
					$PL = new HTML_Template('inc/modules/product_price_table.php');
					$PL->bind('P', $P);
					$PL->render();
					?>
					</div>
                    
					<?php if(intval($P->orderable) > 0): ?>
                  <div class="add_bottom_sm">
                  <table width="158" height="40" class="detailAdd_sm">
                  <tr>
                    <td align="right" valign="middle" width="78">Qty <input value="1" id="quantity_<?php echo $P->ID; ?>" name="quantity" type="text" size="1" style="text-align:right" /></td>
                    <td width="80" align="right" valign="middle" id="add-btn-script"><img src="/images/add_btn.png" width="76" height="22" align="absmiddle" class="add-to-cart" id="<?php echo $P->ID; ?>" style="cursor:pointer" /></td>
					<td width="80" align="right" valign="middle" id="add-btn-noscript"><input type="image" src="/images/add_btn.png" width="76" height="22" align="absmiddle" class="add-to-cart" id="<?php echo $P->ID; ?>" /></td>
                  </tr>
                </table></div>
					<?php else: ?>
					<div class="add_bottom_sm"><div class="notAvailable">(To Order Please Call <br />800-624-4488)</div></div>
					<?php endif; ?>

				</form>
	<!-- end Cont--></div>
	<!-- end Box--></div>
			<div class="moreNav_wrap">
                <div class="moreNav"><span onclick="javascript:addFavorites('/wishlist.php?action=add_product&amp;l=ajax&amp;product=<?php echo $P->getID(); ?>');" style="cursor:pointer;"><img src="/images/add-to-bulb-closet_lg.png" width="168" height="22" /></span></div>
                <div class="moreNav"><a href="<?php echo $product_url; ?>"><img src="/images/more_info_btn_lg.png" width="168" height="22" /></a></div>
		<?php if(true == is_a($CUSTOMER, 'Sales_Rep')): ?>
		<?php $warehouse = Product_Controller::lookupWarehouseData($P->catalog_code); ?>
		<div class="moreNav">
			Qty Available: <?php echo intval($warehouse['qty_available']); ?>
		</div>
		<?php endif; ?>
			</div>
	<!-- end AddToCart --></div>
<!-- end listRight --></div>
        <div class="comapareBar">
		
        <div>
            <input id="compare_box_<?php echo $P->ID; ?>" type="checkbox" name="compare_box[]" value="<?php echo $P->ID; ?>" />
            <input type="image" class="go_button" src="/images/compare_prod_bttn.gif" width="90" height="11" />
        </div>
            
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
<?php if($PRODUCT_LIST[0]['no_results'] != true): ?>
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
<?php endif; ?>
<?php echo empty($HTML_FOOTER) ? null : $HTML_FOOTER; ?>

<br />
<br />
