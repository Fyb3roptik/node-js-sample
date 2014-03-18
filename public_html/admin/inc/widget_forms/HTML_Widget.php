<div>
	<p>
		<strong>HTML</strong>
		<br />
		You put HTML in here and it will come right back out when this Widget is rendered.</p>
	<textarea name="config[html]" cols="80" rows="13"><?php echo htmlentities(stripslashes($WB->configure('html'))); ?></textarea>
	<p>
		<strong style="color: red;">Warning:</strong> You break it, you bought it. Anything you put in here will be spit out verbatim. Leave
		an XHTML tag unclosed and you may very well BREAK THE WEBSITE.
	</p>
</div>