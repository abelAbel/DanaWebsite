
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
  	$rend = renderToHtml($resultsRow);
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

	function hsl_rating ($rating){
    $change;
	$step = 0.16666666666666666666666666666667;
	$hue;
	  if($rating == 0){
	    $change = 1;
	  }
	  else{
	    $change=((6-$rating)*round($step,17));
	  }

	  $hue = (1 - $change) *120;
	  // echo "step->".number_format((float)$step, 16, '.', '')."<br>"; 
	  return($hue);
	}

function renderToHtml($rows)
{
	$workArr = array('error' =>DB::SUCCESS);
	$html = "";
	$tagIdCount = 0;
	$wAverage = array();
	$popups = array();
	foreach ($rows as $row) {
		$h = hsl_rating($row['rating']);
		$html .=
      '<div style="background-color: lightgrey;
                  margin-bottom: 10px;
                  box-shadow: 5px 5px 5px #888888;">
        <a href="#" 
          style="padding-left: 5px;
              padding-right: 5px; 
              padding-top: 1px;
              margin-bottom: 10px;
              text-decoration: none;
              -webkit-tap-highlight-color: rgba(0,255,0,.1);
              border-bottom: 3px dashed hsl('.$h.', 100%, 50%);
              display: block;">
              <h4>'.$row['title'].'</h4> 
        </a> 

        <p style="padding-left: 5px; padding-right: 5px; ">'.$row['description'].'</p>';
		if(!empty($row['tags_sound_like'])){
			$sound_like = split(' ',$row['tags_sound_like']);
			if(sizeof($sound_like))
			{
				foreach ($sound_like as $sound) {
					if(!isset($workArr[$sound]))
					{

						$q = DB::query("SELECT tag_name, tag_url, tag_frequency FROM `tags` WHERE tag_sound_like='".$sound."'");
    				if(is_object($q) && $q->rowCount())
    				{
    					$curTag = $q->fetch(PDO::FETCH_ASSOC);
              $workArr[$sound] = $curTag;
    				} 
    				else
    				{
    					$workArr['error'] = $q;
    					return $returnArr;
    				}
					}
					$theme = (($workArr[$sound]['tag_url'] != "")?"b":"a");
					$html .= '
    				<a href="#popup-tg'.$tagIdCount.'" data-transition="pop" class="ui-btn ui-btn-'.$theme.' ui-corner-all ui-mini ui-shadow ui-btn-inline" data-rel="popup">'.$workArr[$sound]['tag_name'].'</a>';
    				array_push($popups,
		        '<div data-role="popup" data-short-tg='.$tagIdCount.' id=popup-tg'.$tagIdCount.' class="ui-content" data-arrow="t" data-theme="d">
		          <p><b> Frequency: </b>'.$workArr[$sound]['tag_frequency'].'</p>'.(($theme === "b")?('
		          <a href="'.$workArr[$sound]['tag_url'].'" target="_blank">'.$workArr[$sound]['tag_name'].' Link</a>'):'').'
		        </div>');
		        $tagIdCount +=1;
				}
			}
		}

		$html .= '
		<div style="font-size: .8em; background: hsl('.$h.', 100%, 50%); text-shadow:none; padding: 0px"><b>Rating - '.$row['rating'].' </b></div>
    </div>';

    $wAverage[$row['rating']] = (!isset($wAverage[$row['rating']])) ? 1 : $wAverage[$row['rating']]+=1;
	}
  $workArr['wAvg'] = $wAverage;
  $workArr['popups'] = $popups;
	$workArr['html'] = $html;
	return $workArr;
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
