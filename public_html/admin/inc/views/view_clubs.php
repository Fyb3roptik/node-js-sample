<script type="text/javascript">
$(document).ready(function() {
	$(".club-data:odd").each(function() {
		$(this).css("background-color", "#CCC").nextUntil(".club-data").css("background-color", "#CCC");
	});
	$(".club-data:even").each(function() {
		$(this).css("background-color", "#FEFEDC").nextUntil(".club-data").css("background-color", "#FEFEDC");
	});
	$(".club-data").mouseover(function() {
		$(this).css("background-color", "#898989").nextUntil(".club-data").css("background-color", "#898989");
	});
	$(".club-data").mouseout(function() {
		$(".club-data:odd").each(function() {
			$(this).css("background-color", "#CCC").nextUntil(".club-data").css("background-color", "#CCC");
		});
		$(".club-data:even").each(function() {
			$(this).css("background-color", "#FEFEDC").nextUntil(".club-data").css("background-color", "#FEFEDC");
		});
	});
	$(".remove-club").click(function() {
		if(confirm("Are you sure you want to remove this club from your list?")) {
			var club_id = this.id;
			
			var post_data = {"club_id" : club_id}
			$.post("/admin/clubs/removeClub/", post_data, function(data) {
				if(true == data['success']) {
					$("#club-"+data['club_id']).fadeOut();
				}
			}, "json");
		}
	});
	$("input[name=confirmed_only]").click(function() {
		$("#confirmed_form").submit();
	});
});
</script>
<?php echo $MS->messages('clubs'); ?>
<fieldset>
<legend>Add Club</legend>
<a href="/admin/clubs/add/">Add Club</a>
</fieldset>
<fieldset>
<legend>Club Search</legend>
<form action="" id="confirmed_form" method="get">
<input type="checkbox" id="confirmed_only" name="confirmed_only" value="1" <?php if(get_var('confirmed_only') == "1"): ?>checked="checked"<?php endif; ?> />&nbsp;&nbsp;<label for="confirmed_only">Only show confirmed clubs?</label>
<br clear="all" />
<br clear="all" />
<input type="text" id="club_search" name="name" size="40" value="<?php echo get_var('name'); ?>" />&nbsp;&nbsp;<input type="submit" id="club_search_btn" value="Search" />
</form>
</fieldset>
<fieldset>
<legend>Clubs</legend>
<table id="results_table" width="100%" border="0" cellspacing="0" cellpadding="0" class="favouriteTable">
	<thead>
		<th>Name</th>
		<th>Address 1</th>
		<th>Address 2</th>
		<th>City</th>
		<th>State</th>
		<th>Zipcode</th>
		<th>Phone</th>
		<th>Latitude</th>
		<th>Longitude</th>
		<th></th>
		<th></th>
	</thead>
	<tbody>
		<?php foreach($CLUBS as $C): ?>
		<tr class="club-data" id="club-<?php echo $C->ID; ?>">
			<td><a href="/admin/clubs/edit/<?php echo $C->ID; ?>"><?php echo $C->name; ?></a></td>
			<td><?php echo $C->address_1; ?></td>
			<td><?php echo $C->address_2; ?></td>
			<td><?php echo $C->city; ?></td>
			<td><?php echo $C->state; ?></td>
			<td><?php echo $C->zipcode; ?></td>
			<td><?php echo $C->phone; ?></td>
			<td><?php echo $C->lat; ?></td>
			<td><?php echo $C->lng; ?></td>
			<td>&nbsp;</td>
			<td><a href="#" class="remove-club" id="<?php echo $C->ID; ?>">Remove</a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</fieldset>