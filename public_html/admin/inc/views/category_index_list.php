<?php
if(count($SUBCATEGORY_LIST) > 0) {
?>
<script type="text/javascript">
	$(document).ready(function() {
		restripe_category();
	});
</script>
<?php
foreach($SUBCATEGORY_LIST as $i => $CAT) {
	$kittens = $CAT->getSubcategories(null, false, true);
?>
<div class="category">
	<div class="category_actions">
		(<a href="?category=<?php echo $CAT->ID; ?>">open</a> |
		<a href="/admin/category/edit/<?php echo $CAT->ID; ?>/">edit</a> |
		<a href="javascript:void(0);" class="delete">delete</a>)
	</div>
	<input type="hidden" name="category_id[]" value="<?php echo $CAT->ID; ?>" />
	<strong><?php echo $CAT->name; ?></strong>
	<?php
	if(count($kittens) > 0) {
	?>	
		[<a href="javascript:void(0)" class="cat_expand">+<?php echo count($kittens); ?> categories</a>]
	<?php
	}
?>
	<a href="javascript:void(0)" class="indent">&gt;&gt;</a>
	<div class="category_list"></div>
</div>
<?php
}
} else {
	echo "ERROR";
}
?>
