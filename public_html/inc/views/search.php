<script type="text/javascript">
$(document).ready(function() {
	$("#addressInput").focus(function() {
		if($(this).val() == "ex: DJ Markham") {
			$(this).val("");
			$(this).removeClass('search_input_inactive');
			$(this).addClass('search_input');
		}
	});
	$("#addressInput").blur(function() {
		if($(this).val() == "") {
			$(this).val("ex: DJ Markham");
			$(this).removeClass('search_input');
			$(this).addClass('search_input_inactive');
		}
	});
	$(".add-customer").click(function() {
		var dj_id = this.id;
		var customer_id = <?php echo $CUSTOMER->ID; ?>;
		
		var post_data = {"customer_id": customer_id, "dj_id": dj_id}
		$.post("/search/addFavorite/", post_data, function(data) {
			alert("DJ has been added to your Favorites");
		});
		
		return false;
	});
	$("tr:last").find("td").addClass("bottom-td");
	$("tr:last").find("td:last").removeClass("bottom-td").addClass("bottom-noright-td");
});
</script>
<div id="search-content">
	<div id="search-content">
		<div id="search-title" class="LargeTextDark">Search for a DJ or Club</div>
		<br clear="all" />
		<br clear="all" />
		<form action="/search/find/" method="get">
		<div id="search_box">
			<input type="text" id="addressInput" class="search_input" name="q" value="<?php echo get_var('q'); ?>" />
		</div>
		<div id="search_button">
			<img id="search_button" src="/images/search_button.png" />
		</div>
		</form>
	</div>
	<br clear="all" />
	<br clear="all" />
	<div id="search-results">
		<table cellpadding="5" cellspacing="0">
			<thead>
				<th class="search-header">Club/DJ Name</th>
				<th class="search-header">Type</th>
				<th class="search-header">Location</th>
				<th></th>
			</thead>
			<tbody>
			<?php foreach($search_results as $k => $sr): ?>
				<?php if($sr instanceof Customer): ?>
				<tr>
					<td class="left-td" width="30%"><div class="search-results-text"><a href="/<?php echo $sr->username; ?>/"><?php echo $sr->name; ?></a></div></div>
					<td class="center-td" width="10%"><div class="search-results-text">DJ</div></td>
					<td class="center-td" width="50%"><div class="search-results-text">N/A</div></td>
					<td class="right-td" width="10%"><div class="search-results-text"><a href="#" class="add-customer" id="<?php echo $sr->ID; ?>"><img title="Add to Favorites" alt="Add to Favorites" src="/images/add.png"></a></div></td>
				</tr>
				<?php else: ?>
				<tr>
					<td class="left-td" width="30%"><div class="search-results-text"><?php echo $sr->name; ?></div></td>
					<td class="center-td" width="10%"><div class="search-results-text">Club</div></td>
					<td class="center-td" width="50%"><div class="search-results-text"><?php echo $sr->city; ?>, <?php echo $sr->state; ?></div></td>
					<td class="right-td" width="10%"><div class="search-results-text"></div></td>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>