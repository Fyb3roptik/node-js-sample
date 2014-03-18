<script type="text/javascript">
$(document).ready(function() {
	var product_id = <?php echo $P->ID; ?>;

	$("#new_attribute").click(function() {
		$("#new_attribute_fields").show();
		$(this).hide();
	});

	$("#cancel_attribute").click(function() {
		$("#new_attribute_fields").hide();
		$("#new_attribute").show();
	});

	$("#attribute_id").change(function() {
		var value = $(this).val();
		if("new" == value) {
			$("#new_attribute_holder").show();
			$(this).hide();
			load_attribute_values(0);
		} else if(false == isNaN(parseInt(value))) {
			load_attribute_values(parseInt(value));
		}
	});

	$("#attribute_value_id").change(function() {
		var value = $(this).val();
		if("new" == value) {
			$("#new_value_holder").show();
			$(this).hide();
		}
	});

	$("#save_attribute").click(function() {
		var post_data = {
			"product_id" : product_id,
			"attribute_id" : $("#attribute_id").val(),
			"attribute_value_id" : $("#attribute_value_id").val(),
			"new_attribute" : $("#new_attribute_name").val(),
			"new_attribute_value" : $("#new_attribute_value").val()
		}
		$.post('/admin/product/saveAttribute/', post_data, function(data) {
			if(true == data['success']) {
				window.location.reload();
			}
		}, "json");
	});

	$("#new_attribute_cancel").click(function() {
		$(this).parent().hide();
		$("#attribute_id").val(0).change().show();
		return false;
	});

	$("#new_value_cancel").click(function() {
		$(this).parent().hide();
		$("#attribute_value_id").val(0).change().show();
		return false;
	});

	$("#new_value_holder").hide();
	$("#new_attribute_holder").hide();

	/* Call some default actions. */
	$("#attribute_field").hide();
	$("#attribute_id").val(0).change();
	$("#attribute_value_id").attr('disabled', true);
	$("#attribute_value_field").hide();
	$("#new_attribute_fields").hide();

	restripe();

	$("a.delete_pa").click(function() {
		var $row = $(this).parents('tr');
		var product_attribute_id = $row.find('input[name="product_attribute_id"]').val();
		var post_data = { "product_attribute_id" : product_attribute_id }
		if(true == confirm("Are you sure you want to remove this attribute from this product?")) {
			$.post('/admin/product/deleteAttribute/', post_data, function(data) {
				if(true == data['success']) {
					$row.remove();
					restripe();
				}
			}, "json");
		}

		return false;
	});

	$('.value_name').click(function() {
		var $name_form = $(this);
		var pa_id = $(this).prev('input').val();
		$name_form.hide();
		$name_form.parent().children('.ajax_loading').show();
		$.get('/admin/product/getAttributeValues/' + pa_id, null, function(data) {
			if(true == data['success']) {
				var $value_form = $name_form.next('.value_form');
				var $select = $value_form.children('select');
				$select.children().remove();
				var default_value = data['default'];
				for(var attribute_value_id in data['values']) {
					var attribute_name = data['values'][attribute_value_id];
					var $option = $(document.createElement('option'));
					$option.attr('value', attribute_value_id);
					$option.text(attribute_name);
					if(attribute_value_id == default_value) {
						$option.attr('selected', true);
					}
					$option.appendTo($select);
				}

				$name_form.hide();
				$value_form.show();
				$value_form.children('.cancel_value').click(function() {
					$(this).parent('.value_form').hide();
					$name_form.show();
					return false;
				});

				$select.change(function() {
					var data = {
						"product_attribute_id" : pa_id,
						"attribute_value_id" : $select.val()
					}
					$.post('/admin/product/updateAttributeValue/', data, function(data) {
						if(true == data['success']) {
							document.location.reload();
						}
					}, "json");
				});
			} else {
				$name_form.show();
			}
			$('span.ajax_loading').hide();
		}, 'json');
	});

	$('.value_form').hide();
	$('span.ajax_loading').hide();
});

