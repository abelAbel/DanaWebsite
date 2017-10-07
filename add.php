
<?php

	include('..\env.php');
	require('connect-mysql.php');
  // $_POST['validation'] = getenv('ADD_TOKEN');
  // $_POST['method'] = "addRequestMain";
  // $_POST['title'] = "Test Title";
  // $_POST['description'] = "Update tester";
  // $_POST['keywords'] = "update";
  // $_POST['url'] = "https://www.google.com/#q=shrimp&*";
  // $_POST['slider-rating'] = "3.5";
  // $_POST['tags'] = "Walmart,Google and live";

  if (isset($_GET['method']) && $_GET['method'] == "verifiedAdd" && isset($_GET['url_hash'])) 
  {
      $results = DB::query("SELECT * FROM `index` WHERE url_hash=:url_hash AND verified=0",array(':url_hash' => $_GET['url_hash']));
      if($results->rowCount())
      {
        $finalAddRow = $results->fetchAll()[0];
        $id = $finalAddRow['id'];
        $title = $finalAddRow['title'];
        $description = $finalAddRow['description'];
        $keywords = $finalAddRow['keywords'];
        $url = $finalAddRow['url'];
        $url_hash = $finalAddRow['url_hash'];
        $rating = $finalAddRow['rating'];
        $tags = $finalAddRow['tags'];
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
        	<meta charset = "UTF-8"/>
        	<meta name="description" content=" "/> <!-- max = 200 -->
        	<meta name="keywords" content="search, ethical, etc."/> <!-- max=a thousand -->
        	<meta name="robots" content="index,follow"/><!-- How search engines should index my content -->
        	<!-- <base href="http://localhost/html/"> --> <!-- Default location of all your links -->
        	<link rel="icon" href="img/bluehex.png"><!-- 16by 16 icon that appear on your tab -->
        	<!-- <link rel="stylesheet" type="text/css" href="default_style.css"> -->
        <!-- 	<link rel="stylesheet" type="text/css" media="only screen and (min-width:320px) and (max-width:688x)" href="mobile_style.css"> -->

        	 <!-- JQuery Mobile -->
        	<meta name="viewport" content="width=device-width, initial-scale=1">
          <!-- Custom -->
          <link rel="stylesheet" href="themes/custom_c_d.min.css" />
          <link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
          <!-- End Custom -->
        	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />

        	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        	<!-- <script src="js/shared_functions.js"></script> -->

          <title>Every Body Knows</title>
          <!-- Tag system begin -->
          <link rel="stylesheet"  href="tagsystem/tags.css">
          <script src="tagsystem/tags.js"></script>
          <!-- Tag system end -->
          <script type="text/javascript">
        //   	$(function () {
        //   		  // Starting at http://everybodyknows.world/add.php?method=verifiedAdd&url_hash=...
								// // Alter the URL: http://example.com/ => http://example.com/#foo
        //   			// $.mobile.navigate( "http://10.0.0.197:3000/add.php", { info: "info about the #foo hash" });
								// // Alter the URL: http://example.com/#foo => http://example.com/#bar
								// // $.mobile.navigate( "http://10.0.0.197:3000/add.php" );
								 
								// // Bind to the navigate event
								// $( window ).on( "navigate", function( event, data ) {
								//   console.log( data.state.info );
								//   console.log( data.state.direction )
								//   console.log( data.state.url )
								//   console.log( data.state.hash )
								//   if(data.state.direction == "back")
								//   {
								//   	$.mobile.navigate( "http://10.0.0.197:3000/index.php" );
								//   }
								// });
								 
								// // Alter the URL: http://example.com/#bar => http://example.com/#foo
								// // window.history.back();
        //   	});
     //   		$(document).on('pagebeforechange', function(e, data){  
					//     var to = data.toPage,
					//         from = data.options.fromPage;
					//     console.log("to: " + to);
					//     console.log("from: " + from);
					     
					// 		 if (typeof to  === 'string') 
					// 		 {
					// 		        var u = $.mobile.path.parseUrl(to);
					// 		        to = u.hash || '#' + u.pathname.substring(1);
					// 		        if (from) from = '#' + from.attr('id');
					// 		         console.log("inside if string -> to: " + to);
					// 		         console.log("inside if string -> from: " + from);
					// 		        // if (from === '#finalAddResponce' && to === '#finalAddPage') {
					// 		        if (to === '#finalAddPage') {
					// 		            alert('Can not transition from #finalAddResponce to #finalAddPage!');
					// 		            e.preventDefault();
					// 		            e.stopPropagation();
							             
					// 		            // remove active status on a button if a transition was triggered with a button
					// 		            $.mobile.activePage.find('.ui-btn-active').removeClass('ui-btn-active ui-shadow').css({'box-shadow':'0 0 0 #3388CC'});
					// 		        }  
					// 		  }
					// });
          </script>
</head>
<body>
  <div data-role="page" id="finalAddPage" data-theme="c">
  	<div data-role="header">
  		<h1>E.K.W Final Add</h1>
  	</div><!-- /header -->

  	<div role="main" class="ui-content">
      <!-- <form  data-ajax="false" id="finalAddF" method="POST" action="add.php" style="padding:10px 20px;"> -->
      <form id="finalAddF" method="POST" action="add.php" style="padding:10px 20px;">
        <!-- <div class="ui-field-contain"> -->
        <div class="ui-field-contain">
            <label for="title">Title:</label>
            <input type="text" name="title" data-clear-btn="true" required value=<?php echo '"'.$title.'"';?>>
        </div>

        <div class="ui-field-contain">
            <label for="keywords">Keywords:</label>
            <input type="text" name="keywords" id="keywords" data-clear-btn="true" value=<?php echo '"'.$keywords.'"';?>>
        </div>

        <div class="ui-field-contain">
            <label for="url">Url: <span id="urlErr" style="color: red">  </span> </label>
             <input  name="url" id="url" data-clear-btn="true" required value=<?php echo '"'.$url.'"'; ?>>
        </div>

        <div class="ui-field-contain">
           <label for="rating">Rating:</label>
              <input name="slider-rating" type="range"  id="slider-rating" value=<?php echo '"'.$rating .'"'; ?> min="0" max="5" step=".1" data-highlight="true"  >
        </div>
        <div class="ui-field-contain">
            <label for="description">Description:</label>
          <textarea name="description" id="textarea"> <?php echo $description; ?> </textarea>
        </div>
        <input type="hidden" name="tags" value="<?php echo $tags; ?>">
        <input type="text" name="tagsz" placeholder="Tags" class="tags"/>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <!-- <input type="hidden" name="url_hash" value="<?php //echo $url_hash; ?>"> -->
        <input type="hidden" name="validation" value=<?php echo getenv('ADD_TOKEN');?>>
        <input id="addOrdelete" type="hidden" name="addOrdelete" value="add">
       	<div data-role="controlgroup" data-type="horizontal" >
	        <input type="submit" data-inline="true" value="Final Add" data-icon="plus">
        	<input type="button" data-inline="true" value="Delete" data-icon="delete"  onclick="$('#addOrdelete').val('delete'); $('#finalAddF').submit();">
        	<input type="button" data-inline="true" value="Google" data-icon="search" onclick="window.open('http://www.google.com', '_system');">
		</div>
      </form>
  	</div><!-- /content -->

  	<div data-role="footer" data-position="fixed">
  		<h4>Page Footer</h4>
  	</div><!-- /footer -->
  </div><!-- /page -->
<!--   	<div data-role="page" id="fianAddResponce" data-theme="<?php echo $theme; ?>" >
				<div data-role="header"><h1>E.K.W Final Add</h1></div>
				<div role="main" class="ui-content"> <h2><?php echo $responce; ?> </h2></div>
				<div data-role="footer" data-position="fixed"><h4>Page Footer</h4></div>
	</div> -->
</body>
</html>

<?php return;}else die("Data already entered and activited or url_hash is invalid......Goodbye!!!!!");
  }
  
  if(!isset($_POST['validation']) || $_POST['validation'] != getenv('ADD_TOKEN'))
  {
    die("E.K.W add page does not know you, Goodbye");
  }

	if (isset($_POST['addOrdelete'])) 
	{

		$responce = "Something Horrible went wrong";
		$theme = 'c';

		if($_POST['addOrdelete'] == "add")
		{
			//TODO:MAKE SURE TO CHANGE IT BACK TO 1
			$params = dbParamsArray($_POST['title'], $_POST['keywords'], $_POST['description'], $_POST['url'], 0, $_POST['tags']);
			// $params = dbParamsArray($_POST['title'], $_POST['keywords'], $_POST['description'], $_POST['url'], 1, $_POST['tags']);
			if(DB::update($params))
			{
				$responce = "Succesfully Added and Verified";
				$theme = 'd';
			}
			else $responce = "Unable to finalize verification";
		}elseif ($_POST['addOrdelete'] == "delete") {
			if(DB::delete($_POST['id']))
			{
				$responce = "Succesfully deleted";
				$theme = 'd';
			}
			else $responce = "Unable to delete at this time";
		}
		echo 
		'<div data-role="page" id="finalAddResponce" data-theme="'.$theme.'" >
			<div data-role="header"><h1>E.K.W Final Add</h1></div>
			<div role="main" class="ui-content"> <h2>'.$responce.' </h2></div>
			<div data-role="footer" data-position="fixed"><h4>Page Footer</h4></div>
		<script type="text/javascript">
		       		$(document).on("pagecontainerbeforechange", function(e, data){ 
		       		 var activePage = $(":mobile-pagecontainer").pagecontainer("getActivePage");
		      //  		 console.log("activePage ->" + activePage.attr("id")); 
						  //   var to = data.toPage,
						  //   from = data.options.fromPage;
								// console.log("from: " + from);
						  //   console.log("to: " + to);
								 if (activePage.attr("id") === "finalAddResponce" ) 
								 {
				            e.preventDefault();
				            e.stopPropagation();
				            location.reload();
								  }
							});
				</script>
				</div>
		';
		return;
	}

	if(isset($_GET['method']))
	{
		return $_GET['method']();
	}
	elseif (isset($_POST['method']))
	{
		return $_POST['method']();
	}

  function addRequestMain()
  {
    // $finalResult = array();
    // $finalResult['email_sent'] = false;
    $email_sent = false;

    // $title = test_input($_POST['title']);
    // $keywords = test_input($_POST['keywords']);
    // $description = test_input($_POST['textarea']);
    // $url = test_input($_POST['url']);
    // // $url_hash = md5($url);
    // $tags = test_input($_POST['tags']);
    $params = dbParamsArray($_POST['title'], $_POST['keywords'], $_POST['description'], $_POST['url'], 0, $_POST['tags']);
    if(DB::add($params))
    {
      send_email($params[':title'],create_body($params[':title'], $params[':keywords'], $params[':description'], $params[':url'], $params[':tags']));
      // $finalResult['email_sent'] = true;
      $email_sent = true;
      // echo "Succesfully sent email and added to E.K.W as unverified";
    }
    else
    {
      echo "unable to add to E.K.W";
    }
    if(!$email_sent)
    	echo "<br>false";
    else
    	echo $email_sent;

  }

  function dbParamsArray($_title, $_keywords, $_description, $_url, $_verified, $_tags)
  {
  	$title = test_input($_title);
    $keywords = test_input($_keywords);
    $description = test_input($_description);
    $url = test_input($_url);
    $tags = test_input($_tags);
  	$pArr = array(':title'=>$title,':keywords'=>$keywords,
            ':url'=>$url,':slider_rating'=>$_POST['slider-rating'],
            ':description'=>$description,':url_hash'=>md5($url),
            ':verified'=>$_verified, ':tags'=>$tags);
  	if (isset($_POST['id']))
  		$pArr[':id'] = $_POST['id'];

  	return $pArr;
  }
	
	// function add_main($token)
	// {
	// 	$finalResult = array();
	// 	$finalResult['urlErr'] = false;
	// 	$finalResult['email_sent'] = false;

	// 	$url = test_input($_POST['url']);
	// 	// filter_var($url, FILTER_SANITIZE_URL);
	// 	if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url))
	// 	{
	// 		$finalResult['urlErr'] = true;
	// 		return  json_encode($finalResult);
	// 	}

	// 	send_email(create_body($url, $token));
	// 	$finalResult['email_sent'] = true;
	// 	return json_encode($finalResult);

	// }

  function create_body($title, $keywords, $description, $url, $tags)
  {
  
   $finalAddUrl = getenv('DOMAIN').'/add.php?method=verifiedAdd&url_hash='.md5($url);
    return (
        // '<form method="POST" action="http://localhost/add.php" >
        '   Title: '.$title.'<hr>
            Keywords: '.$keywords.'<hr>
            Url: '.$url.' <hr>
            Rating: '.$_POST['slider-rating'].'<br>
            >=0 to <= 1  - Unacceptable<br>
          > 1 to <= 2  - Questionable<br>
          > 2 to <= 3  - Neutral<br>
          > 3 to <= 4  - Good<br>
          > 4 to <= 5  - Great<br>
            <hr>
            Description: '.$description.'<hr>
            Tags: '.$tags.'<hr>
            <a href="'.$finalAddUrl.'">Click Here for Final Add</a>'
          );

  }


	function test_input($data)
	{
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	function send_email($title, $body)
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
		$mail->Subject = 'E.K.W new add request - '.$title;
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
