<div class='col-xs-12'>
    <div class='page-header page-header-with-buttons'>
        <h1 class='pull-left'>
          <i class='icon-gamepad'></i>
          <span><?php echo $MATCH->name; ?></span>
        </h1>
    </div>
    
    <div class='row'>
        <div class='col-sm-6'>
            <div class='box'>
                <div class='box-header purple-background'>
                  <div class='title'>
                    <div class='icon-gamepad'></div>
                    Match Details
                  </div>
                </div>
                <div class='box-content'>
                    <div class='col-sm-12'>
                        <div class="col-sm-6">
                            <div class='box-content box-statistic'>
                                <h3 class='title text-banana'><?php echo $TOTAL_TEAMS; ?></h3>
                                <small>Total Teams</small>
                                <div class='text-purple icon-user align-right'></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class='box-content box-statistic'>
                                <h3 class='title text-banana'><?php echo date("m/d/Y h:i A", $MATCH->start_date); ?> PST</h3>
                                <small>Match Date</small>
                                <div class='text-purple icon-time align-right'></div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <?php if($TEAM_EXISTS['check'] == false): ?>
                                <?php if($MATCH->locked == 0): ?>
                                    <a href="/match/joinMatch/<?php echo $MATCH->ID; ?>" class="btn btn-lg btn-block btn-info"><div class="icon-plus"></div> Join Match</a>
                                <?php else: ?>
                                    <button class="btn btn-lg btn-block disabled btn-danger"><div class="icon-lock"></div> Match Locked</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="/team/view/<?php echo $TEAM_EXISTS['team_id']; ?>" class="btn btn-lg btn-block btn-info"><div class="icon-eye-open"></div> View My Team</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>