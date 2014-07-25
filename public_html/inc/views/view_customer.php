<div class='col-md-12 col-sm-12 col-xs-12'>    
    <div class='row'>
        <div class="col-md-offset-1 col-md-8 col-sm-12 col-xs-12">
            <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">TODAY'S MATCHES</div>
                </div>
                <div class="box-content table-responsive">
                    <?php if(!empty($MATCHES)): ?>
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>CONTEST</th>
                                <th>MLB GAMES</th>
                                <th>SIZE</th>
                                <th>MAX ENTRANTS</th>
                                <th>ENTRY FEE</th>
                                <th>PRIZE POOL</th>
                                <th>ENTRIES</th>
                                <th>START TIME</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($MATCHES as $M): ?>
                            <?php $TOTAL = $M->getTotalTeams(); ?>
                            <?php 
                                $team_exists = $M->teamExists($CUSTOMER->ID); 
                                $teams_arr = explode(",", $M->match_teams);
                            ?>
                                <?php if(($M->max_entrants != -1 && ($team_exists['check'] == true || $M->getTotalTeams() < $M->max_entrants)) || ($M->max_entrants == -1)): ?>
                                <tr>
                                    <td><?php echo $M->name; ?></td>
                                    <td>
                                        <?php foreach($GAMES as $game): ?>
                                            <?php if(in_array($game['home_team'], $teams_arr)): ?>
                                                <h5><?php echo $game['away_team']; ?> @ <?php echo $game['home_team']; ?></h5>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><?php echo $M->getTotalTeams(); ?></td>
                                    <td><?php if($M->max_entrants != -1): ?><?php echo $M->max_entrants; ?><?php else: ?>Unlimited<?php endif; ?></td>
                                    <td><?php if($M->entry_fee > 0): ?>$<?php echo $M->entry_fee; ?><?php else: ?>Free<?php endif; ?></td>
                                    <td>$<?php echo $M->getPrizePool($M->max_entrants); ?></td>
                                    <td><?php echo $TOTAL; ?></td>
                                    <td><?php echo date("g:i A T", $M->start_date); ?></td>
                                    <td>
                                        <?php if($team_exists['check'] == true): ?>
                                            <a href="/team/view/<?php echo $team_exists['team_id']; ?>" class="btn btn-info">View My Team</button>
                                        <?php else: ?>
                                            <a href="/match/joinMatch/<?php echo $M->ID; ?>" class="btn btn-success">Join Match</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <div class="alert alert-danger"><p><i class='icon-remove-circle alert-icon'></i> <strong>NO MATCHES TODAY</strong></p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>