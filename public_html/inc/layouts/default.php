<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta property="og:title" content="Beast Franchise"/>
    <meta property="og:image" content="<?php echo SITE_URL; ?>/img/Claw_Marks.png"/>
    <meta property="og:url" content="<?php echo SITE_URL; ?>" />
    <meta name="author" content="">
    <meta name="description" content="">
    <link rel="icon" href="favicon.ico">

    <title><?php echo $LAYOUT_TITLE; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    
    <link href='http://fonts.googleapis.com/css?family=Gravitas+One' rel='stylesheet' type='text/css'>
    
    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
	        
   

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php //require_once 'modules/javascript.php'; ?>
  	<?php require_once 'modules/meta_tags.php'; ?>
  	<?php require_once 'modules/css.php'; ?>
  </head>
	
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
    <?php require_once 'modules/header.php'; ?>
    
    <?php if($_SERVER['REQUEST_URI'] == "/"): ?>
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div class="container">
          <h1 class="header-text">Batting Order</h1>
          <h2 class="header-text">Daily Fantasy Baseball</h1>
          <iframe src="https://player.vimeo.com/video/124081239" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          <p>Learn How to Play BEAST FRANCHISE</p>
          <p>Taught by Kenny Lofton</p>
          <a class="btn btn-primary btn-lg jumbotronBtn" href="/register" role="button">Register Now</a>
        </div>
      </div>
    <?php endif; ?>
    
    <div class="container">
        
      <?php require_once VIEW_HANDLER; ?>

      <?php require_once 'modules/footer.php'; ?>
      
    </div> <!-- document div -->        

        <script>
            var _gaq=[['_setAccount','UA-44185609-1'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(function() {
          $("#username").keyup(function() {
              var username = $("#username").val();
              $.ajax({
                  url: "/customer/checkUsername", 
                  dataType: "json",
                  data: {
                      username: username
                  }, 
                  success: function(data) {
                      if(data['user_exists'] === false) {
                          console.log('good');
                          $("#username").parent().removeClass('has-error');
                          $("#username").parent().addClass("has-success");
                          
                          $(".username-control").remove();
                          $("#username").parent().append('<span class="glyphicon glyphicon-ok form-control-feedback username-control" aria-hidden="true"></span><span class="sr-only username-control">(success)</span>');
                          
                      } else {
                          $("#username").parent().removeClass('has-success');
                          $("#username").parent().addClass("has-error");
                          
                          $(".username-control").remove();
                          $("#username").parent().append('<span id="username-fail" class="glyphicon glyphicon-remove form-control-feedback username-control" aria-hidden="true"></span><span class="sr-only username-control">(error)</span>');
                      }
                  }
              }); 
          });
      });
    </script>
  </body>
</html>