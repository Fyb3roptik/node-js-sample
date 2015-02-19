<div class="page-header">
    <h1 class="pull-left"><?php echo $TITLE; ?></h1>
    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#addMatchPriceModal" class="pull-right">Add New Match Price</button>
    <div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php foreach($PRICES as $P): ?>
            <div class="col-lg-4">
                <div class="panel panel-primary">
                  <div class="panel-heading"><?php echo money_format('$%i', $P->price); ?> Match Price <a href="#" data-toggle="modal" data-target="#editMatchPriceModal" id="<?php echo $P->ID; ?>" class="edit-match-price pull-right"><i class="fa fa-pencil"></i></a></div>
                  <div class="panel-body">
                    <p>Cost Per Player: <?php echo money_format('$%i', $P->price); ?></p>
                    <p>Profit: <?php echo money_format('$%i', $P->profit); ?></p>
                    <p>Prize: <?php echo money_format("$%i", $P->prize); ?></p>
                    <p>Promotion Eligible: <?php if($P->promotion_eligible == 1): ?>Yes<?php else: ?>No<?php endif; ?></p>
                  </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="editMatchPriceModal" tabindex="-1" role="dialog" aria-labelledby="editMatchPriceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="editMatchPriceModalLabel">EDIT MATCH PRICE</h4>
      </div>
      <form role="form" action="/admin/settings/saveMatchPrice" method="post">
          <div class="modal-body">
            <div class="form-group">
                <label for="price">Price</label>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" aria-label="Price" name="price" id="price" placeholder="Price">
                </div>
            </div>
            
            <div class="form-group">
                <label for="profit">Profit</label>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" aria-label="Profit" name="profit" id="profit" placeholder="Profit">
                </div>
            </div>
            
            <div class="form-group">
                <label for="prize">Prize</label>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" aria-label="Prize" name="prize" id="prize" placeholder="Prize">
                </div>
            </div>
            
            <div class="radio">
                <strong>Promotion Eligible:&nbsp;</strong>
                <label class="radio-inline">
                  <input type="radio" name="promotion_eligible" value="1"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="promotion_eligible" value="0"> No
                </label>
            </div>
            
            <div class="radio">
                <strong>Active:&nbsp;</strong>
                <label class="radio-inline">
                  <input type="radio" name="active" value="1"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="active" value="0"> No
                </label>
            </div>
            
            <div class="clearfix"></div>
          </div>
          <div class="modal-footer">
            <a class="deleteMatch pull-left btn btn-danger" href="#">DELETE MATCH PRICE</a>
            <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
            <input type="submit" id="create_match" class="btn btn-primary" value="SAVE MATCH PRICE" />
          </div>
          <input type="hidden" name="match_price_id" id="match_price_id" value="" />
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="addMatchPriceModal" tabindex="-1" role="dialog" aria-labelledby="addMatchPriceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addMatchPriceModalLabel">ADD MATCH PRICE</h4>
      </div>
      <form role="form" action="/admin/settings/saveMatchPrice" method="post">
          <div class="modal-body">
            <div class="form-group">
                <label for="price">Price</label>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" aria-label="Price" name="price" placeholder="Price">
                </div>
            </div>
            
            <div class="form-group">
                <label for="profit">Profit</label>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" aria-label="Profit" name="profit" placeholder="Profit">
                </div>
            </div>
            
            <div class="form-group">
                <label for="prize">Prize</label>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" aria-label="Prize" name="prize" placeholder="Prize">
                </div>
            </div>
            
            <div class="radio">
                <strong>Promotion Eligible:&nbsp;</strong>
                <label class="radio-inline">
                  <input type="radio" name="promotion_eligible" value="1"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="promotion_eligible" value="0"> No
                </label>
            </div>
            
            <div class="radio">
                <strong>Active:&nbsp;</strong>
                <label class="radio-inline">
                  <input type="radio" name="active" value="1"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="active" value="0"> No
                </label>
            </div>
            
            <div class="clearfix"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
            <input type="submit" id="create_match" class="btn btn-primary" value="SAVE MATCH PRICE" />
          </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(function() {
        $(".edit-match-price").click(function() {
            var id = this.id;
            $.get("/match/getMatchPriceInfo/"+id, function(data) {
                $("#price").val(data['price']);
                $("#profit").val(data['profit']);
                $("#prize").val(data['prize']);
                $("#match_price_id").val(data['id']);
                
                var $promotion = $('input:radio[name=promotion_eligible]');
                $promotion.filter('[value='+data['promotion_eligible']+']').prop('checked', true);
                
                var $active = $('input:radio[name=active]');
                $active.filter('[value='+data['active']+']').prop('checked', true);
                
                $("a.deleteMatch").click(function() {
                    if(confirm("Are you sure you want to delete this Match Price?")) {
                        window.location = "/admin/settings/deleteMatchPrice/"+data['id'];
                    }
                });
                
            }, "json");
        });
    })
</script>