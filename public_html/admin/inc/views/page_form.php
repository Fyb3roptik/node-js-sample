<?php
FB::group('views/page_form.php');
FB::log($P, "Page");
?>
<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="/admin/js/widget_list.js.php"></script>

<script type="text/javascript">
/* <[CDATA[ */
/*
tinyMCE.init({
	mode : "exact",
	elements: "page_content",
	theme : "advanced",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",

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
*/
$(document).ready(function() {
	$("#page_nickname").change(function() {
		var title = $(this).val();
		$("#page_fieldset legend").text('Editing Page "' + title + '"');
	});

	$("#page_nickname").change();

	$("#new_meta_button").click(function() {
		var $last_tr = $("#meta_tag_table tr:last");
		var $new_tr = $last_tr.clone();
		var input_names = "";

		var regex = new RegExp(/meta_tag\[([0-9]+)\]\[([a-zA-Z]+)\]/);

		$new_tr.find('input, textarea').each(function() {
			var $input = $(this);
			var name = $input.attr("name");
			var matches = regex.exec(name);
			var new_index = parseInt(matches[1]) + 1;
			var new_field = matches[2];

			var new_name = 'meta_tag[' + new_index + '][' + new_field + ']';
			if(new_field != 'delete') {
				$input.attr("name", new_name).val("");
			} else {
				$input.attr("name", new_name).attr('checked', false);
			}

		});

		$new_tr.insertAfter($last_tr);
	});

	$("#page_url").keyup(function() {
		reset_page_link();
	});

	$("#page_url").change(function() {
		reset_page_link();
	});

	var Page_Widget_List = new Widget_List(parseInt($("#page_id").val()), 'Page_Widget', '<?php echo get_xsrf_field_name(); ?>', '<?php echo get_xsrf_field_value(); ?>');
	Page_Widget_List.refresh_widget_table('/admin/page/getWidgets/', 'get_page_widgets');

	$("#new_widget_button").click(function() {
		var widget_id = parseInt($("#new_widget_id").val());
		if(widget_id > 0) {
			var page_id = $("#page_id").val();

			var data = {"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
					"action": "add_widget",
					"page_id" : page_id,
					"widget_id" : widget_id}
			$.post('/admin/page/addWidget/', data, function(data) {
				var new_widget_id = parseInt(data.widget_id);
				if(new_widget_id > 0) {
					Page_Widget_List.refresh_widget_table('/admin/page/getWidgets', 'get_page_widgets');
				}
			}, "json");
		}
		$("#new_widget_id").val(0);
	});

	$("#full_page_notice").hide();

	$("#full_page_select").change(function() {
		var full_page = false;
		if(parseInt($(this).val()) > 0) {
			full_page = true;
		}
		if(true == full_page) {
			$("#widgets").hide();
			$("#full_page_notice").show();
		} else {
			$("#widgets").show();
			$("#full_page_notice").hide();
		}
	});	

	//reset the page link preview
	reset_page_link();
	$("#full_page_select").change();
});

function reset_page_link() {
	var server_root = 'http://<?php echo sanitize_string($_SERVER['SERVER_NAME']); ?>/pages/';
	var page_name = $("#page_url").val();
	var page_url = server_root + page_name + ".html";
	$("#page_url_link").attr("href", page_url).text(page_url);
}

/* ]]/> */
</script>
<form id="page_form" action="/admin/page/processPage/" method="post">
	<fieldset id="page_fieldset">
		<legend>Edit "<?php echo $P->title; ?>"</legend>
		<input type="hidden" name="action" value="process_page" />
		<input type="hidden" id="page_id" name="page_id" value="<?php echo $P->ID; ?>" />
		<table>
			<tr>
				<td>Nickname</td>
				<td>
					<input type="text" id="page_nickname" name="page[nickname]" value="<?php echo $P->nickname; ?>" />
					(for internal use only)
				</td>
			</tr>
			<tr>
				<td>Title</td>
				<td><input type="text" id="page_title" name="page[title]" value="<?php echo $P->title; ?>" /></td>
			</tr>
			<tr>
				<td>URL</td>
				<td>
					<input type="text" id="page_url" name="page[url]" value="<?php echo $P->url; ?>" />
					<a href="#" id="page_url_link">http://<?php echo sanitize_string($_SERVER['SERVER_NAME']); ?>/pages/<?php echo $P->url; ?>.html</a>
				</td>
			</tr>
			<tr>
				<td>Full Page</td>
				<td>
					<?php
					$full_page_options = array(0 => 'No', 1 => 'Yes');
					echo draw_select('page[full_page]', $full_page_options, $P->full_page, 'id="full_page_select"');
?>
					<span id="full_page_notice">When full page is selected, the page will be rendered without a leftside column.</span>	
				</td>
			</tr>
			<tr>
				<td valign="top">Content</td>
				<td><textarea id="page_content" name="page[content]" cols="80" rows="10"><?php echo convert_for_tinymce($P->content); ?></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" name="save" value="Save Page" />
					<input type="submit" name="save_continue" value="Save Page &amp; Continue Editing" />
					or <a href="<?php echo LOC_PAGES; ?>">Cancel</a>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend>CSS</legend>
		<p>Enter raw CSS in here and it will be included as an external stylesheet when the page is rendered.</p>
		<textarea cols="100" rows="6" name="page[css]"><?php echo htmlentities($P->css); ?></textarea>
	</fieldset>
	<?php require_once 'modules/widget_table.php'; ?>
	<fieldset id="meta_tag_fieldset">
		<legend>Edit Meta Tags</legend>
		<input type="button" id="new_meta_button" value="Add New Meta Tag" />
		<table id="meta_tag_table">
			<tr>
				<th>Tag Type</th>
				<th>Content</th>
				<th>Delete</th>
			</tr>
			<?php
			$tags = $P->getMetaTags();
			if(0 == count($tags)) {
				$tags[] = new Page_Meta_Tag();
			}
			foreach($tags as $i => $T) {
			?>
			<tr>
				<td valign="top">
					<input type="hidden" name="meta_tag[<?php echo $i; ?>][ID]" value="<?php echo $T->ID; ?>" />
					<input type="text" name="meta_tag[<?php echo $i; ?>][name]" value="<?php echo $T->name; ?>" />
				</td>
				<td><textarea name="meta_tag[<?php echo $i; ?>][content]" rows="4" cols="80"><?php echo $T->content; ?></textarea></td>
				<td style="text-align: center"><input type="checkbox" name="meta_tag[<?php echo $i; ?>][delete]" value="1" /></td>
			</tr>
			<?php
			}
			?>
		</table>
	</fieldset>
</form>
<?php
FB::groupEnd();
?>