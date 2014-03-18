<script type="text/javascript">
$(document).ready(function() {
	$(".striped-table:odd").each(function() {
		$(this).addClass('odd');
	});
	$(".striped-table:even").each(function() {
		$(this).addClass('even');
	});
	$(".mark-sung").click(function() {
		if($(this).is(":checked")) {
			$(this).parent().parent().addClass('selected');
		} else {
			$(this).parent().parent().removeClass('selected');
		}
	});
	$("#hide-sung").click(function() {
		$(".striped-table").each(function() {
			var row = $(this).find(".mark-sung:checked").parent().parent();
			var id = $(this).find(".mark-sung:checked").parent().parent().attr('id');

			var params = {customer_request_id: id}
			$.post("/<?php echo $DJ->username; ?>/markSung/", params, function(data) {
				row.fadeOut();
			}, "json");
		});

		//Re-stripe
		$(".striped-table:odd").each(function() {
			$(this).addClass('odd');
		});
		$(".striped-table:even").each(function() {
			$(this).addClass('even');
		});
	});

	//Check for new requests
	setInterval(function() {
        var tr = $('#request_table tr:last');
        var latest_id = tr[0].id;

        var params = {latest_id:latest_id}
        $.post("/<?php echo $DJ->username; ?>/requestsJSON/", params, function(data) {
        	if(data[0].empty == false) {
				for(var i = 0; i < data.length; i++) {
					$("#request_table > tbody:last").append("<tr class='striped-table' id='"+data[i].id+"'><td class='left-td'><input type='checkbox' class='mark-sung' id='"+data[i].playlist_id+"' /> <label for='"+data[i].playlist_id+"'>Mark song as sung</label></td><td class='center-td'>"+data[i].title+" -- "+data[i].artist+"</td><td class='center-td'>"+data[i].customer+"</td><td class='right-td'>"+data[i].request_time+"</td></tr>")
				}

				//Re-stripe
				$(".striped-table:odd").each(function() {
					$(this).addClass('odd');
				});
				$(".striped-table:even").each(function() {
					$(this).addClass('even');
				});

				$(".mark-sung").click(function() {
					if($(this).is(":checked")) {
						$(this).parent().parent().addClass('selected');
					} else {
						$(this).parent().parent().removeClass('selected');
					}
				});
        	}
        }, "json");

    }, 5000);
});
</script>
<div id="homepage-content">
	<div class="customers-box">
		<h3><div id="customers-click"><?php echo $DJ->name; ?>'s Requests</div></h3>
		<div id="search-results-playlist">
			<table width="680px" cellspacing="0" cellpadding="10" id="request_table">
				<tr class="tr-head">
					<th><input type="button" id="hide-sung" value="Hide Sung Songs" /></th>
					<th class="search-header">Song</th>
					<th class="search-header">User</th>
					<th class="search-header">Date</th>
					<th></th>
				</tr>
				<?php if(!empty($REQUESTS)): ?>
				<?php foreach($REQUESTS as $r): ?>
				<?php $DJ = new Customer($r->dj_id); ?>
				<?php $REQUESTING_CUSTOMER = new Customer($r->customer_id); ?>
				<?php if($DJ->has_playlist == 0): ?>
				<?php $PLAYLIST = new Playlist("default_playlist", "default_playlist_id", $r->song_id); ?>
				<?php else: ?>
				<?php $PLAYLIST = new Playlist($DJ->ID."_playlist", $DJ->ID."_playlist_id", $r->song_id); ?>
				<?php endif; ?>
				<tr class="striped-table" id="<?php echo $r->ID; ?>">
					<td class="left-td"><input type="checkbox" class="mark-sung" id="<?php echo $PLAYLIST->ID; ?>" /> <label for="<?php echo $PLAYLIST->ID; ?>">Mark song as sung</label></td>
					<td class="center-td"><?php echo $PLAYLIST->title; ?> -- <?php echo $PLAYLIST->artist; ?></td>
					<td class="center-td"><?php if($r->customer_id != '0'): ?><?php echo $REQUESTING_CUSTOMER->getStageName(); ?><?php else: ?>Kiosk<?php endif; ?></td>
					<td class="right-td"><?php echo date("F d, Y g:i A", $r->request_time); ?></td>
				</tr>
				<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>