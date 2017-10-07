<?php
	// include('..\env.php');
	include('..\..\env.php');		

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

		public static function delete($id){
			try 
			{
				$dbConnect = self::getConnection();
				$query = "DELETE FROM `index` WHERE id=:id";
				$delete = $dbConnect->prepare($query);
				$delete->bindParam(":id",$id);
				$delete->execute();
				return $delete->rowCount();
			} catch(PDOException $e){return 0;}
		}

		public static function update($param){
			try 
			{
				$dbConnect = self::getConnection();
				$query = "UPDATE `index` SET title=:title,description=:description,keywords=:keywords,url=:url,rating=:slider_rating,url_hash=:url_hash,verified=:verified,tags=:tags WHERE id=:id";
				$update = $dbConnect->prepare($query);
				$update->execute($param);
				return $update->rowCount();
			} catch(PDOException $e){return 0;}
		}

    public static function add($param){
      try 
      {
      	$dbConnect = self::getConnection();
	      $query = "INSERT INTO `index` VALUES ('',:title,:description,:keywords,:url,:slider_rating,:url_hash,:verified,:tags)";
	      $add = $dbConnect->prepare($query);
	      $add->execute($param);
	      return $add->rowCount();
      } catch(PDOException $e)
		  {
		  	// echo $e->getMessage();
				// die("<br> <b> Error Connecting to Data Base");
				return 0;
		  }

    }

	}//End of DB class

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

?>
