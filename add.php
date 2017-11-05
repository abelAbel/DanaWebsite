
<?php

	include('..\env.php');
	require('connect-mysql.php');
	session_start();
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
      if(is_object($results) && $results->rowCount())
      {
        $finalAddRow = $results->fetch(PDO::FETCH_ASSOC);
        $id = $finalAddRow['id'];
        $title = $finalAddRow['title'];
        $description = $finalAddRow['description'];
        $url = $finalAddRow['url'];
        $url_hash = $finalAddRow['url_hash'];
        $rating = $finalAddRow['rating'];
        $tags = DB::tags_info_string($finalAddRow['tags_sound_like']);
        $_SESSION['validationAddPage'] = getenv('ADD_TOKEN');
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
          <script src="tagsystem/tags.js"></script>
          <!-- Tag system end -->
          <script type="text/javascript">
		        $(document).one('pagebeforecreate',function (a) {
		          $("#tags").tagSystem({maxTags:10,addAutocomplete:true});
		          // $("#tags").val("hello|https://hellow.com,howdy,walmart,good things,great art").trigger('input');
		        });

		        $(document).one('pagecreate',function (a) {
		          $("#tags").val("<?php echo $tags; ?>").trigger('input');
		        });

          </script>
</head>
<body>
  <div data-role="page" id="finalAddPage" data-theme="c">
  	<div data-role="header">
  		<h1>E.K.W Final Add</h1>
  	</div><!-- /header -->

  	<div role="main" class="ui-content">
			<div data-role="popup" id="popupDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;">
					<h3 class="ui-title">Are you sure you want to do this?</h3>
					<p><span style="color: red">This action cannot be undone.</span></p>
					<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">Cancel</a>
					<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" onclick="$('#finalAddF').submit();" data-transition="flow">Comfirm</a>
			</div>
      <!-- <form  data-ajax="false" id="finalAddF" method="POST" action="add.php" style="padding:10px 20px;"> -->
      <form id="finalAddF" method="POST" action="add.php" style="padding:10px 20px;">
        <!-- <div class="ui-field-contain"> -->
        <div class="ui-field-contain">
            <label for="title">Title:</label>
            <input type="text" name="title" data-clear-btn="true" required value=<?php echo '"'.$title.'"';?>>
        </div>

        <div class="ui-field-contain">
            <label for="url">Url: <span id="urlErr" style="color: red">  </span> </label>
             <input  name="url" id="url" data-clear-btn="true" required value=<?php echo '"'.$url.'"'; ?>>
        </div>

        <div class="ui-field-contain">
				    <label for="tags">Who is being rated tags: <br/> (MAX = 10)</label>
				    <input type="text" name="tags-main-input" data-name="tags" id="tags" data-clear-btn="true">
				</div>

        <div class="ui-field-contain" data-theme="b">
           <label for="slider-rating">Rating:</label>
              <input name="slider-rating" type="range"  id="slider-rating" value=<?php echo '"'.$rating .'"'; ?> min="0" max="5" step=".1" data-highlight="true" >
        </div>

        <div class="ui-field-contain">
          <label for="description">Description:</label>
          <textarea name="description" id="description"> <?php echo $description; ?> </textarea>
        </div>

        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <!-- <input type="hidden" name="validation" value=<?php echo getenv('ADD_TOKEN');?>> -->
        <input id="addOrdelete" type="hidden" name="addOrdelete">
       	<div data-role="controlgroup" data-type="horizontal" >
        	<a href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-plus ui-btn-icon-left" onclick="$('#addOrdelete').val('add');">Final Add</a>
        	<a href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-delete ui-btn-icon-left" onclick="$('#addOrdelete').val('delete');">Delete</a>
        	<input type="button" data-inline="true" value="Google" data-icon="search" onclick="window.open('http://www.google.com', '_system');">
		</div>
      </form>
  	</div><!-- /content -->

  	<div data-role="footer" data-position="fixed">
  		<h4>Page Footer</h4>
  	</div><!-- /footer -->
  </div><!-- /page -->

</body>
</html>

<?php return;}
   else{
   	  unset($_SESSION['validationAddPage']);
   		die("Data already entered and activited or url_hash is invalid......Goodbye!!!!!");
   } 
  }

  if(isset($_SESSION['validationAddPage'])){
  	$_POST['validation'] = $_SESSION['validationAddPage'];
  }
  // else 
  // {
  	
  	// remove all session variables
		// session_unset();
		// destroy the session
		// session_destroy();
		// unset($_SESSION['name']); // will delete just the name data
		// session_destroy(); // will delete ALL data associated with that user.
  // }
  
  if(!isset($_POST['validation']) || $_POST['validation'] != getenv('ADD_TOKEN'))
  {
  	unset($_SESSION['validationAddPage']);
    die("E.K.W add page does not know you, Goodbye");
  }

	if (isset($_POST['addOrdelete'])) 
	{

		$responce = "Something Horrible went wrong";
		$theme = 'c';

		if($_POST['addOrdelete'] === "add")
		{
			$params = dbParamsArray($_POST['title'], $_POST['description'], $_POST['url'], 1);
			$addDB = DB::update($params,json_decode($_POST['tags']));
			if($addDB === DB::SUCCESS)
			{
				$responce = "Succesfully Added and Verified";
				$theme = 'd';
			}
			else $responce = "Unable to finalize verification <hr/> Error: ".$addDB;
			
		}elseif ($_POST['addOrdelete'] === "delete") {
			$deleteDB = DB::delete($_POST['id']);
			if( $deleteDB === DB::SUCCESS)
			{
				$responce = "Succesfully deleted";
				$theme = 'd';
			}
			else $responce = "Unable to delete at this time <hr/> Error: ".$deleteDB;
			
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
		unset($_SESSION['validationAddPage']);
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

    // $title = test_input($_POST['title']);
    // $keywords = test_input($_POST['keywords']);
    // $description = test_input($_POST['textarea']);
    // $url = test_input($_POST['url']);
    // // $url_hash = md5($url);
    // $tags = test_input($_POST['tags']);
    $params = dbParamsArray($_POST['title'], $_POST['description'], $_POST['url'], 0);
    if(send_email($params[':title'],create_body($params[':title'], $params[':description'], $params[':url'], $_POST['tags'])) === DB::SUCCESS)
    	{
    		 $added = DB::add($params,json_decode($_POST['tags']));
		     if($added === DB::SUCCESS)
			    {
			      echo DB::SUCCESS;
			    }
			    else
			    {
			      echo "Error adding/submit to E.K.W - Error code = ".$added;
			    }
    }

  }

  function dbParamsArray($_title, $_description, $_url, $_verified)
  {
  	$title = test_input($_title);
    // $keywords = test_input($_keywords);
    $description = test_input($_description);
    $url = test_input($_url);
  	$pArr = array(':title'=>$title,':url'=>$url,
  		          ':slider_rating'=>$_POST['slider-rating'],
            ':description'=>$description,':url_hash'=>md5($url),
            ':verified'=>$_verified);
  	if (isset($_POST['id']))
  		$pArr[':id'] = $_POST['id'];

  	return $pArr;
  }
	

  function create_body($title, $description, $url, $tags)
  {
  
   $finalAddUrl = getenv('DOMAIN').'/add.php?method=verifiedAdd&url_hash='.md5($url);
    return (
        // '<form method="POST" action="http://localhost/add.php" >
        '   Title: '.$title.'<hr>
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
		// date_default_timezone_set('Etc/UTC');
		// include('..\env.php');
		$mail = new PHPMailer();
		$mail->isSMTP();
		// $mail->SMTPDebug = 2;
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
		   echo "<br/> Error sending: " . $mail->ErrorInfo;
		   exit("<br/> Error occured when trying to email!!");
		}
		else
		{
		   // echo "E-mail sent";
			return DB::SUCCESS;
		}

	}



?>
