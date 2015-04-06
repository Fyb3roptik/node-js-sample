<script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>

<div class='col-md-8 col-sm-12 col-xs-12'>    
    <div class='row'>
        <?php if($C->ID == $CUSTOMER->ID): ?>
            <?php if($Season_Started == true || SITE_DEV == 1): ?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="box bordered-box purple-border">
                        <div class="box-header purple-background">
                            <div class="title"><i class="fa fa-gamepad"></i> MATCHES</div>
                        </div>
                        <div class="box-content">
                            <?php if(!empty($MATCHES)): ?>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <th>Match Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </thead>
                                <?php foreach($MATCHES as $M): ?>
                                <?php 
                                    $status = $M->getStatus(); 
                                    $myStatus = $M->getMyStatus($CUSTOMER->ID);
                                    $matchPrice = $M->entry_fee;
                                    $myFunds = $CUSTOMER->funds;
                                    $Opponent = $M->getOpponent($M->ID, $CUSTOMER->ID);
                                    $team_id = $M->teamExists($CUSTOMER->ID);
                                    $TEAM = new Team($team_id['team_id']);
                                ?>                                
                                    <tr class="info">
                                        <td><?php echo $M->name; ?></td>
                                        <td><span class="status text-info"><?php echo $status; ?></span></td>
                                        <?php if($status == "Pending" && $myStatus == 1): ?>
                                            <td>Waiting for opponent to accept</td>
                                        <?php elseif($status == "Pending" && $myStatus == 0): ?>
                                            <td>
                                                <?php if($myFunds < ($matchPrice * 100)): ?>
                                                  <div class="tooltip-wrapper pull-left" data-toggle="tooltip" data-placement="bottom" title="Please add more funds to accept game invite">
                                                    <a href="/match/accept/" class="btn btn-success accept disabled"><i class="fa fa-check-circle"></i> ACCEPT</a>
                                                  </div>
                                                <?php else: ?>
                                                  <a href="/match/accept/" class="btn btn-success pull-left accept"><i class="fa fa-check-circle"></i> ACCEPT</a>  
                                                <?php endif; ?>
                                                <a href="/match/decline" class="btn btn-danger pull-left decline"><i class="fa fa-times-circle"></i> DECLINE</a>
                                                <a href="/team/view/<?php echo $TEAM->ID; ?>" class="btn btn-info pull-left lineup hide"><i class="fa fa-list"></i> SET YOUR LINEUP</a>
                                            </td>
                                        <?php elseif($status == "Accepted" && $myStatus == 1): ?>
                                            <td>
                                                <?php if($M->active == 1): ?>
                                                  <a href="/team/view/<?php echo $TEAM->ID; ?>" class="btn btn-info pull-left lineup"><i class="fa fa-gamepad"></i> VIEW GAME</a>
                                                <?php else: ?>
                                                  <a href="/team/view/<?php echo $TEAM->ID; ?>" class="btn btn-info pull-left lineup"><i class="fa fa-list"></i> SET YOUR LINEUP</a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <input type="hidden" class="matchPrice" value="<?php echo $matchPrice; ?>" />
                                    <input type="hidden" class="myFunds" value="<?php echo $myFunds; ?>" />
                                    <input type="hidden" class="opponent_match_id" value="<?php echo $M->ID; ?>" />
                                    <input type="hidden" class="opponent_id" value="<?php echo $Opponent->ID; ?>" />
                                <?php endforeach; ?>
                            </table>
                            <?php endif; ?>
                            
                           <a href="#" class="btn btn-primary pull-left" data-toggle="modal" data-target="#createMatchModal">CREATE A MATCH</a>
                          
                            <div class="filters pull-left">
                              <h4>Filters: </h4>
                              <button id="clear_filters" class="btn btn-default">Clear Filters</button>
                              <button id="beast_slam" class="btn btn-info">5K BEAST SLAM ONLY</button>
                            </div>
                            
                            <div class="clearfix"></div>
                            
                            <table id="matches" class="table table-striped table-bordered" cellspacing="0" width="100%">
                              <thead>
                                  <tr>
                                      <th></th>
                                      <th>TYPE</th>
                                      <th>PRICE</th>
                                      <th>START TIME</th>
                                      <th>5K BEAST SLAM</th>
                                      <th>PRIZE</th>
                                      <th>GAMES</th>
                                  </tr>
                              </thead>
                       
                              <tfoot>
                                  <tr>
                                      <th></th>
                                      <th>TYPE</th>
                                      <th>PRICE</th>
                                      <th>START TIME</th>
                                      <th>5K BEAST SLAM</th>
                                      <th>PRIZE</th>
                                      <th>GAMES</th>
                                  </tr>
                              </tfoot>
                       
                              <tbody>
                                <?php foreach($LOBBY as $match): ?>
                                  <tr>
                                    <td><a href="/match/createMatch/<?php echo $match['match_price']->ID;?>-<?php echo $match['start_time']; ?>-<?php echo $match['type']; ?>" class="btn btn-success join-match">Join Match</a></td>
                                    <td>H-2-H</td>
                                    <td><?php echo money_format("$%i", $match['match_price']->price); ?></td>
                                    <td class="match-start-time"><?php echo date("h:i A", $match['start_time']); ?></td>
                                    <td><?php if($match['match_price']->promotion_eligible == 1): ?><span class="label label-info">5K BEAST SLAM ELIGIBLE</span><?php else: ?><span class="label label-danger">NOT ELIGIBLE</span><?php endif; ?></td>
                                    <td><?php echo money_format("$%i", $match['match_price']->prize); ?></td>
                                    <td>
                                      <?php foreach($match['teams'] as $team): ?>
                                        <p><?php echo $team; ?></p>
                                      <?php endforeach; ?>
                                    </td>
                                  </tr>
                                  <input type="hidden" class="match_price_id" value="<?php echo $match['match_price']->ID; ?>" />
                                  <input type="hidden" class="start_time" value="<?php echo $match['start_time']; ?>" />
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-offset-2 col-md-7 col-sm-12 col-xs-12">
                    <div class="box bordered-box purple-border">
                        <div class="box-header purple-background">
                            <div class="title"></div>
                        </div>
                        <div class="box-content">
                            <h1 class="text-purple">MLB Season has not started yet! Please come back on opening day!</h1>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-5 hidden-xs hidden-sm">
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

