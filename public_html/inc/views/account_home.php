<script type="text/javascript">
$(document).ready(function() {
	$("a.edit_info").click(function() {
		$("#account_info_container").load($(this).attr('href'));
		return false;
	});
});
</script>
<?php echo $MS->messages(); ?>

<br clear="all" />


<script type="text/javascript">
$(document).ready(function() {
	$("#add-club").click(function() {
		$("#add-club").hide();
		$("#new-club").slideDown();
	});
	$(".remove-club").click(function() {
		if(confirm("Are you sure you want to remove this club from your list?")) {
			var club_id = this.id;
			
			var post_data = {"club_id" : club_id}
			$.post("/<?php echo $CUSTOMER->username; ?>/removeClub/", post_data, function(data) {
				if(true == data['success']) {
					$("#club-"+data['club_id']).fadeOut();
				}
			}, "json");
		}
	});
	$(".checkin").click(function() {
		var active = "1";
		var customer_id = '<?php echo $CUSTOMER->ID; ?>';
		var customer_club_id = this.id;
		
		var post_data = {"active" : active, "customer_id" : customer_id, "customer_club_id" : customer_club_id}
		$.post("/<?php echo $CUSTOMER->username; ?>/clubCheck/", post_data, function(data) {
			if(true == data['Success']) {
				window.location = "/myaccount/";
			} else {
				alert(data['Reason']);
			}
		}, "json");
		return false; 
	});
	$(".checkout").click(function() {
		var active = "0";
		var customer_id = '<?php echo $CUSTOMER->ID; ?>';
		var customer_club_id = this.id;
		
		var post_data = {"active" : active, "customer_id" : customer_id, "customer_club_id" : customer_club_id}
		$.post("/<?php echo $CUSTOMER->username; ?>/clubCheck/", post_data, function(data) {
			if(true == data['Success']) {
				window.location = "/myaccount/";
			}
		}, "json"); 
		return false;
	});
	$("#club-show").hide();
	$("#list-show").hide();
	$("#plan-show").hide();
	$("#dj-show").hide();
	
	$("#Info").click(function() {
		var hideArray = ["club-show", "list-show", "plan-show", "dj-show"];
		for(i=0; i<hideArray.length; i++) {
			if($("#"+hideArray[i]).is(":visible")) {
				$("#"+hideArray[i]).slideUp();
			}
		}
		$("#info-show").slideDown();
	});
	$("#Clubs").click(function() {
		var hideArray = ["info-show", "list-show", "plan-show", "dj-show"];
		for(i=0; i<hideArray.length; i++) {
			if($("#"+hideArray[i]).is(":visible")) {
				$("#"+hideArray[i]).slideUp();
			}
		}
		$("#club-show").slideDown();
	});
	$("#List").click(function() {
		var hideArray = ["info-show", "club-show", "plan-show", "dj-show"];
		for(i=0; i<hideArray.length; i++) {
			if($("#"+hideArray[i]).is(":visible")) {
				$("#"+hideArray[i]).slideUp();
			}
		}
		$("#list-show").slideDown();
	});
	$("#Plan").click(function() {
		var hideArray = ["info-show", "club-show", "list-show", "dj-show"];
		for(i=0; i<hideArray.length; i++) {
			if($("#"+hideArray[i]).is(":visible")) {
				$("#"+hideArray[i]).slideUp();
			}
		}
		$("#plan-show").slideDown();
	});
	$("#Dj").click(function() {
		var hideArray = ["info-show", "club-show", "list-show", "plan-show"];
		for(i=0; i<hideArray.length; i++) {
			if($("#"+hideArray[i]).is(":visible")) {
				$("#"+hideArray[i]).slideUp();
			}
		}
		$("#dj-show").slideDown();
	});
	$(window.location.hash).click();
});
</script>

