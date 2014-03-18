<script type="text/javascript">
$(document).ready(function() {
	$('textarea').css('width', '80%').css('display', 'block');
});
</script>
<h2><?php echo $TITLE; ?></h2>
<form id="content_form" action="/admin/content/processContent/" method="post">
	<fieldset>
		<legend>HTML</legend>
		<input type="hidden" name="config_key" value="<?php echo $CR->config_key; ?>" />
		<p>Whatever you put in here will be spit out verbatim.</p>
		<textarea name="config_text" cols="60" rows="20"><?php echo htmlentities(stripslashes($CR->config_text)); ?></textarea>
		<input type="submit" value="Save" />
		or <a href="/admin/content/">Cancel</a>
	</fieldset>
</form>
