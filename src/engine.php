
<?php 

	$host = '127.0.0.1'; //127.0.0.1 
	$db = 'ebk'; //Data base name
	$userName ='root';
	$psw = ''; //password
	$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
// name="textarea" name="slider-rating" name="url" name="title" 
	// $pdo = new PDO();
	$results = $pdo->query("SELECT * FROM `index`");
	echo "<pre>";
	print_r($results->fetchAll());
	
		$searchInput = $_GET['query'];
		echo 'hello ' . $searchInput;

?>






