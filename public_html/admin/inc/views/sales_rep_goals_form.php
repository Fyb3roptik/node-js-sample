<script type="text/javascript">
$(document).ready(function() {
	$('a.cancel_goal').click(function() {
		$("#goal_form_holder").empty();
		return false;
	});

	$("#start_date_picker").datepicker({ dateFormat: 'yy-mm-dd'});
	$("#end_date_picker").datepicker({ dateFormat: 'yy-mm-dd'});
});
</script>
<form id="goal_form" method="post" action="/admin/sales_rep_goals/processGoal/">
	<fieldset>
		<legend>Sales Rep Goals</legend>
		<input type="hidden" name="goal[sales_rep_goal_id]" value="<?php echo $SRG->ID; ?>" />
		<input type="hidden" name="goal[sales_rep_id]" value="<?php echo $SR->ID; ?>" />
		Month:<br />
		<select name="goal[month]">
        	<option value=""  <?php if($month == ""): ?> selected <?php endif; ?>>--Select Month--</option>
			<option value="01" <?php if($month == "1"): ?> selected <?php endif; ?>>January</option>
			<option value="02" <?php if($month == "2"): ?> selected <?php endif; ?>>February</option>
			<option value="03" <?php if($month == "3"): ?> selected <?php endif; ?>>March</option>
			<option value="04" <?php if($month == "4"): ?> selected <?php endif; ?>>April</option>
			<option value="05" <?php if($month == "5"): ?> selected <?php endif; ?>>May</option>
			<option value="06" <?php if($month == "6"): ?> selected <?php endif; ?>>June</option>
			<option value="07" <?php if($month == "7"): ?> selected <?php endif; ?>>July</option>
			<option value="08" <?php if($month == "8"): ?> selected <?php endif; ?>>August</option>
			<option value="09" <?php if($month == "9"): ?> selected <?php endif; ?>>September</option>
			<option value="10" <?php if($month == "10"): ?> selected <?php endif; ?>>October</option>
			<option value="11" <?php if($month == "11"): ?> selected <?php endif; ?>>November</option>
			<option value="12" <?php if($month == "12"): ?> selected <?php endif; ?>>December</option>
		</select><br />
		Year:<br />
		<select name="goal[year]">
        	<option value="" <?php if($year == ""): ?> selected <?php endif; ?>>--Select Year--</option>
			<option value="2010" <?php if($year == "2010"): ?> selected <?php endif; ?>>2010</option>
			<option value="2011" <?php if($year == "2011"): ?> selected <?php endif; ?>>2011</option>
			<option value="2012" <?php if($year == "2012"): ?> selected <?php endif; ?>>2012</option>
			<option value="2013" <?php if($year == "2013"): ?> selected <?php endif; ?>>2013</option>
			<option value="2014" <?php if($year == "2014"): ?> selected <?php endif; ?>>2014</option>
			<option value="2015" <?php if($year == "2015"): ?> selected <?php endif; ?>>2015</option>
			<option value="2016" <?php if($year == "2016"): ?> selected <?php endif; ?>>2016</option>
			<option value="2017" <?php if($year == "2017"): ?> selected <?php endif; ?>>2017</option>
			<option value="2018" <?php if($year == "2018"): ?> selected <?php endif; ?>>2018</option>
			<option value="2019" <?php if($year == "2019"): ?> selected <?php endif; ?>>2019</option>
		</select><br />
		Monthly Goal:<br />
		<input type="text" name="goal[monthly_goal]" value="<?php echo $SRG->monthly_goal; ?>" /><br />
		Working Days:<br />
		<input type="text" name="goal[working_days]" value="<?php echo $SRG->working_days; ?>" />
		<br />
		<input type="submit" value="Save Goal" /> or <a href="#" class="cancel_goal">cancel</a>
	</fieldset>
</form>
