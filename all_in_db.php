
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset = "UTF-8"/>
	<meta name="description" content=" "/> <!-- max = 200 -->
	<meta name="keywords" content="search, ethical, etc."/> <!-- max=a thousand -->
	<meta name="robots" content="index,follow"/><!-- How search engines should index my content -->
	<!-- <base href="http://localhost/html/"> --> <!-- Default location of all your links -->
	<link rel="icon" href="bluehex.png"><!-- 16by 16 icon that appear on your tab -->
	<!-- <link rel="stylesheet" type="text/css" href="default_style.css"> -->
<!-- 	<link rel="stylesheet" type="text/css" media="only screen and (min-width:320px) and (max-width:688x)" href="mobile_style.css"> -->
	 
	 <!-- JQuery Mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Custom -->
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

	<script>
		
	</script>

	<title>Every Body Knows See All in DB</title>

</head>
<body>

	<!-- Start of second page -->
	<div data-role="page" id="pResults">

		<div id = "rH" data-role="header"  data-position="fixed">
			<h1>DB All</h1>
			<button id = "toTop" onclick="$.mobile.silentScroll(0)" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-carat-u ui-btn-icon-notext">Top</button>
		</div><!-- /header -->

		<div id = "rMainTop" role="main" class="ui-content">

		</div><!-- /content -->

		<div data-role="footer" data-position="fixed">
			<h4>Result Page Footer</h4>
		</div><!-- /footer -->
	</div><!-- /page -->
	
</body>
</html>

<?php
	if(isset($_POST["Login"]))
	{
		
		// $username = mysqli_real_escape_string($pdo, $_POST["username"]),
		$password = $_POST["password"];
		require('connect-mysql.php');
		$results = $pdo->prepare("SELECT * FROM `index`");
		$results->execute($params);
		echo "<pre>";
		print_r($results->fetchAll());
	}
	else
	{
		header("Location: add.php");
		exit();
	}
 ?>