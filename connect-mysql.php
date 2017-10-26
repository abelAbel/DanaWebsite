<?php
	// include('..\env.php');
	include('..\..\env.php');		
// $dbConnect->beginTransaction();$dbConnect->commit();$dbConnect->rollback();
	// try
	// {
	// 	$host = getenv('DB_HOSTNAME');
	// 	$db = getenv('DB');
	// 	$userName = getenv('DB_USERNAME');;
	// 	$psw = getenv('DB_PASSWORD');
	// 	$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
	// 	// echo "You have connected successfully to EBK";
	// 	// echo "DB_HOSTNAME : " . getenv('DB_HOSTNAME');

	// } catch(PDOException $e)
	// {
	// 	echo $e->getMessage();
	// 	die("<br> <b> Error Connecting to Data Base");
	// }


	class DB
	{//Begining of DB class

		// public function __construct()
		// {
		// 	$this->pdo = $this->connect();
		// }
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
				echo $e->getMessage();
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
			} catch(PDOException $e){return "";}
		}

		public static function deleteTags($tags_sound = array(),$checkFrequency=true){
			try 
			{
				$dbConnect = self::getConnection();
				$success = 0;
				$query = "DELETE FROM `tags` WHERE tag_sound_like=:tag_sound_like";
				$delete = $dbConnect->prepare($query);
				foreach ($tags_sound as $tag_sound) {
					$q = self::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tag_sound."'");
      		// $frequency = ($frequency != '') ? $frequency->fetch(PDO::FETCH_ASSOC)['tag_frequency']:0;
      		// echo "<br/> Tag sound = ". $tag_sound."<br>";
      		if($q != "" && $q->rowCount())
      		{
      		  $row = $q->fetch(PDO::FETCH_ASSOC);
      			// echo "<br/> q-> tag_verified = ".$row['tag_verified']."<br/>";
      			$frequency = $row['tag_frequency'];
      			echo "<br/> frequency : " . $frequency . "<br/>";
	      		if($checkFrequency && $frequency > 0)
	      		{// Update tag frequency number 
		      		 $tag_param = array();
	      			 $tag_param[':frequency'] = $frequency - 1;
	      			 $tag_param[':sound_like'] = $tag_sound;
	      			 echo "<br/> q-> tag_verified = ".$row['tag_verified']."<br/>";
	      			 if($tag_param[':frequency'] == 0 && $row['tag_verified'] == 0)
	      			 	{
	      			 			echo "<br/> deleteTags (frequency=0) Tag Table.... <pre>";
	      			 			$delete->bindParam(":tag_sound_like",$tag_sound);
										$delete->execute();
										$success = $delete->rowCount();
	      			 	}else{
	      			 		echo "<br/> deleteTags (update) Tag Table.... <pre>"; 
	      			 		$success = self::updateTag($tag_param);
	      			 		echo "<br/> delete (update) tag => ".$tag_sound."<br/>";
	      			 	}
	      		}
	      		else { //delete tag
	      			echo "<br/> delete tag => ".$tag_sound."<br/>";
							$delete->bindParam(":tag_sound_like",$tag_sound);
							$delete->execute();
							$success = $delete->rowCount();
							if($success && $checkFrequency == false)
							{//Go delete tag_sound_like in index table 
								$indexQ = self::query("SELECT * FROM `index` WHERE tags_sound_like LIKE '%".$tag_sound."%'");
								$indexRows = ($indexQ != "") ? $indexQ->fetchall(PDO::FETCH_ASSOC):array();
								foreach ($indexRows as $r) {
									$array = explode(' ',$r['tags_sound_like']);
									echo var_dump($array)."<br/>";
									$array = array_diff($array,array($tag_sound));
                  $array = implode(' ',$array);
									echo var_dump($array)."<br/>";
									$success = self::update(array(':tags_sound_like' => $array, ':id'=>$r['id'] ));
								}
							}
	      		}
      		}
				}
				// $delete = $dbConnect->prepare($query);
				// $delete->bindParam(":id",$id);
				// $delete->execute(array(":id":$id));
				// return $delete->rowCount();
				return $success;
			} catch(PDOException $e){
				echo $e->getMessage();
				die("<br> <b> Error in deleteTags");
				return 0;
			}
		}

		public static function delete($id){
			$dbConnect = self::getConnection();
			try 
			{
				$dbConnect->beginTransaction();
				$success = 1;
				$result = self::query("SELECT * FROM `index` WHERE id='".$id."'");
				if($result != ""  && $result->rowCount())
				{
					$tag_sound_like = $result->fetch(PDO::FETCH_ASSOC)['tags_sound_like'];
					if($tag_sound_like !="")
					{
						$success = self::deleteTags(explode(" ",$tag_sound_like));
					}
					if($success){
						$query = "DELETE FROM `index` WHERE id=:id";
						$delete = $dbConnect->prepare($query);
						$delete->bindParam(":id",$id);
						$delete->execute();
						$success = $delete->rowCount();
						$dbConnect->commit();
					}
					else { 
						echo "<br/> Unable to delete tags => " . $result->fetch(PDO::FETCH_ASSOC)['tags_sound_like'];
						$dbConnect->rollback();
						return 0;
					}
				} 
				
				return $success;
			} catch(PDOException $e){
				$dbConnect->rollback();
				echo $e->getMessage();
				die("<br> <b> Error in delete");
				return 0;
			}
		}

		public static function update($param,$tags=array()){
			$dbConnect = self::getConnection();
			try 
			{
				$success = 0;
      	$SET_param = "";
		    $SET_param .= (isset($param[':title'])) ? ',title=:title':'';
				$SET_param .= (isset($param[':description'])) ? ',description=:description':'';
				$SET_param .= (isset($param[':url'])) ? ',url=:url':'';
				$SET_param .= (isset($param[':slider_rating'])) ? ',rating=:slider_rating':'';
				$SET_param .= (isset($param[':url_hash'])) ? ',url_hash=:url_hash':'';
				$SET_param.=  (isset($param[':verified'])) ? ',verified=:verified':'';
				$SET_param =  ltrim($SET_param, ',');
				echo "<br/> >>>>>>>>>>>>>". var_dump($SET_param ) . "<<<<<<<<<<<<<<<<<<<< <br/>";
				echo "<pre>";
				print_r($param);
				if(sizeof($tags) == 0)
				{//Only update index table
					$SET_param.=  (isset($param[':tags_sound_like'])) ? ',tags_sound_like=:tags_sound_like':'';
					$query = "UPDATE `index` SET ".$SET_param." WHERE id=:id";
					$update = $dbConnect->prepare($query); 
					$update->execute($param);
					$success =  $update->rowCount();
					return $success;
				}

				$dbConnect->beginTransaction();
				// $query = "UPDATE `index` SET title=:title,description=:description,keywords=:keywords,url=:url,rating=:slider_rating,url_hash=:url_hash,verified=:verified,tags=:tags WHERE id=:id";
				// $update = $dbConnect->prepare($query);
				// $update->execute($param);
				// return $update->rowCount();
				//Get current sound like column for index
				$result = self::query("SELECT * FROM `index` WHERE id='".$param[':id']."'");
				if($result != ""  && $result->rowCount())
				{
					echo "--------result--------<br/>";
					print_r($result);
					$param[':tags_sound_like'] = "";
					foreach ($tags as $tag) {
						$tagSound = metaphone($tag->text);
						$param[':tags_sound_like'] .= $tagSound. " ";
						$tagQuery = self::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tagSound."'");
					  echo "<br/>------tagQuery------ <br/>";
					  print_r($tagQuery);
	      		if($tagQuery != "" && $tagQuery->rowCount()){// Update tag
		      		 $tag_param = array();
	      			 $tag_param[':sound_like'] = $tagSound;
	      			 $tag_param[':verified'] = $param[':verified'];
	      			 $tag_param[':name'] = $tag->text;
	      			 if($tag->url !=""){
	      			 	 $tag_param[':url'] = $tag->url;
	      			 }
	      			 echo "<br/>!!!! update Tag Table !!!!<pre>"; 
	      			 $success = self::updateTag($tag_param);
	      		}
	      		else{ // add new tag 
	      			echo "<br/>!!!! add Tag Table !!!!<pre>"; 
	            $success = self::addTag(array(":name" =>$tag->text,":url" =>$tag->url,":sound_like" =>$tagSound,":verified" =>$param[':verified'],":frequency" => 1));
	      		}
					    
					}
					$param[':tags_sound_like'] = trim($param[':tags_sound_like']);
					if($param[':tags_sound_like'] != "")
					{
						//Determine if we need to delete any tags
					  $new_tags_sound_like = explode(" ",$param[':tags_sound_like']);
					  $old_tags_sound_like = explode(" ",$result->fetch(PDO::FETCH_ASSOC)['tags_sound_like']);
					  $tags_to_delete = array_diff($old_tags_sound_like,$new_tags_sound_like);
					  echo "<br/>-------new_tags_sound_like------- <pre>";
					  print_r($new_tags_sound_like);
					  echo "<br/>-------old_tags_sound_like------- <pre>";
					  print_r($old_tags_sound_like);				  
					  echo "<br/>-------tags_to_delete------- <pre>";
					  print_r($tags_to_delete);
					  if(sizeof($tags_to_delete))
					  {
					  	echo "<br/>-------Call deleteTags------- ";
					  	$success = self::deleteTags($tags_to_delete);
					  }
					}

					// $query = "UPDATE `index` SET title=:title,description=:description,url=:url,rating=:slider_rating,url_hash=:url_hash,verified=:verified,tags_sound_like=:tags_sound_like WHERE id=:id";
					$SET_param.=  (isset($param[':tags_sound_like'])) ? ',tags_sound_like=:tags_sound_like':'';
					echo "<br/> >>>>>>>>>>>>>". var_dump($SET_param ) . "<<<<<<<<<<<<<<<<<<<< <br/>";
					echo "<pre>";
					print_r($param);
					$query = "UPDATE `index` SET ".$SET_param." WHERE id=:id";
					$update = $dbConnect->prepare($query); 
					$update->execute($param);
					$success =  $update->rowCount();

					if(!$success){
				  	$dbConnect->rollback();
				  	echo "Error occured when updating";
				  	return 0;
				  }else {
				  	$dbConnect->commit();
				  	return $success;
				  } 
				}
				else {
					$dbConnect->rollback();
					echo "<br/> No ID exists";
					return 0;
				}
			} catch(PDOException $e){
				$dbConnect->rollback();
				echo $e->getMessage();
				die("<br> <b> Error in update");
				return 0;
			}
		}

		public static function updateTag($param){
			$dbConnect = self::getConnection();
			try 
			{
				 if (isset($param[':name'])) {
				 		$param[':sound_like'] = metaphone($param[':name']);
				 }
				// $query = "UPDATE `tags` SET tag_name=:name,tag_url=:url,tag_sound_like=:sound_like,tag_verified=:verified,tag_frequency=:frequency WHERE tag_sound_like=:sound_like";
        $SET_param = "";
		    $SET_param .= (isset($param[':name'])) ? ',tag_name=:name':'';
				$SET_param .= (isset($param[':url'])) ? ',tag_url=:url':'';
				$SET_param .= (isset($param[':sound_like'])) ? ',tag_sound_like=:sound_like':'';
				$SET_param .= (isset($param[':verified'])) ? ',tag_verified=:verified':'';
				$SET_param.=  (isset($param[':frequency'])) ? ',tag_frequency=:frequency':'';
				$SET_param =  ltrim($SET_param, ',');
				// $query = "UPDATE `tags` SET tag_name=:name,tag_url=:url,tag_sound_like=:sound_like,tag_verified=:verified,tag_frequency=:frequency WHERE tag_sound_like=:sound_like";

				 

				echo "<pre>";
				print_r($param);
				echo var_dump($SET_param) . "<br/>";
				$query = "UPDATE `tags` SET ".$SET_param." WHERE tag_sound_like=:sound_like";
				echo "<br/>Query => " . $query;
				$update = $dbConnect->prepare($query);
				$update->execute($param);
				return $update->rowCount();
			} catch(PDOException $e){
				echo '<br>' . $e->getMessage();
				die("<br> <b> Error in updateTag");
				return 0;
			}
		}		

    public static function add($param,$tagsArray=array()){
    	$success = 0;
    	$dbConnect = self::getConnection();
      try 
      {
      	$dbConnect->beginTransaction();
	      // $query = "INSERT INTO `index` VALUES ('',:title,:description,:keywords,:url,:slider_rating,:url_hash,:verified,:tags)";
	      // $add = $dbConnect->prepare($query);
	      // $add->execute($param);
      	// $tagsArray = $param[':tags'];
      	// echo print_r($tagsArray);
      	$param[':tags_sound_like'] = "";
      	foreach ($tagsArray as $tag) {
      		// echo var_dump( $tag['text']);
      		// die( var_dump( $tag['text']));
      		$tagSound = metaphone($tag->text);
      		$param[':tags_sound_like'] .= $tagSound. " ";
      		$frequency = self::query("SELECT * FROM `tags` WHERE tag_sound_like='".$tagSound."'");
      		if($frequency != '' && $frequency->rowCount()){// Update tag frequency number 
      			 $frequency = $frequency->fetch(PDO::FETCH_ASSOC)['tag_frequency'];
      			 echo "frequency : " . $frequency . "<br/>";
	      		 $tag_param = array();
      			 $tag_param[':frequency'] = $frequency + 1;
      			 $tag_param[':sound_like'] = $tagSound;
      			 $tag_param[':verified'] = $param[':verified'];
      			 if($tag->url !=""){
      			 	 $tag_param[':url'] = $tag->url;
      			 }
      			 echo "update Tag Table.... <pre>"; 
      			 $success = self::updateTag($tag_param);
      		}
      		else{ 
      			echo "add Tag Table.... <pre>";
            $success = self::addTag(array(":name" =>$tag->text,":url" =>$tag->url,":sound_like" =>$tagSound,":verified" =>$param[':verified'],":frequency" => 1));
      		}
      	}
      	if($success != 0)
      	{
      		$param[':tags_sound_like'] = trim($param[':tags_sound_like']);
      		echo "Inset in Index Table <pre>";
      		print_r($param);
	        $query = "INSERT INTO `index` VALUES ('',:title,:description,:url,:slider_rating,:url_hash,:verified,:tags_sound_like)";
		      $add = $dbConnect->prepare($query);
		      $add->execute($param);      	
		      $success = $add->rowCount();
		      $dbConnect->commit();
      	}
      	else
      	{
      		echo "<br> was not able to add tags";
      		$dbConnect->rollback();
      	}
      } catch(PDOException $e)
		  {
		  	$dbConnect->rollback();
		  	echo "<br>" . $e->getMessage();
				die("<br> <b> Error in add");
				return 0;
		  }

		  return $success; 

    }

    public static function addTag($param){
      try 
      {
      	$dbConnect = self::getConnection();
      	if(!isset($param[':sound_like'])){
      			$param[':sound_like'] = metaphone($param[':name']);
      	}
      	$query = "INSERT INTO `tags` VALUES ('',:name,:url,:sound_like,:verified,:frequency)";
      	$add = $dbConnect->prepare($query);
      	$add->execute($param);
	      return $add->rowCount();
      } catch(PDOException $e)
		  {
		  	echo $e->getMessage();
				die("<br> <b> Error in addTag");
				return 0;
		  }

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
	// echo "<pre>";
	// print_r(DB::query("SELECT * FROM `index`")->fetchAll());
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
