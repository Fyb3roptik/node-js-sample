<script type="text/javascript">  
$(document).ready(function() {
	$("#search_button").click(function() {
		searchLocations();
		
		var address = document.getElementById("addressInput").value;
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({address: address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var center = results[0].geometry.location;
				var radius = $('#radiusSelect').val();
				$.get('/locator/getClubs/?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius, function(data) {
				
				}, "json");
			}
		});
		
		return false;
	});
});
</script>
<div id="search-content">
	<div id="search-title" class="LargeTextDark">Locate a club near you</div>
	<br clear="all" />
	<br clear="all" />
	<form action="/locator/searchClubs/" method="get">
	<div id="search_box">
		<input type="text" id="addressInput" class="search_input" name="q" />
	</div>
	<div id="search_button">
		<img id="search_button" src="/images/search_button.png" />
		<select id="radiusSelect">
	      <option value="25" selected>25mi</option>
	      <option value="100">100mi</option>
	      <option value="200">200mi</option>
	    </select>
	</div>
	</form>
	<div><select id="locationSelect" style="width:100%;visibility:hidden"></select></div>
    <div id="map" style="width: 875px; height: 400px;"></div>
</div>