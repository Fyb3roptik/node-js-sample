<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(document).ready(function() {
	$("#search").click(function() {
		search();
	});

	$("#create").click(function() {
		saveCustomer();
	});

	$("#results_table").hide();
	$("#empty_results").show();

	$("#customer_search_form").submit(function() {
		search();
		return false;
	});

	$("#name").focus();
	$("#customer_state_text").hide();
	$("#phone").numeric();
	$("#numeric3").numeric();
	$("#numeric4").numeric();
});

function saveCustomer()
{
	var data = {	"action" : "add_customer_ajax",
			"name" : $("#customer_name").val(),
			"sales_rep" : $("#customer_sales_rep").val(),
			"email" : $("#customer_email").val(),
			"customer_id" : $("#customer_id").val(),
			"secondary_email" : $("#customer_email2").val(),
			"customer_company" : $("#customer_company").val(),
			"customer_address1" : $("#customer_address1").val(),
			"customer_address2" : $("#customer_address2").val(),
			"customer_address3" : $("#customer_address3").val(),
			"customer_city" : $("#customer_city").val(),
			"customer_state" : $("#customer_state").val(),
			"customer_zipcode" : $("#customer_zipcode").val(),
			"customer_phone" : $("#customer_phone").val(),
			"customer_ext" : $("#customer_ext").val()
			}
    $.post("/sales", data, function(value) {
    	var email = $("#customer_email").val();
		$("#email").val(email);
		$("#search").click();
    });
}

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
	$.post("/customer_search.http.php", data, function(value) {
		if(value['results'].length > 0) {
			$("#results_table").show();
			$("#empty_results").hide();
			$("#results_table tbody").empty();
			var results = value['results'];
			for(var index in results) {
				var rec = results[index];
				var customer_id = rec['customer_id'];
				var customer_name = rec['name'];
				var $tr = $(document.createElement('tr'));
				for(var key in rec) {
					var $td = $(document.createElement('td'));
					var td_text = rec[key];

					if(key != 'customer_id') {
						$td.text(td_text);
					} else {
						var $a = $(document.createElement('a'));
						var onclick_action = 'select_customer("' + customer_id + '", "' + customer_id + '")';
						$a.attr('href', 'javascript:'+onclick_action).text(rec[key]).appendTo($td);
						//$a.attr('onclick', onclick_action);
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

function select_customer(customer_id, name) {
	this.opener.change_customer(customer_id, name);
	self.close();
}
/* ]]> */
</script>
<div style="width:1000px;">
<h3>Search for Customers</h3>
<div style="float:left;">
	<form id="customer_search_form" action="" method="post">
			<table>
				<tr>
					<td>Name:</td>
					<td><input type="text" id="name" value="" /></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><input type="text" id="email" value="" /></td>
				</tr>
				<tr>
					<td>Company:</td>
					<td><input type="text" id="company" value="" /></td>
				</tr>
				<tr>
					<td>Phone:</td>
					<td><input type="text" id="phone" value="" /></td>
				</tr>
				<tr>
					<td>Zip Code:</td>
					<td><input type="text" id="zip" value="" /></td>
				</tr>
				<tr>
					<td>City:</td>
					<td><input type="text" id="city" value="" /></td>
				</tr>
				<tr>
					<td>State:</td>
					<td>
						<?php
						echo draw_select("state", array_merge(array(0 => '-Select State-'), get_states()), $address['state'], 'id="state"');
						?>
					</td>
				</tr>
				<tr>
					<td>Web Order #:</td>
					<td><input type="text" id="order_id" value="" /></td>
				</tr>
				<tr>
					<td>Syspro #:</td>
					<td><input type="text" id="syspro_id" value="" disabled="disabled" /></td>
				</tr>
				<tr>
					<td>Customer #:</td>
					<td><input type="text" id="customer_id" value="" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="button" id="search" name="search" value="Search Customers" />
						<input type="reset" value="Reset" />
					</td>
				</tr>
			</table>
	</form>
</div>

<h3>Create New Customer</h3>
<div style="float:left;">
	<form id="customer_form" action="" method="post">
		<input type="hidden" name="customer_id" value="<?php echo $C->ID; ?>" />
		<input type="hidden" id="customer_sales_rep" name="customer[sales_rep]" value="<?php echo $CUSTOMER->ID; ?>" />
		<input type="hidden" name="action" value="process_customer" />
		<input type="hidden" name="redirect" value="false" />
			<table>
				<tr>
					<td>Name:</td>
					<td><input type="text" id="customer_name" name="customer[name]" value="<?php echo $C->name; ?>" /></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><input type="text" id="customer_email" name="customer[email]" value="<?php echo $C->email; ?>" /></td>
				</tr>
				<tr>
					<td>Secondary Email:</td>
					<td><input type="text" id="customer_email2" name="customer[secondary_email]" value="<?php echo $C->secondary_email; ?>" /></td>
				</tr>
				<tr>
					<td>Company Name </td>
					<td><input type="text" id="customer_company" name="customer[company]" value="<?php echo $C->company; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td>Street Address</td>
					<td><input type="text" id="customer_address1" name="customer[address_1]" value="<?php echo $C->address_1; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td>       Address 2</td>
					<td><input type="text" id="customer_address2" name="customer[address_2]" value="<?php echo $C->address_2; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td>       Address 3</td>
					<td><input type="text" id="customer_address3" name="customer[address_3]" value="<?php echo $C->address_3; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td>City</td>
					<td><input type="text" id="customer_city" name="customer[city]" value="<?php echo $C->city; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td>State</td>
					<td>
						<input type="text" name="customer[state]" value="<?php echo $C->state; ?>" class="textfield" id="customer_state_text" />
						<?php echo draw_select('customer[state]', get_states(), $C->state, 'id="customer_state" class="textfield"'); ?>
					</td>
				</tr>
				<tr>
					<td>Zip Code</td>
					<td><input type="text" id="customer_zipcode" name="customer[zip_code]" value="<?php echo $C->zip_code; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td>Phone</td>
					<td><input type="text" id="customer_phone" id="numeric3" name="customer[phone]" value="<?php echo $C->phone; ?>" class="textfield"/></td>
				</tr>
				<tr>
					<td>Ext</td>
					<td><input type="text" id="customer_ext" id="numeric4" name="customer[ext]" value="<?php echo $C->ext; ?>" size="5"/></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="button" id="create" name="create" value="Save" /> or <a href="<?php echo LOC_SALES; ?>">Cancel</a></td>
				</tr>
			</table>
	</form>
</div>
</div>
<br clear="all" />
<div id="search_results">
	<h3 class="greeting">Results:</h3>
	<div class="contentbox">
		<div id="empty_results">No results were found.</div>
		<table id="results_table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Sales Rep</th>
					<th>Email</th>
					<th>Company</th>
					<th>Address 1</th>
					<th>Address 2</th>
					<th>City</th>
					<th>State</th>
					<th>Zip</th>
					<th>Phone</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>