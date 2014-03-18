<H1>Create New Plan</H1>
<form method="post" action="/admin/plans/processCreate">
Plan Type: 
<select name="plans[plan_type]">
	<option value="dj">DJ</option>
</select>
<br />
<br />
Plan Name: <input type="text" name="plans[name]" />
<br />
<br />
Plan Description:
<br />
<textarea rows="10" cols="40" name="plans[description]"></textarea>
<br />
<br />
Free Plan?: Yes<input type="radio" name="plans[free]" value="1" />&nbsp;&nbsp;No<input type="radio" name="plans[free]" value="0" />
<br />
<br />
Plan Price: <input type="text" name="plans[price]" /> Ex: 400.00, 499.99
<br />
<br />
Plan Recurring: 
<input type="text" size="5" name="plans[length]" />
<select name="plans[recurring]">
	<option value="months">Months</option>
</select>
<br />
<br />
Timed: <input type="checkbox" name="timed" value="1" />
<br />
<br />
Active: <input type="checkbox" name="active" value="1" />
<br />
<br />
<input type="submit" value="Create Plan" />
</form>