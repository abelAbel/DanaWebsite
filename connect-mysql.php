<?php 
	include('..\env.php');
	// try
	// {
	// 	$host = '127.0.0.1'; //127.0.0.1 
	// 	$db = 'ebk'; //Data base name
	// 	$userName ='root';
	// 	$psw = ''; //password
	// 	$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
	// 	// echo "You have connected successfully";

	// } catch(PDOException $e)
	// {
	// 	echo $e->getMessage();
	// 	die("<br> <b> Error Connecting to Data Base");
	// }

	try
	{
		$host = getenv('DB_HOSTNAME');
		$db = getenv('DB');
		$userName = getenv('DB_USERNAME');;
		$psw = getenv('DB_PASSWORD');
		$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
		// echo "You have connected successfully to EBK";
		// echo "DB_HOSTNAME : " . getenv('DB_HOSTNAME');

	} catch(PDOException $e)
	{
		echo $e->getMessage();
		die("<br> <b> Error Connecting to Data Base");
	}

	class DB
	{//Begining of DB class

		// public function __construct()
		// {
		// 	$this->pdo = $this->connect();
		// }

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
			$dbConnect = self::connect();
			$results = $dbConnect->prepare($query);
			$results->execute($param);
			return $results;
		}

		public static function delete($id){
			$dbConnect = self::connect();
			$query = "DELETE FROM `index` WHERE id=:id";
			$delete = $dbConnect->prepare($query);
			$delete->bindParam(":id",$id);
			$delete->execute();
			return $delete->rowCount();
		}

		public static function update($param){
			$dbConnect = self::connect();
			$query = "UPDATE `index` SET title=:title,description=:description,keywords=:keywords,url=:url,rating=:slider_rating,url_hash=:url_hash WHERE id=:id";
			$update = $dbConnect->prepare($query);
			$update->execute($param);
			return $update->rowCount();
		}

	}//End of DB class

	// $dbTest = new DB();
	// echo $dbTest->delete("232");

	// $_POST['title'] = "z8888";
	// $_POST['textarea'] = "Update tester";
	// $_POST['keywords'] = "update";
	// $_POST['url'] = "https://www.google.com/#q=shrimp&*";
	// $_POST['slider-rating'] = "3.5";

	// $params = array(':id'=>"2s2",':title'=>$_POST['title'],':keywords'=>$_POST['keywords'],
	// 					':url'=>$_POST['url'],':slider_rating'=>$_POST['slider-rating'],
	// 					':description'=>$_POST['textarea'],':url_hash'=>md5($_POST['url']));
	// echo $dbTest->update($params);
	// echo "Results = " . $dbTest->results . "<br>";
	// print_r($dbTest->query("SELECT * FROM `index` WHERE id=:id", array(':id' => 192))); 
	// print_r($dbTest->query("SELECT * FROM `index`"));
	// echo "<br>";
	// print_r($dbTest->results->fetchAll(PDO::FETCH_ASSOC));
	// print_r(DB::query("SELECT * FROM `index` WHERE id=:id", array(':id' => 192))->fetchAll());
	// print_r(DB::update($params));
	// $id = "42";
	// print_r(DB::delete($id));

 // print_r(DB::query("SELECT * FROM `index`")->fetchAll());

?>	