<!-- Create Match Modal -->
        <div class="modal fade" id="createMatchModal" tabindex="-1" role="dialog" aria-labelledby="createMatchModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">CREATE A MATCH</h4>
              </div>
              <div class="modal-body">
                <form method="post" action="/match/createMatch/">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                      <div class="panel panel-success">
                        <div class="panel-heading" role="tab" id="headingOne">
                          <h4 class="panel-title">
                            <span aria-expanded="true">SET YOUR PRICE</span>
                          </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
                            <?php foreach($MATCH_PRICES as $MP): ?>
                                <div class="col-md-4">
                          				<div class="offer offer-success offer-price <?php if($CUSTOMER->funds < ($MP->price * 100)): ?>offer-add-funds<?php endif; ?>" id="<?php echo $MP->ID; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-controls="collapseOne">
                          					<div class="shape">
                          						<div class="shape-text">
                          							<?php echo money_format("$%i", $MP->price); ?>								
                          						</div>
                          					</div>
                          					<div class="offer-content">
                          						<h3 class="lead text-success"><?php echo money_format("$%i", $MP->price); ?></h3>
                          						<h4>Head-to-Head</h4>
                          						<p>
                          							<strong class="text-success"><?php echo money_format("$%i", $MP->prize); ?></strong> Prize <?php if($MP->promotion_eligible == 1): ?><span class="label label-danger">5K BEAST SLAM</span><?php endif; ?>
                          						</p>
                          					</div>
                          					<input type="hidden" name="myFunds" id="myFunds" value="<?php echo $CUSTOMER->funds; ?>" />
                          					<input type="hidden" name="matchPrice" id="matchPrice" value="<?php echo $MP->price; ?>" />
                          				</div>
                          			</div>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                      
                      <div class="panel panel-primary">
                        <div class="panel-heading" role="tab" id="headingTwo">
                          <h4 class="panel-title">
                            <span aria-expanded="false">SELECT GAME TYPE</span>
                          </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                              <?php $time = key($GAME_TIMES['all']); ?>
                              <?php if($time > time()): ?>
                              <div class="col-md-6">
                        				<div class="offer offer-primary offer-time" id="<?php echo $MP->ID; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-controls="collapseTwo">
                          				<div class="shape">
                          						<div class="shape-text shape-text-time">
                          							<?php echo date("h:i A", key($GAME_TIMES['all'])); ?>								
                          						</div>
                          					</div>
                        					<div class="offer-content">
                        						<h3 class="lead text-primary">All Games</h3>
                        						<p>
                        							<?php $game_key = key($GAME_TIMES['all']); foreach($GAME_TIMES['all'] as $teams): ?>
                        							  <?php foreach($teams as $key => $team): ?><span class="<?php echo (++$count%2 ? "text-primary" : "text-danger"); ?>"><?php echo (++$count2%2 ? $team : ' <span class="text-info">vs</span> ' . $team); ?></span><?php echo (++$count3%2 ? "" : "<br />"); ?><?php endforeach; ?>
                        							  <input type="hidden" name="matchTime" id="matchTime" value="<?php echo key($GAME_TIMES['all']); ?>" />
                        							  <input type="hidden" name="matchTeams" id="matchTeams" value="<?php echo implode(",", $GAME_TIMES['all'][$game_key]); ?>" />
                        							<?php endforeach; ?>
                        						</p>
                        					</div>
                        				</div>
                        			</div>
                        			<?php else: ?>
                        			  <div class="col-md-12">
                          			  <h4>No more games today. Please check back tomorrow!</h4>
                        			  </div>
                        			<?php endif; ?>
                        			<?php $game_key = key($GAME_TIMES['early']); if(count($GAME_TIMES['early'][$game_key]) > 4): ?>
                          			<div class="col-md-6">
                          				<div class="offer offer-primary offer-time" id="<?php echo $MP->ID; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-controls="collapseTwo">
                            				<div class="shape">
                            						<div class="shape-text shape-text-time">
                            							<?php echo date("h:i A", key($GAME_TIMES['early'])); ?>								
                            						</div>
                            					</div>
                          					<div class="offer-content">
                          						<h3 class="lead text-primary">Early Only Games</h3>
                          						<p>
                          							<?php foreach($GAME_TIMES['early'] as $teams): ?>
                          							  <?php foreach($teams as $key => $team): ?><span class="<?php echo (++$count%2 ? "text-primary" : "text-danger"); ?>"><?php echo (++$count2%2 ? $team : ' <span class="text-info">vs</span> ' . $team); ?></span><?php echo (++$count3%2 ? "" : "<br />"); ?><?php endforeach; ?>
                          							  <input type="hidden" name="matchTime" id="matchTime" value="<?php echo key($GAME_TIMES['early']); ?>" />
                          							  <input type="hidden" name="matchTeams" id="matchTeams" value="<?php echo implode(",", $GAME_TIMES['early'][$game_key]); ?>" />
                          							<?php endforeach; ?>
                          						</p>
                          					</div>
                          				</div>
                          			</div>
                          		<?php endif; ?>
                          		<?php $game_key = key($GAME_TIMES['late']); if(count($GAME_TIMES['late'][$game_key]) > 4): ?>
                          			<div class="col-md-6">
                          				<div class="offer offer-primary offer-time" id="<?php echo $MP->ID; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-controls="collapseTwo">
                            				<div class="shape">
                            						<div class="shape-text shape-text-time">
                            							<?php echo date("h:i A", key($GAME_TIMES['late'])); ?>								
                            						</div>
                            					</div>
                          					<div class="offer-content">
                          						<h3 class="lead text-primary">Evening Only Games</h3>
                          						<p>
                          							<?php foreach($GAME_TIMES['late'] as $teams): ?>
                          							  <?php foreach($teams as $key => $team): ?><span class="<?php echo (++$count%2 ? "text-primary" : "text-danger"); ?>"><?php echo (++$count2%2 ? $team : ' <span class="text-info">vs</span> ' . $team); ?></span><?php echo (++$count3%2 ? "" : "<br />"); ?><?php endforeach; ?>
                          							  <input type="hidden" name="matchTime" id="matchTime" value="<?php echo key($GAME_TIMES['late']); ?>" />
                          							  <input type="hidden" name="matchTeams" id="matchTeams" value="<?php echo implode(",", $GAME_TIMES['late'][$game_key]); ?>" />
                          							<?php endforeach; ?>
                          						</p>
                          					</div>
                          				</div>
                          			</div>
                          		<?php endif; ?>
                        			
                        			<div class="clearfix"></div>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-controls="collapseTwo" class="btn btn-default pull-left">PREVIOUS</a>
                          </div>
                        </div>
                      </div>
                      
                      <div class="panel panel-info">
                        <div class="panel-heading" role="tab" id="headingThree">
                          <h4 class="panel-title">
                            <span aria-expanded="false">SELECT YOUR OPPONENT</span>
                          </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                          <div class="panel-body">
                            <div class="radio">
                              <label>
                                <input type="radio" class="opponent" name="opponent" id="random_opponent" value="random">
                                Random Opponent
                              </label>
                            </div>
                            <div class="radio">
                                <label>
                                <input type="radio" class="opponent" name="opponent" id="friend_opponent" value="friend">
                                Friend
                              </label>
                            </div>
                            <div class="form-group form-inline hide" id="friend_username">
                                <div class="ui-widget">
                                <input type="text" class="form-control pull-left" id="friend_username_field" name="friend_username" placeholder="Username">
                                </div>
                                <span class="pull-left friend-info">OR</span>
                                <input type="email" class="form-control pull-left" id="friend_email" name="friend_email" placeholder="Email Address">
                            </div>
                            <div class="clearfix"></div>
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-controls="collapseThree" class="btn btn-default pull-left create-match-previous">PREVIOUS</a>
                          </div>
                        </div>
                      </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                <input type="submit" id="create_match" class="btn btn-primary disabled" value="CREATE MATCH" />
              </div>
              <input type="hidden" name="match_price_id" id="match_price_id" value="" />
              <input type="hidden" name="match_time" id="match_time" value="" />
              <input type="hidden" name="match_teams" id="match_teams" value="" />
              </form>
            </div>
          </div>
        </div>
        
        <script type="text/javascript">
            $(function() {
                $(".shape-text-time").each(function() {
                  var time = moment("<?php echo date("m/d/Y", time()) . " "; ?>" + $(this).html() + "<?php echo " " . date("T", time()); ?>").format("h:mm A");
                  $(this).html(time);
                });
                $(".match-start-time").each(function() {
                  var time = moment("<?php echo date("m/d/Y", time()) . " "; ?>" + $(this).html() + "<?php echo " " . date("T", time()); ?>").format("h:mm A");
                  $(this).html(time);
                });
                $("#matches").dataTable({
                  "paging":   true,
                  "order": [[ 2, "asc" ]],
                  "columnDefs": [
                    { "width": "2%", "targets": 0 },
                    { "width": "5%", "targets": 1 },
                    { "width": "10%", "targets": [2, 3, 4] }
                  ],
                  "language": {
                    "emptyTable": "No Games Left To Play Today."
                  }
                });
                $("#beast_slam").on("click", function() {
                  var table = $('#matches').DataTable();
                  var filteredData = table
                    .column(4)
                    .search("5k")
                    .draw();
                });
                $("#clear_filters").on("click", function() {
                  var table = $('#matches').DataTable();
                  var filteredData = table
                    .column(4)
                    .search("")
                    .draw();
                });
                $("#friend_opponent").change(function() {
                    $("#friend_username").removeClass('hide');
                    $("#create_match").removeClass('disabled');
                });
                $("#random_opponent").change(function() {
                    $("#friend_username").addClass('hide');
                    $("#create_match").removeClass('disabled');
                });
                $(".offer-price").click(function() {
                    var myFunds = $(this).find('#myFunds').val();
                    var matchPrice = $(this).find('#matchPrice').val();
                    if(myFunds < (matchPrice * 100)) {
                        $("#addFundsModal").modal();
                        return false;
                    } else {
                        var match_price_id = this.id;
                        $("#match_price_id").val(match_price_id);
                        $(".offer").removeClass('offer-price-selected');
                        $(this).addClass("offer-price-selected");
                    }
                });
                $(".offer-time").click(function() {
                    var matchTime = $(this).find('#matchTime').val();
                    var matchTeams = $(this).find('#matchTeams').val();
                    $("#match_time").val(matchTime);
                    $("#match_teams").val(matchTeams);
                    
                    $(".offer-time").removeClass('offer-time-selected');
                    $(this).addClass("offer-time-selected");
                });
                $("#friend_username_field").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "/customer/getUsernames", 
                            dataType: "json",
                            data: {
                                q: request.term
                            }, 
                            success: function(data) {
                                response(data)
                            }
                        });
                    },
                    minLength: 2,
                    select: function(event, ui) {
                        $(this).val(ui.item.value);
                    }
                });
                $('.tooltip-wrapper').tooltip()
                $(".accept").click(function() {
                    var matchPrice = $(this).parent().parent().parent().find('.matchPrice').val();
                    var myFunds = $(this).parent().parent().parent().find('.myFunds').val();
                    var $clicked = this;

                    if(myFunds < (matchPrice * 100)) {
                        $("#addFundsModal").modal();
                        return false;
                    } else {
                        
                        var match_id = $(this).parent().parent().parent().find('.opponent_match_id').val();
                        var opponent_id = $(this).parent().parent().parent().find('.opponent_id').val();

                        var params = { match_id: match_id, opponent_id: opponent_id }

                        $.post("/match/accept", params, function(data) {
                            $($clicked).parent().find('.accept').addClass('hide');
                            $($clicked).parent().find('.decline').addClass('hide');
                            $($clicked).parent().find('.lineup').removeClass('hide');
                            $($clicked).parent().parent().find('.status').html('Accepted');
                            
                            // Reduce balance
                            $(".user_balance").html(data['newPrice']);
                        }, "json");
                    }
                    return false;
                });
            });
        </script>