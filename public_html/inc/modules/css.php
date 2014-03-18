<?php
if(true == is_array($CSS_LIST) && true == isset($CSS_LIST)) {
	foreach($CSS_LIST as $css_url) {
	?>
	<link rel="stylesheet" href="<?php echo $css_url; ?>" type="text/css" />
	<?php
	}
}
?>
