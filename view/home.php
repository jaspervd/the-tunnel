<!DOCTYPE html>
<html>
<head>
	<base href="<?php echo $basePath; ?>/">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>The Tunnel - Change my view</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="container"></div>
	<script>
	window.app = window.app || {};
	window.app.basename = '<?php echo $basePath;?>';
	</script>
	<script src="js/script.js"></script>
  	<script id="__bs_script__">//<![CDATA[
  	document.write("<script async src='http://HOST:8890/browser-sync/browser-sync-client.2.12.12.js'><\/script>".replace("HOST", location.hostname));
  	//]]></script>
  </body>
  </html>
