<header>
  <nav class='navbar navbar-default'>
    <div class="pull-left logo">
        <a href-"/"><img src="/img/beast_franchise.png" width="150" height="100%" /></a>
    </div>
    
    <nav class="pull-left">
        <div class='navigation'>
          <ul class='nav'>
            <li <?php if(strtolower($_SERVER['REQUEST_URI']) == "/".strtolower($CUSTOMER->username)): ?>class="active pull-left"<?php else: ?>class="pull-left"<?php endif; ?>>
              <a href='/<?php echo $CUSTOMER->username; ?>'>
                <span>LOBBY</span>
              </a>
            </li>
            <?php if($CUSTOMER->exists()): ?>
            <li <?php if(strtolower($_SERVER['REQUEST_URI']) == "/team/history"): ?>class="active pull-left"<?php else: ?>class="pull-left"<?php endif; ?>>
              <a href='/team/history'>
                <span>MY GAMES</span>
              </a>
            </li>
            <?php endif; ?>
            <li class="pull-left">
              <a href='mailto:support@beastfantasysports.com'>
                <span>SUPPORT</span>
              </a>
            </li>
          </ul>
        </div>
    </nav>
    
    <ul class='nav pull-right'>
      <?php if($CUSTOMER->exists()): ?>  
      <li class="balance">
        <span class='user-name'>Current Balance: <span class="text-success"><a class="text-success user_balance" href="/<?php echo $CUSTOMER->username; ?>/settings"><?php echo '$' . number_format(bcdiv(floatval($CUSTOMER->funds), 100, 2), 2); ?></a></span></span>
      </li>
      <li>
        <a href="/<?php echo $CUSTOMER->username; ?>/settings" class="btn btn-success">DEPOSIT</a>
      </li>
      <?php endif; ?>
      <li class="first">
        <a class="pull-left" href="/img/BeastFranchiseRules.pdf" target="_blank"><i class="pull-left glyphicon glyphicon-book"></i>&nbsp;&nbsp;RULES</a>
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
                <a href='/<?php echo $CUSTOMER->username; ?>/settings'>
                  <i class='icon-dollar'></i>
                  Account Settings
                </a>
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