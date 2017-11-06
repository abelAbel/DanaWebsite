<?php
	include('..\env.php');
	require('connect-mysql.php');

	session_start();

	$error = "";

	if(isset($_GET['method']))
	{
		echo $_GET['method']();
		return;
	}
	elseif (isset($_POST['method']))
	{
		echo $_POST['method']();
		return;
	}

	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		if(($_POST["username"] == getenv('LOGIN_USERNAME') ) && ($_POST["password"] == getenv('LOGIN_PASSWORD') ) )
		{
			$_SESSION['loged_in'] = getenv('ADD_TOKEN');
			// header("Location: all_in_db.php");

		}else
		{
			$error = '<p style = "color:red">Invalid Username and/or Password</p> <hr>';
			// // remove all session variables
			// session_unset();
			// // destroy the session
			// session_destroy();
			clearSession();
		}

	}
	elseif( isset($_POST['redirect']))
	{
		return $_POST['method']();
	}

	function clearSession()
	{
		// remove all session variables
		session_unset();
		// destroy the session
		session_destroy();
	}

	function getAll()
	{
		$finalResult = array('total'=> 0, 'error' => DB::SUCCESS);

		if (isset($_SESSION['loged_in']) && ($_SESSION['loged_in'] == getenv('ADD_TOKEN')))
		{
			$res = DB::query("SELECT * FROM `index` WHERE verified=1");
		  if(is_object($res))
		  {   
		  	$resultsRow = $res->fetchAll(PDO::FETCH_ASSOC);
		  	$rend = DB::renderToHtml($resultsRow,
		  				// <button class="ui-btn ui-btn-inline" onclick="deleteItem($(this).parent());">Delete</button>
           		'<button style = "margin-bottom:0px" class="ui-btn ui-btn-b" onclick="setUpEditPopUp($(this).parent());">Edit</button> ');

		  	if($rend['error'] === DB::SUCCESS)
		  	{
		    	$finalResult['popups'] = $rend['popups'];
		    	$finalResult['html'] = $rend['html'];
				  $finalResult['total'] = $res->rowCount();
		  	}
		  	$finalResult['error'] = $rend['error'];
		  }

		  return json_encode($finalResult);
		}
		else
			return 0;
	}

	function getInfoForForm()
	{

		 $results = DB::query("SELECT * FROM `index` WHERE id=:id",array(':id' => $_GET['id']));
      if(is_object($results) && $results->rowCount())
      {
        $finalAddRow = $results->fetch(PDO::FETCH_ASSOC);
        $finalAddRow['tags'] = DB::tags_info_string($finalAddRow['tags_sound_like']);
      }
      $finalAddRow['error'] = 0;
      return json_encode($finalAddRow);

	}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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
	function updateContent()
	{
		if (isset($_SESSION['loged_in']) && ($_SESSION['loged_in'] == getenv('ADD_TOKEN')))
		{
			$params = dbParamsArray($_POST['title'], $_POST['description'], $_POST['url'],1);
			$updateDB = DB::update($params,json_decode($_POST['tags']));
			if($updateDB === DB::SUCCESS)
				{
					return getAll();
				}
			else{
					$finalResult = array('error' => "Unable to update <hr/> Error: ".$updateDB);
					return json_encode($finalResult);
			} 
		}
		else
			return 0;
	}

	function deleteContent()
	{
		if (isset($_SESSION['loged_in']) && ($_SESSION['loged_in'] == getenv('ADD_TOKEN')))
		{
			$deleteDB = DB::delete($_POST['id']);
			if( $deleteDB === DB::SUCCESS)
				return getAll();
			else{
					$finalResult = array('error' => "Unable to delete at this time <hr/> Error: ".$deleteDB );
					return json_encode($finalResult);
			}
		}
		else
			return 0;
	}

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
	    <!-- Custom -->
    <link rel="stylesheet" href="themes/custom_c_d.min.css" />
    <link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
    <!-- End Custom -->
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<script src="js/shared_functions.js"></script>
   <!-- Tag system begin -->
    <script src="tagsystem/tags.js"></script>
    <!-- Tag system end -->
