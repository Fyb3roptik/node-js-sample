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
                <div class="form-group">
                    <label for="player[player_team]">Player Team</label>
                    <select class="form-control" id="player[player_team]" name="player[player_team]">
                        <option>--Player Team--</option>
                        <option value="Angels" <?php if($P->player_team == "Angels"): ?>selected<?php endif; ?>>Angels</option>
                        <option value="Astros" <?php if($P->player_team == "Astros"): ?>selected<?php endif; ?>>Astros</option>
                        <option value="Athletics" <?php if($P->player_team == "Athletics"): ?>selected<?php endif; ?>>Athletics</option>
                        <option value="Blue Jays" <?php if($P->player_team == "Blue Jays"): ?>selected<?php endif; ?>>Blue Jays</option>
                        <option value="Braves" <?php if($P->player_team == "Braves"): ?>selected<?php endif; ?>>Braves</option>
                        <option value="Brewers" <?php if($P->player_team == "Brewers"): ?>selected<?php endif; ?>>Brewers</option>
                        <option value="Cardinals" <?php if($P->player_team == "Cardinals"): ?>selected<?php endif; ?>>Cardinals</option>
                        <option value="Cubs" <?php if($P->player_team == "Cubs"): ?>selected<?php endif; ?>>Cubs</option>
                        <option value="Diamondbacks" <?php if($P->player_team == "D-backs"): ?>selected<?php endif; ?>>D-backs</option>
                        <option value="Dodgers" <?php if($P->player_team == "Dodgers"): ?>selected<?php endif; ?>>Dodgers</option>
                        <option value="Giants" <?php if($P->player_team == "Giants"): ?>selected<?php endif; ?>>Giants</option>
                        <option value="Indians" <?php if($P->player_team == "Indians"): ?>selected<?php endif; ?>>Indians</option>
                        <option value="Mariners" <?php if($P->player_team == "Mariners"): ?>selected<?php endif; ?>>Mariners</option>
                        <option value="Marlins" <?php if($P->player_team == "Marlins"): ?>selected<?php endif; ?>>Marlins</option>
                        <option value="Mets" <?php if($P->player_team == "Mets"): ?>selected<?php endif; ?>>Mets</option>
                        <option value="Nationals" <?php if($P->player_team == "Nationals"): ?>selected<?php endif; ?>>Nationals</option>
                        <option value="Orioles" <?php if($P->player_team == "Orioles"): ?>selected<?php endif; ?>>Orioles</option>
                        <option value="Padres" <?php if($P->player_team == "Padres"): ?>selected<?php endif; ?>>Padres</option>
                        <option value="Phillies" <?php if($P->player_team == "Phillies"): ?>selected<?php endif; ?>>Phillies</option>
                        <option value="Pirates" <?php if($P->player_team == "Pirates"): ?>selected<?php endif; ?>>Pirates</option>
                        <option value="Rangers" <?php if($P->player_team == "Rangers"): ?>selected<?php endif; ?>>Rangers</option>
                        <option value="Rays" <?php if($P->player_team == "Rays"): ?>selected<?php endif; ?>>Rays</option>
                        <option value="Red Sox" <?php if($P->player_team == "Red Sox"): ?>selected<?php endif; ?>>Red Sox</option>
                        <option value="Reds" <?php if($P->player_team == "Reds"): ?>selected<?php endif; ?>>Reds</option>
                        <option value="Rockies" <?php if($P->player_team == "Rockies"): ?>selected<?php endif; ?>>Rockies</option>
                        <option value="Royals" <?php if($P->player_team == "Royals"): ?>selected<?php endif; ?>>Royals</option>
                        <option value="Tigers" <?php if($P->player_team == "Tigers"): ?>selected<?php endif; ?>>Tigers</option>
                        <option value="Twins" <?php if($P->player_team == "Twins"): ?>selected<?php endif; ?>>Twins</option>
                        <option value="White Sox" <?php if($P->player_team == "White Sox"): ?>selected<?php endif; ?>>White Sox</option>
                        <option value="Yankees" <?php if($P->player_team == "Yankees"): ?>selected<?php endif; ?>>Yankees</option>
                    </select>
                </div>
                <div class="form-group checkbox">
                    <label for="dh">Player can DH</label>
                    <input type="checkbox" name="dh" id="dh" value="1" <?php if($P->dh == 1): ?>checked<?php endif; ?> />
                </div>
                <div class="form-group checkbox">
                    <label for="active">Player is Active</label>
                    <input type="checkbox" name="active" id="active" value="1" <?php if($P->active == 1): ?>checked<?php endif; ?> />
                </div>
                <button class="btn btn-success pull-right">Update</button>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
</div>