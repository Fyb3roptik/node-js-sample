<script>
$(function() {
    $( "ul.droptrue" ).sortable({
      connectWith: "ul",
      placeholder: "ui-state-highlight"
    });
    
    $( "#sortable1, #sortable2, #sortable3" ).disableSelection();
    
    $("#saveBattingOrder").click(function() {
        
        var players = [];
        
        $("#sortable1 li").each(function(i) {
            players[i] = $(this).attr('id');
        });

        $.post("/team/saveMyBattingOrder/", {players: JSON.stringify(players)}, function(data) {
            if(data == "Success") {
                console.log(data);
                $("#message").html('<div class="alert alert-success">Batting Order Saved</div>').fadeIn();
                window.setTimeout(function() { $("#message").fadeOut(); }, 3000);
            }
        });
    });
});
</script>
<style>
  #sortable1, #sortable2, #sortable3 { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; background: #CCC; padding: 5px; width: 100%;}
  #sortable1 li, #sortable2 li, #sortable3 li { margin: 5px; padding: 24px; font-size: 1.2em; width: 94%; cursor: move; color: #9564e2; }
  h2 {color: #333}
  .ui-state-highlight { height: 4.5em; line-height: 1.2em; background-color: #FFFF89; }
</style>

<div class='col-xs-12'>
<?php if($MATCH->locked == "0"): ?>
    <div class='page-header page-header-with-buttons'>
        <h1 class='pull-left'>
          <i class='icon-star'></i>
          <span>My Team for <?php echo $MATCH->name; ?></span>
        </h1>
    </div>
    
    <div class="row">
        <div class="col-lg-9">
            <div id="message" style="display:none;"></div>
        </div>
        <br />
        <br />
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="box blue">
                <div class="box-header">
                    <h2>Lineup</h2>
                </div>
                <div class="box-content">
                    <form role="form" action="/team/processMyTeamPlayers" method="post">
                        <input type="hidden" id="team_id" name="team_id" value="<?php echo $TEAM->ID; ?>" />
                        
                        <div class="form-group">
                            <label for="team[c]">C</label>
                            <select class="form-control" id="team[c]" name="team[c]">
                                <option>--C--</option>
                                <?php foreach($CA as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="team[1b]">1B</label>
                            <select class="form-control" id="team[1b]" name="team[1b]">
                                <option>--1B--</option>
                                <?php foreach($FB as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="team[2b]">2B</label>
                            <select class="form-control" id="team[2b]" name="team[2b]">
                                <option>--2B--</option>
                                <?php foreach($SB as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="team[3b]">3B</label>
                            <select class="form-control" id="team[3b]" name="team[3b]">
                                <option>--3B--</option>
                                <?php foreach($TB as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="team[ss]">SS</label>
                            <select class="form-control" id="team[ss]" name="team[ss]">
                                <option>--SS--</option>
                                <?php foreach($SS as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="team[of1]">OF</label>
                            <select class="form-control" id="team[of1]" name="team[of1]">
                                <option>--OF--</option>
                                <?php foreach($OF as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($OFS[0], $SELECTED_PLAYERS_LIST) && $OFS[0] == $P->ID): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="team[of2]">OF</label>
                            <select class="form-control" id="team[of2]" name="team[of2]">
                                <option>--OF--</option>
                                <?php foreach($OF as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($OFS[1], $SELECTED_PLAYERS_LIST) && $OFS[1] == $P->ID): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="team[of3]">OF</label>
                            <select class="form-control" id="team[of3]" name="team[of3]">
                                <option>--OF--</option>
                                <?php foreach($OF as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($OFS[2], $SELECTED_PLAYERS_LIST) && $OFS[2] == $P->ID): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="team[dh]">DH</label>
                            <select class="form-control" id="team[dh]" name="team[dh]">
                                <option>--DH--</option>
                                <?php foreach($DH as $P): ?>
                                <option value="<?php echo $P->ID; ?>" <?php if(in_array($DHS[0], $SELECTED_PLAYERS_LIST) && $DHS[0] == $P->ID): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button class="btn btn-success pull-right">Update</button>
                    </form>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="box blue">
                <div class="box-header">
                    <h2>Batting Order</h2>
                </div>
                <div class="box-content">
                    <?php if(!empty($SELECTED_PLAYERS)): ?>
                            <input type="hidden" id="team_id" name="team_id" value="<?php echo $TEAM->ID; ?>" />
                            <div class="form-group">
                                <div class="pull-left col-lg-1">
                                    <h2><span class="label label-info batting_num">1</span></h2>
                                    <h2><span class="label label-info batting_num">2</span></h2>
                                    <h2><span class="label label-info batting_num">3</span></h2>
                                    <h2><span class="label label-info batting_num">4</span></h2>
                                    <h2><span class="label label-info batting_num">5</span></h2>
                                    <h2><span class="label label-info batting_num">6</span></h2>
                                    <h2><span class="label label-info batting_num">7</span></h2>
                                    <h2><span class="label label-info batting_num">8</span></h2>
                                    <h2><span class="label label-info batting_num">9</span></h2>
                                </div>
                                <div class="col-lg-11">
                                    <ul id="sortable1" class="droptrue pull-left">
                                        <?php foreach($SELECTED_PLAYERS as $k => $SP): ?>
                                            <?php $P = new Player($SP['player_id']); ?>
                                            <li class="ui-state-default" id="<?php echo $SP['teams_lineup_id']; ?>"><?php echo $P->first_name . " " . $P->last_name; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                            <button class="btn btn-success pull-right" id="saveBattingOrder">Update</button>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <p>Please set your lineup</p>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <script type="text/javascript">
    $(document).ready(function() {
        setInterval('window.location.reload()', 120000);
    });
    </script>
    <div class='page-header page-header-with-buttons'>
        <h1 class='pull-left'>
          <i class='icon-play'></i>
          <span>My Team for <?php echo $MATCH->name; ?> Scoring</span>
        </h1>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <span class="label label-success">First Base</span>
            <span class="label label-warning">Second Base</span>
            <span class="label label-danger">Third Base</span>
            <h5>Page will auto refresh. Stats are updated every 15 min. Scores will adjust as your batters bat in order.</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
           <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">Lineup Scores</div>
                </div>
                <div class="box-content">
                    <div class="responsive-table">
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
                                                <span class="label label-primary"><?php echo $stat ." "; ?></span>&nbsp;
                                                <?php endforeach; ?>
                                            </td>
                                            <td><?php if(isset($SCORE['scores'][$mlb_id]['score'])): ?><?php echo $SCORE['scores'][$mlb_id]['score']; ?><?php else: ?>0<?php endif; ?></td>
                                            <td class="at_bat"><?php if($AT_BAT['player_id'] == $P->ID && $SCORE['done']['final_done'] == false): ?><button class="btn btn-xs disabled btn-primary">At Bat</button><?php endif; ?></td>
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
           </div>
        </div>
        
        <div class="col-lg-4">
            <div class="box bordered-box banana-border">
                <div class="box-header banana-background">
                    <div class="title">Situation Points</div>
                </div>
                <div class="box-content box-no-padding">
                    <div class="responsive-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Situation</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Single</td>
                                    <td>2 Points</td>
                                </tr>
                                <tr>
                                    <td>Double</td>
                                    <td>4 Points</td>
                                </tr>
                                <tr>
                                    <td>Triple</td>
                                    <td>6 Points</td>
                                </tr>
                                <tr>
                                    <td>Homerun</td>
                                    <td>9 Points</td>
                                </tr>
                                <tr>
                                    <td>Walk</td>
                                    <td>1 Points</td>
                                </tr>
                                <tr>
                                    <td>Hit By Pitch</td>
                                    <td>1 Points</td>
                                </tr>
                                <tr>
                                    <td>Strikeout</td>
                                    <td>-0.5 Points</td>
                                </tr>
                                <tr>
                                    <td>Run</td>
                                    <td>3 Points</td>
                                </tr>
                                <tr>
                                    <td>RBI</td>
                                    <td>3 Points</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-10">
            <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">Leaderboard</div>
                </div>
                <div class="box-content box-no-padding">
                    <div class="responsive-table">
                        <table class='data-table table table-bordered table-striped' style='margin-bottom:0;'>
                            <thead>
                                <tr>
                                    <th>Place</th>
                                    <th>User</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($LEADERBOARD as $k => $T): ?>
                                <?php $CUST = new Customer($T->customer_id); ?>
                                    <tr>
                                        <td><?php echo addOrdinalNumberSuffix(($k + 1)); ?></td>
                                        <td><?php echo $CUST->name; ?></td>
                                        <td><?php echo $T->score; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>