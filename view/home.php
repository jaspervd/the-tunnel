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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-formhelpers.min.js"></script>
  <script src="js/backbone-validation-amd-min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
