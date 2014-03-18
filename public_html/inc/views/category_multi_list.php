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

<?php foreach($SUBCATEGORY_LIST as $i => $SUBCAT): ?>

	<?php $sub_subcategory_list = $SUBCAT->getSubcategories('sort_order', true); ?>
	<div class="sub_cat_title"><?php echo get_category_link($SUBCAT); ?></div>

	<?php if(count($sub_subcategory_list) > 0): ?>
	<table width="100%" cellpadding="0" cellspacing="0" id="subcategory_listing">
		<?php
		$rows = array();
		$row_index = 0;
		foreach($sub_subcategory_list as $i => $sub_subcat) {
			$rows[$row_index][] = $sub_subcat;
			if(count($rows[$row_index]) >= 4) {
				$row_index++;
			}
		}
?>
		<?php foreach($rows as $row): ?>
		<tr>
			<?php foreach($row as $subcat): ?>
			<td>
				<a href="<?php echo get_category_url($subcat); ?>">
				<img src="<?php echo $subcat->getImageUrl(); ?>" alt="<?php echo $subcat->list_name; ?>" />
				</a>
				<br />
				<?php echo get_category_link($subcat); ?>
			</td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
<?php endforeach; ?>

<?php echo $C->footer; ?>