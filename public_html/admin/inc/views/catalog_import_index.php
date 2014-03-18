<?php
$total_record_count = $CI->getRecordCount(null);
$percentage = 0;
if($total_record_count > 0) {
	$percentage = floor(($CI->getRecordCount(1) / $total_record_count) * 100);
}
?>
<script type="text/javascript" src="/js/jquery.progressbar.min.js" /></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#progress").progressBar(<?php echo $percentage; ?>);

	$("#process_records_button").click(function() {
		process_records();
		poll_progress();
	});
});

var master_total_records = 0;
var master_processed_records = 0;

function process_records() {
	var data = {
			"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
			"action" : "process_records" }
	$.post('/admin/catalog_import.http.php', data, function(data) {

	}, "json");
}

function poll_progress() {
	var data = {
			"<?php echo get_xsrf_field_name(); ?>" : "<?php echo get_xsrf_field_value(); ?>",
			"action" : "poll_progress" }
	$.post('/admin/catalog_import.http.php', data, function(data) {
		var left = parseInt(data.unprocessed_records);
		var percentage = parseInt(data.percent_finished);
		var processed = parseInt(data.processed_records);
		var total_records = parseInt(data.total_records);

		if(total_records > master_total_records) {
			master_total_records = total_records;
		}

		if(processed > master_processed_records) {
			master_processed_records = processed;
		}

		$("#progress_fraction").text(master_processed_records + " / " + master_total_records);

		$("#progress").progressBar(percentage);

		if(left > 0) {
			setTimeout(
				function() {
					poll_progress(); }
				, 1000);
		}
	}, "json");
}
</script>
<h2>Product Import (Legacy)</h2>
<?php
if($MS->count() > 0) {
?>
<div class="messages">
	<?php	echo $MS->messages(); ?>
</div>
<?php
}
?>
<form id="catalog_upload_form" action="" enctype="multipart/form-data" method="post">
	<fieldset>
		<legend>Upload Catalog CSV</legend>
		<input type="hidden" name="action" value="import_catalog_csv" />
		<input type="file" name="catalog_csv" />
		<input type="submit" value="Upload Catalog" />
		<?php
		if($percentage < 100) {
		?>
		<hr />
		<div id="process_records">
			<?php echo "Found " . $CI->getRecordCount(0) . " unprocessed records in your .csv file."; ?>
			<br />
			<input type="button" id="process_records_button" value="Process Records" />
			<span id="progress"></span>
			(<span id="progress_fraction"><?php echo $CI->getRecordCount(1) . " / " . $CI->getRecordCount(null); ?></span> records processed)
		</div>
		<?php
		}
		?>
	</fieldset>
</form>

<form id="catalog_image_upload_form" action="" method="post">
	<fieldset>
		<legend>Scan Image Folder</legend>
		<input type="hidden" name="action" value="batch_image_import" />
		<input type="submit" value="Scan Image Folder" />
		<p><strong>Instructions:</strong> Upload images into the folder <em><?php echo DIR_BATCH_IMAGE_UPLOAD; ?></em> on the web server and click the button above to import them.</p>
		<p>Files should be named as &lt;catalog-code&gt;.jpg<p>
		<p>i.e. A product with catalog code "FOO-BAR" should be named "FOO-BAR.jpg"</p>
		<?php
		if(true == is_a($PIBI, 'Product_Image_Batch_Importer') && count($PIBI->processing_output) > 0) {
		?>
		<br />
		<strong>Image Import Results</strong>
		<table width="100%">
			<tr>
				<th style="text-align:left;">File</th>
				<th style="text-align:left;">Message</th>
			</tr>
			<?php
			foreach($PIBI->processing_output as $file_name => $message) {
			?>
			<tr>
				<td><?php echo $file_name; ?></td>
				<td><?php echo $message; ?></td>
			</tr>
			<?php
			}
			?>
		</table>
		<?php
		}
		?>
	</fieldset>
</form>