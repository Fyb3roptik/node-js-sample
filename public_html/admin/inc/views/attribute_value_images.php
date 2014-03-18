<script type="text/javascript">
	$(document).ready(function() {
		$("#attribute_id").attr('disabled', true);

		$("#attribute_id").change(function() {
			refresh_attribute_values();
		});

		$("#attribute_value_id").change(function() {
			get_attribute_value_details();
		});

		refresh_attributes();
		refresh_attribute_values();
		get_attribute_value_details();

		$("#cancel_upload").click(function() {
			$("#attribute_id").val(0).change();
			$("#upload_fields").hide();
		});
	});

	function refresh_attributes() {
		var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
				"action" : "get_attributes"}
		$.post("/admin/attributes.http.php", data, function(data) {
			var $attribute_select = $("#attribute_id");
			$attribute_select.find('option:not(:first)').remove();
			for(var attribute_id in data["attributes"]) {
				var $opt = $(document.createElement('option'));
				$opt.attr('value', attribute_id).text(data["attributes"][attribute_id]);
				$opt.appendTo($attribute_select);
			}
			if($attribute_select.find('option:not(:first)').length > 0) {
				$attribute_select.attr('disabled', false);
			} else {
				$attribute_select.attr('disabled', true);
			}
		}, "json");
	}

	function refresh_attribute_values() {
		var attribute_id = parseInt($("#attribute_id").val());
		if(attribute_id > 0) {
			var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
					"action" : "get_attribute_values",
					"attribute_id" : attribute_id}
			$.post("/admin/attributes.http.php", data, function(data) {
				var $value_select = $("#attribute_value_id");
				$value_select.find('option:not(:first)').remove();
				for(var attribute_value_id in data["attribute_values"]) {
					var $opt = $(document.createElement('option'));
					$opt.attr('value', attribute_value_id).text(data["attribute_values"][attribute_value_id]);
					$opt.appendTo($value_select);
				}
				if($value_select.find('option:not(:first)').length > 0) {
					$("#row_select_value").show();
				} else {
					$("#row_select_value").hide();
				}
			}, "json");
		} else {
			$("#row_select_value").hide();
		}
	}

	function get_attribute_value_details() {
		var attribute_value_id = parseInt($("#attribute_value_id").val());
		if(attribute_value_id > 0) {
			var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
					"action" : "get_attribute_value_details",
					"attribute_value_id" : attribute_value_id}
			$.post("/admin/attributes.http.php", data, function(data) {
				if(true == data.status) {
					$("#attribute_value_picture").empty();
					if(data['attribute']['image'] != "") {
						var $img = $(document.createElement('img'));
						$img.attr('src', data['attribute']['image']);
						$img.appendTo($("#attribute_value_picture"));
						$("#attribute_value_picture").show();
					} else {
						$("#attribute_value_picture").hide();
					}
					$("#upload_fields").show();
				} else {
					$("#upload_fields").hide();
				}
			}, "json");
		} else {
			$("#upload_fields").hide();
		}
	}
</script>
<h2>Manage Attribute Value Images</h2>
<p>
	This form lets you upload images that are associated with attribute values. If a product has the associated
	attribute/value, the image will be displayed on the product listing as well as the product detail pages on the store-front.
</p>
<div class="messages">
	<?php echo $MS->messages('attributes'); ?>
</div>
<form id="attribute_value_images" action="" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Locate the Attribute/Value Pair</legend>
		<table>
			<tr id="row_select_attribute">
				<td>Select Attribute</td>
				<td>
					<select id="attribute_id" name="attribute_id">
						<option value="0">-Select Attribute-</option>
					</select>
				</td>
			</tr>
			<tr id="row_select_value">
				<td>Select Value</td>
				<td>
					<select id="attribute_value_id" name="attribute_value_id">
						<option value="0">-Select Value-</option>
					</select>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset id="upload_fields">
		<legend>Upload an Image</legend>
		<input type="hidden" name="action" value="process_new_image" />
		<div id="attribute_value_picture">&nbsp;</div>
		<input type="file" name="new_attribute_value_image" />
		<input type="submit" value="Upload New Image" /> or <a href="javascript:void(0);" id="cancel_upload">Cancel</a>
	</fieldset>
</form>