<?php 
	session_start();
	include('..\env.php');
	$error = "";
	if(isset($_SESSION['admin'])) :
		header("Location: index-live.php");
	elseif ((isset($_POST['token']) && ($_POST['token'] == getenv('ADD_TOKEN')))):
		if(($_POST["username"] == getenv('LOGIN_USERNAME') ) && ($_POST["password"] == getenv('LOGIN_PASSWORD') ) ){
			$_SESSION['admin'] = getenv('ADD_TOKEN');
			// include_once("index.html");
			header("Location: index-live.php");
		}
		else {
			$error = "Invalid User name and/or Password"; 
		} 
	endif;

	// if(isset($_SESSION['admin'])):
	// 	include_once("index.html");
		// echo "<h1 style='color:red'>Website is under development, you need admin credentials</h1>";
?>


<!DOCTYPE html>
<html>
<head>
	<title>Every Body Knows is under construction </title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{
			background-image: url("img/Caution-Pattern.jpg");
			margin: 0px;
			padding: 0px;
			/*background-color: red;*/
			text-align: center;
		}
		h1{
			background-color: purple;
			color:white; 
			padding: 10px;
		}
		input
		{
			outline:none;
			padding: 10px;
			display: block;
			margin: 5px auto;	
		}
		input[name]
		{
			width: 50%;
			border-radius: 5px;		
			border: 3px solid purple;
		}
		input[type="submit"]
		{
			border: 0px;
			border-radius: 3px;
			cursor:pointer;
			background-color: purple;
			color: #fff;
			width: 30%;
		}

		input[type="submit"]:hover{
			background-color: #ff4dff;
			color: black;
		}

	</style>
</head>
<body>
	<h1>Website is under development, you need admin credentials</h1>
	<form action="index.php" method="POST">
	<?php if(!empty($error)): ?>
		<p style="color:white; background-color: red"><?php echo $error; ?></p>
	<?php endif; ?>
		<input type="text" name="username" placeholder="User name">
		<input type="password" name="password" placeholder="Password">
		<input type="submit">
		<input type="hidden" name="token" value=<?php echo getenv('ADD_TOKEN'); ?>>
	</form>	
</body>
</html>



 