function restripe() {
	$("#attribute_table tr:odd, #pqd_table tr:odd").css('background', '#DDD');
	$("#attribute_table tr:even, #pqd_table tr:even").css('background', '#FFF');
}

function load_attribute_values(attribute_id) {
	var data = {'action' : 'get_attribute_values',
			'attribute_id' : attribute_id,
			'<?php echo get_xsrf_field_name(); ?>' : '<?php echo get_xsrf_field_value(); ?>' }
	$.get('/admin/attribute/getValuesJson/' + attribute_id, data, function(data) {
		if(data.status == true) {
			var $select = $("#attribute_value_id");
			$select.empty();
			for(var index in data.values) {
				var value = data.values[index];
				var $option = $(document.createElement('option'));
				$option.attr('value', value.id).text(value.value);
				$option.appendTo($select);
			}
			$select.attr('disabled', false);
		}
	}, "json");
}
</script>
<fieldset>
	<legend>Attributes</legend>

	<input type="button" id="new_attribute" value="Add New Attribute" />
	<div id="new_attribute_fields">
		<table>
			<tr>
				<td>Attribute:</td>
				<td>
					<?php
					$sql = SQL::get()->select('attribute_id, name')
						->from('attributes')
						->orderBy('name');
					$query = db_query($sql);
					$attribute_options = array('0' => '-Select Attribute-',
									'new' => '-New Attribute-');
					while($query->num_rows > 0 && $a = $query->fetch_assoc()) {
						$attribute_options[$a['attribute_id']] = $a['name'];
					}
					echo draw_select('attribute_id', $attribute_options, 0, 'id="attribute_id"');
?>
					<span id="new_attribute_holder">
						<input type="text" name="new_attribute" id="new_attribute_name" value="New Attribute" /> or <a href="#" id="new_attribute_cancel">cancel</a>
					</span>
				</td>
			</tr>
			<tr>
				<td>Value:</td>
				<td>
					<select id="attribute_value_id">
						<option value="0">-Select Value-</option>
					</select>
					<span id="new_value_holder">
						<input type="text" name="new_attribute_value" id="new_attribute_value" value="New Attribute Value" /> or <a href="#" id="new_value_cancel">cancel</a>
					</span>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="button" id="save_attribute" value="Save Attribute" /> or <a href="javascript:void(0);" id="cancel_attribute">Cancel</a></td>
			</tr>
		</table>
	</div>
	<table id="attribute_table" cellspacing="0" cellpadding="0" width="100%">
		<thead>
			<tr>
				<th>Attribute</th>
				<th>Value</th>
				<th>Listing<br />Visible</th>
				<th>Detail<br />Visible</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$attributes = $P->getAttributes(true);
		foreach($attributes as $i => $A):
		?>
		<tr>
			<td>
				<?php echo $A->getName(); ?>
			</td>
			<td>
				<input type="hidden" name="pa_id[]" value="<?php echo $A->ID; ?>" />
				<span class="value_name">
					<?php echo $A->getValue(); ?>
				</span>
				<span class="value_form">
					<select name="new_value">
					</select>
					<a href="#" class="cancel_value">cancel</a>
				</span>
				<span class="ajax_loading">loading...</a>
			</td>
			<td style="text-align: center;">
				<input type="hidden" name="product_attribute_id" value="<?php echo $A->ID; ?>" />
				<?php echo draw_checkbox('attribute[show][' . $A->ID . ']', 1, $A->visible); ?>
			</td>
			<td>
				<?php echo draw_checkbox('attribute[detail_show][' . $A->ID . ']', 1, $A->detail_visible); ?>
			</td>
			<td>
				<a class="delete_pa" href="#">delete</a>
			</td>
		</tr>
		<?php
		endforeach;
		?>
		</tbody>
	</table>
	<input type="submit" value="Save" />
</fieldset>
