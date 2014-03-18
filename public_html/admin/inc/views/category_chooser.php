<script type="text/javascript">
function expand_category(category_id) {
	var $subcat_container = $("#subcats_" + category_id);
	var subcat_chooser_url = '/admin/category/chooser/' + category_id;
	$subcat_container.load(subcat_chooser_url);
}
</script>
<?php
if(count($CAT_LIST) > 0) {
?>
<div class="cat_list">
	<?php 
	foreach($CAT_LIST as $CAT) {
		$subcat_count = count($CAT->getSubcategories(null, false, true));
	?>
	<div class="cat">
		<a href="javascript:void(0);" onclick="choose_category(<?php echo $CAT->ID; ?>)"><?php echo $CAT->name; ?></a>
		<?php
		if($subcat_count > 0) {
		?>
		(<a class="subcat_expander" href="javascript:void(0)" onclick="expand_category(<?php echo $CAT->ID; ?>)">+ <?php echo $subcat_count; ?> subcategories</a>)
		<?php
		}
		?>
		<div class="subcat_list" id="subcats_<?php echo $CAT->ID; ?>"></div>
	</div>
	<?php
	}
	?>
</div>
<?php
} else {
?>
<p>
	<strong>Whoops:</strong> It looks like this category doesn't have any subcategories associated with it.
</p>
<?php
}
?>
