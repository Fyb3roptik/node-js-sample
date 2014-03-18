<script type="text/javascript">
var open_tabs = new Array();
<?php foreach($P->getTabs(true) as $i => $tab):
	if(Product_Tab::OPEN == $tab->default_view): ?>
		open_tabs[<?php echo $i; ?>] = <?php echo $tab->ID; ?>;
<?php	endif;
endforeach; ?>
$(document).ready(function() {
	$("td#add-btn-noscript").hide();

	$("a.print_product").click(function() {
		window.print();
		return false;
	});

	$("a.lightbox").lightBox();

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
	$PA = $P->getDetailAttributes();
	?>
</div>
<?php endif; ?>

<div class="printPage"><img src="/images/print_icon2.gif" />&nbsp;<a href="#" class="print_product">Print page</a></div>
<br clear="all" />


<div class="Details">

	<div class="Image">
	<h2><?php echo $P->name; ?></h2><br />
		<img src="<?php echo $P->getDefaultImage(323); ?>" alt="<?php echo $P->getDefaultImageAlt(); ?>" />
	</div>



<div class="AddCartTable">

<div class="add_top"><img src="/images/addcart_box_top.jpg" align="center" width="320" height="7" alt="" /></div>
  <div class="ProdNameCode"><?php if(is_null($PA[0])): ?><?php else: ?><?php echo $PA[0]->getValue(true); ?>&nbsp;&nbsp;#&nbsp;<?php echo $PA[1]->getValue(true); ?><?php endif; ?></div>
  <div class="DetCatCode">Stock Code:&nbsp;<?php echo $P->catalog_code; ?></div> <br />
   
      <form id="product_price_form" method="post" action="<?php echo LOC_CART; ?>">
      <input type="hidden" name="product_id" id="product_id<?php echo $P->ID; ?>" value="<?php echo $P->ID; ?>" />
      <input type="hidden" name="product_image" id="product_image<?php echo $P->ID; ?>" value="<?php echo $P->getDefaultImage(150); ?>" />
	  <input type="hidden" name="product_name" id="product_name<?php echo $P->ID; ?>" value="<?php echo $P->name; ?>" />
	  <input type="hidden" name="action" value="add_product_noscript" />
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
<td align="right" valign="middle" width="150px">Qty&nbsp;&nbsp;<input name="quantity" id="quantity_<?php echo $P->ID; ?>" type="text" size="2" value="1" />&nbsp;&nbsp;<?php echo $P->unit_measure; ?></td>
<td valign="bottom" width="156px" id="add-btn-script"> <img src="/images/addtocart_btn.png" style="cursor: pointer;" class="add-to-cart" id="<?php echo $P->ID; ?>" align="right" width="150" height="30" alt="Add to Cart" /></td>
<td width="80" align="right" valign="middle" id="add-btn-noscript"><input type="image" src="/images/add_btn.png" width="76" height="22" align="absmiddle" class="add-to-cart" id="<?php echo $P->ID; ?>" /></td>
</tr>
</table>

		<div class="addWishlist"><span onclick="javascript:addFavorites('/wishlist.php?action=add_product&amp;l=ajax&amp;product=<?php echo $P->getID(); ?>');" style="cursor:pointer;"><img src="/images/add-to-bulb-closet-button.png" width="145" height="22" /></span></div>
	<!-- end add_bottom--></div>
	 
      <?php else: ?>
      <div class="add_bottom"><div class="notAvailable">To Order Please Call <br />800-624-4488.</div></div>
      <?php endif; ?>
      </form>

<?php if(count($P->getImages()) > 0): ?>
<div class="additional">
Additional images<br />
<div class="addbox_wrap">
	<?php foreach($P->getImages() as $image): ?>
	<div class="adbox">
		<a href="/product_image/view/<?php echo $image->ID; ?>.jpg?y=650" class="lightbox">
		<img src="/product_image/view/<?php echo $image->ID; ?>?x=50&amp;y=50" width="50" height="50" /></a>
	</div>
	<?php endforeach; ?>
<!-- end adbox_wrap--></div>
<br clear="all" />
<!-- end additional--></div>
<?php endif; ?>

<!-- end AddCartTable	--></div>

</div>
<br clear="all" />
<br />

<div class="product_detail_bottom">
<div class="product_detail_container">
<?php $tab_list = $P->getTabs(true);
if(count($tab_list) > 0): ?>
<script type="text/javascript">
$(document).ready(function() {
	$(function() {
		$("#tabs").tabs();
	});
});
</script>

<div id="tabs">
<!-- THERES SOME INLINE HERE -->
<div id="tabs" style="float:left">
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
<!-- end product_detail_container--></div>
<?php endif; ?>

<br clear="all" />
<!--end product_detail_bottom--></div>
<!-- START BAYNOTE RECS -->
<?php $RECS = Baynote_API::getRecommendations($P); ?>
<div class="BuyMore">
<div class="sidePromo_wrap">
<div class="sidePromo_top"><h3>We Also Recommend</h3></div>
<div class="sidePromo">
<br clear="all" />
<?php $n=0; ?>
<?php foreach($RECS as $rec): ?>
<?php
	$BP = Object_Factory::OF()->newObject('Product', $rec['product_id']);
	$product_url = get_product_url($BP);
?>
<div class="bn_product_rec">
	<div class="bn_image"><a href="<?php echo $product_url; ?>" baynote_req="<?php echo $rec['gr']; ?>" baynote_bnrank="<?php echo $rec['rk']; ?>" baynote_guide="<?php echo $rec['g']; ?>"><img src="<?php echo $BP->getDefaultImage(90); ?>" alt="<?php echo $BP->getDefaultImageAlt(); ?>" /></a></div>
	<br />
	<div class="bn_text"><a href="<?php echo $product_url; ?>"  baynote_req="<?php echo $rec['gr']; ?>" baynote_bnrank="<?php echo $rec['rk']; ?>" baynote_guide="<?php echo $rec['g']; ?>" id="rec-product-name-<?php echo $n; ?>"><?php echo $BP->name; ?></a></div>
	<div class="bn_price"><?php echo price_format($BP->getPrice()); ?></div>
	<br clear="all" />
</div>
<?php $n++; ?>
<?php endforeach; ?>
<!--end sidePromo--></div>
<div class="side_Promo_btm"><img src="/images/baynoteBack_bttm.png" /></div>
<!--end sidePromo_wrap--></div>
<!--end buymore--></div>
<!-- END BAYNOTE RECS -->
<br />
<br />

<?php if(true == is_a($CUSTOMER, 'Sales_Rep')): ?>

	<?php require_once 'inc/views/product_detail_extra.php'; ?>
<?php endif; ?>
