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
	
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="/admin/js/respond.min.js"></script>
		<script src="/admin/css/ie6-8.css"></script>
		
	<![endif]-->	
		
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
        </div>
    </header>
	<div class="container">
		<div class="row">
		    <div id="sidebar-left" class="col-lg-2 col-sm-1">&nbsp;</div>
		    <div id="content" class="col-lg-10 col-sm-11">
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4">
                        <div class="box blue">
                            <div class="box-header">
                                <h2>Login</h2>
                            </div>
                            <div class="box-content">    
                                <?php require_once VIEW_HANDLER; ?>
                            </div>
                        </div>
                    </div>
                </div>
		    </div>
		</div>
	</div>
	
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
</body>
</html>