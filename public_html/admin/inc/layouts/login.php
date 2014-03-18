<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $LAYOUT_TITLE; ?></title>
	<link rel="stylesheet" href="/admin/style.css" type="text/css" />
	<script type="text/javascript" src="/js/jquery-1.4.2.min.js" ></script>
	<script type="text/javascript" src="/js/jquery.pngFix.js" ></script>
	<script type="text/javascript" src="/js/jquery.crossfade.js" ></script>
	<style type="text/css">
		#login_frame {
			width: 400px;
			margin: 0 auto;
		}
	</style>
</head>
<body>
	<div id="login_frame">
	<?php require_once VIEW_HANDLER; ?>
	</div>
</body>
</html>