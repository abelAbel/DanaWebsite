
<?php 

	$host = '127.0.0.1'; //127.0.0.1 
	$db = 'ebk'; //Data base name
	$userName ='root';
	$psw = ''; //password
	$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
// name="textarea" name="slider-rating" name="url" name="title" 
	$searchInput = $_GET['query'];
	$searchE = explode(" ",$searchInput);
	//print_r($searchE);
	if(count($searchE) == 1)
	{
		$searchE = str_split($searchE[0],2);
	}
	$params = array();
	$x = 0;
	$construct = "";
	foreach ($searchE as $term){
		$x++;
		if($x == 1){
			// $construct.="title LIKE '%$term%' OR description LIKE '%$term%' OR keywords LIKE '%$term%'";
			$construct.="title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
		}else{
			$construct.="AND title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
		}
		$params[":search".$x] = $term ;
	}
	// $results = $pdo->query("SELECT * FROM `index` WHERE title LIKE '%$searchInput%'");
		// $results = $pdo->query("SELECT * FROM `index` WHERE $construct");
	$results = $pdo->prepare("SELECT * FROM `index` WHERE $construct");
	$results->execute($params);
	//echo "<pre>";
	//print_r($results->fetchAll());
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
			$finalResult['average'] = 5;
			$finalResult['total'] = $results->rowCount();
			//echo $finalResult["contents"];
			echo json_encode($finalResult);
			//No result foud
		//}


?>






