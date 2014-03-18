<?php
if(true == is_array($META_LIST) && true == isset($META_LIST)) {
	foreach($META_LIST as $TAG) {
		if(true == is_a($TAG, 'Meta_Tag')) {
		?>
		<meta name="<?php echo $TAG->name; ?>" content="<?php echo $TAG->content; ?>" />
		<?php
		}
	}
}
?>
