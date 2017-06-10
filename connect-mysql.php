<?php 
	include('..\env.php');
	// try
	// {
	// 	$host = '127.0.0.1'; //127.0.0.1 
	// 	$db = 'ebk'; //Data base name
	// 	$userName ='root';
	// 	$psw = ''; //password
	// 	$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
	// 	// echo "You have connected successfully";

	// } catch(PDOException $e)
	// {
	// 	echo $e->getMessage();
	// 	die("<br> <b> Error Connecting to Data Base");
	// }

	try
	{
		$host = getenv('DB_HOSTNAME');
		$db = getenv('DB');
		$userName = getenv('DB_USERNAME');;
		$psw = getenv('DB_PASSWORD');
		$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
		// echo "You have connected successfully to EBK";
		// echo "DB_HOSTNAME : " . getenv('DB_HOSTNAME');

	} catch(PDOException $e)
	{
		echo $e->getMessage();
		die("<br> <b> Error Connecting to Data Base");
	}

?>	
