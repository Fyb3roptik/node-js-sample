<script type="text/javascript">
$(document).ready(function() {
	$("#button-small1").hover(function() {
		$(this).toggleClass('button-hover-small');
	});
	$("#button-small2").hover(function() {
		$(this).toggleClass('button-hover-small');
	});
});
</script>
<div id="homepage-content">
	<div class="customers-box">
		<h3><div id="customers-click"><?php echo $DJ->name; ?>'s Page</div></h3>
		<br clear="all" />
		<div id="customer-buttons">
			<a href="/<?php echo $DJ->username; ?>/playlist/">
				<div class="button">
					<div id="iphone-button-text">Search Playlist</div>
				</div>
			</a>
			<br clear="all" />
			<br clear="all" />
			<?php if($CUSTOMER->ID == $DJ->ID): ?>
			<a href="/<?php echo $DJ->username; ?>/requests/">
				<div class="button">
					<div id="iphone-button-text">Request Center</div>
				</div>
			</a>
			<?php endif; ?>
		</div>
	</div>
</div>