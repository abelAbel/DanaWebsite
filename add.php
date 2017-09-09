
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

	include('..\env.php');

	if(isset($_GET['method']))
	{
		return $_GET['method']();
	}
	elseif (isset($_POST['method']))
	{
		return $_POST['method']();
	}

	addPHP();

	function addPHP()
	{
		$token = getenv('ADD_TOKEN');
		// echo $token . '<br>';
		if(isset($_POST['ADD']))
		{
			echo add_main($token);
		}
		elseif(isset($_POST['validation']))
		{
			if ($_POST['validation'] == $token)
			{
				add_to_ebk();
			}
			else echo "!!!!!INVALID TOKEN!!!!!";
		}
		else
		{
			echo "E.K.W Don't know you...Goodbye";
		}
	}

	function add_to_ebk()
	{

		// echo "Validate = " . $_GET['validate'] . "<br>";
		// echo "Successfully added <br>";
		// echo print_r($_POST)  . "<br>";

		// echo "title: ".$_POST['title'] . "<br>";
		// echo "keywords: ".$_POST['keywords'] . "<br>";
		// echo "url: ".$_POST['url'] . "<br>";
		// echo "slider-rating: ".$_POST['slider-rating'] . "<br>";
		// echo "textarea: ". $_POST['textarea'] . "<br>";

		// ===============Begin good
		// $host = '127.0.0.1'; //127.0.0.1
		// $db = 'ebk'; //Data base name
		// $userName ='root';
		// $psw = ''; //password
		// $pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)

		require('connect-mysql.php');
		$rows = $pdo->query("SELECT * FROM `index` WHERE url_hash='".md5($_POST['url'])."'");
	// 	//echo "MD5: ".md5($_POST['url']);
		$rows = $rows->fetchColumn();
	// 	//echo "Row fetch column: ".$rows;

		$params = array(':title'=>$_POST['title'],':keywords'=>$_POST['keywords'],
						':url'=>$_POST['url'],':slider_rating'=>$_POST['slider-rating'],
						':description'=>$_POST['textarea'],':url_hash'=>md5($_POST['url']));

		if($rows > 0)
		{//update
			$result = $pdo->prepare("UPDATE `index` SET title=:title,description=:description,keywords=:keywords,url=:url,rating=:slider_rating,url_hash=:url_hash WHERE url_hash=:url_hash");
			$result = $result->execute($params);
			echo "Successfull Update to EBK";

		}
		else
		{ //Add
			$result = $pdo->prepare("INSERT INTO `index` VALUES ('',:title,:description,:keywords,:url,:slider_rating,:url_hash)");
			$result = $result->execute($params);
			echo "Successfull Add to E.K.W";
		}

	}


	function add_main($token)
	{
		$finalResult = array();
		$finalResult['urlErr'] = false;
		$finalResult['email_sent'] = false;

		$url = test_input($_POST['url']);
		// filter_var($url, FILTER_SANITIZE_URL);
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url))
		{
			$finalResult['urlErr'] = true;
			return  json_encode($finalResult);
		}

		send_email(create_body($url, $token));
		$finalResult['email_sent'] = true;
		return json_encode($finalResult);

	}

	function create_body($url, $token)
	{
		// $title = $keywords = $description = "";
		$title = test_input($_POST['title']);
		$keywords = test_input($_POST['keywords']);
		$description = test_input($_POST['textarea']);
		// echo 'Second -> '. $token . '<br>';

		return (
				// '<form method="POST" action="http://localhost/add.php" >
				'<form method="POST" action="'.getenv('DOMAIN').'/add.php" >
				    Title:<br>
				    <input type="text" name="title" value="'.$title.'" ><br>
				    Keywords:<br>
				    <input type="text" name="keywords" value="'.$keywords.'"><br>
				    Url:<br>
				    <input  name="url" value="'.$url.'" > <br>
				    Rating: <br>
				    >=0 to <= 1  - Unacceptable<br>
					> 1 to <= 2  - Questionable<br>
					> 2 to <= 3  - Neutral<br>
					> 3 to <= 4  - Good<br>
					> 4 to <= 5  - Great<br>
				    <input type="text" name="slider-rating" value="'.$_POST['slider-rating'].'" ><br>
				    Description:<br>
					<textarea name="textarea" rows="10" cols="90" >'.$description.'</textarea><br>
				    <input type="submit" value="Final Add" style = "padding: 25px 50px">
				    <input type="hidden" name="validation" value='.$token.'>
			    </form>
			    '
			    );

				// return (
										// <a href="https://everybodyknows.herokuapp.com/add.php?validate='.$token.'"> Hyper link <a/>
				// '	<a href="https://everybodyknows.herokuapp.com/confirm_add.php?

					// '	<a href="http://localhost/confirm_add.php?
				 //    title='.$title.
				 //    '&keywords='.$keywords.
				 //    '&url='.$url.
				 //    '&slider-rating='.$_POST['slider-rating'].
				 //    '&textarea='.$description.'"> Confirm Add Page</a>'
			    // );

	}

	function test_input($data)
	{
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	function send_email($body)
	{
		require_once('PHPMailer-master/PHPMailerAutoload.php');
		// include('..\env.php');
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;//Tell Php mailler that we need to Authenticate with Gmail to let them know so we can send an email
		$mail->SMTPSecure = 'ssl'; //With gmail we need to use SSL else gmail wont send any messages
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '465'; //465 or 587 or other
		$mail->isHTML();
		$mail->Username = getenv('EMAIL_USERNAME');
		$mail->Password = getenv('EMAIL_PSW');
		$mail->SetFrom(getenv('EMAIL_USERNAME'));
		$mail->Subject = 'EBK new add request';
		$mail->Body = $body;
		$mail->AddAddress(getenv('EMAIL_TO'));
		$mail->AddCC(getenv('EMAIL_TO2'));

		// $mail->Send();//send the mail (Limited to 99 messages a day)
		if(!$mail->Send())
		{
		   echo "Error sending: " . $mail->ErrorInfo;
		   exit("Error occured when trying to email!!");
		}
		// else
		// {
		//    // echo "E-mail sent";
		// 	return;
		// }

	}



?>
