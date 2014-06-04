<?php
$nav_file = 'modules/category_nav.php';
$phone_in_header = false; //so jank, my apologies
if(true == isset($NAV_FILE) || false == is_null($NAV_FILE)) {
	$nav_file = $NAV_FILE;
	$phone_in_header = true;
}
global $CUSTOMER;

?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid" id="navfluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="/">
              <div class="logo">BEAST FRANCHISE &trade;</div>
          </a>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#beast-nav">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="beast-nav">
            <div class="navbar-right">
                <a href="/login" class="btn btn-success btn-nav">Login</a>
                <a href="/register" class="btn btn-primary btn-nav">Register</a>
            </div>
            <div class="clearfix"></div>
        </div><!--/.navbar-collapse -->
    </div>
</nav>