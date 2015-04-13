<div class='col-md-12 col-sm-12 col-xs-12'>    
    <div class='row'>
      <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="box bordered-box purple-border">
              <div class="box-header purple-background">
                  <div class="title"><i class="fa fa-gamepad"></i> MY GAMES</div>
              </div>
              <div class="box-content">
                  <table class="table table-hover table-striped table-bordered">
                      <thead>
                          <tr>
                            <th>Match Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th>Place</th>
                            <th></th>
                          </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>Match Name</th>
                          <th>Date</th>
                          <th>Status</th>
                          <th>Score</th>
                          <th>Place</th>
                          <th></th>
                        </tr>
                      </tfoot>
                      
                      <tbody>
                        <?php foreach($HISTORY as $TEAM): ?>
                        <?php $M = new Match($TEAM['match_id']); ?>
                          <tr>
                            <td><?php echo $M->name; ?></td>
                            <td><?php echo date("m/d/Y h:i A", $M->start_date); ?></td>
                            <td><?php if($M->active == 2): ?>Game Over<?php elseif($M->active == 1): ?>Active<?php else: ?>Not Active<?php endif; ?></td>
                            <td><?php echo $TEAM['score']; ?></td>
                            <td><?php echo addOrdinalNumberSuffix($TEAM['place']); ?></td>
                            <td><a href="/team/view/<?php echo $TEAM['team_id']; ?>" class="btn btn-info"><i class="fa fa-gamepad"></i> View Team</a></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
    </div>
</div>