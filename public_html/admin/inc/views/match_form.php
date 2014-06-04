<?php
if($M->start_date) {
    $start_date = intval($M->start_date);
} else { 
    $start_date = time();
}
?>

<script type="text/javascript">
$(document).ready(function(){
    /* ---------- Datapicker ---------- */
	$('.date-picker').datepicker();
	
	/* ---------- Timepicker for Bootstrap ---------- */
	$('#timepicker1').timepicker('setTime', '<?php echo date('h:i A', $start_date); ?>');
	
	$("#selectAll").click(function () {
    	$(this).closest('div').find('input:checkbox').prop('checked', this.checked);
	});
});
</script>

<div class="page-header">
    <h1><?php echo $TITLE; ?></h1>
</div>

<div class="col-lg-6">
    <div class="box blue">
        <div class="box-header">
            <h2>&nbsp;</h2>
        </div>
        <div class="box-content">
            <form role="form" action="/admin/match/process" method="post">
                <input type="hidden" id="match_id" name="match_id" value="<?php echo $M->ID; ?>" />
                <div class="form-group">
                    <label for="match[name]">Name</label>
                    <input type="text" class="form-control" id="match[name]" name="match[name]" placeholder="Name" value="<?php echo $M->name; ?>">
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <div class='input-group date' id='datetimepicker'>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type='text' id="start_date" name="start_date" class="form-control date-picker" data-date-format="mm-dd-yyyy" value="<?php echo date("m/d/Y", $start_date); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <div class="controls">
    					<div class="input-group col-sm-4 bootstrap-timepicker">
    						<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
    						<input type="text" class="form-control timepicker" name="start_time" id="timepicker1" value="">
    					</div>	
    				</div>
                </div>
                <div class="form-group">
                    <label for="entry_fee">Entry Fee</label>
                    <div class="controls">
    					<div class="input-group col-sm-4">
    						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
    						<input type="text" class="form-control" name="match[entry_fee]" id="entry_fee" value="<?php echo $M->entry_fee; ?>">
    					</div>	
    				</div>
                </div>
                <?php $TOTAL = $M->getTotalTeams(); ?>
                <div class="form-group">
                    <label for="prize_pool">Prize Pool - <i><strong>$<?php echo $M->getPrizePool($TOTAL); ?></strong></i></label>
                    <div class="controls">
    					<div class="input-group col-sm-4">
    						<input type="text" class="form-control" name="match[prize_pool]" id="prize_pool" value="<?php echo $M->prize_pool; ?>">
    						<span class="input-group-addon"><i><strong>%</strong></i></span>
    					</div>
    				</div>
                </div>
                <div class="form-group">
                    <label class="control-label">Active Teams for Match</label>
                    <br />
                    <input type="checkbox" id="selectAll" /> <label for="selectAll">Select All</label>
                    <div class="controls">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Angels", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Angels"> Angels
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Astros", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Astros"> Astros
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Athletics", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Athletics"> Athletics
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Blue Jays", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Blue Jays"> Blue Jays
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Braves", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Braves"> Braves
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Brewers", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Brewers"> Brewers
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Cardinals", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Cardinals"> Cardinals
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Cubs", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Cubs"> Cubs
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Diamondbacks", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Diamondbacks"> Diamondbacks
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Dodgers", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Dodgers"> Dodgers
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Giants", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Giants"> Giants
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Indians", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Indians"> Indians
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Mariners", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Mariners"> Mariners
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Marlins", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Marlins"> Marlins
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Mets", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Mets"> Mets
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Nationals", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Nationals"> Nationals
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Orioles", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Orioles"> Orioles
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Padres", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Padres"> Padres
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Phillies", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Phillies"> Phillies
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Pirates", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Pirates"> Pirates
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Rangers", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Rangers"> Rangers
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Rays", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Rays"> Rays
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Red Sox", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Red Sox"> Red Sox
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Reds", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Reds"> Reds
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Rockies", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Rockies"> Rockies
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Royals", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Royals"> Royals
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Tigers", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Tigers"> Tigers
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Twins", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Twins"> Twins
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("White Sox", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="White Sox"> White Sox
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="match_teams[]" <?php if(in_array("Yankees", $MATCH_TEAMS)): ?>checked<?php endif; ?> value="Yankees"> Yankees
                        </label>
					</div>
                </div>
                <div class="form-group">
                    
                    <div class="controls">
                        <div class="col-lg-3 pull-left">
                            <label for="active">Active</label>
                            <label class="switch switch-long switch-success">
                                <input type="checkbox" name="match_active" class="switch-input" value="1" <?php if($M->active == "1"): ?>checked<?php endif; ?> />
                                <span class="switch-label" data-on="Active" data-off="Inactive"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="controls">
                        <div class="col-lg-3 pull-left">
                            <label for="active">Locked</label>
                            <label class="switch switch-long switch-danger">
                                <input type="checkbox" name="match_locked" class="switch-input" value="1" <?php if($M->locked == "1"): ?>checked<?php endif; ?> />
                                <span class="switch-label" data-on="Locked" data-off="Unlocked"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success pull-right">Update</button>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
</div>