<?php
	include('..\env.php');
	// include('..\..\env.php');		
// $dbConnect->beginTransaction();$dbConnect->commit();$dbConnect->rollback();

	class DB
	{//Begining of DB class

		// public function __construct()
		// {
		// 	$this->pdo = $this->connect();
		// } 
		const SUCCESS = 0;  
		const QUERY_ERROR = 1;  
		const ADD_ERROR = 2;
		const UPDATE_ERROR = 3;
		const DELETE_ERROR = 4;
		const ADD_TAG_ERROR = 5;
		const UPDATE_TAG_ERROR = 6;
		const DELETE_TAG_ERROR = 7;

		protected static $pdo = null;

		public static function endConnection()
		{
			self::$pdo = null;
		}

		protected function getConnection()
		{
	       // initialize $pdo on first call
	        if (self::$pdo == null) {
	        	// echo "Establishing 1st new connection \n";
	            self::$pdo = self::connect();
	        }
	        else
	        {
	          	// now we should have a $pdo, whether it was initialized on this call or a previous one
	        	// but it could have experienced a disconnection
		        try {
		            // echo "Testing connection...\n";
		            // $old_errlevel = error_reporting(0);
		            self::$pdo->query("SELECT 1");
		            // echo "Sucessfull testing...\n";
		        } catch (PDOException $e) {
		            echo "Connection failed, reinitializing...\n";
		            self::$pdo = self::connect();
		        }
		        // error_reporting($old_errlevel);
	        }

	        return self::$pdo;
		}

		private function connect()
		{
			$pdo = "";
			try
			{
				$host = getenv('DB_HOSTNAME');
				$db = getenv('DB');
				$userName = getenv('DB_USERNAME');;
				$psw = getenv('DB_PASSWORD');
				$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
				$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

			} catch(PDOException $e)
			{
				echo "<br>" . $e->getMessage();
				die("<br> <b> Error Connecting to Data Base");
			}
			return $pdo;
		}

		public static function query($query,$param=array()){
			try 
			{
				$dbConnect = self::getConnection();
				$results = $dbConnect->prepare($query);
				$results->execute($param);
				return $results;
			} catch(PDOException $e){return self::QUERY_ERROR;}
		}

		public static function deleteTags($tags_sound = array(),$checkFrequency=true){
			$dbConnect = self::getConnection();
			try 
			{
				$query = "DELETE FROM `tags` WHERE tag_sound_like=:tag_sound_like";
				$delete = $dbConnect->prepare($query);
				foreach ($tags_sound as $tag_sound) {
					$q = self::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tag_sound."'");
      		if(is_object($q) && $q->rowCount())
      		{
      		  $row = $q->fetch(PDO::FETCH_ASSOC);
      			$frequency = $row['tag_frequency'];
	      		if($checkFrequency && $frequency > 0)
	      		{// Update tag frequency number 
		      		 $tag_param = array();
	      			 $tag_param[':frequency'] = $frequency - 1;
	      			 $tag_param[':sound_like'] = $tag_sound;
	      			 if($tag_param[':frequency'] == 0 && $row['tag_verified'] == 0)
	      			 	{
	      			 			$delete->bindParam(":tag_sound_like",$tag_sound);
										$delete->execute();
										if(!$delete->rowCount())
											return self::DELETE_TAG_ERROR.":1";
	      			 	}else{
	      			 		if(self::updateTag($tag_param) != self::SUCCESS)
	      			 			 return self::DELETE_TAG_ERROR.":2";
	      			 	}
	      		}
	      		else { //delete tag
							$delete->bindParam(":tag_sound_like",$tag_sound);
							$delete->execute();
							if(!$delete->rowCount())
								return self::DELETE_TAG_ERROR.":3";
							if($checkFrequency == false)
							{//Go delete tag_sound_like in index table 
								$indexQ = self::query("SELECT * FROM `index` WHERE tags_sound_like LIKE '%".$tag_sound."%'");
								$indexRows = (is_object($indexQ)) ? $indexQ->fetchall(PDO::FETCH_ASSOC):array();
								foreach ($indexRows as $r) {
									$array = explode(' ',$r['tags_sound_like']);
									$array = array_diff($array,array($tag_sound));
                  $array = implode(' ',$array);
									if(self::update(array(':tags_sound_like' => $array, ':id'=>$r['id'] )) != self::SUCCESS)
										return self::DELETE_TAG_ERROR.":4";
								}
							}
	      		}
      		}
				}
			} catch(PDOException $e){
				// echo $e->getMessage();
				// die("<br> <b> Error in deleteTags");
				return "<br>" . $e->getMessage()."<br> <b> Error in deleteTags";
			}

			return self::SUCCESS;
		}

		public static function delete($id){
			$dbConnect = self::getConnection();
			$success = self::SUCCESS;
			$dbConnect->beginTransaction();
			try 
			{
				$result = self::query("SELECT * FROM `index` WHERE id='".$id."'");
				if(is_object($result) && $result->rowCount())
				{
					$tag_sound_like = $result->fetch(PDO::FETCH_ASSOC)['tags_sound_like'];
					if($tag_sound_like !="")
					{
						if(self::deleteTags(explode(" ",$tag_sound_like)) != self::SUCCESS)
							$success = self::DELETE_ERROR.":1";
					}
					if($success === self::SUCCESS){
						$query = "DELETE FROM `index` WHERE id=:id";
						$delete = $dbConnect->prepare($query);
						$delete->bindParam(":id",$id);
						$delete->execute();
						if (!$delete->rowCount())
							$success = self::DELETE_ERROR.":2";
					}
				}else $success = self::DELETE_ERROR.":3"; //ID does not exist  

				if($success === self::SUCCESS)
					$dbConnect->commit();
				else $dbConnect->rollback();
				
			} catch(PDOException $e){
				$dbConnect->rollback();
				// echo "<br>" . $e->getMessage();
				// die("<br> <b> Error in delete");
				return "<br>" . $e->getMessage()."<br> <b> Error in delete";
			}

			return $success;
		}

		public static function update($param,$tags=array()){
			$dbConnect = self::getConnection();
			$success = self::SUCCESS;
			$dbConnect->beginTransaction();
			try 
			{
      	$SET_param = "";
		    $SET_param .= (isset($param[':title'])) ? ',title=:title':'';
				$SET_param .= (isset($param[':description'])) ? ',description=:description':'';
				$SET_param .= (isset($param[':url'])) ? ',url=:url':'';
				$SET_param .= (isset($param[':slider_rating'])) ? ',rating=:slider_rating':'';
				$SET_param .= (isset($param[':url_hash'])) ? ',url_hash=:url_hash':'';
				$SET_param.=  (isset($param[':verified'])) ? ',verified=:verified':'';

				if(sizeof($tags) == 0)
				{//Only update index table
					$SET_param.=  (isset($param[':tags_sound_like'])) ? ',tags_sound_like=:tags_sound_like':'';
					$SET_param =  ltrim($SET_param, ',');
					$query = "UPDATE `index` SET ".$SET_param." WHERE id=:id";
					$update = $dbConnect->prepare($query); 
					$update->execute($param);
					if(!$update->rowCount())
					{
						$dbConnect->rollback();
						return self::UPDATE_ERROR.":1";
					}
					$dbConnect->commit();
					return $success;
				}
				//Get current sound like column for index
				$result = self::query("SELECT * FROM `index` WHERE id='".$param[':id']."'");
				if(is_object($result) && $result->rowCount())
				{
					$param[':tags_sound_like'] = "";
					foreach ($tags as $tag) {
						$tagSound = metaphone($tag->text);
						$param[':tags_sound_like'] .= $tagSound. " ";
						$tagQuery = self::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tagSound."'");

	      		if(is_object($tagQuery) && $tagQuery->rowCount()){// Update tag
		      		 $tag_param = array();
	      			 $tag_param[':sound_like'] = $tagSound;
	      			 $tag_param[':verified'] = $param[':verified'];
	      			 $tag_param[':name'] = $tag->text;
	      			 if($tag->url !=""){
	      			 	 $tag_param[':url'] = $tag->url;
	      			 }
 							
	      			 if(self::updateTag($tag_param) != self::SUCCESS)
	      			 	{
	      			 		$success = self::UPDATE_ERROR.":2";
	      			 		break;
	      			 	}
	      		}
	      		else{ // add new tag 
 
	            if (self::addTag(array(":name" =>$tag->text,":url" =>$tag->url,":sound_like" =>$tagSound,":verified" =>$param[':verified'],":frequency" => 1)) != self::SUCCESS)
	            	{
	            		$success = self::UPDATE_ERROR.":3";
	            		break;
	            	}
	      		}
					    
					}
					$param[':tags_sound_like'] = trim($param[':tags_sound_like']);
					if($param[':tags_sound_like'] != "")
					{
						//Determine if we need to delete any tags
					  $new_tags_sound_like = explode(" ",$param[':tags_sound_like']);
					  $old_tags_sound_like = explode(" ",$result->fetch(PDO::FETCH_ASSOC)['tags_sound_like']);
					  $tags_to_delete = array_diff($old_tags_sound_like,$new_tags_sound_like);

					  if(sizeof($tags_to_delete))
					  {
					  	if(self::deleteTags($tags_to_delete) != self::SUCCESS)
					  		$success = self::UPDATE_ERROR.":4";
					  }
					  $SET_param.= ',tags_sound_like=:tags_sound_like';
					}
          $SET_param =  ltrim($SET_param, ',');
					$query = "UPDATE `index` SET ".$SET_param." WHERE id=:id";
					$update = $dbConnect->prepare($query); 
					$update->execute($param);
					// if(!$update->rowCount())
					// 	$success = self::UPDATE_ERROR.":5";
				}
				else $success = self::UPDATE_ERROR.":6";// No ID exists";
				
				if($success === self::SUCCESS)
					$dbConnect->commit();
				else $dbConnect->rollback();

			} catch(PDOException $e){
				$dbConnect->rollback();
				return "<br>" . $e->getMessage()."<br> <b> Error in update";
				// echo "<br>" . $e->getMessage();
				// die("<br> <b> Error in update");
			}

			return $success;
		}

		public static function updateTag($param){
			$dbConnect = self::getConnection();
			try 
			{
				if (isset($param[':name'])) {
				 	$param[':sound_like'] = metaphone($param[':name']);
				}
        $SET_param = "";
		    $SET_param .= (isset($param[':name'])) ? ',tag_name=:name':'';
				$SET_param .= (isset($param[':url'])) ? ',tag_url=:url':'';
				$SET_param .= (isset($param[':sound_like'])) ? ',tag_sound_like=:sound_like':'';
				$SET_param .= (isset($param[':verified'])) ? ',tag_verified=:verified':'';
				$SET_param.=  (isset($param[':frequency'])) ? ',tag_frequency=:frequency':'';
				$SET_param =  ltrim($SET_param, ',');
				$query = "UPDATE `tags` SET ".$SET_param." WHERE tag_sound_like=:sound_like";
				$update = $dbConnect->prepare($query);
				$update->execute($param);
				// if(!$update->rowCount())
				// 	return self::UPDATE_TAG_ERROR; 
			} catch(PDOException $e){
				// echo '<br>' . $e->getMessage();
				// die("<br> <b> Error in updateTag");
				return "<br>" . $e->getMessage()."<br> <b> Error in updateTag";
			}

			return self::SUCCESS;
		}		

    public static function add($param,$tagsArray=array()){
    	$success = self::SUCCESS;
    	$dbConnect = self::getConnection();
    	$dbConnect->beginTransaction();
      try 
      {
      	$param[':tags_sound_like'] = "";
      	foreach ($tagsArray as $tag) {
      		$tagSound = metaphone($tag->text);
      		$param[':tags_sound_like'] .= $tagSound. " ";
      		$frequency = self::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tagSound."'");
      		if(is_object($frequency) && $frequency->rowCount()){// Update tag frequency number 
      			 $frequency = $frequency->fetch(PDO::FETCH_ASSOC)['tag_frequency'];
	      		 $tag_param = array();
      			 $tag_param[':frequency'] = $frequency + 1;
      			 $tag_param[':sound_like'] = $tagSound;
      			 $tag_param[':verified'] = $param[':verified'];
      			 if($tag->url !=""){
      			 	 $tag_param[':url'] = $tag->url;
      			 }
      			 if( self::updateTag($tag_param) != self::SUCCESS)
      			 	{
      			 		$success = self::ADD_ERROR.":1";
      			 		break;
      			 	}
      		}
      		else{ //Add tag
            if(self::addTag(array(":name" =>$tag->text,":url" =>$tag->url,":sound_like" =>$tagSound,":verified" =>$param[':verified'],":frequency" => 1)) != self::SUCCESS)
            	{
            		$success = self::ADD_ERROR.":2";
            		break;
            	}
      		}
      	}

      	if($success === self::SUCCESS)
      	{
      		$param[':tags_sound_like'] = trim($param[':tags_sound_like']);
	        $query = "INSERT INTO `index` VALUES ('',:title,:description,:url,:slider_rating,:url_hash,:verified,:tags_sound_like)";
		      $add = $dbConnect->prepare($query);
		      $add->execute($param);      	
		      if(!$add->rowCount())
		      	$success = self::ADD_ERROR.":3";
      	}
      	else $success = self::ADD_ERROR.":4";

				if($success === self::SUCCESS)
					$dbConnect->commit();
				else $dbConnect->rollback();

      } catch(PDOException $e)
		  {
		  	$dbConnect->rollback();
		  	// echo "<br>" . $e->getMessage();
				// die("<br> <b> Error in add");
				$success = "<br>" . $e->getMessage()."<br> <b> Error in add";
		  }

		  return $success; 

    }

    public static function addTag($param){
    	$dbConnect = self::getConnection();
      try 
      {
      	if(!isset($param[':sound_like'])){
      			$param[':sound_like'] = metaphone($param[':name']);
      	}
      	$query = "INSERT INTO `tags` VALUES ('',:name,:url,:sound_like,:verified,:frequency)";
      	$add = $dbConnect->prepare($query);
      	$add->execute($param);
	      if(!$add->rowCount())
	      	return self::ADD_TAG_ERROR;
      } catch(PDOException $e)
		  {
		  	// echo "<br>" . $e->getMessage();
				// die("<br> <b> Error in addTag");
				return "<br>" . $e->getMessage()."<br> <b> Error in addTag";
		  }

		  return self::SUCCESS;
    }

		public static function tags_info_string($tags_sound_like)
		{
			$tagInfoString = "";
			if(isset($tags_sound_like))
			{
				$tags_sounds = explode(' ',$tags_sound_like);
				foreach ($tags_sounds as $tg_snd) {
						$q = self::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tg_snd."'");
	      		if(is_object($q) && $q->rowCount())
	      		{
	             $row = $q->fetch(PDO::FETCH_ASSOC);
	             $tagInfoString .= $row['tag_name'];
	      			 if($row['tag_url'] !=""){
	      			 	 $tagInfoString .= "|".$row['tag_url'];
	      			 }
	             $tagInfoString .= ",";
	      		}
				}
				rtrim($tagInfoString, ',');
			} 
			return $tagInfoString;
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
    public static function renderToHtml($rows,$addToEachEnd="")
		{
			$workArr = array('error' =>self::SUCCESS);
			$html = "";
			$tagIdCount = 0;
			$wAverage = array();
			$popups = array();
			foreach ($rows as $row) {
				$h = self::hsl_rating($row['rating']);
				$html .=
		      '<div db-id='.$row['id'].' style="background-color: lightgrey;
		                  margin-bottom: 10px;
		                  box-shadow: 5px 5px 5px #888888; 
		                  border-radius: 10px 10px 0px 0px;">
		        <a href='.$row['url'].' 
		          style="padding-left: 5px;
		              padding-right: 5px; 
		              padding-top: 1px;
		              margin-bottom: 10px;
		              text-decoration: none;
		              -webkit-tap-highlight-color: rgba(0,255,0,.1);
		              border-bottom: 3px dashed hsl('.$h.', 100%, 50%);
		              display: block;" target="_blank">
		              <h4>'.$row['title'].'</h4> 
		        </a> 

		        <p style="padding-left: 5px; padding-right: 5px;">'.$row['description'].'</p>';
				if(!empty($row['tags_sound_like'])){
					$sound_like = split(' ',$row['tags_sound_like']);
					if(sizeof($sound_like))
					{
						foreach ($sound_like as $sound) {
							if(!isset($workArr[$sound]))
							{

								$q = self::query("SELECT tag_name, tag_url, tag_frequency FROM `tags` WHERE tag_sound_like='".$sound."'");
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
        $html.=$addToEachEnd;
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

	}//End of DB class


	// $_POST['title'] = "z8888";
	// $_POST['textarea'] = "Update tester";
	// $_POST['url'] = "https://www.google.com/#q=shrimp&*";
	// $_POST['slider-rating'] = "3.5";
	// $_POST['verified'] = "0";
	// $_POST['tags'] = "Walmart,Google and live";

 //  $params = array(':title'=>$_POST['title'],':keywords'=>$_POST['keywords'],
 //          ':url'=>$_POST['url'],':slider_rating'=>$_POST['slider-rating'],
 //          ':description'=>$_POST['textarea'],':url_hash'=>md5($_POST['url']),
 //          ':verified'=>0, ':tags'=>$_POST['tags']);

			// 	try 
			// {
				
			// } catch(PDOException $e){
			// 	return 0;
			// }

	// $dbTest = new DB();
	// echo $dbTest->delete("232");

// 	$_POST['title'] = "z8888";
// 	$_POST['textarea'] = "Update tester";
// 	$_POST['keywords'] = "update";
// 	$_POST['url'] = "https://www.google.com/#q=shrimp&*";
// 	$_POST['slider-rating'] = "3.5";
// 	$_POST['verified'] = "0";
// 	$_POST['tags'] = "Walmart,Google and live";
//
//   $params = array(':title'=>$_POST['title'],':keywords'=>$_POST['keywords'],
//           ':url'=>$_POST['url'],':slider_rating'=>$_POST['slider-rating'],
//           ':description'=>$_POST['textarea'],':url_hash'=>md5($_POST['url']),
//           ':verified'=>0, ':tags'=>$_POST['tags']);
// print_r(DB::add($params));
	// echo $dbTest->update($params);
	// echo "Results = " . $dbTest->results . "<br>";
	// print_r($dbTest->query("SELECT * FROM `index` WHERE id=:id", array(':id' => 192)));
	// print_r($dbTest->query("SELECT * FROM `index`"));
	// echo "<br>";
	// print_r($dbTest->results->fetchAll(PDO::FETCH_ASSOC));
	
	// $q = DB::query("SELECT * FROM `index`");
	// if(is_object($q))
	// {
	// 	echo "<pre>";
 //    print_r($q->fetchAll());
	// }
	// else echo "Error = " . $q;
	// echo "\nSleeping 10 seconds...\n";
	// DB::endConnection();
	// sleep(10); /* meanwhile I use another window to KILL the connection */
	// echo "\n";
	// print_r(DB::query("SELECT * FROM `index` WHERE id=:id", array(':id' => 212))->fetchAll());
	// print_r(DB::update($params));
	// $id = "42";
	// print_r(DB::delete($id));

 // print_r(DB::query("SELECT * FROM `index`")->fetchAll());
      		// $tagSound = metaphone("walmart");
      		// echo $tagSound ."<br>";
      		// if($frequency = DB::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tagSound."'")){
      		// 	$frequency = $frequency->fetch(PDO::FETCH_ASSOC)['tag_frequency'];
      		// 	if($frequency>0)
      		// 		echo $frequency. "<br>";
      		// 	echo var_dump($frequency);
      		// }else{ echo "NADA";}
// $frequency = DB::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tagSound."'");
//           echo var_dump($frequency) . "<br>";
//       		$frequency = ($frequency != '')? $frequency->fetch(PDO::FETCH_ASSOC)['tag_frequency']:0;
//       		if($frequency > 0){ 
//       			echo var_dump($frequency);
//       		}else echo "Does not exist" ; 
// $string = "HJK YUT KKIEO KJJPP UYAHAY POPP";
// echo var_dump($string) . "<br/>";   		
// $array = explode(' ',$string);
// echo var_dump($array) . "<br/>";
// $array = array_diff($array,array('HJK'));
// echo var_dump($array) . "<br/>"; 
// echo var_dump(implode(" ",$array)) . "<br/>";
?>
