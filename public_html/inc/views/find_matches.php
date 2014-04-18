<div class='col-xs-12'>
    <div class='page-header page-header-with-buttons'>
        <h1 class='pull-left'>
          <i class='icon-gamepad'></i>
          <span>Today's Matches</span>
        </h1>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class='row box box-transparent'>
                
                <?php if(!empty($MATCHES)): ?>
                    <?php foreach($MATCHES as $M): ?>
                    <div class='col-xs-4 col-sm-2'>
                      <div class='box-quick-link banana-background'>
                        <a href='/match/view/<?php echo $M->ID; ?>'>
                          <div class='header'>
                            <div class='icon-gamepad'></div>
                          </div>
                          <div class='content'><?php echo $M->name; ?></div>
                        </a>
                      </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class='col-xs-4 col-sm-2'>
                      <div class='box-quick-link red-background'>
                        <a href='#'>
                          <div class='header'>
                            <div class='icon-remove-circle'></div>
                          </div>
                          <div class='content'>NO GAMES TODAY</div>
                        </a>
                      </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>