<!DOCTYPE html>
<html lang="en">
   <head>
   	
    <meta charset="utf-8" />
		
    <title><?php echo $LAYOUT_TITLE; ?></title>						
    <meta name="description" content="" />
    <meta property="og:title" content="Beast Franchise"/>
    <meta property="og:image" content="<?php echo SITE_URL; ?>/img/shield_logo.png"/>
    <meta property="og:url" content="<?php echo SITE_URL; ?>" />
    <link rel="icon" href="favicon.ico">
    
     <!-- Meta Viewport -->
     <meta name="viewport" content="width=device-width, initial-scale=1">
    
     <!-- All CSS Styles -->
     <link type="text/css" href="/css/bootstrap.css" rel="stylesheet" media="screen" />
     <link type="text/css" href="/css/animate.css" rel="stylesheet" media="screen" />
     <link type="text/css" href="/css/swiper.css" rel="stylesheet" media="screen" />
     <link type="text/css" href="/css/owl.carousel.css" rel="stylesheet" media="screen" />
     <link type="text/css" href="/css/owl.theme.css" rel="stylesheet" media="screen" />
     <link type="text/css" href="/css/magnific-popup.css" rel="stylesheet" media="screen" />
     <link type="text/css" href="/css/style.css" rel="stylesheet" media="screen" /> 	
     <link type="text/css" href="/css/font-awesome.min.css" rel="stylesheet" media="screen" />
     <link rel="stylesheet" href="/css/fontello.css">
    
     <!-- Media Queries -->
     <link type="text/css" href="/css/media.css" rel="stylesheet" media="screen" />
        
     <!-- Modernizr -->
     <script type="text/javascript" src="/js/modernizr.js"></script> 	
    
     <!-- Comments for IE8 and Lower -->				
    
     <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
     <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
     <![endif]-->     
     
     <!-- Gradient Support IE9 -->
     <!--[if gte IE 9]>
        <style type="text/css">
          .gradient {
             filter: none;
          }
        </style>
     <![endif]-->	
     <?php //require_once 'modules/javascript.php'; ?>
     <?php //require_once 'modules/meta_tags.php'; ?>
     <?php //require_once 'modules/css.php'; ?>
      
	</head>
	<body class="overlay-gradient">
		      
	      <!-- Preloader -->
         <div id="loader-wrapper">
             <div id="loader"></div>
         </div>
         <!-- Preloader End -->
			
         <!-- Header -->
         <header>
         <?php require_once 'modules/header.php'; ?>    
         </header>
         <!-- Header -->
         <!-- Content -->
         <?php require_once VIEW_HANDLER; ?> 
         <!-- Content End -->

         <!-- Footer -->
         <footer>
           <?php require_once 'modules/footer.php'; ?>
         </footer>
         <!-- Footer -->
         
         <!-- Modal -->
        <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Beast Franchise Terms and Conditions</h4>
              </div>
              <div class="modal-body"></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
	   
         <!-- Javascript Files -->
         <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script> 		
         <script type="text/javascript" src="/js/bootstrap.min.js"></script>	
         <script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
         <script type="text/javascript" src="/js/scrollIt.js"></script>         
         <script type="text/javascript" src="/js/swiper.min.js"></script>
         <script type="text/javascript" src="/js/owl.carousel.min.js"></script>
         <script type="text/javascript" src="/js/jquery.magnific-popup.min.js"></script>
         <script type="text/javascript" src="/js/wow.min.js"></script>
         <script type="text/javascript" src="/js/jquery.stellar.min.js"></script>
         <script type="text/javascript" src="/js/jquery.ajaxchimp.min.js"></script>
         <script type="text/javascript" src="/js/jquery.particleground.min.js"></script>
         <script type="text/javascript" src="/js/custom.js"></script>
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
          <script>
              var _gaq=[['_setAccount','UA-44185609-1'],['_trackPageview']];
              (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
              g.src='//www.google-analytics.com/ga.js';
              s.parentNode.insertBefore(g,s)}(document,'script'));
          </script>
	</body>
</html>