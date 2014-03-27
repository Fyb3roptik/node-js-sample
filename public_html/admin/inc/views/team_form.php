<div class="page-header">
    <h1><?php echo $TITLE; ?></h1>
</div>

<div class="col-lg-6">
    <div class="box blue">
        <div class="box-header">
            <h2>&nbsp;</h2>
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