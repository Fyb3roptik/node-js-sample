<header>
  <nav class='navbar navbar-default'>
    <div class="pull-left logo">
        <img src="/img/claws_white.png" /> <span class="logoText">Beast Franchise <span class="tm">&trade;</span></span>
    </div>
    <ul class='nav'>
      <li class="first">
        <a class="pull-left" href="#"><i class="pull-left glyphicon glyphicon-book"></i>&nbsp;&nbsp;RULES</a>
      </li>
      <li class='dropdown user-menu'>
        <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
          <img width="23" height="23" alt="<?php echo $CUSTOMER->name; ?>" src="<?php echo get_gravatar($CUSTOMER->email, "23"); ?>" />
          <span class='user-name'><?php echo $CUSTOMER->name; ?></span>
          <b class='caret'></b>
        </a>
        <ul class='dropdown-menu'>
          <li>
            <a href='/<?php echo $CUSTOMER->username; ?>'>
              <i class='icon-user'></i>
              Profile
            </a>
          </li>
          <li class='divider'></li>
          <li>
            <?php if($CUSTOMER->exists()): ?>
                <a href='/logout'>
                  <i class='icon-signout'></i>
                  Logout
                </a>
            <?php else: ?>
                <a href='/login'>
                  <i class='icon-signin'></i>
                  Login
                </a>
            <?php endif; ?>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</header>