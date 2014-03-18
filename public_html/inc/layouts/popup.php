<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $LAYOUT_TITLE; ?></title>
	<link rel="stylesheet" type="/css/text/css" href="/style.css" />
	<script type="text/javascript" src="/js/jquery-1.4.2.min.js" ></script>
	<script type="text/javascript" src="/js/jquery.numeric.pack.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#close_window").click(function() {
			self.close();
			return false;
		});

		<?php if(true == $PRINT) { ?>
		window.print();
		<?php } ?>

		$('a.print').parent().hide();
	});
	</script>
</head>
<body>
<div id="popup_header" style="text-align: right;">
	<a href="#" id="close_window">[Close Window]</a>
</div>
<div id="popup_content">
	<?php require_once VIEW_HANDLER; ?>
</div>
</body>
</html>