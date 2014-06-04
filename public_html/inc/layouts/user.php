<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $LAYOUT_TITLE; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        
        <link href='//d1359auewlqy3z.cloudfront.net/wp-content/uploads/SH.png' rel='shortcut icon' type='image/x-icon'>
        <link href='/img/meta_icons/apple-touch-icon.png' rel='apple-touch-icon-precomposed'>
        <link href='/img/meta_icons/apple-touch-icon-57x57.png' rel='apple-touch-icon-precomposed' sizes='57x57'>
        <link href='/img/meta_icons/apple-touch-icon-72x72.png' rel='apple-touch-icon-precomposed' sizes='72x72'>
        <link href='/img/meta_icons/apple-touch-icon-114x114.png' rel='apple-touch-icon-precomposed' sizes='114x114'>
        <link href='/img/meta_icons/apple-touch-icon-144x144.png' rel='apple-touch-icon-precomposed' sizes='144x144'>
        <link href='//fonts.googleapis.com/css?family=Cinzel:400,900' rel='stylesheet' type='text/css'>
        <!-- / START - page related stylesheets [optional] -->
        <link href="/css/plugins/bootstrap_daterangepicker/bootstrap-daterangepicker.css" media="all" rel="stylesheet" type="text/css" />
        <link href="/css/plugins/fullcalendar/fullcalendar.css" media="all" rel="stylesheet" type="text/css" />
        <link href="/css/plugins/common/bootstrap-wysihtml5.css" media="all" rel="stylesheet" type="text/css" />
        <!-- / END - page related stylesheets [optional] -->
        <!-- / bootstrap [required] -->
        <link href="/css/bootstrap/bootstrap.css" media="all" rel="stylesheet" type="text/css" />
        <!-- / theme file [required] -->
        <link href="/css/dark-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css" />
        <!-- / coloring file [optional] (if you are going to use custom contrast color) -->
        <link href="/css/theme-colors.css" media="all" rel="stylesheet" type="text/css" />
        <!-- / demo file [not required!] -->
        <link href="/css/user.css" media="all" rel="stylesheet" type="text/css" />
        <!--[if lt IE 9]>
          <script src="assets/javascripts/ie/html5shiv.js" type="text/javascript"></script>
          <script src="assets/javascripts/ie/respond.min.js" type="text/javascript"></script>
        <![endif]-->
    
    	<?php require_once 'modules/javascript.php'; ?>
    	<?php require_once 'modules/meta_tags.php'; ?>
    	<?php require_once 'modules/css.php'; ?>
    </head>
    <body class='contrast-purple main-nav-closed'>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
    <?php require_once 'modules/header_user.php'; ?>
    
    <div class="clearfix"></div>
    
    <div class="row">
        <nav>
            <div class='navigation'>
              <ul class='nav'>
                <li <?php if(strtolower($_SERVER['REQUEST_URI']) == "/".strtolower($CUSTOMER->username)): ?>class="active pull-left"<?php else: ?>class="pull-left"<?php endif; ?>>
                  <a href='/<?php echo $CUSTOMER->username; ?>'>
                    <span>Dashboard</span>
                  </a>
                </li>
                
                <li <?php if($_SERVER['REQUEST_URI'] == "/match/find/"): ?>class="active pull-left"<?php else: ?>class="pull-left"<?php endif; ?>>
                  <a href='/match/find/'>
                    <span>Find Matches</span>
                  </a>
                </li>
                
                <li <?php if($_SERVER['REQUEST_URI'] == "/team/find/"): ?>class="active pull-left"<?php else: ?>class="pull-left"<?php endif; ?>>
                  <a href='/team/find/'>
                    <span>My Teams</span>
                  </a>
                </li>
              </ul>
            </div>
        </nav>
    </div>
    
    <div id='wrapper'>
      <section id='content'>
        <div class='container'>            
            <div class='row' id='content-wrapper'>
                
                <?php require_once VIEW_HANDLER; ?>
            </div>
        
            <footer id='footer'>
                <div class='footer-wrapper'>
                  <div class='row'>
                    <div class='col-sm-6 text'>
                      Copyright &copy; <?php echo date('Y', time()); ?> Beast Fantasy Sports Inc | All Rights Reserved
                    </div>
                  </div>
                </div>
            </footer>
        </div>
      </section>
      
      <div class="clearfix"></div>
      
    </div> 
    </body>
</html>