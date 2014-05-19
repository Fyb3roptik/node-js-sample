<?php
$nav_file = 'modules/category_nav.php';
$phone_in_header = false; //so jank, my apologies
if(true == isset($NAV_FILE) || false == is_null($NAV_FILE)) {
	$nav_file = $NAV_FILE;
	$phone_in_header = true;
}
global $CUSTOMER;

?>
<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">
              <div class="logo">BEAST FRANCHISE &trade;</div>
          </a>
        </div>
        <div class="navbar-collapse collapse">
            <div class="navbar-right">
                <a href="/login" class="btn btn-success btn-nav">Login</a>
                <a href="/register" class="btn btn-primary btn-nav">Register</a>
            </div>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>