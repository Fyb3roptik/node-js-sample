<div class='col-xs-12'>
    <div class='page-header page-header-with-buttons'>
        <h1 class='pull-left'>
          <i class='icon-star'></i>
          <span>Today's Teams</span>
        </h1>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
    <?php foreach($TEAM_LIST as $T): ?>
        <?php if($T->created_date >= strtotime('today')): ?>
            <?php $MATCH = new Match($T->match_id); ?>
            <div class='row box box-transparent'>
                <div class='col-xs-4 col-sm-2'>
                  <div class='box-quick-link purple-background'>
                    <a href='/team/view/<?php echo $T->ID; ?>'>
                      <div class='header'>
                        <div class='icon-star'></div>
                      </div>
                      <div class='content'><?php echo $MATCH->name; ?></div>
                    </a>
                  </div>
                </div>                        
            </div>
        <?php endif;?>
    <?php endforeach; ?>
        </div>
    </div>
    
    <div class='page-header page-header-with-buttons'>
        <h1 class='pull-left'>
          <i class='icon-star'></i>
          <span>Archived Teams</span>
        </h1>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
    <?php foreach($TEAM_LIST as $T): ?>
        <?php if($T->created_date < strtotime('today')): ?>
            <?php $MATCH = new Match($T->match_id); ?>
            <div class='row box box-transparent'>
                <div class='col-xs-4 col-sm-2'>
                  <div class='box-quick-link purple-background'>
                    <a href='/team/view/<?php echo $T->ID; ?>'>
                      <div class='header'>
                        <div class='icon-star'></div>
                      </div>
                      <div class='content'><?php echo $MATCH->name; ?> <?php echo date("m/d/Y", $T->created_date); ?></div>
                    </a>
                  </div>
                </div>                        
            </div>
        <?php endif;?>
    <?php endforeach; ?>
        </div>
    </div>