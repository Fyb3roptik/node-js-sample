<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title><?php echo $LAYOUT_TITLE; ?></title>
	<meta name="description" content="SimpliQ - Flat & Responsive Bootstrap Admin Template.">
	<meta name="author" content="Łukasz Holeczek">
	<meta name="keyword" content="SimpliQ, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	<!-- end: Meta -->
	
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	
	<!-- start: CSS -->
	<link href="/admin/css/bootstrap.min.css" rel="stylesheet">
	<link href="/admin/css/style.css" rel="stylesheet">
	<link href="/admin/css/retina.min.css" rel="stylesheet">
	<!-- end: CSS -->
	

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	
	  	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="/admin/js/respond.min.js"></script>
		<script src="/admin/css/ie6-8.css"></script>
		
	<![endif]-->	
	
	<!-- start: JavaScript-->
	<!--[if !IE]>-->

			<script src="/admin/js/jquery-2.1.0.min.js"></script>

	<!--<![endif]-->

	<!--[if IE]>
	
		<script src="/admin/js/jquery-1.11.0.min.js"></script>
	
	<![endif]-->

	<!--[if !IE]>-->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='/admin/js/jquery-2.1.0.min.js'>"+"<"+"/script>");
		</script>

	<!--<![endif]-->

	<!--[if IE]>
	
		<script type="text/javascript">
	 	window.jQuery || document.write("<script src='/admin/js/jquery-1.11.0.min.js'>"+"<"+"/script>");
		</script>
		
	<![endif]-->
	<script src="/admin/js/jquery-migrate-1.2.1.min.js"></script>
	<script src="/admin/js/bootstrap.min.js"></script>
	
		
	
	
	<!-- page scripts -->
	<script src="/admin/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="/admin/js/jquery.ui.touch-punch.min.js"></script>
	<script src="/admin/js/jquery.sparkline.min.js"></script>
	<script src="/admin/js/fullcalendar.min.js"></script>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/admin/js/excanvas.min.js"></script><![endif]-->
	<script src="/admin/js/jquery.flot.min.js"></script>
	<script src="/admin/js/jquery.flot.pie.min.js"></script>
	<script src="/admin/js/jquery.flot.stack.min.js"></script>
	<script src="/admin/js/jquery.flot.resize.min.js"></script>
	<script src="/admin/js/jquery.flot.time.min.js"></script>
	<script src="/admin/js/jquery.autosize.min.js"></script>
	<script src="/admin/js/jquery.placeholder.min.js"></script>
	
	<!-- theme scripts -->
	<script src="/admin/js/custom.min.js"></script>
	<script src="/admin/js/core.min.js"></script>
	
	<!-- inline scripts related to this page -->
	<script src="/admin/js/pages/index.js"></script>
	
	<!-- end: JavaScript-->
		
</head>
<body>
    <header class="navbar">
        <div class="container">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".sidebar-nav.nav-collapse">
			      <span class="icon-bar"></span>
			      <span class="icon-bar"></span>
			      <span class="icon-bar"></span>
			</button>
			<a id="main-menu-toggle" class="hidden-xs open"><i class="fa fa-bars"></i></a>		
				<a class="navbar-brand col-lg-2 col-sm-1 col-xs-12" href="index.html"><img class="img-responsive" src="/img/logo.png" /></a>
            <!-- start: Header Menu -->
			<div class="nav-no-collapse header-nav">
				<ul class="nav navbar-nav pull-right">
				    <!-- start: User Dropdown -->
					<li class="dropdown">
						<a class="btn account dropdown-toggle" data-toggle="dropdown" href="index.html#">
							<div class="avatar"><img src="<?php echo get_gravatar($ADMIN->email); ?>" alt="Avatar"></div>
							<div class="user">
								<span class="hello">Welcome!</span>
								<span class="name"><?php echo $ADMIN->name; ?></span>
							</div>
						</a>
						<ul class="dropdown-menu">
							<li class="dropdown-menu-title">
								
							</li>
							<li><a href="index.html#"><i class="fa fa-user"></i> Profile</a></li>
							<li><a href="/admin/login.php?logout"><i class="fa fa-off"></i> Logout</a></li>
						</ul>
					</li>
					<!-- end: User Dropdown -->
				</ul>
			</div>
        </div>
    </header>
    <div class="container">
		<div class="row">
		    <div id="sidebar-left" class="col-lg-2 col-sm-1">
    		    <div class="nav-collapse sidebar-nav collapse navbar-collapse bs-navbar-collapse">
					<ul class="nav nav-tabs nav-stacked main-menu">
					    <li><a href="/admin/"><i class="fa fa-tachometer"></i><span class="hidden-sm"> Dashboard</span></a></li>
					    <li><a href="/admin/player"><i class="fa fa-hospital-o"></i><span class="hidden-sm"> Manage Players</span></a></li>
					    <li>
							<a class="dropmenu" href="index.html#"><i class="fa fa-users"></i><span class="hidden-sm"> Manage Users</span> <span class="label">2</span></a>
							<ul>
							    <li><a class="submenu" href="<?php echo LOC_ADMIN_MANAGE; ?>"><i class="fa fa-users"></i><span class="hidden-sm"> Administrators</span></a></li>
                                <li><a class="submenu" href="/admin/customer/"><i class="fa fa-users"></i><span class="hidden-sm"> Customers</span></a></li>
							</ul>
                        </li>
					</ul>
    		    </div>
		    </div>
		    <div id="content" class="col-lg-10 col-sm-11">
                <div class="row">
                    <?php require_once VIEW_HANDLER; ?>
                </div>
		    </div>
		</div>
	</div>
	<div class="clearfix"></div>
	
	<footer>
		
		<div class="row">
			
			<div class="col-sm-5">
				&copy; <?php echo date('Y', time()); ?> Beast Franchise
			</div><!--/.col-->
			
			<div class="col-sm-7 text-right">
				<span id="build-text">Build Version: <?php echo BUILD; ?></span>
			</div><!--/.col-->	
			
		</div><!--/.row-->	

	</footer>
</body>
</html>