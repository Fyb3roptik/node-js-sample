<div class='col-md-12 col-sm-12 col-xs-12'>    
    <div class='row'>
        <?php if($C->ID == $CUSTOMER->ID): ?>
        <div class="col-md-offset-1 col-md-5 col-sm-12 col-xs-12">
            <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title"><i class="fa fa-trophy"></i> LEAGUES</div>
                </div>
                <div class="box-content">
                    <a href="#" class="btn btn-primary pull-left">CREATE A LEAGUE</a>
                    <a href="#" class="btn btn-success pull-right">FIND A LEAGUE</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-12 col-xs-12">
            <div class="box bordered-box purple-border">
                <div class="box-header purple-background">
                    <div class="title"><i class="fa fa-search"></i> FIND A MATCH</div>
                </div>
                <div class="box-content">
                    <?php if(!empty($MATCHES)): ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>Match Name</th>
                            <th>Status</th>
                        </thead>
                        <?php foreach($MATCHES as $M): ?>
                            <tr class="info">
                                <td><?php echo $M->name; ?></td>
                                <td><span class="text-info">Pending...</span></tr>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php endif; ?>
                    <a href="#" class="btn btn-primary pull-left" data-toggle="modal" data-target="#createMatchModal">CREATE A MATCH</a>
                    <a href="#" class="btn btn-success pull-right" data-toggle="modal" data-target="#findMatchModal">FIND A MATCH</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
                    				<div class="offer offer-success" id="<?php echo $MP->ID; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-controls="collapseOne">
                    					<div class="shape">
                    						<div class="shape-text">
                    							<?php echo money_format("$%i", $MP->price); ?>								
                    						</div>
                    					</div>
                    					<div class="offer-content">
                    						<h3 class="lead"><?php echo money_format("$%i", $MP->price); ?></h3>
                    						<p>
                    							<strong><?php echo money_format("$%i", $MP->prize); ?></strong> Prize <?php if($MP->promotion_eligible == 1): ?><span class="label label-danger">10K Eligible</span><?php endif; ?>
                    						</p>
                    					</div>
                    				</div>
                    			</div>
                                
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                      
                      <div class="panel panel-info">
                        <div class="panel-heading" role="tab" id="headingTwo">
                          <h4 class="panel-title">
                            <span aria-expanded="false">SELECT YOUR OPPONENT</span>
                          </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-controls="collapseTwo" class="btn btn-default pull-right">PREVIOUS</a>
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
              </form>
            </div>
          </div>
        </div>
        
        <!-- Find Match Modal -->
        <div class="modal fade" id="findMatchModal" tabindex="-1" role="dialog" aria-labelledby="findMatchModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">FIND A MATCH</h4>
              </div>
              <div class="modal-body">
                <form method="post" action="/match/findMatch/">
                    <?php foreach($MATCH_PRICES as $MP): ?>
                        <div class="radio">
                          <label>
                            <input type="radio" name="matchPrice" id="<?php echo $MP->ID; ?>" value="<?php echo $MP->ID; ?>">
                            <span class="<?php if($CUSTOMER->funds < ($MP->price * 100)): ?>disabled-text<?php endif; ?>"><strong class="text-success"><?php echo money_format("$%i", $MP->price); ?></strong> Match <strong class="text-success"><?php echo money_format("$%i", $MP->prize); ?></strong> Prize <?php if($MP->promotion_eligible == 1): ?><span class="label label-danger">10K Eligible</span><?php endif; ?></span>
                          </label>
                        </div>
                    <?php endforeach; ?>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-primary">FIND MATCH</button>
              </div>
            </div>
          </div>
        </div>
        
        <script type="text/javascript">
            $(function() {
                $("#friend_opponent").change(function() {
                    $("#friend_username").removeClass('hide');
                    $("#create_match").removeClass('disabled');
                });
                $("#random_opponent").change(function() {
                    $("#friend_username").addClass('hide');
                    $("#create_match").removeClass('disabled');
                });
                $(".offer").click(function() {
                    var match_price_id = this.id;
                    $("#match_price_id").val(match_price_id);
                    $(".offer").removeClass('offer-selected');
                    $(this).addClass("offer-selected");
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
            });
        </script>