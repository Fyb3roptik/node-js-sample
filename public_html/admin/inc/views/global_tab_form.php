<script type="text/javascript">
	var TYPE_GALLERY = "<?php echo Product_Tab::TYPE_GALLERY; ?>";
	var TYPE_HTML = "<?php echo Product_Tab::TYPE_HTML; ?>";
	var TYPE_PDF = "<?php echo Product_Tab::TYPE_PDF; ?>";
	var TYPE_OVERVIEW = "<?php echo Product_Tab::TYPE_OVERVIEW; ?>";

	var ORIGINAL_TITLE = "<?php echo $PT->title; ?>";

	$(document).ready(function() {
		$("select[name='tab[type]']").change(function() {
			var new_val = $(this).val();
			var $title = $("input[name='tab[title]']");
			if(TYPE_HTML == new_val) {
				show_tab("#tab_data");
				$title.val(ORIGINAL_TITLE).attr('disabled', false);
			} else if(TYPE_GALLERY == new_val) {
				show_tab("#tab_gal");
				ORIGINAL_TITLE = $title.val();
				$title.val('Product Gallery').attr('disabled', true);
			} else if(TYPE_PDF == new_val) {
				show_tab("#tab_pdf");
				$title.val(ORIGINAL_TITLE);
			} else if(TYPE_OVERVIEW == new_val) {
				$title.val(ORIGINAL_TITLE);
				show_tab("#tab_overview");
			}
		}).change();

		$("select[name='pdf_action']").change(function() {
			var new_val = $(this).val();
			if('new' == new_val) {
				show_pdf_tab("#pdf_uploader");
			} else {
				show_pdf_tab("#pdf_picker");
			}	
		}).change();
	});

	function show_tab(tab_id) {
		$("#options").children().hide();
		$(tab_id).show();
	}

	function show_pdf_tab(tab_id) {
		$("#pdf_action_options").children().hide();
		$(tab_id).show();
	}
</script>
<h2>Editing Global Tab "<?php echo $PT->title; ?>"</h2>
<form id="product_tab_form" action="/admin/gtab/processTab/" method="post" enctype="multipart/form-data">
	<fieldset>
		<input type="hidden" name="global_tab_id" value="<?php echo $PT->ID; ?>" />
		Title:<br />
		<input type="text" name="tab[title]" value="<?php echo $PT->title; ?>" />
		<br />
		Default View:<br />
		<?php
		$view_options = array(Product_Tab::OPEN => 'open', Product_Tab::CLOSED => 'closed');
		echo draw_select('tab[default_view]', $view_options, $PT->default_view);
		?><br />
		Tab Type:<br />
		<?php
		$type_options = array(
			Product_Tab::TYPE_HTML => 'HTML', 
			Product_Tab::TYPE_GALLERY => 'Image Gallery',
			Product_Tab::TYPE_PDF => 'PDF',
			Product_Tab::TYPE_OVERVIEW => 'Product Overview');
		echo draw_select('tab[type]', $type_options, $PT->type);
?>
		<div id="options">
			<div id="tab_data">
				HTML:<br />
				<textarea name="tab[data]" cols="60" rows="6"><?php echo htmlentities($PT->data); ?></textarea>
				<p>Dump your HTML in here. It'll get dumped bit for bit in this tab on the front end.</p>
			</div>
			<div id="tab_gal">
				<p>Nothing to edit here. Just click save below and an image gallery for this product will be generated in this tab.</p>
			</div>
			<div id="tab_pdf">
				<p>
					Choose One: 
					<?php
					$default_option = 'new';
					if(true == $PT->exists() && Product_Tab::TYPE_PDF == $PT->type) {
						$default_option = 'pick';
					}
					$options = array('new' => 'Upload New PDF', 'pick' => 'Pick Existing PDF');
					echo draw_select('pdf_action', $options, $default_option);
					?>
				</p>
				<div id="pdf_action_options">
					<div id="pdf_uploader">
						<input type="file" name="new_pdf" />
					</div>
					<div id="pdf_picker">
						Choose File:
					<?php
					if(count($PDF_LIST) > 0) {
						echo draw_select('picked_pdf', $PDF_LIST, $PT->data);
					} else {
						?>No files found, please upload one.<?php
}
?>
					</div>
				</div>
			</div>
			<div id="tab_overview">
				<p>You needn't do anything else! We'll take care of displaying the overview in this tab. Just click "Save Tab".</p>
			</div>
		</div>
		<br />
		<input type="submit" value="Save Tab" />
		or <a href="/admin/gtab/">Cancel</a>
	</fieldset>
</form>
