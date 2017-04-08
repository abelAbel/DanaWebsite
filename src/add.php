
<?php 

// ===============Begin good
	// $host = '127.0.0.1'; //127.0.0.1 
	// $db = 'ebk'; //Data base name
	// $userName ='root';
	// $psw = ''; //password
	// $pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
// name="textarea" name="slider-rating" name="url" name="title" name="keywords"
	// $_POST['title'] = "https://www.google.com";
	// $_POST['textarea'] = "https://www.google.com";
	// $_POST['keywords'] = "https://www.google.com";
	// $_POST['url'] = "https://www.google.com/#q=shrimp&*";
	// $_POST['slider-rating'] = "3.5";

// 	$finalResult = array();
// 	 $finalResult['titleError'] = "";

// 	 $finalResult['titleError'] = "Invalid";

// 	$rows = $pdo->query("SELECT * FROM `index` WHERE url_hash='".md5($_POST['url'])."'");
// 	//echo "MD5: ".md5($_POST['url']);
// 	$rows = $rows->fetchColumn();
// 	//echo "Row fetch column: ".$rows;

// 	$params = array(':title'=>$_POST['title'],':keywords'=>$_POST['keywords'],
// 					':url'=>$_POST['url'],':slider_rating'=>$_POST['slider-rating'],
// 					':description'=>$_POST['textarea'],':url_hash'=>md5($_POST['url']));

// 	if($rows > 0)
// 	{
// 		//update
// 		$result = $pdo->prepare("UPDATE `index` SET title=:title,description=:description,keywords=:keywords,url=:url,rating=:slider_rating,url_hash=:url_hash WHERE url_hash=:url_hash");
// 		$result = $result->execute($params);

// 	}
// 	else{
// 		$result = $pdo->prepare("INSERT INTO `index` VALUES ('',:title,:description,:keywords,:url,:slider_rating,:url_hash)");
// 		$result = $result->execute($params);
// 	}

// 	// echo json_encode("Successfull Add/update");
// 	echo json_encode($finalResult);

// ===============End good



	

	// $title= $_POST['title'];
	
		// echo 'hello ' . $title;

		// echo 
		// "
		// <div data-role='page' id='bar'>

		// 	<div data-role='header'>
		// 		<h1>Bar</h1>
		// 	</div><!-- /header -->

		// <div role='main' class='ui-content'>
		// 	<p>I'm the second in the source order so I'm hidden when the page loads. I'm just shown if a link that references my id is beeing clicked.</p>
		// 	<p><a href='#foo'>Back to foo</a></p>
		// </div><!-- /content -->

		// <div data-role='footer'>
		// 	<h4>Page Footer</h4>
		// </div><!-- /footer -->
		// </div><!-- /page -->


		// ";

		//$pdo -> query("SELECT * FROM index");

	if(!isset($_GET['validate'])){
		require_once('PHPMailer-master/PHPMailerAutoload.php');
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;//Tell Php mailler that we need to Authenticate with Gmail to let them know so we can send an email
		$mail->SMTPSecure = 'ssl'; //With gmail we need to use SSL else gmail wont send any messages
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '465'; //465 or 587 or other
		$mail->isHTML();
		$mail->Username = 'rapeurabel@gmail.com';
		$mail->Password = '1FranceAfrica';
		$mail->SetFrom('NO-REPLY');
		$mail->Subject = 'Testing';
		// $mail->Body = 'testing email body: <a href="http://localhost/add.php?validate=true">https://wwww.everybodyknows.world/add.php?validate=true</a>';

	// $_POST['title'] = "https://www.google.com";
	// $_POST['textarea'] = "https://www.google.com";
	// $_POST['keywords'] = "https://www.google.com";
	// $_POST['url'] = "https://www.google.com/#q=shrimp&*";
	// $_POST['slider-rating'] = "3.5";
	
	$mail->Body ='<form method="POST" action="http://localhost/add.php?validate=true" >
				    Title:<br>
				    <input type="text" name="title" value="'.$_POST['title'].'" readonly><br>
				    Keywords:<br>
				    <input type="text" name="keywords" value="'.$_POST['keywords'].'" readonly><br>
				    Url:<br>
				    <input  name="url" value="'.$_POST['url'].'" readonly> <br>
				    Rating:<br>
				    <input type="text" name="slider-rating" value="'.$_POST['slider-rating'].'" readonly><br>
				    Description:<br>
					<textarea name="textarea" rows="10" cols="90" readonly>'.$_POST['textarea'].'</textarea><br>
				   <input type="submit" value="Add">
			    </form>';
		$mail->AddAddress('aamadou194@gmail.com');

		$mail->Send();//send the mail (Limited to 99 messages a day)

		echo add_main();

	}
	else 
	{
		echo "Validate = " . $_GET['validate'];
		echo "<br>Successfully added <br>";
		echo print_r($_POST);
		echo $_POST['title'];
		echo $_POST['textarea'];
		echo $_POST['keywords'];
		echo $_POST['url'];
		echo $_POST['slider-rating'];
	}




	function test_input($data)
	{
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	function verify()
	{

	}
	function add_main()
	{
		// define variables and set to empty values
		// $titleErr = $keywordsErr = $urlErr = $descriptionErr = "Invalid *";
		$finalResult = array();




		$finalResult['urlErr'] = "blanker";

		$title = $keywords = $url = $description = "";

		$title = test_input($_POST['title']);
		$keywords = test_input($_POST['keywords']);
		$url = test_input($_POST['url']);

		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) 
		{
			$finalResult['urlErr'] = "Invalid *";
			return  json_encode($finalResult);
			//return;
		}

		if(!empty($finalResult['urlErr']))
		{
				$finalResult['urlErr'] = "hahahah";
		}
	   		
		$description = test_input($_POST['textarea']);

		return json_encode($finalResult);


	}


		
?>