<script>
$(document).ready(function() {
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
    
    // Remove options from dropdowns
    $("#team\\[c\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_c = this.value;
    }).change(function() {
        $('#team\\[dh\\] option[value='+previous_c+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_c = this.value;
        $('#team\\[dh\\] option[value='+val+']').attr('disabled','disabled');
    });
    $("#team\\[1b\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_1b = this.value;
    }).change(function() {
        $('#team\\[dh\\] option[value='+previous_1b+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_1b = this.value;
        $('#team\\[dh\\] option[value='+val+']').attr('disabled','disabled');
    });
    $("#team\\[2b\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_2b = this.value;
    }).change(function() {
        $('#team\\[dh\\] option[value='+previous_2b+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_2b = this.value;
        $('#team\\[dh\\] option[value='+val+']').attr('disabled','disabled');
    });
    $("#team\\[3b\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_3b = this.value;
    }).change(function() {
        $('#team\\[dh\\] option[value='+previous_3b+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_3b = this.value;
        $('#team\\[dh\\] option[value='+val+']').attr('disabled','disabled');
    });
    $("#team\\[ss\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_ss = this.value;
    }).change(function() {
        $('#team\\[dh\\] option[value='+previous_ss+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_ss = this.value;
        $('#team\\[dh\\] option[value='+val+']').attr('disabled','disabled');
    });
    $("#team\\[of1\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_of1 = this.value;
    }).change(function() {
        $('#team\\[dh\\] option[value='+previous_of1+']').removeAttr('disabled');
        $('#team\\[of2\\] option[value='+previous_of1+']').removeAttr('disabled');
        $('#team\\[of3\\] option[value='+previous_of1+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_of1 = this.value;
        $('#team\\[dh\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of2\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of3\\] option[value='+val+']').attr('disabled','disabled');
    });
    $("#team\\[of2\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_of2 = this.value;
    }).change(function() {
        $('#team\\[dh\\] option[value='+previous_of2+']').removeAttr('disabled');
        $('#team\\[of1\\] option[value='+previous_of2+']').removeAttr('disabled');
        $('#team\\[of3\\] option[value='+previous_of2+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_of2 = this.value;
        $('#team\\[dh\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of1\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of3\\] option[value='+val+']').attr('disabled','disabled');
    });
    $("#team\\[of3\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_of3 = this.value;
    }).change(function() {
        $('#team\\[dh\\] option[value='+previous_of3+']').removeAttr('disabled');
        $('#team\\[of1\\] option[value='+previous_of3+']').removeAttr('disabled');
        $('#team\\[of2\\] option[value='+previous_of3+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_of3 = this.value;
        $('#team\\[dh\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of1\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of2\\] option[value='+val+']').attr('disabled','disabled');
    });
    $("#team\\[dh\\]").one('focus', function () {
        // Store the current value on focus and on change
        previous_dh = this.value;
    }).change(function() {
        $('#team\\[c\\] option[value='+previous_dh+']').removeAttr('disabled');
        $('#team\\[1b\\] option[value='+previous_dh+']').removeAttr('disabled');
        $('#team\\[2b\\] option[value='+previous_dh+']').removeAttr('disabled');
        $('#team\\[3b\\] option[value='+previous_dh+']').removeAttr('disabled');
        $('#team\\[ss\\] option[value='+previous_dh+']').removeAttr('disabled');
        $('#team\\[of1\\] option[value='+previous_dh+']').removeAttr('disabled');
        $('#team\\[of2\\] option[value='+previous_dh+']').removeAttr('disabled');
        $('#team\\[of3\\] option[value='+previous_dh+']').removeAttr('disabled');
        
        var val = $(this).val();
        previous_dh = this.value;
        $('#team\\[c\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[1b\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[2b\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[3b\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[ss\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of1\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of2\\] option[value='+val+']').attr('disabled','disabled');
        $('#team\\[of3\\] option[value='+val+']').attr('disabled','disabled');
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
<?php if($MATCH->locked == "0" && $CUSTOMER->ID == $TEAM->customer_id): ?>
    <div class="row">
        <div class="col-lg-9">
            <div id="message" style="display:none;"></div>
        </div>
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
                            <select class="form-control team_ss" id="team[ss]" name="team[ss]">
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
                            <select class="form-control team_dh" id="team[dh]" name="team[dh]">
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
        //setInterval('window.location.reload()', 120000);
        
        $("a.popup").click(function() {
            //window.open("/chat", "Beast Chat", "status=1, height=300, width=600, resizable=1, toolbar=no, location=no");
        });
    });
    </script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
    <script type="text/javascript" src="/flash/config.js"></script>
    <?php $U = new Customer($TEAM->customer_id); ?>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3">
           <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">LINE UP</div>
                </div>
                <div class="box-content">
                    <div class="responsive-table">
                        <table class="table table-bordered-bottom table-hover score">
                            <?php foreach($TEAM_LIST as $key => $lineup): ?>
                                <?php if($lineup['order'] > 0): ?>
                                    <?php $P = new Player($lineup['player_id']); $mlb_id = $P->mlb_id; ?>
                                    <tr>
                                        <td><?php echo $lineup['position']; ?></td>
                                        <td><?php echo $P->first_name . " " . $P->last_name; ?></td>
                                        <td><?php echo $P->player_team; ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
           </div>
        </div>
        
        <div class="col-lg-5 col-md-5 col-sm-5 hidden-xs hidden-sm">
           <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">BEAST CHAT</div>
                </div>
                <div class="box-content">
                    <?php if($CUSTOMER->exists()): ?>
                        <iframe src="/chat" width="100%" height="500"></iframe>
                    <?php else: ?>
                        <div class="alert alert-warning"><p>Please Login to use chat</p></div>
                    <?php endif; ?>
                </div>
           </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 field-margin">
            <img class="svg" src="/img/field.svg" />
        </div>
    </div>
    
    <?php if(!empty($GAMES)): ?>
    <div class="row">
        <div class="col-lg-offset-1 col-lg-12">
            <?php foreach($GAMES as $game): ?>
            <div class="shield pull-left">
                <p><center><?php echo $game['away_team_abbr'] . " " . $game['away_score']; ?> <hr /> <?php echo $game['home_team_abbr'] . " " . $game['home_score']; ?></center></p>
            </div>
            <?php endforeach; ?>

        <div class="clearfix"></div>
        <br />
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8">
           <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">BOX SCORES</div>
                </div>
                <div class="box-content">
                    <div class="table-responsive">
                        <table class="table table-bordered-bottom table-hover score">
                            <thead>
                                <th>Player</th>
                                <?php if($BAT_COUNT >= 1): ?>
                                <th>1st At Bat</th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 2): ?>
                                <th>2nd At Bat</th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 3): ?>
                                <th>3rd At Bat</th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 4): ?>
                                <th>4th At Bat</th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 5): ?>
                                <th>5th At Bat</th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 6): ?>
                                <th>6th At Bat</th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 7): ?>
                                <th>7th At Bat</th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 8): ?>
                                <th>8th At Bat</th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 9): ?>
                                <th>9th At Bat</th>
                                <?php endif; ?>
                                
                                <th>Score</th>
                            </thead>
                            <tbody>
                            
                                <?php foreach($TEAM_LIST as $key => $lineup): ?>
                                    <?php if($lineup['order'] > 0): ?>
                                        <?php $P = new Player($lineup['player_id']); $mlb_id = $P->mlb_id; ?>
                                        <tr id="<?php echo $mlb_id; ?>">
                                                                                    
                                            <td><?php echo $P->first_name . " " . $P->last_name; ?></td>
                                            
                                            <?php if($BAT_COUNT >= 1): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][0] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 2): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][1] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 3): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][2] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 4): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][3] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 5): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][4] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 6): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][5] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 7): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][6] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 8): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][7] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 9): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][8] as $at_bat_stat): ?>
                                                    <span class="label label-primary"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php //foreach($SCORE['scores'][$mlb_id]['at_bat_stat'] as $stat): ?>
                                                
                                            <?php //endforeach; ?>
                                            <td><?php if(isset($SCORE['scores'][$mlb_id]['score'])): ?><?php echo $SCORE['scores'][$mlb_id]['score']; ?><?php else: ?>0<?php endif; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <th><?php if($SCORE['done']['final_done'] == true): ?>FINAL Score<?php else: ?>Total Score<?php endif; ?></th>
                                
                                <?php if($BAT_COUNT >= 1): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 2): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 3): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 4): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 5): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 6): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 7): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 8): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <?php if($BAT_COUNT >= 9): ?>
                                <th></th>
                                <?php endif; ?>
                                
                                <th><?php echo $total; ?></th>
                            </tfoot>
                        </table>
                    </div>
                </div>
           </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">LEADERBOARD</div>
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
                                <?php $CUST_TEAM = new Team(); ?>
                                    <tr>
                                        <td><?php echo addOrdinalNumberSuffix(($k + 1)); ?></td>
                                        <td><a href="/team/view/<?php echo $T->ID; ?>"><?php echo $CUST->username; ?></a></td>
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
    <div class="row">
        <div class="col-lg-8 hidden-xs hidden-sm">
             <div class="responsive-table">
                    <table class="table table-striped table-hover table-horizontal sitPoints" cellspacing="3">
                        <thead>
                            <tr>
                                <th><span class="label label-info">SITUATION POINTS</span></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Single</td>
                                <td>Double</td>
                                <td>Triple</td>
                                <td>Homerun</td>
                                <td>Walk</td>
                                <td>Hit By Pitch</td>
                                <td>Strikeout</td>
                                <td>Run</td>
                                <td>RBI</td>
                                <td>SB</td>
                            </tr>
                            <tr>
                                <td class="empty"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>4</td>
                                <td>6</td>
                                <td>9</td>
                                <td>1</td>
                                <td>1</td>
                                <td>-.5</td>
                                <td>3</td>
                                <td>3</td>
                                <td>2</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
