<script type="text/javascript" src="/admin/inc/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/admin/inc/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	$('#version').change(function() {
	
		var id = $('#version').val();
		window.location="/admin/homepage/"+id;
	});
	
});
</script>
<fieldset>
<legend>Mission</legend>
<br />
<br />
<form method="post" action="/admin/homepage/saveMission/<?php echo $HP->ID; ?>">
Version to edit: 
<select name="version" id="version">
	<?php for($i=0;$i<count($mission_version);$i++): ?>
	<option value="<?php echo $mission_version[$i]['mission_id']; ?>" <?php if($id == $mission_version[$i]['mission_id']): ?>selected<?php endif; ?>><?php echo $mission_version[$i]['date']; ?><?php if($i == '0'): ?>(Latest)<?php endif; ?></option>
	<?php endfor; ?>
</select>
<br />
<br />
<textarea name="mission" id="content" style="width:50%; height: 50%;">
<?php echo urldecode($MISSION->text); ?>
</textarea>
<script type="text/javascript">
//<![CDATA[
var editor = CKEDITOR.replace( 'content' );
CKFinder.setupCKEditor( editor, '/admin/inc/ckfinder/' ) ;
//]]>
</script>
<br />
<br />
<input type="submit" name="submit" value="Update" />
</form>
</fieldset>
<fieldset>
<legend>How it Works</legend>
<br />
<br />
<form method="post" action="/admin/homepage/saveAbout/<?php echo $HP->ID; ?>">
Version to edit: 
<select name="version" id="version">
	<?php for($i=0;$i<count($about_version);$i++): ?>
	<option value="<?php echo $about_version[$i]['about_id']; ?>" <?php if($id == $about_version[$i]['about_id']): ?>selected<?php endif; ?>><?php echo $about_version[$i]['date']; ?><?php if($i == '0'): ?>(Latest)<?php endif; ?></option>
	<?php endfor; ?>
</select>
<br />
<br />
<textarea name="about" id="content_about" style="width:50%; height: 50%;">
<?php echo urldecode($ABOUT->text); ?>
</textarea>
<script type="text/javascript">
//<![CDATA[
var editor = CKEDITOR.replace( 'content_about' );
CKFinder.setupCKEditor( editor, '/admin/inc/ckfinder/' ) ;
//]]>
</script>
<br />
<br />
<input type="submit" name="submit" value="Update" />
</form>
</fieldset>