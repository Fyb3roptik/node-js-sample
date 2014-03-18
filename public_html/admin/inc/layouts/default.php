<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $LAYOUT_TITLE; ?></title>
	<link rel="stylesheet" href="/admin/style.css" type="text/css" />
	<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.7.1.custom.css" type="text/css" />
	<link rel="stylesheet" href="/css/xqtools.css" type="text/css" />
	<style type="text/css">
		div.ui-datepicker {
			font-size: 10px;
		}
	</style>
	<script type="text/javascript" src="/js/jquery-1.5.1.min.js" ></script>
	<script type="text/javascript" src="/js/jquery.pngFix.js" ></script>
	<script type="text/javascript" src="/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script type="text/javascript" src="/js/jquery.mesh.js"></script>
	<script type="text/javascript" src="/js/jquery.xqtools-multiup.js"></script>
</head>
<body>
	<div id="mainframe">
		<div id="header">
			<div style="float: right;">
				Hello, <?php echo $ADMIN->name; ?>. 
				<a href="<?php echo LOC_LOGIN; ?>?action=logout">logout</a>
			</div>
			<strong>KaraokeVibe.com Admin</strong>
		</div>
		<div id="nav">
			<strong>Manage Plans</strong>
			<ul>
				<li><a href="/admin/plans/">Manage Plans</a></li>
			</ul>
			<br />
			<strong>Manage Clubs</strong>
			<ul>
				<li><a href="/admin/clubs/">Manage Clubs</a></li>
			</ul>
			<br />
			<strong>Manage Pages</strong>
			<ul>
				<li><a href="/admin/homepage/">Manage Homepage</a></li>
			</ul>
			<br />
			<strong>Manage Users</strong>
			<ul>
				<li><a href="<?php echo LOC_ADMIN_MANAGE; ?>">Administrators</a></li>
				<li><a href="/admin/customer/">Customers</a></li>
			</ul>
		</div>
		<div id="stage">
		<?php require_once VIEW_HANDLER; ?>
		</div>
		<div id="footer">
			<span id="build-text">Build Version: <?php echo BUILD; ?></span>
			&copy; <?php echo date('Y'); ?> KaraokeVibe.com 
		</div>
	</div>
</body>
</html>