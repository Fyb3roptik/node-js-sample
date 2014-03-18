<H1>Edit <?php echo $PLAN->name; ?> Plan</H1>
<form method="post" action="/admin/plans/process">
Plan Type: 
<select name="plans[plan_type]">
	<option value="dj" <?php if("dj" == $PLAN->plan_type): ?>selected<?php endif; ?>>DJ</option>
</select>
<br />
<br />
Plan Name: <input type="text" name="plans[name]" value="<?php echo $PLAN->name; ?>" />
<br />
<br />
Plan Description:
<br />
<textarea rows="10" cols="40" name="plans[description]"><?php echo $PLAN->description; ?></textarea>
<br />
<br />
Free Plan?: Yes<input type="radio" name="plans[free]" value="1" <?php if($PLAN->free == "1"): ?>checked<?php endif; ?> />&nbsp;&nbsp;No<input type="radio" name="plans[free]" value="0" <?php if($PLAN->free != "1"): ?>checked<?php endif; ?> />
<br />
<br />
Plan Price: <input type="text" name="plans[price]" value="<?php echo $PLAN->price; ?>" /> Ex: 400.00, 499.99
<br />
<br />
Plan Recurring: 
<input type="text" size="5" name="plans[length]" value="<?php echo $PLAN->length; ?>" />
<select name="plans[recurring]">
	<option value="months" <?php if("Month" == $PLAN->recurring): ?>selected<?php endif; ?>>Months</option>
</select>
<br />
<br />
Timed: <input type="checkbox" name="timed" value="1" <?php if(true == $PLAN->timed): ?>checked<?php endif; ?> />
<br />
<br />
Active: <input type="checkbox" name="active" value="1" <?php if(true == $PLAN->active): ?>checked<?php endif; ?> />
<br />
<br />
<input type="hidden" name="plan_id" value="<?php echo $PLAN->ID; ?>" />
<input type="submit" value="Update Plan" />
</form>