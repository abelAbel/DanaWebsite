<?php
	require('..\connect-mysql.php');
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
// $finalResult = array(
// 					array('text' => 'Walmart' ,'url' => 'http://walmart.com' ),
// 					array('text' => 'amaZon' ,'url' => 'http://amazon.com' ),
// 					array('text' => 'abel' ,'url' => '' ),
// 					array('text' => 'camping' ,'url' => '' ),
// 					// array('text' => 'acamping' ,'url' => '' ),
// 					// array('text' => 'acalmping' ,'url' => '' ),
// 					// array('text' => 'heath and hands' ,'url' => '' ),
// 					array('text' => 'health and hands' ,'url' => '' )
//                     );
// $finalResult['contents1'] = "a";
// $finalResult['contents2'] = "b";
// $finalResult['contents3'] = "c";
// $finalResult['contents4'] = "abel";

// echo json_encode($finalResult);
// return json_encode($finalResult); 
// echo var_dump(metaphone("Walmart")) ."<br>";
// echo var_dump(metaphone("Wallmarts")) ."<br>";
// echo var_dump(metaphone("Wamart")) ."<br>";
// echo var_dump(metaphone("Walmart center")) ."<br>";
// echo var_dump(metaphone("green power partnership")) ."<br>";
// echo var_dump(metaphone("green power")) ."<br>";
// echo var_dump(metaphone("National top green power users")) ."<br>";
// echo var_dump(metaphone("top 100")) ."<br>";
// echo var_dump(metaphone("Purchasing green power")) ."<br>";
// echo var_dump(metaphone("a")) ."<br>";
// echo var_dump(metaphone("A")) ."<br>";
// echo var_dump(metaphone("B")) ."<br>";
// echo var_dump(metaphone("K")) ."<br>";
// echo var_dump(metaphone("w")) ."<br>";

// 



// include("../share_cls_&_fnc.php");

// $urls = array(
//   'http://www.pbs.org/newshour/rundown/2016-hottest-year-record-takeaways-noaas-new-climate-report/',
//   'epa.gov/greenpower/green-power-partnership-fortune-500r-partners-list#IntelCorporation',
//   'www.baidu.com/',
//   'https://w3guy.com/php-retrieve-web-page-titles-meta-tags/',
//   "https://www.w3schools.com/PhP/php_arrays.asp"
// );

// foreach ($urls as $url) {
// 	echo "<pre/>";
//   print "$url\n";
//   $m = new URLMeta($url);
//   if ($resp = $m->parse()) {
//     print_r($resp);
//   } else {
//     printf("FAILED\nERROR CODE:%s\nRESPONSE: %s", $m->error_code, $m->error_response);
//   }
//   print "\n\n";
// }

// print_r($_POST['tags']);
// echo "<br>";
// echo "<br>";
// $tags = test_input($_POST['tags']);

// 	function test_input($data)
// 	{
// 	  $data = trim($data);
// 	  $data = stripslashes($data);
// 	  $data = htmlspecialchars($data);
// 	  return $data;
// 	}
// echo $tags;
// echo "<br>";
// $tagsJSON = json_decode($_POST['tags']);
// echo " <pre/>";
// print_r($tagsJSON);
                // $param = array(':name' => "hello");
                // $param = array(':name' => "hello",':url' => " url",":sound_like"=>'tag_sound_like',':verified'=>"tag_verified", ':frequency'=>'tag_frequency');
    //             $param = array(":sound_like"=>'tag_sound_like',':verified'=>"tag_verified", ':frequency'=>'tag_frequency');
    //             $SET_param = "";
			 //    $SET_param .= (isset($param[':name'])) ? ',tag_name=:name':'';
				// $SET_param .= (isset($param[':url'])) ? ',tag_url=:url':'';
				// $SET_param .= (isset($param[':sound_like'])) ? ',tag_sound_like=:sound_like':'';
				// $SET_param .= (isset($param[':verified'])) ? ',tag_verified=:verified':'';
				// $SET_param.=  (isset($param[':frequency'])) ? ',tag_frequency=:frequency':'';
				// $SET_param =  ltrim($SET_param, ',');
				// echo "[".$SET_param. "]";
				// echo "<br> Count = " . $cnt;
   

   	$title = "kkk 23";
	$description = "3 Update tester";
	$url = "https://www.google.com/#q=shrimp&*";
	$_POST['slider-rating'] = "2.5";
	$_verified = 0;
	// $_POST['tags'] = '[{"text":"hello world","url":"https://hellow.com"},{"text":"howdy do you do","url":""},{"text":"walmart","url":""},{"text":"good","url":""},{"text":"great","url":""}]';
	// $_POST['tags'] = '[{"text":"walmart","url":"http://walmart.com 2"},{"text":"good","url":""},{"text":"great","url":""},{"text":"King souper","url":""}]';
		// $_POST['tags'] = '[{"text":"new tag","url":"http://new tag only"}]';
	$_POST['tags'] = '[{"text":"walmart","url":"http://walmart.com 2"},{"text":"new tag","url":""},{"text":"great","url":""},{"text":"King souper","url":""}]';
	
  	$pArr = array(':title'=>$title,':url'=>$url,
  		          ':slider_rating'=>$_POST['slider-rating'],
            ':description'=>$description,':url_hash'=>md5($url),
            // ':verified'=>$_verified);
            ':verified'=>'verified',':id'=>1072);
  	// echo "<br/>" . $_POST['tags'];
  	// echo "<pre>";
  	// print_r(json_decode($_POST['tags'])[0]->url);
  	// echo "<br/><pre>";
  	// echo var_dump(json_decode($_POST['tags']));
   
  	// echo "<br/>" . DB::add($pArr,json_decode($_POST['tags']));
  	// echo $success = DB::addTag(array(":name" =>"new tag",":url" =>"http://newtags.com",":sound_like" =>metaphone("new tag"),":verified" =>0,":frequency" => 2));
  	// echo $success = DB::addTag(array(":name" =>"old tag",":url" =>"http://oldtags.com",":sound_like" =>metaphone("old tag"),":verified" =>1,":frequency" => 2));
  	// echo "<br/>" . $success = DB::updateTag(array(":url" =>"http://newtags.com 5 z",":sound_like" =>metaphone("new tag"),":verified" =>1,":frequency" => 0));
  	// echo "<br/>" . DB::update($pArr,json_decode($_POST['tags']));
  	echo "<br/>" . DB::deleteTags(array(metaphone("new tag"),metaphone("old tag")));
  	// echo "<br/>" . DB::delete(1062);

?>
