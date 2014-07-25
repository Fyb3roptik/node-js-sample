<script>
$(document).ready(function() {
    $(".lineup").click(function() {
    
        $("#loading").removeClass("hide");
        $("#player_select").addClass("hide");
    
        var position = $(this).attr('id');
        var team_id = $("#team_id").val();
        
        var params = { "position": position }
        
        $.get("/team/getAvailablePlayers/" + team_id, params, function(data) {
            var data = $.parseJSON(data);
            
            $("#player_select_table tbody").html("");
            
            var current = [ parseInt($("#team_CA").val()), parseInt($("#team_FB").val()), parseInt($("#team_SB").val()), parseInt($("#team_TB").val()), parseInt($("#team_SS").val()), parseInt($("#team_OF1").val()), parseInt($("#team_OF2").val()), parseInt($("#team_OF3").val()), parseInt($("#team_DH").val()) ];
            
            console.log(data);
            
            if(data == "None") {
                $("#player_none").html('<span class="text-danger">No Players Found Yet. Check Back Later</span>');
                
                $("#loading").addClass("hide");
                $("#player_select").addClass("hide");
                $("#player_none").removeClass("hide");
                $("#player_none").addClass("fadeIn");
            } else {
                for(i=0; i < data.length; i++) {
                    if($.inArray(data[i]['player_id'], current) === -1) { 
                        $("#player_select_table tbody").append("<tr><td>"+ ((typeof data[i]['is_home'] == 'Object' && data[i]['is_home'] == true) ? "@" : "") + data[i]['player_team'] +"</td><td>"+ data[i]['player'] +"</td><td>"+ ((typeof data[i]['is_home'] != 'undefined' && data[i]['is_home'] == false) ? "@" : "") + data[i]['sp_team'] +"</td><td>"+ data[i]['sp'] +"</td><td><button id=\""+ data[i]['player_id'] +"-"+ data[i]['position_original'] +"\" class=\"btn btn-info selectPlayer\">Select</button></td></tr>");
                    } else {
                        $("#player_select_table tbody").append("<tr class=\"hide\"><td>"+ ((typeof data[i]['is_home'] == 'Object' && data[i]['is_home'] == true) ? "@" : "") + data[i]['player_team'] +"</td><td>"+ data[i]['player'] +"</td><td>"+ ((typeof data[i]['is_home'] != 'undefined' && data[i]['is_home'] == false) ? "@" : "") + data[i]['sp_team'] +"</td><td>"+ data[i]['sp'] +"</td><td><button id=\""+ data[i]['player_id'] +"-"+ data[i]['position_original'] +"\" class=\"btn btn-info selectPlayer\">Select</button></td></tr>");
                    }
                }
                
                $("#loading").addClass("hide");
                $("#player_none").addClass("hide");
                $("#player_select").removeClass("hide");
                $("#player_select").addClass("fadeIn");
            }
            
            return false;
            
        });

        return false;
    });
    
    $("#player_select_table tbody").on('click', '.selectPlayer', function() {
        var id_pos = $(this).attr('id').split('-');
        
        var player_id = id_pos[0];
        var position = id_pos[1];
        var player_name = $(this).parent().parent().children(':eq(1)').html();
        var position_name = "";
        
        if(position == "CA") {
            position_name = "Catcher";
        } else if(position == "FB") {
            position_name = "1st Base";
        } else if(position == "SB") {
            position_name = "2nd Base";
        } else if(position == "TB") {
            position_name = "3rd Base";
        } else if(position == "SS") {
            position_name = "Short Stop";
        } else if(position == "OF1" || position == "OF2" || position == "OF3") {
            position_name = "Outfielder";
        } else if(position == "DH") {
            position_name = "DH";
        }
        
        $("#player_select_table tbody > tr").each(function() {
            if($(this).hasClass("hide")) {
                $(this).removeClass("hide");
            }
        });
        
        $(this).parent().parent().addClass("hide");
        
        $("#team_"+position).val(player_id);
        $("#"+position).removeClass('btn-danger').addClass('btn-info').html(player_name + " - " + position_name);
        
        $("#player_select").addClass("hide");
        $("#player_select").removeClass('fadeIn').addClass("fadeOut");
        
        
    });
    
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
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Lineups only pull players that are listed as active. If a player you want is not listed, please come back after their lineups are set and they are showing as playing today.</strong>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-2">
            <div class="box blue">
                <div class="box-header">
                    <h2>Lineup</h2>
                </div>
                <div class="box-content">
                    <form role="form" action="/team/processMyTeamPlayers" method="post">
                        <input type="hidden" id="team_id" name="team_id" value="<?php echo $TEAM->ID; ?>" />
                        
                        <div class="form-group">
                            <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "C"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="CA"><?php echo $P->first_name . " " . $P->last_name; ?> - Catcher</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="CA">Select Starting Catcher</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[c]" id="team_CA" value="<?php echo $P->ID; ?>" />
                        </div>
                        <div class="form-group">
                            <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "1B"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="FB"><?php echo $P->first_name . " " . $P->last_name; ?> - 1st Base</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="FB">Select Starting 1st Base</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[1b]" id="team_FB" value="<?php echo $P->ID; ?>" />
                        </div>
                        <div class="form-group">
                            <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "2B"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="SB"><?php echo $P->first_name . " " . $P->last_name; ?> - 2nd Base</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="SB">Select Starting 2nd Base</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[2b]" id="team_SB" value="<?php echo $P->ID; ?>" />
                        </div>
                        <div class="form-group">
                            <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "3B"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="TB"><?php echo $P->first_name . " " . $P->last_name; ?> - 3rd Base</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="TB">Select Starting 3rd Base</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[3b]" id="team_TB" value="<?php echo $P->ID; ?>" />
                        </div>
                        <div class="form-group">
                            <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "SS"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="SS"><?php echo $P->first_name . " " . $P->last_name; ?> - Short Stop</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="SS">Select Starting Short Stop</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[ss]" id="team_SS" value="<?php echo $P->ID; ?>" />
                        </div>
                        <div class="form-group">
                            <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "OF1"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="OF1"><?php echo $P->first_name . " " . $P->last_name; ?> - Outfielder</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="OF1">Select Starting Outfielder</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[of1]" id="team_OF1" value="<?php echo $P->ID; ?>" />
                        </div>

                        <div class="form-group">
                            <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "OF2"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="OF2"><?php echo $P->first_name . " " . $P->last_name; ?> - Outfielder</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="OF2">Select Starting Outfielder</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[of2]" id="team_OF2" value="<?php echo $P->ID; ?>" />
                        </div>
                        <div class="form-group">
                            <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "OF3"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="OF3"><?php echo $P->first_name . " " . $P->last_name; ?> - Outfielder</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="OF3">Select Starting Outfielder</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[of3]" id="team_OF3" value="<?php echo $P->ID; ?>" />
                        </div>
                        <div class="form-group">
                           <?php if(!empty($SELECTED_PLAYERS)): ?>
                                <?php foreach($SELECTED_PLAYERS as $key => $info): ?>
                                <?php $TEAM_LINEUP = new TeamsLineup($info['teams_lineup_id']); ?>
                                    <?php if($TEAM_LINEUP->position == "DH"): ?>
                                        <?php $P = new Player($TEAM_LINEUP->player_id); ?>
                                        <button class="btn btn-info lineup" id="DH"><?php echo $P->first_name . " " . $P->last_name; ?> - DH</button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <button class="btn btn-danger lineup" id="DH">Select Starting DH</button>
                            <?php endif; ?>
                            <input type="hidden" name="team[dh]" id="team_DH" value="<?php echo $P->ID; ?>" />
                        </div>
                        
                        <button class="btn btn-success pull-right">Save</button>
                    </form>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="box blue">
                <div class="box-header">
                    <h2>Player Select</h2>
                </div>
                <div class="box-content hide" id="loading">
                    <div class="progress progress-striped active">
                        <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                            <span class="">Loading Players</span>
                        </div>
                    </div>
                </div>
                <div class="box-content hide" id="player_none">
                
                </div>
                <div class="box-content hide" id="player_select">
                    <table id="player_select_table" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Player</th>
                                <th>vs</th>
                                <th>Starting Pitcher</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        
        <div class="col-lg-5 <?php if(empty($SELECTED_PLAYERS)): ?>hide<?php endif; ?>">
            <div class="box blue">
                <div class="box-header">
                    <h2>Batting Order</h2>
                </div>
                <div class="box-content">
                    
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
                    <button class="btn btn-success pull-right" id="saveBattingOrder">Save</button>
                    
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
                    <div class="title">LINEUP</div>
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
        
        <div class="col-lg-3 col-md-4 col-sm-12 hidden-xs field-margin">
            <img class="svg" src="/img/field.svg" />
        </div>
        <div class="clearfix"></div>
    </div>
    
    <?php if(!empty($GAMES)): ?>
    <div class="row">
        <div class="col-lg-12 hidden-xs">
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
                    <div class="title pull-left">BOX SCORES</div>
                    <div class="pull-right">
                        <span class="label label-primary">Situation</span>
                        <span class="label label-danger">3rd Out</span>
                    </div>
                </div>
                <div class="box-content">
                    <div class="table-responsive">
                        <table id="box_score" class="table table-bordered-bottom table-hover score">
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
                                                    <span class="label <?php if(isset($SCORE['scores'][$mlb_id]['at_bat_stat']['third_out'][0])): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 2): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][1] as $at_bat_stat): ?>
                                                    <span class="label <?php if(isset($SCORE['scores'][$mlb_id]['at_bat_stat']['third_out'][1])): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 3): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][2] as $at_bat_stat): ?>
                                                    <span class="label <?php if(isset($SCORE['scores'][$mlb_id]['at_bat_stat']['third_out'][2])): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 4): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][3] as $at_bat_stat): ?>
                                                    <span class="label <?php if(isset($SCORE['scores'][$mlb_id]['at_bat_stat']['third_out'][3])): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
                                            <?php if($BAT_COUNT >= 5): ?>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][4] as $at_bat_stat): ?>
                                                    <span class="label <?php if(isset($SCORE['scores'][$mlb_id]['at_bat_stat']['third_out'][4])): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <?php endif; ?>
                                            
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
                                <th><?php echo $total; ?></th>
                            </tfoot>
                        </table>
                    </div>
                </div>
           </div>
        </div>
        
        <div class="col-lg-3 col-md-4 col-sm-4">
            <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">LEADERBOARD</div>
                </div>
                <div class="box-content box-no-padding">
                    <div class="responsive-table">
                        <table id="leaderboard" class='data-table table table-bordered table-striped' style='margin-bottom:0;'>
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
                                        <td><?php if($CUST->ID == "366190"): ?><span class="label label-info">Inventor</span>&nbsp;&nbsp;&nbsp;<?php elseif($CUST->ID == "366181"): ?><span class="label label-info">Architect</span>&nbsp;&nbsp;&nbsp;<?php endif; ?><a href="/team/view/<?php echo $T->ID; ?>"><?php echo $CUST->username; ?></a></td>
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
    
    <?php if($MATCH->locked == "1"): ?>
    setInterval(function() {
        $.getJSON("/team/getScores/<?php echo $team_id; ?>", function(data) {
            //console.log(data);
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
            if(typeof bases[1] != 'object') {
                $('#base1').css({ fill: "#FFF" });               
                
                $("#base1").attr('data-original-title', "").tooltip('fixTitle').tooltip('hide');
            }
            if(typeof bases[1] == 'object') {
                $('#base1').css({ fill: "#F1C40F" });

                var base_names = bases[1][0];
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
            
            if(typeof bases[2] != 'object') {
                $('#base2').css({ fill: "#FFF" });
                $("#base2").attr('data-original-title', "").tooltip('fixTitle').tooltip('hide');
            }
            if(typeof bases[2] == 'object') {
                $('#base2').css({ fill: "#F1C40F" });
                
                var base_names = bases[2][0];
                
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
            
            if(typeof bases[3] != 'object') {
                $('#base3').css({ fill: "#FFF" });
                $("#base3").attr('data-original-title', "").tooltip('fixTitle').tooltip('hide');
            }
            if(typeof bases[3] == 'object') {
                $('#base3').css({ fill: "#F1C40F" });
                
                var base_names = bases[3][0];
                
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
            
            // Box Score
            /*$("table#box_score thead").find('tr').each(function() {
                            
                var bat_count = data['bat_count'];
                
                if(bat_count > 5) {
                    bat_count = 5;
                }
                
                var count = $("table#box_score thead").find('tr:first th').length;             
                if(bat_count >= 1 && bat_count <= 5){
                    var at_bat = "";
                    
                    var child = count - 1;
                    
                    if((child == 1 && count == 2) || (count == 2 && count < bat_count)) {
                        at_bat = child + "st";
                    }
                    
                    if((child == 2) || (count == 3 && count < bat_count)) {
                        at_bat = child + "nd";
                    }
                    
                    if((child == 3) || (count == 4 && count < bat_count)) {
                        at_bat = child + "rd";
                    }
                    
                    if((child > 3) || (count >= 5 && count < bat_count)) {
                        at_bat = child + "th";
                    }
                    
                    if(count - 1 <= bat_count) {
                    
                        $(this).find('th:nth-child(' + (count - 1) + ')').after('<th>' + at_bat + ' At Bat</th>');
                    }
                    
                    if(count - 1 <= bat_count) {
                        $("table#box_score tbody").find('tr').each(function(k, v) {
                            
                            if(typeof data['box_score'][child][k] == 'object') {
                                
                                // Update Box Score
                                for(i=0; i < Object.keys(data['box_score'][child][k]).length; i++) {
                                    if(Object.keys(data['box_score'][child][k]).length > 1) {                                    
                                        if(i == 0) {
                                            $(this).find('td').eq(child - 1).after('<td><span class="label label-primary">' + data['box_score'][child][k][i] + '</span><br />');
                                        } else {
                                            $(this).find('td').eq(child).after().append('<span class="label label-primary">' + data['box_score'][child][k][i] + '</span><br />');
                                        }
                                        
                                        if(i + 1 == Object.keys(data['box_score'][child][k]).length) {
                                            $(this).find('td').eq(child).after().append('</td>');
                                        }
                                    } else {
                                        $(this).find('td').eq(child - 1).after('<td><span class="label label-primary">' + data['box_score'][child][k][i] + '</span><br /></td>');
                                    }
                                }
                                
                                // Update Score
                                $(this).find('td:last').text(data['player_score'][k]);
                                
                                // Update Total
                                $("table#box_score tfoot").find('th:last').html(data['score_total']);
                            } else {
                                 $(this).find('td').eq(child - 1).after('<td></td>');
                            }
                        });
                    }
                    
                    if(count - 1 > bat_count) {
                        $("table#box_score tbody").find('tr').each(function(k, v) {
                            if(typeof data['box_score'][child][k] != 'undefined') {
                                if($(this).find('td').eq(child).text() != data['box_score'][child][k]) {
                                    
                                    // Update Box Score
                                    $(this).find('td').eq(child).html('<td><span class="label label-primary">' + data['box_score'][child][k] + '</span></td>');

                                    // Update Score
                                    $(this).find('td:last').text(data['player_score'][k]);
                                    
                                    // Update Total
                                    $("table#box_score tfoot").find('th:last').html(data['score_total']);
                                }
                            }
                        });
                    }
                    
                    if(count - 1 <= bat_count) {
                        $("table#box_score tfoot").find('tr').each(function() {
                            $(this).find('th:nth-child(' + child + ')').after('<th></th>');
                        });
                    }
                    
                    
                }
            });*/
            
            //Leaderboard
            var leaderboard = data['leaderboard'];
            for(i=0; i < leaderboard.length; i++) {
                var tr = $("table#leaderboard tbody").find('tr').eq(i);
                tr.find('td').eq(1).html('<a href="/team/view/'+leaderboard[i]["team_id"]+'">'+leaderboard[i]["user"]+'</a>');
                tr.find('td').eq(2).html(leaderboard[i]['score']);
            }
            
        });
    }, 10000);
    
    <?php endif; ?>
});
</script>