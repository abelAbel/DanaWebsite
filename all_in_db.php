
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset = "UTF-8"/>
	<meta name="description" content=" "/> <!-- max = 200 -->
	<meta name="keywords" content="search, ethical, etc."/> <!-- max=a thousand -->
	<meta name="robots" content="index,follow"/><!-- How search engines should index my content -->
	<!-- <base href="http://localhost/html/"> --> <!-- Default location of all your links -->
	<link rel="icon" href="bluehex.png"><!-- 16by 16 icon that appear on your tab -->
	<!-- <link rel="stylesheet" type="text/css" href="default_style.css"> -->
<!-- 	<link rel="stylesheet" type="text/css" media="only screen and (min-width:320px) and (max-width:688x)" href="mobile_style.css"> -->
	 
	 <!-- JQuery Mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Custom -->
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

	<script>
		
	</script>

	<title>Every Body Knows See All in DB</title>

</head>
<body>

	<?php
	$finalResult = "";
	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		include('..\env.php');  
		if(($_POST["username"] == getenv('LOGIN_USERNAME') ) && ($_POST["password"] == getenv('LOGIN_PASSWORD') ) )
		{
			require('connect-mysql.php');
			$results = $pdo->prepare("SELECT * FROM `index`");
			$results->execute($params);

		}else
		{
			$finalResult = '<hr/> <p style = "color:red">Invalid Username and/or Password</p>';
		}

	}
	// else
	// {
	// 	header("Location: index.php");//Redirect to main mpage
	// 	exit();
	// }
			// include('..\env.php'); 
			// require('connect-mysql.php');
			// $results = $pdo->prepare("SELECT * FROM `index`");
			// $results->execute($params);


 ?>

	<!-- Start of second page -->
	<div data-role="page" id="pAllDB">

		<div id = "rH" data-role="header"  data-position="fixed">
			<h1>DB All</h1>
			<button id = "toTop" onclick="$.mobile.silentScroll(0)" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-carat-u ui-btn-icon-notext">Top</button>
		</div><!-- /header -->

		<div id = "rMainTop" role="main" class="ui-content">
			
			<a href="#popupLogin" data-rel="popup" data-position-to="window" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-check ui-btn-icon-left ui-btn-a" data-transition="pop">Sign in</a>
			<div data-role="popup" id="popupLogin" data-theme="a" class="ui-corner-all">
			    <form method="POST" action="#">
			        <div style="padding:10px 20px;">
			            <h3>Please sign in</h3>
			            <label for="un" class="ui-hidden-accessible">Username:</label>
			            <input type="text" name="username" id="un" value="" placeholder="username" data-theme="a">
			            <label for="pw" class="ui-hidden-accessible">Password:</label>
			            <input type="password" name="password" id="pw" value="" placeholder="password" data-theme="a">
			            <button type="submit" name = "Login" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Sign in</button>
			        </div>
			    </form>
			</div>

			<?php 
			if(isset($results))
			{
				$finalResult .= "Total result: ".$results->rowCount()."<hr/>";
				foreach ($results->fetchAll() as $result) 
				{
					$finalResult.=
					 "<div style='border-bottom: 6px solid hsl(".($result['rating'] == 0 ? '0':'99.99999999999999').", 100%, 50%);
	    					     background-color: lightgrey;
	    					     margin-bottom: 10px;
	    					     box-shadow: 5px 5px 5px #888888;'
	    			 >"."Title: ".$result['title']."<br>". 
	    			 	"Rating: ".$result['rating']."<br>".
	    			 	"URL: <a href='".$result['url']."'>".$result['url']."</a> <br>".
	    			 	"Keywords: ".$result['keywords']."<br>".
	    			 	"Description: ".$result['description']."<br>".
	    			 "</div>";
	    		}
			}
    		echo $finalResult;

			// foreach ($variable as $key => $value) {
			// 							 $finalResult.= 
   //           '<div style='.'"border-bottom: 6px solid 0, 100%, 50%);\
   //                        background-color: lightgrey;\
   //                        margin-bottom: 10px;\
   //                        box-shadow: 5px 5px 5px #888888;">'.
   //                        'Title: '. l['title'] . '<br>'.
   //                        'Rating: '. l['rating'] . '<br>'.
   //                        'URL: <a target="_blank" href="'. l['url'] .'">'.l['url'].'</a> <br>'.
   //                        'Keywords: '. l['keywords'] . '<br>'.
   //                        'Description: '.l['description'] . '<br>'.
   //            '</div>';
			// }

			?>
		</div><!-- /content -->

		<div data-role="footer" data-position="fixed">
			<h4>Result Page Footer</h4>
		</div><!-- /footer -->
	</div><!-- /page -->
	
</body>
</html>

