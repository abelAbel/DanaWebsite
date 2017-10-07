<?php

// function test_input($data) {
//   // echo "Original: " . $data . "<br>";
//   $data = trim($data);
//   // echo "trim: " . $data . "<br>";
//   $data = stripslashes($data);
//   // echo "stripslashes: " . $data . "<br>";
//   $data = htmlspecialchars($data);
//   // echo " htmlspecialchars: " . $data . "<br>";
//   return $data;
// }

// function engine()
// {
// 	$_GET['q'] = "a";
// 	$searchInput = test_input($_GET['q']);
// 	if(empty($searchInput))
// 	{
// 		    // $finalResult['contents'] = "";
// 			$finalResult['total'] = 0;
// 			return json_encode($finalResult);
// 	}

// 	require('../connect-mysql.php');

// 	$searchE = explode(" ",$searchInput);

// 	$params = array();
// 	$x = 0;
// 	$construct = "";
// 	foreach ($searchE as $term){
// 		$x++;
// 		if($x == 1){
// 			// $construct.="title LIKE '%$term%' OR description LIKE '%$term%' OR keywords LIKE '%$term%'";
// 			$construct.="title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
// 			// $construct.="title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%') OR url LIKE CONCAT('%',:search$x,'%')";
// 		}else{
// 			$construct.="AND title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
// 			// $construct.="AND title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%') OR url LIKE CONCAT('%',:search$x,'%')";

// 		}
// 		$params[":search".$x] = $term ;
// 	}
// 	// $results = $pdo->query("SELECT * FROM `index` WHERE title LIKE '%$searchInput%'");
// 		// $results = $pdo->query("SELECT * FROM `index` WHERE $construct");

// 	// $results = $pdo->prepare("SELECT * FROM `index` WHERE $construct");
// 	// $results = $pdo->prepare("SELECT * FROM `index` WHERE $construct ORDER BY rating ". $_GET['order-choice']); //ASC|DESC
// 	// $results->execute($params);
//   $results = DB::query("SELECT * FROM `index` WHERE verified=1 AND ($construct) ORDER BY rating ASC", $params);
// 	$finalResult = array();
// 		    $finalResult['contents'] = $results->fetchAll();
// 			// $finalResult['average'] = 5;
// 			$finalResult['total'] = $results->rowCount();

// 			return json_encode($finalResult);
// 			//No result foud
// 		//}

// }

// echo engine();
$finalResult = array(
					array('text' => 'Walmart' ,'url' => 'http://walmart.com' ),
					array('text' => 'amaZon' ,'url' => 'http://amazon.com' ),
					array('text' => 'abel' ,'url' => '' ),
					array('text' => 'camping' ,'url' => '' ),
					// array('text' => 'acamping' ,'url' => '' ),
					// array('text' => 'acalmping' ,'url' => '' ),
					// array('text' => 'heath and hands' ,'url' => '' ),
					array('text' => 'health and hands' ,'url' => '' )
                    );
// $finalResult['contents1'] = "a";
// $finalResult['contents2'] = "b";
// $finalResult['contents3'] = "c";
// $finalResult['contents4'] = "abel";

echo json_encode($finalResult);
// return json_encode($finalResult);
?>
