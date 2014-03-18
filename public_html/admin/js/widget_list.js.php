<?php
require_once '../inc/global.php';
?>
function Widget_List(foreign_key, widget_type, xsrf_field_name, xsrf_field_value) {
	this.foreign_key = parseInt(foreign_key);
	this.widget_type = widget_type;
	this.xsrf_field_name = xsrf_field_name;
	this.xsrf_field_value = xsrf_field_value;

	/**
	 * Get the current Foreign_Key_Widgets for a given foreign key
	 */
	this.refresh_widget_table = function (post_page, post_action) {
		var widget_type = this.widget_type;
		if(this.foreign_key > 0) {
			var data = {"action" : post_action,
					"foreign_key" : foreign_key}
			data[this.xsrf_field_name] = this.xsrf_field_value;
			var widget_type = this.widget_type;
			var xsrf_field_name = this.xsrf_field_name;
			var xsrf_field_value = this.xsrf_field_value;
			$.post(post_page, data, function(data) {
				var widgets = data.widgets;
				if(widgets.length > 0) {
					var $widget_table = $("#widget_table tbody");
					$("#widget_table tr:not(:first)").remove();
					for(var i in widgets) {
						var W = widgets[i];
						var $tr = $(document.createElement('tr'));
						var $name_td = $(document.createElement('td'));

						var $widget_id_input = $(document.createElement('input'));
						$widget_id_input.attr('type', 'hidden').attr('name', 'widget_id[]').val(W['ID']);
						$widget_type_input = $(document.createElement('input'));
						$widget_type_input.attr('type', 'hidden').attr('name', 'widget_type[]').val(widget_type);

						$name_td.text(W['nickname']);
						$name_td.appendTo($tr);
						$widget_id_input.appendTo($name_td);
						$widget_type_input.appendTo($name_td);
						var $type_td = $(document.createElement('td'));
						$type_td.text(W['widget_class']);
						$type_td.appendTo($tr);

						var $drop_td = $(document.createElement('td'));
						var $drop_link = $(document.createElement('a'));
						$drop_link.attr('href', 'javascript:void(0)').attr('class', 'widget_delete').text('[delete]');
						$drop_link.attr('onclick', 'delete_widget(this)');

						$drop_link.appendTo($drop_td);

						$drop_td.appendTo($tr);
						$tr.appendTo($widget_table);
						$tr.attr('class', 'widget_record');
					}
					$("#widget_table").show();
					$("#widgets").show();
				} else {
					$("#widget_table").hide();
				}

				$("#widget_table tbody").sortable({	accept : "widget_record",
										axis: "vertically",
										stop: function(event, ui) {
											save_sort(widget_type, xsrf_field_name, xsrf_field_value);
										}
									});
			}, "json");
		}
	}
}

function save_sort(widget_type, xsrf_field_name, xsrf_field_value) {
	var widget_ids = new Array();
	var index = 0;
	$("#widget_table tbody tr").each(function() {
		widget_ids[index] = $(this).find('input[type="hidden"][name="widget_id[]"]').val();
		index++;
	});

	var data = {"action" : "save_sort_order",
			"widget_type" : widget_type}
	data[xsrf_field_name] = xsrf_field_value;
	for(var i in widget_ids) {
		data["widgets[" + i + "]"] = widget_ids[i];
	}
	$.post('/admin/widgets.http.php', data, function(data) {
		//stub
	}, "json");
}

function delete_widget(hyperlinky) {
	var $link = $(hyperlinky);
	var $parent_row = $link.closest('tr');
	var widget_id = parseInt($parent_row.find('input[type="hidden"][name="widget_id[]"]').val());
	var widget_type = $parent_row.find('input[type="hidden"][name="widget_type[]"]').val();
	if(widget_id > 0) {
		//post delete and hide $parent_row
		var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
				"action" : "drop_fk_widget",
				"widget_id" : widget_id,
				"widget_type" : widget_type }
		$.post( '/admin/widgets.http.php', data, function(data) {
			var deleted_widget = parseInt(data.widget_id);
			if(deleted_widget > 0) {
				$("#widget_table tbody tr").each(function() {
					if(deleted_widget == parseInt($(this).find('input[type="hidden"][name="widget_id[]"]').val())) {
						$(this).remove();
					}
				});
			}
		}, "json" );
	}
}