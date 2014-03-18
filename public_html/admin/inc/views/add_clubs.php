<fieldset>
<legend>Add Club</legend>
<br />
<br />
<form method="post" action="/admin/clubs/addClub/">
Confirmed: <input type="checkbox" name="confirmed" />
<br clear="all" />
<br clear="all" />
Name: <input type="text" name="club[name]" />
<br clear="all" />
<br clear="all" />
Address 1: <input type="text" name="club[address_1]" />
<br clear="all" />
<br clear="all" />
Address 2: <input type="text" name="club[address_2]" />
<br clear="all" />
<br clear="all" />
City: <input type="text" name="club[city]" />
<br clear="all" />
<br clear="all" />
State: <?php echo draw_select('club[state]', get_states()); ?>
<br clear="all" />
<br clear="all" />
Zipcode: <input type="text" name="club[zipcode]" />
<br clear="all" />
<br clear="all" />
Phone: <input type="text" name="club[phone]" />
<br clear="all" />
<br clear="all" />
<strong><u>Temp Club Times</u></strong>
<br clear="all" />
<br clear="all" />
<input type="checkbox" name="sunday" value="1" /> Sunday
<br clear="all" />
<input type="checkbox" name="monday" value="1" /> Monday
<br clear="all" />
<input type="checkbox" name="tuesday" value="1" /> Tuesday
<br clear="all" />
<input type="checkbox" name="wednesday" value="1" /> Wednesday
<br clear="all" />
<input type="checkbox" name="thursday" value="1" /> Thursday
<br clear="all" />
<input type="checkbox" name="friday" value="1" /> Friday
<br clear="all" />
<input type="checkbox" name="saturday" value="1" /> Saturday
<br clear="all" />
<br clear="all" />
<input type="submit" value="Save New Club" />
</form>
</fieldset>