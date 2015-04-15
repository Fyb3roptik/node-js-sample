<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.3.0/moment-timezone.min.js"></script>
<script type="text/javascript" src="/js/skycons.js"></script>
<script>
$(document).ready(function() {    
    var start_time = moment("<?php echo date("m/d/Y g:i A T", $MATCH->start_date); ?>").format("LLL");
    
    $(".match_start_time").html("Match starts " + start_time);
    
    $(".game_start_time").each(function() {
      var time = moment("<?php echo date("m/d/Y", time()) . " "; ?>" + $(this).html() + "<?php echo " " . date("T", time()); ?>").format("h:mm A");
      $(this).html(time);
    });
    
    $("#player_select").on('click', '.selectPlayer', function() {
      var id_pos = $(this).attr('id').split('-');
      
      var player_id = id_pos[0];
      var position = id_pos[1];
      var position_name = "";
      
      $.get("/team/getPlayerInfo/"+player_id, function(data) {
        
        var confirmed;
        if(data['confirmed'] == true) {
          confirmed = '<span class="label label-success">Confirmed Starter</span>';
        } else {
          confirmed = '<span class="label label-danger">Unconfirmed Starter</span>';
        }
        
        var home;
        if(data['is_home'] == true) {
          home = data['sp_team'] + '@<strong>' + data['player_team'] + '</strong>';
        } else {
          home = '<strong>' + data['player_team'] + '</strong>@' + data['sp_team'];
        }
        
        var html = '<div class="pull-left"><img width="48" height="100%" src="/img/player-image/'+data['mlb_id']+'.jpg" class="img-thumbnail img-responsive" /></div><div class="player-info pull-left"><div class="playerSelect-name">'+data['player']+'&nbsp;</div><div>'+confirmed+'</div><div>'+home+'</div><div>Facing: <span class="text-info"><strong>'+data['sp']+'</strong></span></div></div><div class="clearfix"></div>';
        
        var current_player_id = $("#team_CA").val();

        if($(".select-player-"+current_player_id).hasClass('hide')) {
          $(".select-player-"+current_player_id).removeClass('hide');
        }
        
        $(".select-player-"+player_id).addClass('hide');
        
        $("#team_"+position).val(player_id);
        
        $("#"+position).html(html);
        
        return false;
      }, "json");
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
          $("#message").html('<div class="alert alert-success">Batting Order Saved</div>').fadeIn();
          window.setTimeout(function() { $("#message").fadeOut(); }, 3000);
        }
      });
    });
    
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
    
    var icons = new Skycons({"color":"#555a92"}),
        list  = [
          "clear-day", "clear-night", "partly-cloudy-day",
          "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
          "fog"
        ],
        i;
    for(i = list.length; i--; ) {
        var weatherType = list[i],
            elements = document.getElementsByClassName( weatherType );
        for (e = elements.length; e--;){
            icons.set( elements[e], weatherType );
        }
    }
    icons.play();
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
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong class="match_start_time"></strong>
            </div>
        </div>
    </div>
    <?php if(!empty($SELECTED_PLAYERS)): ?>
    <?php $games_keys = array_keys($GAMES_INFO); ?>
      <?php for($sp = 0; $sp < count($SELECTED_PLAYERS); $sp++): ?>
      <?php $TEAM_LINEUP = new TeamsLineup($SELECTED_PLAYERS[$sp]['teams_lineup_id']); ?>
        <?php $position = $TEAM_LINEUP->position; $P = new Player($TEAM_LINEUP->player_id); ?>
        <?php for($g = 0; $g < count($GAMES_INFO); $g++): ?>
          <?php if($P->player_team == $GAMES_INFO[$games_keys[$g]]['home_team'] || $P->player_team == $GAMES_INFO[$games_keys[$g]]['away_team']):?>
            <?php
              
              if($GAMES_INFO[$games_keys[$g]]['home_team'] == $P->player_team) {
                $key = "home_team";
              } elseif($GAMES_INFO[$games_keys[$g]]['away_team'] == $P->player_team) {
                $key = "away_team";
              }
              
              if($key == "home_team" || $key == "away_team") {
                $players_team = $GAMES_INFO[$games_keys[$g]][$key . "_abbr"];
                $weather_team = $GAMES_INFO[$games_keys[$g]]['home_team'];
                
                if($key == "home_team") {
                    $is_home = true;
                    $sps_team = "away_team_abbr";
                    $sps = "away_pitcher";
                } else {
                    $is_home = false;
                    $sps_team = "home_team_abbr";
                    $sps = "home_pitcher";
                }
                
                $sp_team = $GAMES_INFO[$games_keys[$g]][$sps_team];
                $starting_pitcher = stripslashes($GAMES_INFO[$games_keys[$g]][$sps]);
                
                $LIST = $LINEUP[$players_team];
                $player_name = $P->first_name . " " . $P->last_name;
                
                $confirmed = false;
                if(in_array($player_name, $LIST)) {
                  $confirmed = true; 
                }
  
                $players_arr[$position] = array("player_id" => $P->ID, "mlb_id" => $P->mlb_id, "players_team" => $players_team, "is_home" => $is_home, "sps_team" => $sps_team, "sps" => $sps, "sp_team" => $sp_team, "sp" => $starting_pitcher, "player_name" => $player_name, "confirmed" => $confirmed, "weather_team" => $weather_team);
                                
                continue 2;
              }
            ?>
          <?php endif; ?>
        <?php endfor; ?>
      <?php endfor; ?>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-3">
            <div class="box blue">
                <div class="box-header contrast-background">
                    <h2>Lineup</h2>
                </div>
                <div class="box-content">
                    <form role="form" action="/team/processMyTeamPlayers" method="post">
                        <input type="hidden" id="team_id" name="team_id" value="<?php echo $TEAM->ID; ?>" />
                        
                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "C"): ?>
                                    <div class="well well-sm" id="CA">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[c]" id="team_CA" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="CA">
                                  <span class="text-muted">Select Catcher</span>
                                </div>
                                <input type="hidden" name="team[c]" id="team_CA" value="" />
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "1B"): ?>
                                    <div class="well well-sm" id="FB">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[1b]" id="team_FB" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="FB">
                                  <span class="text-muted">Select First Base</span>
                                </div>
                                <input type="hidden" name="team[1b]" id="team_FB" value="" />
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "2B"): ?>
                                    <div class="well well-sm" id="SB">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[2b]" id="team_SB" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="SB">
                                  <span class="text-muted">Select Second Base</span>
                                </div>
                                <input type="hidden" name="team[2b]" id="team_SB" value="" />
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "3B"): ?>
                                    <div class="well well-sm" id="TB">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[3b]" id="team_TB" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="TB">
                                  <span class="text-muted">Select Third Base</span>
                                </div>
                                <input type="hidden" name="team[3b]" id="team_TB" value="" />
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "SS"): ?>
                                    <div class="well well-sm" id="SS">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[ss]" id="team_SS" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="SS">
                                  <span class="text-muted">Select Shortstop</span>
                                </div>
                                <input type="hidden" name="team[ss]" id="team_SS" value="" />
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "OF1"): ?>
                                    <div class="well well-sm" id="OF1">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[of1]" id="team_OF1" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="OF1">
                                  <span class="text-muted">Select Outfield 1</span>
                                </div>
                                <input type="hidden" name="team[of1]" id="team_OF1" value="" />
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "OF2"): ?>
                                    <div class="well well-sm" id="OF2">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[of2]" id="team_OF2" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="OF2">
                                  <span class="text-muted">Select Outfield 2</span>
                                </div>
                                <input type="hidden" name="team[of2]" id="team_OF2" value="" />
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "OF3"): ?>
                                    <div class="well well-sm" id="OF3">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[of3]" id="team_OF3" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="OF3">
                                  <span class="text-muted">Select Outfield 3</span>
                                </div>
                                <input type="hidden" name="team[of3]" id="team_OF3" value="" />
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <?php if(!empty($players_arr)): ?>
                              <?php foreach($players_arr as $position => $player): ?>
                                <?php if($position == "DH"): ?>
                                    <div class="well well-sm" id="DH">
                                      <div class="pull-left">
                                        <img width="72" height="100%" src="/img/player-image/<?php echo $player['mlb_id']; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $player['player_name']; ?>
                                        </div>
                                        <div>
                                          <?php if($player['confirmed'] == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($player['is_home'] == true): ?>
                                            <?php echo $player['sp_team']; ?>@<strong><?php echo $player['players_team']; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $player['players_team']; ?></strong>@<?php echo $player['sp_team']; ?>
                                          <?php endif; ?>
                                        </div>
                                        <div>Facing: <span class="text-info"><strong><?php echo $player['sp']; ?></strong></span></div>
                                        <div><canvas class="<?php echo $WEATHER[$player['weather_team']]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$player['weather_team']]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$player['weather_team']]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$player['weather_team']]['precipProbability'] * 100)); ?>%</strong></div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </div>
                                    <input type="hidden" name="team[dh]" id="team_DH" value="<?php echo $player['player_id']; ?>" />
                                <?php endif; ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well well-sm" id="DH">
                                  <span class="text-muted">Select DH</span>
                                </div>
                                <input type="hidden" name="team[dh]" id="team_DH" value="" />
                            <?php endif; ?>
                        </div>
                        
                        <button class="btn btn-success btn-block">Save</button>
                    </form>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="box blue">
                <div class="box-header contrast-background">
                    <h2>Player Select</h2>
                </div>
                <div class="box-content">
                    <div role="tabpanel">
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#Catchers" aria-controls="Catchers" role="tab" data-toggle="tab">C</a></li>
                        <li role="presentation"><a href="#FirstBasemen" aria-controls="FirstBasemen" role="tab" data-toggle="tab">1B</a></li>
                        <li role="presentation"><a href="#SecondBasemen" aria-controls="SecondBasemen" role="tab" data-toggle="tab">2B</a></li>
                        <li role="presentation"><a href="#ThirdBasemen" aria-controls="ThirdBasemen" role="tab" data-toggle="tab">3B</a></li>
                        <li role="presentation"><a href="#Shortstops" aria-controls="Shortstops" role="tab" data-toggle="tab">SS</a></li>
                        <li role="presentation"><a href="#Outfield1" aria-controls="Outfield1" role="tab" data-toggle="tab">OF1</a></li>
                        <li role="presentation"><a href="#Outfield2" aria-controls="Outfield2" role="tab" data-toggle="tab">OF2</a></li>
                        <li role="presentation"><a href="#Outfield3" aria-controls="Outfield3" role="tab" data-toggle="tab">OF3</a></li>
                        <li role="presentation"><a href="#DesignatedHitters" aria-controls="DesignatedHitters" role="tab" data-toggle="tab">DH</a></li>
                      </ul>
                    </div>
                    <div class="tab-content" id="player_select">
                      <div role="tabpanel" class="tab-pane fade in active" id="Catchers">
                        <?php foreach($CA as $Catcher): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($Catcher->player_team, $info)):?>
                            <?php
                              $key = array_search($Catcher->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $Catcher->first_name . " " . $Catcher->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $Catcher->ID; ?> <?php if(in_array($Catcher->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $Catcher->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $Catcher->first_name . " " . $Catcher->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $Catcher->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($Catcher->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $Catcher->injury_info; ?>"><?php echo $Catcher->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $Catcher->ID; ?>-CA" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade in" id="FirstBasemen">
                        <?php foreach($FB as $First): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($First->player_team, $info)):?>
                            <?php
                              $key = array_search($First->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $First->first_name . " " . $First->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $First->ID; ?> <?php if(in_array($Catcher->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $First->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $First->first_name . " " . $First->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $First->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($First->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $First->injury_info; ?>"><?php echo $First->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $First->ID; ?>-FB" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade in" id="SecondBasemen">
                        <?php foreach($SB as $Second): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($Second->player_team, $info)):?>
                            <?php
                              $key = array_search($Second->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $Second->first_name . " " . $Second->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $Second->ID; ?> <?php if(in_array($Second->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $Second->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $Second->first_name . " " . $Second->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $Second->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($Second->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $Second->injury_info; ?>"><?php echo $Second->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $Second->ID; ?>-SB" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade in" id="ThirdBasemen">
                        <?php foreach($TB as $Third): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($Third->player_team, $info)):?>
                            <?php
                              $key = array_search($Third->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $Third->first_name . " " . $Third->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $Third->ID; ?> <?php if(in_array($Third->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $Third->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $Third->first_name . " " . $Third->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $Third->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($Third->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $Third->injury_info; ?>"><?php echo $Third->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $Third->ID; ?>-TB" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade in" id="Shortstops">
                        <?php foreach($SS as $Shortstop): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($Shortstop->player_team, $info)):?>
                            <?php
                              $key = array_search($Shortstop->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $Shortstop->first_name . " " . $Shortstop->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $Shortstop->ID; ?> <?php if(in_array($Shortstop->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $Shortstop->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $Shortstop->first_name . " " . $Shortstop->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $Shortstop->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($Shortstop->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $Shortstop->injury_info; ?>"><?php echo $Shortstop->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $Shortstop->ID; ?>-SS" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade in" id="Outfield1">
                        <?php foreach($OF as $Outfield1): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($Outfield1->player_team, $info)):?>
                            <?php
                              $key = array_search($Outfield1->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $Outfield1->first_name . " " . $Outfield1->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $Outfield1->ID; ?> <?php if(in_array($Outfield1->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $Outfield1->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $Outfield1->first_name . " " . $Outfield1->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $Outfield1->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($Outfield1->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $Outfield1->injury_info; ?>"><?php echo $Outfield1->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $Outfield1->ID; ?>-OF1" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade in" id="Outfield2">
                        <?php foreach($OF as $Outfield2): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($Outfield2->player_team, $info)):?>
                            <?php
                              $key = array_search($Outfield2->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $Outfield2->first_name . " " . $Outfield2->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $Outfield2->ID; ?> <?php if(in_array($Outfield2->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $Outfield2->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $Outfield2->first_name . " " . $Outfield2->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $Outfield2->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($Outfield2->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $Outfield2->injury_info; ?>"><?php echo $Outfield2->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $Outfield2->ID; ?>-OF2" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade in" id="Outfield3">
                        <?php foreach($OF as $Outfield3): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($Outfield3->player_team, $info)):?>
                            <?php
                              $key = array_search($Outfield3->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $Outfield3->first_name . " " . $Outfield3->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $Outfield3->ID; ?> <?php if(in_array($Outfield3->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $Outfield3->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $Outfield3->first_name . " " . $Outfield3->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $Outfield3->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($Outfield3->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $Outfield3->injury_info; ?>"><?php echo $Outfield3->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $Outfield3->ID; ?>-OF3" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade in" id="DesignatedHitters">
                        <?php foreach($DH as $DesignatedHitter): ?>
                        <?php foreach($GAMES as $game => $info): ?>
                          <?php if(in_array($DesignatedHitter->player_team, $info)):?>
                            <?php
                              $key = array_search($DesignatedHitter->player_team, $info);
                              $players_team = $GAMES[$game][$key . "_abbr"];
                              $weather_team = $GAMES[$game]['home_team'];
                              
                              if($key == "home_team") {
                                  $is_home = true;
                                  $sps_team = "away_team_abbr";
                                  $sps = "away_pitcher";
                              } else {
                                  $is_home = false;
                                  $sps_team = "home_team_abbr";
                                  $sps = "home_pitcher";
                              }
                              
                              $sp_team = $GAMES[$game][$sps_team];
                              $sp = stripslashes($GAMES[$game][$sps]);
                            ?>
                          <?php endif; ?>
                          <?php $player_name = $DesignatedHitter->first_name . " " . $DesignatedHitter->last_name; $LIST = $LINEUP[$players_team]; ?>
                          <?php if(in_array($player_name, $LIST)): ?>
                            <?php $confirmed = true; ?>
                          <?php else: ?>
                            <?php $confirmed = false; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                          <div class="well well-sm select-player-<?php echo $DesignatedHitter->ID; ?> <?php if(in_array($DesignatedHitter->ID, $SELECTED_PLAYERS_LIST)): ?>hide<?php endif; ?>">
                            <div class="pull-left">
                              <img width="48" height="100%" src="/img/player-image/<?php echo $DesignatedHitter->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                            </div>
                            <div class="player-info pull-left">
                              <div class="playerSelect-name">
                                <?php echo $DesignatedHitter->first_name . " " . $DesignatedHitter->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php elseif($confirmed != true && $DesignatedHitter->injury_status != ''): ?><span class="label label-danger">Injured</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?> <?php if($DesignatedHitter->injury_status != ''): ?><span class="label label-warning injured" data-toggle="tooltip" data-placement="right" title="<?php echo $DesignatedHitter->injury_info; ?>"><?php echo $DesignatedHitter->injury_status; ?></span><?php endif; ?>
                              </div>
                              <div>
                                <?php if($is_home == true): ?>
                                  <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                <?php else: ?>
                                  <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                <?php endif; ?>
                              </div>
                              <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                              <div><strong><span class="text-warning">Weather Forecast:</span></strong> <canvas class="<?php echo $WEATHER[$weather_team]['icon']; ?>" width="20" height="20"></canvas> <?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong></div>
                            </div>
                            <button id="<?php echo $DesignatedHitter->ID; ?>-DH" class="btn btn-info pull-right selectPlayer">Select Player</button>
                            <div class="clearfix"></div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-lg-4 <?php if(empty($SELECTED_PLAYERS)): ?>hide<?php endif; ?>">
            <div class="box blue">
                <div class="box-header contrast-background">
                    <h2>Batting Order</h2>
                </div>
                <div class="box-content">
                    
                    <input type="hidden" id="team_id" name="team_id" value="<?php echo $TEAM->ID; ?>" />
                    <div class="form-group">
                        <div class="pull-left col-lg-1 batting-order-numbers">
                            <span class="label label-info batting_num">1</span>
                            <span class="label label-info batting_num">2</span>
                            <span class="label label-info batting_num">3</span>
                            <span class="label label-info batting_num">4</span>
                            <span class="label label-info batting_num">5</span>
                            <span class="label label-info batting_num">6</span>
                            <span class="label label-info batting_num">7</span>
                            <span class="label label-info batting_num">8</span>
                            <span class="label label-info batting_num">9</span>
                        </div>
                        <div class="col-lg-11">
                            <ul id="sortable1" class="droptrue pull-left batting-order-sort">
                                <?php foreach($SELECTED_PLAYERS as $k => $SP): ?>
                                    <?php $P = new Player($SP['player_id']); ?>
                                    <?php foreach($GAMES as $game => $info): ?>
                                      <?php if(in_array($P->player_team, $info)):?>
                                        <?php
                                          $key = array_search($P->player_team, $info);
                                          $players_team = $GAMES[$game][$key . "_abbr"];
                                          $weather_team = $GAMES[$game]['home_team'];
                                          
                                          if($key == "home_team") {
                                              $is_home = true;
                                              $sps_team = "away_team_abbr";
                                              $sps = "away_pitcher";
                                          } else {
                                              $is_home = false;
                                              $sps_team = "home_team_abbr";
                                              $sps = "home_pitcher";
                                          }
                                          
                                          $sp_team = $GAMES[$game][$sps_team];
                                          $sp = stripslashes($GAMES[$game][$sps]);
                                        ?>
                                      <?php endif; ?>
                                      <?php $player_name = $P->first_name . " " . $P->last_name; $LIST = $LINEUP[$players_team]; ?>
                                      <?php if(in_array($player_name, $LIST)): ?>
                                        <?php $confirmed = true; ?>
                                      <?php else: ?>
                                        <?php $confirmed = false; ?>
                                      <?php endif; ?>
                                    <?php endforeach; ?>
                                    <li class="ui-state-default batting-sort-item" id="<?php echo $SP['teams_lineup_id']; ?>">
                                      <div class="pull-left">
                                        <img width="60" height="100%" src="/img/player-image/<?php echo $P->mlb_id; ?>.jpg" class="img-thumbnail img-responsive" />
                                      </div>
                                      <div class="player-info pull-left">
                                        <div class="playerSelect-name">
                                          <?php echo $P->first_name . " " . $P->last_name; ?>&nbsp;<?php if($confirmed == true): ?><span class="label label-success">Confirmed Starter</span><?php else: ?><span class="label label-danger">Unconfirmed Starter</span><?php endif; ?>
                                        </div>
                                        <div>
                                          <?php if($is_home == true): ?>
                                            <?php echo $sp_team; ?>@<strong><?php echo $players_team; ?></strong>
                                          <?php else: ?>
                                            <strong><?php echo $players_team; ?></strong>@<?php echo $sp_team; ?>
                                          <?php endif; ?>
                                          &nbsp;<?php echo $WEATHER[$weather_team]['summary']; ?> <strong><?php echo sprintf("%.0f", $WEATHER[$weather_team]['temperature']); ?>&deg;F</strong> Chance of Precip <strong><?php echo sprintf("%.0f", ($WEATHER[$weather_team]['precipProbability'] * 100)); ?>%</strong>
                                        </div>
                                        <div>Facing <span class="text-info"><strong><?php echo $sp; ?></strong></span> on the mound</div>
                                      </div>
                                      <div class="clearfix"></div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <button class="btn btn-success btn-block" id="saveBattingOrder">Save</button>
                    
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
        
        <div class="col-lg-4 col-md-3 col-sm-4">

            <div class="col-lg-12">
              <div class="box bordered-box purple-border">
                  <div class="box-header purple-background">
                      <div class="title">LEADERBOARD</div>
                  </div>
                  <div class="box-content box-no-padding">
                      <div class="responsive-table">
                          <table id="leaderboard" class='data-table table table-bordered table-striped table-hover' style='margin-bottom:0;'>
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
                                      <tr <?php if(($k + 1) == 1): ?>class="success"<?php else: ?>class="danger"<?php endif; ?>>
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
            
            <div class="col-lg-12">
              <div class="field-margin">
                <img class="svg" src="/img/field.svg" />
              </div>
            </div>
        </div>
        
        <div class="col-lg-8 col-md-6 col-sm-8">
           <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title pull-left"><?php echo strtoupper($U->username); ?>'S BOX SCORES</div>
                    <div class="pull-right">
                        <span class="label label-primary">Situation</span>
                        <span class="label label-danger">3rd Out</span>
                    </div>
                </div>
                <div class="box-content">
                    <div class="table-responsive">
                        <table id="box_score" class="table table-bordered-bottom table-striped table-hover score">
                            <thead>
                                <th>Player</th>
                                <th>1st At Bat</th>
                                <th>2nd At Bat</th>
                                <th>3rd At Bat</th>
                                <th>4th At Bat</th>
                                <th>Score</th>
                            </thead>
                            <tbody>
                                <?php foreach($TEAM_LIST as $key => $lineup): ?>
                                    <?php if($lineup['order'] > 0): ?>
                                        <?php $P = new Player($lineup['player_id']); $mlb_id = $P->mlb_id; ?>
                                        <tr id="<?php echo $mlb_id; ?>" <?php if($SCORE['done'][$mlb_id] == true): ?>class="success"<?php endif; ?>>
                                                                                    
                                            <td><?php echo $P->first_name . " " . $P->last_name; ?> <?php if($SCORE['done'][$mlb_id] == true): ?><span class="label label-success">Game Over</span><?php endif; ?></td>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][1]['status'] as $at_bat_stat): ?>
                                                    <span class="label <?php if(isset($at_bat_stat['third_out']) && $at_bat_stat['third_out'] === true): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat['status']; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][2]['status'] as $at_bat_stat): ?>
                                                    <span class="label <?php if(isset($at_bat_stat['third_out']) && $at_bat_stat['third_out'] === true): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat['status']; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][3]['status'] as $at_bat_stat): ?>
                                                    <span class="label <?php if(isset($at_bat_stat['third_out']) && $at_bat_stat['third_out'] === true): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat['status']; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <?php foreach($SCORE['scores'][$mlb_id]['at_bat_stat'][4]['status'] as $at_bat_stat): ?>
                                                    <span class="label <?php if(isset($at_bat_stat['third_out']) && $at_bat_stat['third_out'] === true): ?>label-danger<?php else: ?>label-primary<?php endif; ?>"><?php echo $at_bat_stat['status']; ?></span><br />
                                                <?php endforeach; ?>
                                            </td>
                                            <td><?php if(isset($SCORE['scores'][$mlb_id]['score'])): ?><?php echo $SCORE['scores'][$mlb_id]['score']; ?><?php else: ?>0<?php endif; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <th><?php if($SCORE['done']['final_done'] == true): ?>FINAL Score<?php else: ?>Total Score<?php endif; ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><?php echo $total; ?></th>
                            </tfoot>
                        </table>
                    </div>
                </div>
           </div>
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
        
        <div class="col-lg-3 col-md-3 col-sm-3">
           <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">LINEUP</div>
                </div>
                <div class="box-content">
                    <div class="responsive-table">
                        <table class="table table-bordered-bottom table-hover table-striped score">
                            <?php foreach($TEAM_LIST as $key => $lineup): ?>
                                <?php if($lineup['order'] > 0): ?>
                                    <?php $P = new Player($lineup['player_id']); $mlb_id = $P->mlb_id; ?>
                                    <tr>
                                        <td><?php echo $lineup['position']; ?></td>
                                        <td><?php echo $P->first_name . " " . $P->last_name; ?></td>
                                        <td><?php echo $P->player_team; ?></td>
                                        <?php foreach($GAME_TIMES as $time => $teams): ?>
                                          <?php if(in_array($P->player_team, $teams['teams'])): ?><td class="game_start_time"><?php echo $time; ?></td><?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
           </div>
        </div>
        
        <div class="col-lg-7 col-md-7 col-sm-5 hidden-xs hidden-sm">
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
        
        <div class="col-lg-2 col-md-4 hidden-xs hidden-sm">
             <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title">SITUATION POINTS</div>
                </div>
                <div class="box-content box-no-padding">
                    <div class="responsive-table">
                        <table id="leaderboard" class='data-table table table-bordered table-striped' style='margin-bottom:0;'>
                            <tbody>
                              <tr>
                                  <td>Single</td>
                                  <td>2</td>
                              </tr>
                              <tr>
                                  <td>Double</td>
                                  <td>4</td>
                              </tr>
                              <tr>
                                  <td>Triple</td>
                                  <td>6</td>
                              </tr>
                              <tr>
                                  <td>Homerun</td>
                                  <td>9</td>
                              </tr>
                              <tr>
                                  <td>Walk</td>
                                  <td>1</td>
                              </tr>
                              <tr>
                                  <td>Hit By Pitch</td>
                                  <td>1</td>
                              </tr>
                              <tr>
                                  <td>Strikeout</td>
                                  <td>-.5</td>
                              </tr>
                              <tr>
                                  <td>Run</td>
                                  <td>3</td>
                              </tr>
                              <tr>
                                  <td>RBI</td>
                                  <td>3</td>
                              </tr>
                              <tr>
                                  <td>SB</td>
                                  <td>2</td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
    
    <?php foreach($SCORE['bases'] as $base => $players): ?>
      <?php foreach($players as $player_id): ?>
        <?php $P = new Player($player_id, "mlb_id"); ?>
        <?php if($base == 1): ?>
            /*$('#base1').css({ fill: "#F1C40F" });
            $("#base1").tooltip({
                'container': 'body',
                'placement': 'right',
                'title': '<?php echo $P->first_name . " " . $P->last_name; ?>'
            }).tooltip('show');*/
        <?php endif; ?>
        <?php if($base == 2): ?>
            $('#base2').css({ fill: "#F1C40F" });
            $("#base2").tooltip({
                'container': 'body',
                'placement': 'top',
                'title': '<?php echo $P->first_name . " " . $P->last_name; ?>'
            }).tooltip('show');
        <?php endif; ?>
        <?php if($base == 3): ?>
            $('#base3').css({ fill: "#F1C40F" });
            
            $("#base3").tooltip({
                'container': 'body',
                'placement': 'left',
                'title': '<?php echo $P->first_name . " " . $P->last_name; ?>'
            }).tooltip('show');
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endforeach; ?>
    
    var interval = 1000 * 60 * 1;
    
    <?php if($MATCH->locked == "1"): ?>
    setInterval(function() {
        $.getJSON("/team/getScores/<?php echo $team_id; ?>", function(data) {
            console.log(data);
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
            /*$("table#box_score tbody").find('tr').each(function(k, v) {
                
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