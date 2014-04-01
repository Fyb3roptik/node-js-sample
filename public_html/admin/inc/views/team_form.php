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

        $.post("/admin/team/saveBattingOrder/", {players: JSON.stringify(players)}, function(data) {
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
  #sortable1 li, #sortable2 li, #sortable3 li { margin: 5px; padding: 5px; font-size: 1.2em; width: 94%; cursor: move; }
  h2 {color: #333}
  .ui-state-highlight { height: 1.5em; line-height: 1.2em; }
</style>
<div class="page-header">
    <h1><?php echo $TITLE; ?></h1>
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
                <h2>Match Info</h2>
            </div>
            <div class="box-content">
                <form role="form" action="/admin/team/process" method="post">
                    <input type="hidden" id="team_id" name="team_id" value="<?php echo $T->ID; ?>" />
                    
                    <div class="form-group">
                        <label for="team[customer_id]">Customer List</label>
                        <select class="form-control" id="team[customer_id]" name="team[customer_id]">
                            <option>--Customers--</option>
                            <?php foreach($CUSTOMERS as $C): ?>
                            <option value="<?php echo $C->ID; ?>" <?php if($C->ID == $T->customer_id): ?>selected<?php endif; ?>><?php echo $C->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="team[match_id]">Match List</label>
                        <select class="form-control" id="team[match_id]" name="team[match_id]">
                            <option>--Matches--</option>
                            <?php foreach($MATCHES as $MATCH): ?>
                            <option value="<?php echo $MATCH->ID; ?>" <?php if($MATCH->ID == $T->match_id): ?>selected<?php endif; ?>><?php echo $MATCH->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-success pull-right">Update</button>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>   
</div>

<?php if($T->ID != 0 && $T->ID != ""): ?>
<div class="row">
    <div class="col-lg-6">
        <div class="box blue">
            <div class="box-header">
                <h2>Lineup</h2>
            </div>
            <div class="box-content">
                <form role="form" action="/admin/team/processTeamPlayers" method="post">
                    <input type="hidden" id="team_id" name="team_id" value="<?php echo $T->ID; ?>" />
                    
                    <div class="form-group">
                        <label for="team[sp]">SP</label>
                        <select class="form-control" id="team[sp]" name="team[sp]">
                            <option>--SP--</option>
                            <?php foreach($SP as $P): ?>
                            <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="team[rp]">RP</label>
                        <select class="form-control" id="team[rp]" name="team[rp]">
                            <option>--RP--</option>
                            <?php foreach($RP as $P): ?>
                            <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
                            <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="team[of2]">OF</label>
                        <select class="form-control" id="team[of2]" name="team[of2]">
                            <option>--OF--</option>
                            <?php foreach($OF as $P): ?>
                            <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="team[of3]">OF</label>
                        <select class="form-control" id="team[of3]" name="team[of3]">
                            <option>--OF--</option>
                            <?php foreach($OF as $P): ?>
                            <option value="<?php echo $P->ID; ?>" <?php if(in_array($P->ID, $SELECTED_PLAYERS_LIST)): ?>selected<?php endif; ?>><?php echo $P->first_name . " " . $P->last_name; ?></option>
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
                        <input type="hidden" id="team_id" name="team_id" value="<?php echo $T->ID; ?>" />
                        <div class="form-group">
                            <ul id="sortable1" class="droptrue">
                                <?php foreach($SELECTED_PLAYERS as $SP): ?>
                                    <?php $P = new Player($SP['player_id']); ?>
                                    <li class="ui-state-default" id="<?php echo $SP['teams_lineup_id']; ?>"><?php echo $P->first_name . " " . $P->last_name; ?></li>
                                <?php endforeach; ?>
                            </ul>
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
<?php endif; ?>
