<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="/admin/js/widget_list.js.php"></script>
<script type="text/javascript">
tinyMCE.init({
	mode : "exact",
	elements: "product_description",
	theme : "advanced",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

	// Theme options
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons4 : "spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true
});

var tabs = ['#product_details', '#product_attributes', '#product_pricing', '#product_images', '#product_widgets', '#product_meta', '#product_tabs', '#product_categories', '#product_boxes'];

function change_tab(tab) {
	for(var i in tabs) {
		var tab_name = tabs[i];
		if(tab_name == tab) {
			$(tab_name).show();
			location.hash = tab_name;
			set_hash(tab_name);
		} else {
			$(tab_name).hide();
		}
	}
}

function set_hash(hash_name) {
	$("#form_hash").val(hash_name);
}

$(document).ready(function() {

	$("#product_form").submit(function() {
		$("#hidden_row").remove();
		return true;
	});

	/* Hide our tabs */
	for(var i in tabs) {
		if(i > 0) {
			var tab_id = tabs[i];
			$(tab_id).hide();
		}
	}

	var product_id = parseInt($("input[name='product_id']").val());
	var PWL = new Widget_List(product_id, 'Product_Page_Widget', '<?php echo get_xsrf_field_name(); ?>', '<?php echo get_xsrf_field_value(); ?>');
	PWL.refresh_widget_table('/admin/widgets.http.php', 'get_product_widgets');

	$("#new_widget_button").click(function() {
		var widget_id = parseInt($("#new_widget_id").val());
		if(widget_id > 0) {
			var product_id = parseInt($("input[name='product_id']").val());

			var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
					"action": "add_product_widget",
					"product_id" : product_id,
					"widget_id" : widget_id}
			$.post('/admin/widgets.http.php', data, function(data) {
				var new_widget_id = parseInt(data.widget_id);
				if(new_widget_id > 0) {
					PWL.refresh_widget_table('/admin/widgets.http.php', 'get_product_widgets');
				}
			}, "json");
		}
		$("#new_widget_id").val(0);
	});

	var page_hash = location.hash;
	if('' != page_hash) {
		change_tab(page_hash);
	}

	$("#product_tabs table tbody").zebra();

	$("#product_tabs table tbody").sortable({
		stop: function() {
			save_tab_sort();
		}
	});

	<?php if(false == $P->exists()) { ?>	
	var hidden_tabs = ['#product_attributes', '#product_pricing', '#product_images', '#product_widgets', '#product_meta', '#product_tabs', '#product_categories'];
	for(var tab_index in hidden_tabs) {
		$(hidden_tabs[tab_index]).html('<p>You must save this product before you can edit this information.</p>');
	}
	<?php } ?>
});

function save_tab_sort() {
	var post_data = { }
	var index = 0;
	$("#product_tabs table tbody tr").each(function() {
		$(this).find("input[name='tab_id[]']").each(function() {
			var tab_id = $(this).val();
			var field_name = "tab[" + index + "]";
			post_data[field_name] = tab_id;
			index++;
		});
	});
	$.post('/admin/tab/saveSort/', post_data, function(data) {
		if(true == data['success']) {
			$("#product_tabs table tbody").zebra();
		}
	}, "json");
}

function delete_tab(tab_id) {
	var confirm_drop = confirm("Are you sure you want to delete this tab?");
	if(true == confirm_drop) {
		var post_data = {"product_tab_id" : tab_id}
		$.post('/admin/tab/drop/', post_data, function(data) {
			if(true == data['success']) {
				$("#product_form").submit();
			}
		}, "json");
	}
}
</script>
<h2>Editing Product "<?php echo $P->catalog_code; ?>"</h2>
<div id="tab_nav">
	<a href="javascript:void(0)" onclick="change_tab('#product_details');">Details</a> |
	<a href="javascript:void(0)" onclick="change_tab('#product_attributes');">Attributes</a> |
	<a href="javascript:void(0)" onclick="change_tab('#product_categories');">Categories</a> |
	<a href="javascript:void(0)" onclick="change_tab('#product_pricing');">Pricing</a> |
	<a href="javascript:void(0)" onclick="change_tab('#product_images');">Images</a> |
	<a href="javascript:void(0)" onclick="change_tab('#product_widgets');">Widgets</a> |
	<a href="javascript:void(0)" onclick="change_tab('#product_meta');">Meta Tags</a> |
	<a href="javascript:void(0)" onclick="change_tab('#product_tabs');">Accordion Tabs</a> |
	<a href="javascript:void(0)" onclick="change_tab('#product_boxes');">Shipping &amp; Freight</a>
</div>
<br />
<form id="product_form" action="/admin/product/processProduct/" method="post" enctype="multipart/form-data">
	<div id="product_details">
		<?php require 'modules/product_details.php'; ?>
	</div>
	<div id="product_meta">
		<?php require 'modules/product_meta.php'; ?>
	</div>
	<div id="product_attributes">
		<?php require 'modules/product_attributes.php'; ?>
	</div>
	<div id="product_pricing">
		<?php require 'modules/product_pricing.php'; ?>
	</div>
	<div id="product_images">
		<?php require 'modules/product_images.php'; ?>
	</div>
	<div id="product_widgets">
		<?php require 'modules/widget_table.php'; ?>
	</div>
	<div id="product_tabs">
		<?php require 'modules/product_tabs.php'; ?>
	</div>
	<div id="product_categories">
		<?php require 'modules/product_categories.php'; ?>
	</div>
	<div id="product_boxes">
		<?php require 'modules/product_box_overrides.php'; ?>
	</div>
	<fieldset>
		<legend>Notes</legend>
		<p>For internal use only. Will not be displayed to the customer.</p>
		<textarea cols="80" rows="8" name="product[notes]"><?php echo htmlentities($P->notes); ?></textarea>
	</fieldset>
</form>