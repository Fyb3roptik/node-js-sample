<script type="text/javascript">
$(document).ready(function() {
    setInterval('window.location.reload()', 120000);
});
</script>
<div class="page-header">
    <h1>Team Scores</h1>
</div>

<div class="row">
    <div class="col-lg-4">
        <h2>Outs: <?php echo $SCORE['outs']; ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <span class="label label-success">First Base</span>
        <span class="label label-warning">Second Base</span>
        <span class="label label-danger">Third Base</span>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
       
        <table class="table table-striped table-bordered table-hover score">
            <thead>
                <th>Player</th>
                <th>Game Stats</th>
                <th>Score</th>
            </thead>
            <tbody>
                <?php foreach($TEAM_LIST as $key => $lineup): ?>
                    <?php if($lineup['order'] > 0): ?>
                        <?php $P = new Player($lineup['player_id']); $mlb_id = $P->mlb_id; ?>
                        <tr <?php if($SCORE['bases'][$mlb_id]['base'] == 1): ?>class="success"<?php elseif($SCORE['bases'][$mlb_id]['base'] == 2): ?>class="warning"<?php elseif($SCORE['bases'][$mlb_id]['base'] == 3): ?>class="danger"<?php endif; ?>>
                            <td><?php echo $P->first_name . " " . $P->last_name; ?></td>
                            <td>
                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'] as $stat): ?>
                                <p><?php echo $stat ." "; ?></p>
                                <?php endforeach; ?>
                            </td>
                            <td><?php if(isset($SCORE['scores'][$mlb_id]['score'])): ?><?php echo $SCORE['scores'][$mlb_id]['score']; ?><?php else: ?>0<?php endif; ?></td>
                            <td class="at_bat"><?php if($AT_BAT['player_id'] == $P->ID && $SCORE['done']['final_done'] == false): ?><button class="btn btn-lg disabled btn-primary">At Bat</button><?php endif; ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <th><?php if($SCORE['done']['final_done'] == true): ?>FINAL Score<?php else: ?>Total Score<?php endif; ?></th>
                <th></th>
                <th><?php echo $total; ?></th>
            </tfoot>
        </table>
    </div>
</div>