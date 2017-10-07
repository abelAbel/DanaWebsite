
<?php

function test_input($data) {
  // echo "Original: " . $data . "<br>";
  $data = trim($data);
  // echo "trim: " . $data . "<br>";
  $data = stripslashes($data);
  // echo "stripslashes: " . $data . "<br>";
  $data = htmlspecialchars($data);
  // echo " htmlspecialchars: " . $data . "<br>";
  return $data;
}

function engine()
{
	$searchInput = test_input($_GET['query']);
	if(empty($searchInput))
	{
		    // $finalResult['contents'] = "";
			$finalResult['total'] = 0;
			return json_encode($finalResult);
	}

	// $host = '127.0.0.1'; //127.0.0.1
	// $db = 'ebk'; //Data base name
	// $userName ='root';
	// $psw = ''; //password
	// $pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
	require('connect-mysql.php');

// name="textarea" name="slider-rating" name="url" name="title"
	// if(empty(trim($_GET['query'])))
	// {
	// 	echo "!!It is not set <br>";
	// }

	// echo "searchInput = " . $searchInput . "<br>";
	$searchE = explode(" ",$searchInput);
	//print_r($searchE);
	// if(count($searchE) == 1)
	// {
	// 	$searchE = str_split($searchE[0],2);
	// }
	$params = array();
	$x = 0;
	$construct = "";
	foreach ($searchE as $term){
		$x++;
		if($x == 1){
			// $construct.="title LIKE '%$term%' OR description LIKE '%$term%' OR keywords LIKE '%$term%'";
			$construct.="title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
			// $construct.="title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%') OR url LIKE CONCAT('%',:search$x,'%')";
		}else{
			$construct.="AND title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
			// $construct.="AND title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%') OR url LIKE CONCAT('%',:search$x,'%')";

		}
		$params[":search".$x] = $term ;
	}
	// $results = $pdo->query("SELECT * FROM `index` WHERE title LIKE '%$searchInput%'");
		// $results = $pdo->query("SELECT * FROM `index` WHERE $construct");

	// $results = $pdo->prepare("SELECT * FROM `index` WHERE $construct");
	// $results = $pdo->prepare("SELECT * FROM `index` WHERE $construct ORDER BY rating ". $_GET['order-choice']); //ASC|DESC
	// $results->execute($params);
  $results = DB::query("SELECT * FROM `index` WHERE verified=1 AND ($construct) ORDER BY rating ". $_GET['order-choice'], $params);
	// echo "<pre>";
	// print_r($results->fetchAll());
	$finalResult = array();
	//$finalResult['contents'] = "";

		//if($results->rowCount() == 0){
			//echo "0 result found <hr/>";
		//}else{
			//echo "Total result: ".$results->rowCount()."<hr/>";
			// foreach ($results->fetchAll() as $result) {
			// 	//$finalResult['contents'].=
			// 	echo "<div style='border-bottom: 6px solid red;
   //  					     background-color: lightgrey;
   //  					     margin-bottom: 10px;
   //  					     box-shadow: 5px 5px 5px #888888;'
   //  			 >"."Title: ".$result['title']."<br>".
   //  			 	"Rating: ".$result['rating']."<br>".
   //  			 	"URL: <a href='".$result['url']."'>".$result['url']."<a/> <br>".
   //  			 	"Keywords: ".$result['keywords']."<br>".
   //  			 	"Description: ".$result['description']."<br>".
   //  			 "</div>";
			// }

		    $finalResult['contents'] = $results->fetchAll();
			// $finalResult['average'] = 5;
			$finalResult['total'] = $results->rowCount();
			// echo $finalResult["contents"];
				// echo "<pre>";
				// print_r($finalResult);
			return json_encode($finalResult);
			//No result foud
		//}

}

if(isset($_GET['method']))
{
  echo $_GET['method']();
}
elseif (isset($_POST['method']))
{
  echo $_POST['method']();
}
else {
  die("E.K.W does not know you, good bye");
}
?>
