<div class='col-lg-12 col-sm-12'>    
    <div class="row">
        <div class="box bordered-box purple-border">
            <div class="box-content">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>CONTEST</th>
                            <th>ENTRY FEE</th>
                            <th>PRIZE POOL</th>
                            <th>ENTRIES</th>
                            <th>Date</th>
                            <th>OUTCOME</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($TEAM_LIST as $T): ?>
                            <?php if($T->created_date >= strtotime('today')): ?>
                                <?php $M = new Match($T->match_id); ?>
                                <?php $TOTAL = $M->getTotalTeams(); ?>
                                <?php $team_exists = $M->teamExists($CUSTOMER->ID); ?>
                                    <tr>
                                        <td><?php echo $M->name; ?></td>
                                        <td><?php if($M->entry_fee > 0): ?>$<?php echo $M->entry_fee; ?><?php else: ?>Free<?php endif; ?></td>
                                        <td>$<?php echo $M->getPrizePool($TOTAL); ?></td>
                                        <td><?php echo $TOTAL; ?></td>
                                        <td><?php echo date("m/d/Y g:i A T", $M->start_date); ?></td>
                                        <td></td>
                                        <td>
                                            <a href="/team/view/<?php echo $T->ID; ?>" class="btn btn-info">View My Team</button>  
                                        </td>
                                    </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class='page-header page-header-with-buttons'>
        <h1 class='pull-left'>
          <i class='icon-star'></i>
          <span>Archived Teams</span>
        </h1>
    </div>
    
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="box bordered-box purple-border">
                <div class="box-content">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>CONTEST</th>
                                <th>ENTRY FEE</th>
                                <th>PRIZE POOL</th>
                                <th>ENTRIES</th>
                                <th>Date</th>
                                <th>OUTCOME</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($TEAM_LIST as $T): ?>
                                <?php if($T->created_date < strtotime('today')): ?>
                                    <?php $M = new Match($T->match_id); ?>
                                    <?php $TOTAL = $M->getTotalTeams(); ?>
                                    <?php $team_exists = $M->teamExists($CUSTOMER->ID); ?>
                                        <tr>
                                            <td><?php echo $M->name; ?></td>
                                            <td><?php if($M->entry_fee > 0): ?>$<?php echo $M->entry_fee; ?><?php else: ?>Free<?php endif; ?></td>
                                            <td>$<?php echo $M->getPrizePool($TOTAL); ?></td>
                                            <td><?php echo $TOTAL; ?></td>
                                            <td><?php echo date("m/d/Y g:i A T", $M->start_date); ?></td>
                                            <td></td>
                                            <td>
                                                <a href="/team/view/<?php echo $T->ID; ?>" class="btn btn-info">View My Team</button>  
                                            </td>
                                        </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>