<?php endif; ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
    jQuery('img.svg').each(function(){
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        jQuery.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');

            // Add replaced image's ID to the new SVG
            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);

        }, 'xml');

    });
    
});

$(window).load(function() {
    <?php if($SCORE['outs'] > 0): ?>
        <?php if($SCORE['outs'] == 1): ?>
            $('#out1').css({ fill: "#F1C40F" });
        <?php endif; ?>
        
        <?php if($SCORE['outs'] == 2): ?>
            $('#out1').css({ fill: "#F1C40F" });
            $('#out2').css({ fill: "#F1C40F" });
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if(!empty($AT_BAT)): ?>
        <?php $P = new Player($AT_BAT['player_id']); ?>
        <?php if($P->exists()): ?>
            $('#base4').css({ fill: "#F1C40F" });
            $("#base4").tooltip({
                'container': 'body',
                'placement': 'bottom',
                'title': 'At Bat: <?php echo $P->first_name . " " . $P->last_name; ?>'
            }).tooltip('show');
        <?php endif; ?>
    <?php endif; ?>
    
    <?php foreach($SCORE['bases'] as $player_id => $base): ?>
        <?php $P = new Player($player_id, "mlb_id"); ?>
        <?php if($base['base'] == 1): ?>
            $('#base1').css({ fill: "#F1C40F" });
            $("#base1").tooltip({
                'container': 'body',
                'placement': 'right',
                'title': '<?php echo $P->first_name . " " . $P->last_name; ?>'
            });
        <?php endif; ?>
        <?php if($base['base'] == 2): ?>
            $('#base2').css({ fill: "#F1C40F" });
            $("#base2").tooltip({
                'container': 'body',
                'placement': 'top',
                'title': '<?php echo $P->first_name . " " . $P->last_name; ?>'
            });
        <?php endif; ?>
        <?php if($base['base'] == 3): ?>
            $('#base3').css({ fill: "#F1C40F" });
            
            $("#base3").tooltip({
                'container': 'body',
                'placement': 'left',
                'title': '<?php echo $P->first_name . " " . $P->last_name; ?>'
            });
        <?php endif; ?>
    <?php endforeach; ?>
    
    var interval = 1000 * 60 * 1;
    
    setInterval(function() {
        $.getJSON("/team/getScores/<?php echo $team_id; ?>", function(data) {
            
            // At Bat
            var at_bat = data['at_bat'];
            if('undefined' !== typeof at_bat && at_bat != "") {
                $('#base4').css({ fill: "#F1C40F" });
                
                var currentTitle = $("#base4").attr('data-original-title');
                
                if(currentTitle != "At Bat: " + at_bat) {
                    $("#base4").tooltip({
                        'container': 'body',
                        'placement': 'bottom',
                        'title': 'At Bat: ' + at_bat
                    });
                    
                    $("#base4").attr('data-original-title', 'At Bat: ' + at_bat).tooltip('fixTitle').tooltip('show');
                }
            }
            
            // Outs
            var outs = data['outs'];
            if(outs == 0) {
                $('#out1').css({ fill: "#FFF" });
                $('#out2').css({ fill: "#FFF" });
            }
            if(outs == 1) {
                $('#out1').css({ fill: "#F1C40F" });
                $('#out2').css({ fill: "#FFF" });
            }
            
            if(outs == 2) {
                $('#out1').css({ fill: "#F1C40F" });
                $('#out2').css({ fill: "#F1C40F" });
            }
            
            // On Bases
            var bases = data['bases'];
            if(typeof bases[1] == 'undefined') {
                $('#base1').css({ fill: "#FFF" });               
                
                $("#base1").attr('data-original-title', "").tooltip('fixTitle').tooltip('hide');
            }
            if(typeof bases[1] != 'undefined') {
                $('#base1').css({ fill: "#F1C40F" });
                
                var base_names = bases[1].join(",");
                
                var currentBase1 = $("#base1").attr('data-original-title');
                
                if(currentBase1 != base_names) {
                    $("#base1").tooltip({
                        'container': 'body',
                        'placement': 'top',
                        'title': base_names
                    });
                    
                    $("#base1").attr('data-original-title', base_names).tooltip('fixTitle').tooltip('show');
                }
            }
            
            if(typeof bases[2] == 'undefined') {
                $('#base2').css({ fill: "#FFF" });
                $("#base2").attr('data-original-title', "").tooltip('fixTitle').tooltip('hide');
            }
            if(typeof bases[2] != 'undefined') {
                $('#base2').css({ fill: "#F1C40F" });
                
                var base_names = bases[2].join(",");
                
                var currentBase2 = $("#base2").attr('data-original-title');
                
                if(currentBase2 != base_names) {
                    $("#base2").tooltip({
                        'container': 'body',
                        'placement': 'top',
                        'title': base_names
                    });
                    
                    $("#base2").attr('data-original-title', base_names).tooltip('fixTitle').tooltip('show');
                }
            }
            
            if(typeof bases[3] == 'undefined') {
                $('#base3').css({ fill: "#FFF" });
                $("#base3").attr('data-original-title', "").tooltip('fixTitle').tooltip('hide');
            }
            if(typeof bases[3] != 'undefined') {
                $('#base3').css({ fill: "#F1C40F" });
                
                var base_names = bases[3].join(",");
                
                var currentBase3 = $("#base3").attr('data-original-title');
                
                if(currentBase3 != base_names) {
                    $("#base3").tooltip({
                        'container': 'body',
                        'placement': 'top',
                        'title': base_names
                    });
                    
                    $("#base3").attr('data-original-title', base_names).tooltip('fixTitle').tooltip('show');
                }
            }
            
        });
    }, 10000);
});
</script>