<div id="account-accordian">
	<div class="account-box">
		<h3><a class="header_link" id="Info">My Info</a></h3>
		<br clear="all" />
		<div id="info-show">
			<div class="account_info_container" id="account_info_container">
			    <div class="account_info_label">Name:</div>
				<div class="account_info_info"><?php echo $CUSTOMER->name; ?></div>
			     <br clear="all" />
			    <div class="account_info_label">Stage Name:</div>
				<div class="account_info_info"><?php echo $CUSTOMER->stage_name; ?></div>
			     <br clear="all" />
			    <div class="account_info_label">Email:</div>
				<div class="account_info_info"><?php echo $CUSTOMER->email; ?></div>
			    <br clear="all" />
			    <div class="account_info_label">Username:</div>
				<div class="account_info_info"><?php echo $CUSTOMER->username; ?></div>
			     <br clear="all" />
			    <div class="account_info_edit"><a class="edit_info" href="<?php echo LOC_ACCOUNT_EDIT; ?>">Edit Account Info</a></div>
			</div>
		</div>
	<!-- account-box --></div>
	<div class="account-box">
		<h3><a class="header_link" id="Clubs">My Clubs</a></h3>
		<br clear="all" />
		<div id="club-show">
			<div class="account_info_edit">
				<a href="#" id="add-club" class="edit_info">Add a Club</a>
			</div>
			<br clear="all" />
			<div id="new-club">
				<span class="OrangeText2">Add Club</span>
				<form name="new-club-form" id="new-club-form" method="post" action="/<?php echo $CUSTOMER->username; ?>/addClub/">
				Name: <input type="text" name="club[name]" />
				<br clear="all" />
				<br clear="all" />
				Country: 
				<select id="Countries" name="club[country]">
					<option>--Choose Country--</option>
					<option value="Canada">Canada</option>
					<option value="United States">United States</option>
				</select> 
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
				State/Province: <select id="States" name="club[state]"><?php echo draw_select('club[state]', get_fullname_states(), null, "id='States'", null, false); ?></select>
				<br clear="all" />
				<br clear="all" />
				Zipcode: <input type="text" name="club[zipcode]" />
				<br clear="all" />
				<br clear="all" />
				Phone: <input type="text" name="club[phone]" />
				<br clear="all" />
				<br clear="all" />
				<input type="submit" value="Save New Club" />
				</form>
				<br clear="all" />
				<br clear="all" />
			</div>
			<?php $CLUBS = $CUSTOMER->getClubs(); ?>
			<div id="my-clubs">
				<table>
					<thead>
						<th width="37%">Club Name</th>
						<th width="31%">Time/Date</th>
						<th width="29%">Start/End Show</th>
						<th></th>
						<th></th>
					</thead>
					<tbody>
						<?php foreach($CLUBS AS $CC): ?>
						<?php $CL = new Clubs($CC->club_id); ?>
						<tr id="club-<?php echo $CC->ID; ?>">
							<td><?php echo $CL->name; ?></td>
							<td class="account_info_edit"><a href="/<?php echo $CUSTOMER->username; ?>/times/<?php echo $CC->ID; ?>" class="edit_times">Edit Times/Dates</a></td>
							<td class="account_info_edit"><a href="#" class="checkin" id="<?php echo $CC->ID; ?>" <?php if($CC->active == "1"): ?>style="display:none"<?php endif; ?>>Start Show</a><a href="#" class="checkout" id="<?php echo $CC->ID; ?>" <?php if($CC->active == "0"): ?>style="display:none;"<?php else: ?>style="font-weight:bold;color:#018719"<?php endif; ?>>Exit Show</a></td>
							<td>&nbsp;</td>
							<td class="account_info_edit"><a href="#Clubs" class="remove-club" id="<?php echo $CC->ID; ?>">Remove</a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<div id="edit-times-container">
					
				</div>
			</div>
		</div>
	</div>
	  
	<?php if("dj" == $CUSTOMER->user_type): ?>
	<div class="account-box">
		<h3><a class="header_link" id="List">My List</a></h3>
		<br clear="all" />
		<div id="list-show">
			<div class="account_info_edit">
				<a href="/upload/">Upload your new playlist</a>
			</div>
			<div class="account_info_edit">
				<a href="/<?php echo $CUSTOMER->username; ?>/playlist/">View Playlist</a>
			</div>	
		</div>
	</div>
	 
	<div class="account-box">
		<h3><a class="header_link" id="Plan">My Plan</a></h3>
		<br clear="all" />
		<div id="plan-show">
			<div id="plan_holder">
			<?php $PLAN = new Plans($CUSTOMER->plan_id); ?>
			Current Plan: <?php echo $PLAN->name; ?>	
			</div>
			<div class="account_info_edit" id="plan_upgrade">
			<a href="/plans/">Change Plan</a>
			</div>
		</div>
	</div>
  <?php endif; ?>
</div>
<script type="text/javascript">
		$(document).ready(function(){
			$("#States").hide();
			$("#Countries").change(function() {
				
				var country = $(this).val();
				var params = {"country": country};
				var self = this;
				$.post("/clubs/getStates/", params, function(data) {
					$(self).siblings("#States").html(data['states']).show();
				}, "json");
			});
		});
	</script>