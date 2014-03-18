<script type="text/javascript">
var open_tabs = new Array();
<?php foreach($P->getTabs(true) as $i => $tab):
	if(Product_Tab::OPEN == $tab->default_view): ?>
		open_tabs[<?php echo $i; ?>] = <?php echo $tab->ID; ?>;
<?php	endif;
endforeach; ?>
$(document).ready(function() {
	$("a.print_product").click(function() {
		window.print();
		return false;
	});
});

function addProduct(ID, image, name) {
	var quantity = $("#quantity").val();

	var html = '<span id="impromptu_item_title">Item Added to Cart</span><br /><div id="impromptu_item_info_container"><div id="impromptu_item_img"><img src="'+image+'"></div><div id="impromptu_item_info">'+name+'</div><div id="impromptu_item_quantity">Quantity: '+quantity+'</div></div>';

	$.prompt(html, {
		buttons:{"Continue Shopping":true, Checkout:false},
		callback: function(v,m,f) {
			if(v == true) {
				var url = window.location;
				$("input[name='redirect']").setValue("return");
				$("input[name='redirect_url']").setValue(url);
			} else {
				$("input[name='redirect']").val("");
			}
				$("#product_price_form").submit();
			}
		}
	);
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


<br clear="all" />

<h2><?php echo $P->name; ?></h2>



<div class="Details">
	<div class="Image">
		<img src="<?php echo $P->getDefaultImage(323); ?>" alt="<?php echo $P->getDefaultImageAlt(); ?>" />
	</div>



<div class="AddCartTable">
<div class="printPage"><img src="/images/print_icon2.gif" />&nbsp;<a href="#" class="print_product">Print page</a></div>

<div class="add_top"><img src="/images/addcart_box_top.jpg" align="center" width="320" height="7" alt="" /></div>
  <div class="ProdNameCode">Manufacturer&nbsp;&nbsp;#&nbsp;PartNo</div>
  <div class="DetCatCode">Catalog Code: <?php echo $P->catalog_code; ?></div> <br /> 
   
      <form id="product_price_form" method="post" action="<?php echo LOC_CART; ?>">
      <input type="hidden" name="product_id" value="<?php echo $P->ID; ?>" />
      <input type="hidden" name="action" value="add_product" />
      <input type="hidden" name="redirect" value="" />
      <input type="hidden" name="redirect_url" value="" />
      <?php echo draw_xsrf_field(); ?>
    <div class="add_middle">
      <?php
      $PL = new HTML_Template('inc/modules/product_price_table.php');
      $PL->bind('P', $P);
      $PL->render();
      ?>
    <!-- end Cont --></div>	  
      <?php if(intval($P->orderable) > 0): ?>
      
	<div class="add_bottom">
        <table width="310px" height="33px" class="detailAdd">
          <tr>
            <td valign="middle" width="156px">Qty&nbsp;&nbsp;<input name="quantity" type="text" size="2" value="1" />&nbsp;&nbsp;<?php echo $P->unit_measure; ?></td>
            <td valign="bottom"> <img src="/images/addtocart_btn.jpg" style="cursor: pointer;" onclick="javascript:addProduct(<?php echo $P->ID; ?>, '<?php echo $P->getDefaultImage(150); ?>', '<?php echo $P->name; ?>');" align="right" width="150" height="30" alt="Add to Cart" /></td>
          </tr>
        </table>

		<div class="addWishlist"><a href="/wishlist.php?action=add_product&amp;product=<?php echo $P->getID(); ?>"><img src="/images/add-to-bulb-closet-button.png" width="145" height="22" /></a></div>
	<!-- end add_bottom--></div>
	 
      <?php else: ?>
      <div class="add_error">This product isn't available for order at this time.</div>
      <?php endif; ?>
      </form>

<div class="additional"><img src="/images/TEMPadditional.gif" /></div>
<!-- end AddCartTable	--></div>

</div>

<br clear="all" />
<br />

<?php $tab_list = $P->getTabs(true);
if(count($tab_list) > 0): ?>
<script type="text/javascript">
$(document).ready(function() {
	$(function() {
		$("#tabs").tabs();
	});

	$("div.tab-div a").click(function() {
		window.open($(this).attr('href'), "tab_window");
		return false;
	});
});
</script>

<div class="product_detail_container">

<div id="tabs">
    <ul>
	<?php foreach($tab_list as $TAB): ?>
        <?php if(Product_Tab::TYPE_PDF !== $TAB->type && 'Product Information' != $TAB->title): ?>
	<li><a href="#tabs-<?php echo $TAB->ID; ?>"><?php echo $TAB->title; ?></a></li>
        <?php endif; ?>
	<?php endforeach; ?>
	</ul>
	<?php foreach($tab_list as $TAB): ?>
        <?php if(Product_Tab::TYPE_PDF !== $TAB->type && 'Product Information' != $TAB->title): ?>
    <div id="tabs-<?php echo $TAB->ID ?>" class="tab-div">
		<?php $TAB->render(); ?>
	</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<br />
<br />

<?php if(true == is_a($CUSTOMER, 'Sales_Rep')): ?>
	<hr />
	<?php require_once 'inc/views/product_detail_extra.php'; ?>
<?php endif; ?>
