<script type="text/javascript">
$(document).ready(function() {
    var view = '<?php echo $VIEW_AJAX; ?>';
	if(view != 'ajax')
	{
		//checkCats('<?php echo $URL; ?>');
	}
});
</script>
<?php if(count($BREADCRUMB) > 0): ?>
<div class="BreadCrumb">
	<a href="/">home</a> >
	<?php
	$crumb_links = array();
	foreach($BREADCRUMB as $crumb) {
		$crumb_links[] = get_category_link($crumb);
	}
	echo implode(' > ', $crumb_links);
	?>
</div>
<?php endif; ?>

<?php if($C->show_name > 0): ?>
<div class="prodList_header">
<span class="RedText2"><?php echo $C->getDisplayName(); ?></span>
</div>
<?php endif; ?>
<?php echo $C->header; ?>

<?php require_once 'inc/modules/category_list_cust_favs.php'; ?>

<!--Products-->
<div class="Products">
	<?php
	$rows = array();
	$row_index = 0;
	foreach($SUBCATEGORY_LIST as $i => $SUBCAT) {
		$rows[$row_index][] = $SUBCAT;
		if(count($rows[$row_index]) >= 4) {
			$row_index++;
		}
	}
?>
	<?php foreach($rows as $row): ?>
	<div class="Row">
		<ul>
			<?php foreach($row as $subcat): ?>
			<li>
				<a href="<?php echo get_category_url($subcat); ?>">
				<img src="<?php echo $subcat->getImageUrl(); ?>" alt="" /></a>
				<p>
					<a href="<?php echo get_category_url($subcat); ?>">
					<span class="Name"><?php echo $subcat->list_name; ?></span>
					</a>
					<span class="Desc"><?php if(strlen($subcat->desc_2) > 0): ?></span>
						<br />
						<span class="Desc"><?php echo $subcat->desc_2; ?></span>
					<?php endif; ?>
					<span class="Desc"><?php if(strlen($subcat->desc_3) > 0): ?></span>
						<br />
						<span class="Desc"><?php echo $subcat->desc_3; ?></span>
					<?php endif; ?>
				</p>
			</li>
			<?php endforeach; ?>
		</ul>

	</div>
    <div class="CatListFoot">
			<div class="CatListFootL"></div>
			<div class="CatListFootR">

		</div>
	</div>
	<?php endforeach; ?>
</div>
<!--/Products-->

<?php echo $C->footer; ?>
