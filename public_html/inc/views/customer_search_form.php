<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function() {
	$("#search").click(function() {
		search();
	});

	$("#create").click(function() {
		saveCustomer();
	})

	$("#results_table").hide();
	$("#empty_results").show();

	$("#customer_search_form").submit(function() {
		search();
		return false;
	});

	$(".numeric3").numeric();
	$(".numeric4").numeric();
	$("#customer_state_text").hide();
	$("#name").focus();
	$("#search_ajax").hide();


	$("#form_reset").click(function() {
    	document.customer_search_form.reset();
    return false;
	});

});


function search() {
	var data = { 	"action" : "search",
			"name" : $("#name").val(),
			"email" : $("#email").val(),
			"company" : $("#company").val(),
			"phone" : $("#phone").val(),
			"zip" : $("#zip").val(),
			"city" : $("#city").val(),
			"state" : $("#state").val(),
			"order_id" : $("#order_id").val(),
			"syspro_id" : $("#syspro_id").val(),
			"customer_id" : $("#customer_id").val()
			}
	$("#search_ajax").show();
	$("#results_table").hide();
	$("#empty_results").hide();
	$.post("/customer_search.http.php", data, function(data) {
		if(data['results'].length > 0) {
			$("#results_table").show();
			$("#search_ajax").hide();
			$("#empty_results").hide();
			$("#results_table tbody").empty();
			var results = data['results'];
			for(var index in results) {
				var rec = results[index];
				var customer_id = rec['customer_id'];
				var $tr = $(document.createElement('tr'));
				$(document.createElement('td')).appendTo($tr);
				for(var key in rec) {
					var $td = $(document.createElement('td'));
					var td_text = rec[key];

					if(key != 'customer_id') {
						$td.text(td_text);
					} else {
						var $a = $(document.createElement('a'));
						$a.attr('href', '<?php echo LOC_SALES; ?>?action=customer_detail&customer=' + customer_id).text(rec[key]).appendTo($td);
					}
					$td.appendTo($tr);
				}
				
				$tr.appendTo("#results_table tbody");
			}
		} else {
			$("#empty_results").show();
			$("#results_table").hide();
		}
	}, "json");
}
/* ]]> */
</script>

<div class="order_head"><span class="RedText2">Search for Customers</span>
<span class="CreateNew">&nbsp;&nbsp;or&nbsp;&nbsp;<a href="<?php echo LOC_SALES; ?>?action=create_customer">Create a New Customer</a></span>
</div>
<br clear="all" />

<div class="salesSearch">
<div class="message_stack"><?php echo $MS->messages(); ?></div>
	
	<form id="customer_search_form" name="customer_search_form" action="" method="post">
		<fieldset>
		
			<div class="search_form_wrap">
			
					<div class="search_form">Name<br><input type="text" class="textfield" id="name" value="" /></div>

					<div class="search_form">Company<br><input type="text" class="textfield" id="company" value="" /></div>

					<div class="search_form">Email<br><input type="text" class="textfield" id="email" value="" /></div>
					
					<div class="search_form0">Phone<br><input type="text" class="textfield" id="phone" value="" /></div>

			<!-- end search_form_wrap --></div>
			
			<br clear="all" />
						
			<div class="search_form_wrap">			
					
					<div class="search_form">City<br><input type="text" class="textfield" id="city" value="" /></div>
					
					<div class="search_form">State<br>
					<?php echo draw_select("state", array_merge(array(0 => '-Select State-'), get_states()), $address['state'], 'id="state" class="textfield" '); ?>
					</div>

					<div class="search_form0">Zip Code<br><input type="text" class="textfield" id="zip" value="" /></div>

			<!-- end search_form_wrap --></div>
			
			<br clear="all" />
			
			<div class="search_form_wrap">

					<div class="search_form">Web Order #<br><input type="text" id="order_id" class="textfield" value="" /></div>

					<div class="search_form">Syspro #<br><input type="text" id="syspro_id" class="textfield" value="" disabled="disabled" /></div>

					<div class="search_form0">Customer #<br><input type="text" class="textfield" id="customer_id" value="" /></div>

			<!-- end search_form_wrap --></div>
			
			<br clear="all" />
			
			<div class="search_form_wrap">
			<div class="search_form">
					<input type="image" id="form_reset" alt="reset" src="/images/reset_bttn.png" width="50" height="22" />&nbsp;&nbsp;
					<input type="image" id="search" name="search" src="/images/searchCustomers.png" width="140" height="22" />
			</div>
			<!-- end search_form_wrap --></div>
			
					
		</fieldset>
	</form>
<!-- end sales search --></div>

<br clear="all" />
<br clear="all" />

<div class="search_results" id="search_results">
<h2 class="RedText2">Results:</h2>
<br clear="all" />


		<div id="search_ajax"><img src="/images/ajax-loader.gif" /> Searching...</div>
		<div id="empty_results">No results were found.</div>
		<table id="results_table" width="100%" border="0" cellspacing="0" cellpadding="0" class="favouriteTable">
			<thead>
				<tr>
					<th><div class="LeftCorner">&nbsp;</div></th>
					<th class="spaced">ID</th>
					<th>Name</th>
					<th class="spaced">Sales Rep</th>
					<th class="spaced">Email</th>
					<th class="spaced">Company</th>
					<th class="spaced">Address 1</th>
					<th class="spaced">Address 2</th>
					<th class="spaced">Address 3</th>
					<th class="spaced">City</th>
					<th class="spaced">State</th>
					<th class="spaced">Zip</th>
					<th class="spaced">Phone</th>
					<th class="LastColumn"><div class="RightCorner">&nbsp;</div></th>
				</tr>
			</thead>
			<tbody>
				<tr>
				<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</div>

