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