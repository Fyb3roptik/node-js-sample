<header>
  <nav class='navbar navbar-default'>
    <a class='toggle-nav btn pull-left' href='#'>
      <i class='icon-reorder'></i>
    </a>
    <ul class='nav'>
      <li>
        <a href="#">RULES</a>
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
            <a href='/logout'>
              <i class='icon-signout'></i>
              Sign out
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</header>