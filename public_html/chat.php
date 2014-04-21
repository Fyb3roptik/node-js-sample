<?php include_once('inc/global.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
 <meta name="language" content="de" />
 <meta name="author" content="Valentin Manthei - lightIRC.com" />
 <title>Beast Chat</title>
 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
 <script type="text/javascript" src="/flashconfig.js"></script>
 <style type="text/css">
	html { height: 100%; overflow: hidden; }
	body { height:100%;	margin:0;	padding:0; background-color:#999;	}
 </style>
</head>

<body>
 <div id="chatWindow" style="height:100%; text-align:center;">
  <p><a href="//www.adobe.com/go/getflashplayer"><img src="//www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
 </div>
 
 <script type="text/javascript">
	swfobject.embedSWF("/flash/lightIRC.swf", "chatWindow", "100%", "100%", "10.0.0", "/flash/expressInstall.swf", {host: 'chat.beastfranchise.com', nick:'<?php echo $CUSTOMER->username; ?>', autojoin: '#BeastFranchise'});
 </script>
</body>
</html>