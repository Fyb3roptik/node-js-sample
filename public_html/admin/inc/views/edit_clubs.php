<fieldset>
<legend>Edit Club</legend>
<br />
<br />
<form method="post" action="/admin/clubs/editClub/<?php echo $CLUB->ID; ?>">
Confirmed: <input type="checkbox" name="confirmed" value="1" <?php if($CLUB->confirmed == "1"): ?>checked="checked"<?php endif; ?> />
<br clear="all" />
<br clear="all" />
Name: <input type="text" name="club[name]" value="<?php echo $CLUB->name; ?>" />
<br clear="all" />
<br clear="all" />
Address 1: <input type="text" name="club[address_1]" value="<?php echo $CLUB->address_1; ?>" />
<br clear="all" />
<br clear="all" />
Address 2: <input type="text" name="club[address_2]" value="<?php echo $CLUB->address_2; ?>" />
<br clear="all" />
<br clear="all" />
City: <input type="text" name="club[city]" value="<?php echo $CLUB->city; ?>" />
<br clear="all" />
<br clear="all" />
State: <?php echo draw_select('club[state]', get_states(), $CLUB->state); ?>
<br clear="all" />
<br clear="all" />
Zipcode: <input type="text" name="club[zipcode]" value="<?php echo $CLUB->zipcode; ?>" />
<br clear="all" />
<br clear="all" />
Phone: <input type="text" name="club[phone]" value="<?php echo $CLUB->phone; ?>" />
<br clear="all" />
<br clear="all" />
<strong><u>Temp Club Times</u></strong>
<br clear="all" />
<br clear="all" />
<input type="checkbox" name="sunday" value="1" <?php if($CLUBTIMES->sunday == "1"): ?>checked="checked"<?php endif; ?> /> Sunday
<br clear="all" />
<input type="checkbox" name="monday" value="1" <?php if($CLUBTIMES->monday == "1"): ?>checked="checked"<?php endif; ?> /> Monday
<br clear="all" />
<input type="checkbox" name="tuesday" value="1" <?php if($CLUBTIMES->tuesday == "1"): ?>checked="checked"<?php endif; ?> /> Tuesday
<br clear="all" />
<input type="checkbox" name="wednesday" value="1" <?php if($CLUBTIMES->wednesday == "1"): ?>checked="checked"<?php endif; ?> /> Wednesday
<br clear="all" />
<input type="checkbox" name="thursday" value="1" <?php if($CLUBTIMES->thursday == "1"): ?>checked="checked"<?php endif; ?> /> Thursday
<br clear="all" />
<input type="checkbox" name="friday" value="1" <?php if($CLUBTIMES->friday == "1"): ?>checked="checked"<?php endif; ?> /> Friday
<br clear="all" />
<input type="checkbox" name="saturday" value="1" <?php if($CLUBTIMES->saturday == "1"): ?>checked="checked"<?php endif; ?> /> Saturday
<br clear="all" />
<br clear="all" />
<input type="submit" value="Save New Club" />
</form>
</fieldset>