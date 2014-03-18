<script type="text/javascript" src="/admin/js/widget_list.js.php"></script>
<script type="text/javascript">
	$(document).ready(function() {
		Global_Widget_List = new Widget_List(1, 'Account_Widget', '<?php echo get_xsrf_field_name(); ?>', '<?php echo get_xsrf_field_value(); ?>');
		Global_Widget_List.refresh_widget_table('/admin/widgets.http.php', 'get_account_widgets');

		$("#new_widget_button").click(function() {
			var widget_id = parseInt($("#new_widget_id").val());
			if(widget_id > 0) {
				var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
						"action": "add_account_widget",
						"widget_id" : widget_id}
				$.post('/admin/widgets.http.php', data, function(data) {
					var new_widget_id = parseInt(data.widget_id);
					if(new_widget_id > 0) {
						Global_Widget_List.refresh_widget_table('/admin/widgets.http.php', 'get_account_widgets');
					}
				}, "json");
			}
			$("#new_widget_id").val(0);
		});
	});
</script>
<form id="account_widget_form" action="" method="post">
	<h2>Account Widgets</h2>
	<p>These widgets will show up as the default for the customer account home page. If no widgets are configured, global widgets will be used.</p>
	<?php
	require_once 'modules/widget_table.php';
	?>
</form>
