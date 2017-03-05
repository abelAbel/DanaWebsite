
<?php 
	$host = '127.0.0.1'; //127.0.0.1 
	$db = 'ebk'; //Data base name
	$userName ='root';
	$psw = ''; //password
	$pdo = new PDO('mysql:host='.$host.';dbname='.$db,$userName,$psw); //Php data object (Type of database/host etc..,user name,password)
// name="textarea" name="slider-rating" name="url" name="title" name="keywords"
	// $_POST['title'] = "https://www.google.com";
	// $_POST['textarea'] = "https://www.google.com";
	// $_POST['keywords'] = "https://www.google.com";
	// $_POST['url'] = "https://www.google.com/#q=shrimp&*";
	// $_POST['slider-rating'] = "3.5";


	$rows = $pdo->query("SELECT * FROM `index` WHERE url_hash='".md5($_POST['url'])."'");
	//echo "MD5: ".md5($_POST['url']);
	$rows = $rows->fetchColumn();
	//echo "Row fetch column: ".$rows;

	$params = array(':title'=>$_POST['title'],':keywords'=>$_POST['keywords'],
					':url'=>$_POST['url'],':slider_rating'=>$_POST['slider-rating'],
					':description'=>$_POST['textarea'],':url_hash'=>md5($_POST['url']));

	if($rows > 0)
	{}
	else{
		$result = $pdo->prepare("INSERT INTO `index` VALUES ('',:title,:description,:keywords,:url,:slider_rating,:url_hash)");
		$result = $result->execute($params);
	}

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
?>