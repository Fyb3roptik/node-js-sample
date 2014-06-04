<div class='col-xs-12'>    
    <div class='row'>
        <div class="col-lg-offset-1 col-lg-6">
            <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">TODAY'S MATCHES</div>
                </div>
                <div class="box-content">
                    <?php if(!empty($MATCHES)): ?>
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>CONTEST</th>
                                <th>SIZE</th>
                                <th>ENTRY FEE</th>
                                <th>PRIZE POOL</th>
                                <th>ENTRIES</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($MATCHES as $M): ?>
                            <?php $TOTAL = $M->getTotalTeams(); ?>
                            <tr>
                                <td><?php echo $M->name; ?></td>
                                <td><?php echo $M->getTotalTeams(); ?></td>
                                <td><?php if($M->entry_fee > 0): ?>$<?php echo $M->entry_fee; ?><?php else: ?>Free<?php endif; ?></td>
                                <td>$<?php echo $M->getPrizePool($TOTAL); ?></td>
                                <td><?php echo $TOTAL; ?></td>
                                <td>
                                    <?php $team_exists = $M->teamExists($CUSTOMER->ID); ?>
                                    <?php if($team_exists['check'] == true): ?>
                                        <a href="/team/view/<?php echo $team_exists['team_id']; ?>" class="btn btn-info">View My Team</button>
                                    <?php else: ?>
                                        <a href="/match/joinMatch/<?php echo $M->ID; ?>" class="btn btn-success">Join Match</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
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