<script >
	// $(function(){
	// 	alert("hhh");
	// });
	// $(document).on("pagebeforecreate",function(){
	// 	if($("#main_result_div").length != 0){
	// 		alert("pagebeforecreate / Exists");
	// 	}
	//   // alert("pagebeforecreate event fired - the page is about to be initialized. jQuery Mobile has not begun enhancing the page");
	// });
	$(document).on("pagecreate",function(){
		if($("#main_result_div").length != 0){
			$.mobile.loading( "show");
			ajaxCustom("all_in_db.php","GET",{method:"getAll"},"json",
			function (datas,textStatus,jqXHR) {
				// console.log(datas);
				$("#main_result_div").html(formatResults(datas));
				$.mobile.loading( "hide");
			},
			function (jqXHR, exception) {
				alert("error in Pagecreate");
				ajaxCustom("all_in_db.php","GET",{method:"clearSession",redirect:"all_in_db.php"},"",
				function(){
					// similar behavior as an HTTP redirect
					window.location.replace("all_in_db.php");
					// similar behavior as clicking on a link
					// window.location.href = "index.php";
				},"");
				$.mobile.loading( "hide");
			}
		);
	  }
	  // alert("pagecreate event fired - the page has been created, but enhancement is not complete");
	});

	function formatResults(results)
	{
		$('[data-short-tg]').remove();
		formatedResult = results['total'] + " Result Found <hr/>";
    formatedResult+= results['html'];
    var x;
    for(x in results['popups'])
    {
      console.log(results['popups'][x]);
      $(results['popups'][x]).appendTo("#main_result_div").popup();
      // $(d['popups'][x]).appendTo("#rMainTop").enhanceWithin().popup();
    }

		// $.each( results['contents'], function( i, l ){
        // formatedResult+= 
         // '<div id='+l['id']+ ' style='+'"border-bottom: 6px solid hsl('+hsl_rating(l['rating'])+', 100%, 50%);\
         //              background-color: lightgrey;\
         //              margin-bottom: 10px;\
         //              box-shadow: 5px 5px 5px #888888;">'+
         //              'Title: <span name="title">'+ l['title'] + '</span><br>'+
         //              'Rating: <span name="rating">'+ l['rating'] + '</span><br>'+
         //              'URL: <span name="url"><a target="_blank" href="'+ l['url'] +'">'+l['url']+'</a> </span><br>'+
         //              'Tags: <span name="tags">'+ l['tags'] + '</span><br>'+
         //              'Description: <span name="description">'+ l['description'] + '</span><br>'+
         //              '<button class="ui-btn ui-btn-inline" onclick="deleteItem($(this).parent());">Delete</button>\
         //          	  <button class="ui-btn ui-btn-inline" onclick="setUpUpdatePopUp($(this).parent());">Update</button>'+
         //  '</div>';
        // });
		return formatedResult;
	}

	function updateItem() {
		$.mobile.loading( "show");
		var form = $('#editForm');
		    data = {};
		form.find('[name]').each(function (index, value) {
            var name = $(this).attr('name'),
                value = $(this).val();
                data[name] = value;
                console.log(name+":" + value);

        });
        data['method']="updateContent";
        console.log(data);


      ajaxCustom("all_in_db.php","POST",data,"json",
			function (datas,textStatus,jqXHR) {
				if(datas['error'] != 0){
					addPopUp(datas['error'],'c');
				}else{
					addPopUp("sucess Update",'d');
					$("#main_result_div").html(formatResults(datas));
				}
				$.mobile.loading( "hide");
			},
			function (jqXHR, exception) {
				$.mobile.loading( "hide");
				alert("error in Update ");
			}
		);

        return false;
	}

	function setUpEditPopUp(parentDiv) {
		//Clear all form items
		 $("#tags").get(0).clearAll();
		 $('#editForm').trigger('reset');

		  $.mobile.loading( "show");
			ajaxCustom("all_in_db.php","GET",{method:"getInfoForForm",id:parentDiv.attr('db-id')},"json",
			function (datas,textStatus,jqXHR) {
				console.log(datas);
				if(datas['error'] == 0){
          $("form input[name=title]").val(datas['title']);
          $("form input[name=url]").val(datas['url']);
          $("form input[name=slider-rating]").val(datas['rating']).slider("refresh");
          $("form textarea[name=description]").html(datas['description']);
					$("#tags").val(datas['tags']).trigger('input');
					$("form input[name=id]").val(datas['id']);
					$( "#updatePopup" ).popup( "open" );
				}
				else addPopUp("Error Occured - " + datas['error'],'c');

				$.mobile.loading( "hide");
			},
			function (jqXHR, exception) {
				addPopUp("Error Occured while prepering the form",'c');
				$.mobile.loading( "hide");
			}
		);
	  
		return false;
	}

	function deleteItem(parentDiv) {
		$.mobile.loading( "show");
		// console.log(parentDiv);
		// alert(parentDiv[0].id);
		ajaxCustom("all_in_db.php","POST",{method:"deleteContent",id:$('form input[name=id]').val()},"json",
			function (datas,textStatus,jqXHR) {
				// console.log(datas);
				if(datas['error'] != 0){
					addPopUp(datas['error'],'c');
				}else{
						addPopUp("successfull Delete",'d');
						$("#main_result_div").html(formatResults(datas));
				}
				$.mobile.loading( "hide");
			},
			function (jqXHR, exception) {
				$.mobile.loading( "hide");
				alert("error Deleting");
			}
		);
		return false;
	}
  $(document).on('pagebeforecreate',function (a) {
  	$("#tags").tagSystem({maxTags:10,addAutocomplete:true});
  // $("#tags").val("hello|https://hellow.com,howdy,walmart,good things,great art").trigger('input');
	});
    $( document ).on( "popupafterclose", function() {
        $( "#addPopupDiv" ).remove();
    });

	$(document).on("pagecreate", function () {
    $('.edit').on('click', function (e) { // e is the event
		        setTimeout(function () {
		            $("#popupDialog").popup("open")
		        }, 100);
		    });

    $('#comfirmed').on('tap',function (argument) {
    	 if($("#updateOrdelete").val() == 'delete'){
    	 		deleteItem();
    	 }
    	 else if($("#updateOrdelete").val() == 'update'){
    	 		updateItem();
    	 }
    });

	});

