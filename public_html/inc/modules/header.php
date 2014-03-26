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
          <a class="navbar-brand" href="/">Beast Franchise</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <?php if($CUSTOMER->exists()): ?>
            <li class="active"><a href="/<?php echo $CUSTOMER->username; ?>">My Page</a></li>
            <?php endif; ?>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>