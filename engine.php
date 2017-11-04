
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
	$finalResult = array('total'=> 0, 'error' => 0);
	$searchInput = test_input($_GET['query']);
	if(empty($searchInput))
	{
		    // $finalResult['contents'] = "";
			// $finalResult['total'] = 0;
			return json_encode($finalResult);
	}

	require('connect-mysql.php');
	
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
			$construct.="tags_sound_like LIKE CONCAT('%',:search$x,'%')";			
		}else{
			$construct.="OR tags_sound_like LIKE CONCAT('%',:search$x,'%')";	
		}
		$params[":search".$x] = metaphone($term) ;
	}

	if(sizeof($searchE) > 1)
	{
		$x++;
		$construct.="OR tags_sound_like LIKE CONCAT('%',:search$x,'%')";
		$params[":search".$x] = metaphone($searchInput);
	}

	// print_r($params);
  $results = DB::query("SELECT * FROM `index` WHERE verified=1 AND ($construct) ORDER BY rating ". $_GET['order-choice'], $params);
  // $results = DB::query("SELECT * FROM `index` WHERE verified=1 ORDER BY rating ". $_GET['order-choice'], $params);
  if(is_object($results))
  {   $resultsRow = $results->fetchAll(PDO::FETCH_ASSOC);
  	$rend = DB::renderToHtml($resultsRow);
  	if($rend['error'] === DB::SUCCESS)
  	{
    	$finalResult['wAvg'] = $rend['wAvg'];
    	$finalResult['popups'] = $rend['popups'];
    	$finalResult['html'] = $rend['html'];
		  $finalResult['total'] = $results->rowCount();
  	}
  	$finalResult['error'] = $rend['error'];
  }

	return json_encode($finalResult);

}


function tagSearch()
{
	$searchInput = test_input($_GET['q']);
	if(empty($searchInput))
	{
			$finalResult['total'] = 0;
			return json_encode($finalResult);
	}

	require('connect-mysql.php');
	$searchE = explode(" ",$searchInput);
	$params = array();
	$x = 0;
	$construct = "";
	foreach ($searchE as $term){
		$x++;
		if($x == 1){
			$construct.="tag_sound_like LIKE CONCAT('%',:search$x,'%')";			
		}else{
			$construct.="OR tag_sound_like LIKE CONCAT('%',:search$x,'%')";	
		}
		$params[":search".$x] = metaphone($term) ;
	}
	
	if(sizeof($searchE) > 1)
	{
		$x++;
		$construct.="OR tag_sound_like LIKE CONCAT('%',:search$x,'%')";
		$params[":search".$x] = metaphone($searchInput);
	}
  // echo "<pre/>";
  // print_r($params);
  $results = DB::query("SELECT tag_name, tag_url FROM `tags` WHERE tag_verified=1 AND ($construct) ORDER BY tag_frequency DESC LIMIT 10", $params);
    $finalResult = array();

    if(is_object($results) && $results->rowCount())
    {
    	$finalResult = $results->fetchAll(PDO::FETCH_ASSOC);
    }
    // echo "<pre/>";
    // print_r($finalResult);
	return json_encode($finalResult);

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