</script>

	<title>Every Body Knows See All in DB</title>

</head>
<body>
	<!-- Start of page -->
	<div data-role="page" id="pAllDB">

		<div id = "rH" data-role="header"  data-position="fixed">
			<h1>DB All</h1>
			<button id = "toTop" onclick="$.mobile.silentScroll(0)" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-carat-u ui-btn-icon-notext">Top</button>
		</div><!-- /header -->

		<div id = "rMainTop" role="main" class="ui-content">
		<?php if (isset($_SESSION['loged_in']) && ($_SESSION['loged_in'] == getenv('ADD_TOKEN'))) : ?>
			<div id="main_result_div">

			</div> <!-- End of #main_result_div-->

			<div data-role="popup" id="popupDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;">
					<h3 class="ui-title">Are you sure you want to do this?</h3>
					<p><span style="color: red">This action cannot be undone.</span></p>
					<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">Cancel</a>
					<a href="#" data-rel="back" id='comfirmed' class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b"  data-transition="flow">Comfirm</a>
			</div>
    		<div id="updatePopup" data-role="popup" data-dismissible="false">
    			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
	 			<form method="POST" id="editForm" action="all_in_db.php" style="padding:5px 20px;" onsubmit="updateItem($(this));">
	 				    <div class="ui-field-contain">
            <label for="title">Title:</label>
            <input type="text" name="title" data-clear-btn="true" required >
        </div>

        <div class="ui-field-contain">
            <label for="url">Url: <span id="urlErr" style="color: red"> </span> </label>
             <input  name="url" id="url" data-clear-btn="true" required >
        </div>

        <div class="ui-field-contain">
				    <label for="tags">Who is being rated tags: <br/> (MAX = 10)</label>
				    <input type="text" name="tags-main-input" data-name="tags" id="tags" data-clear-btn="true">
				</div>

        <div class="ui-field-contain" data-theme="b">
           <label for="slider-rating">Rating:</label>
              <input name="slider-rating" type="range"  id="slider-rating" value=0 min="0" max="5" step=".1" data-highlight="true" >
        </div>

        <div class="ui-field-contain">
          <label for="description-a">Description:</label>
          <textarea name="description" id="description-a"></textarea>
        </div>


        <input type="hidden" name="id">
        <input id="updateOrdelete" type="hidden" name="updateOrdelete">
       	<div data-role="controlgroup" data-type="horizontal" >
        	<a href="#"  data-role="button" class='edit' data-rel="back" data-icon="plus" onclick="$('#updateOrdelete').val('update');">Update</a>
        	<a href="#"  data-role="button" class='edit' data-rel="back" data-icon="delete" onclick="$('#updateOrdelete').val('delete');">Delete</a>
        	<input type="button" data-inline="true" value="Google" data-icon="search" onclick="window.open('http://www.google.com', '_system');">
				</div>
				</form>
			</div> <!-- End of #updatePopup -->
		<?php else: ?>
			<form method="POST" action="all_in_db.php">
		        <div style="padding:10px 20px;">
		            <h3>Please sign in</h3>
					<?php echo $error ?>
		            <label for="un" class="ui-hidden-accessible">Username:</label>
		            <input type="text" name="username" id="un" value="" placeholder="username" data-theme="a">
		            <label for="pw" class="ui-hidden-accessible">Password:</label>
		            <input type="password" name="password" id="pw" value="" placeholder="password" data-theme="a">
		            <button type="submit" name = "Login" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Sign in</button>
		        </div>
			</form>
		<?php endif; ?>
		</div> <!-- End of role=main -->


		<div data-role="footer" data-position="fixed">
			<h4>Result Page Footer</h4>
		</div><!-- /footer -->

	</div><!-- End of role=page -->

</body>
</html>
