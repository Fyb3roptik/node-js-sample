<div class="page-header">
    <h1><?php echo $TITLE; ?></h1>
</div>

<div class="col-lg-6">
    <div class="box blue">
        <div class="box-header">
            <h2>&nbsp;</h2>
        </div>
        <div class="box-content">
            <form role="form" action="/admin/player/process" method="post">
                <input type="hidden" id="player_id" name="player_id" value="<?php echo $P->ID; ?>" />
                <div class="form-group">
                    <label for="player[mlb_id]">MLB ID</label>
                    <input type="text" class="form-control" id="player[mlb_id]" name="player[mlb_id]" placeholder="MLB ID" value="<?php echo $P->mlb_id; ?>">
                </div>
                <div class="form-group">
                    <label for="player[first_name]">First Name</label>
                    <input type="text" class="form-control" id="player[first_name]" name="player[first_name]" placeholder="First Name" value="<?php echo $P->first_name; ?>">
                </div>
                <div class="form-group">
                    <label for="player[last_name]">Last Name</label>
                    <input type="text" class="form-control" id="player[last_name]" name="player[last_name]" placeholder="Last Name" value="<?php echo $P->last_name; ?>">
                </div>
                <div class="form-group">
                    <label for="player[position]">Position</label>
                    <select class="form-control" id="player[position]" name="player[position]">
                        <option>--Position--</option>
                        <option value="SP" <?php if($P->position == "SP"): ?>selected<?php endif; ?>>SP</option>
                        <option value="RP" <?php if($P->position == "RP"): ?>selected<?php endif; ?>>RP</option>
                        <option value="C" <?php if($P->position == "C"): ?>selected<?php endif; ?>>C</option>
                        <option value="1B" <?php if($P->position == "1B"): ?>selected<?php endif; ?>>1B</option>
                        <option value="2B" <?php if($P->position == "2B"): ?>selected<?php endif; ?>>2B</option>
                        <option value="3B" <?php if($P->position == "3B"): ?>selected<?php endif; ?>>3B</option>
                        <option value="SS" <?php if($P->position == "SS"): ?>selected<?php endif; ?>>SS</option>
                        <option value="OF" <?php if($P->position == "OF"): ?>selected<?php endif; ?>>OF</option>
                    </select>
                </div>
                <div class="form-group checkbox">
                    <label for="dh">Player can DH</label>
                    <input type="checkbox" name="dh" id="dh" value="1" <?php if($P->dh == 1): ?>checked<?php endif; ?> />
                </div>
                <button class="btn btn-success pull-right">Update</button>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
</div>