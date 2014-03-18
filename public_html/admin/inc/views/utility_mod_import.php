<form id="utility_mod_import_form" action="/admin/ubd/importMods/" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Import Utility Mod CSV</legend>
		<p>Upload utility mod csv file.</p>
		<div id="messages"><?php echo $MS->messages(); ?></div>
		<input type="file" name="utility_mod_csv" />
		<input type="submit" value="Upload CSV" />
	</fieldset>
</form>
