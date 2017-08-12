<?php 
	include('..\env.php');
	require('connect-mysql.php');
	
	session_start();
	
	$error = "";

	if(isset($_GET['method']))
	{
		return $_GET['method']();
	}
	elseif (isset($_POST['method']))
	{
		return $_POST['method']();
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
		if (isset($_SESSION['loged_in']) && ($_SESSION['loged_in'] == getenv('ADD_TOKEN')))
		{
			$finalResult = array();
			$res = DB::query("SELECT * FROM `index`");
			$finalResult['contents'] = $res->fetchAll();
			$finalResult['total'] = $res->rowCount();
			echo json_encode($finalResult);
		}
		else 
			return 0;
	}

	function updateContent()
	{
		if (isset($_SESSION['loged_in']) && ($_SESSION['loged_in'] == getenv('ADD_TOKEN')))
		{
			$params = array(':id'=>$_POST['id'],':title'=>$_POST['title'],':keywords'=>$_POST['keywords'],
						':url'=>$_POST['url'],':slider_rating'=>$_POST['rating'],
						':description'=>$_POST['description'],':url_hash'=>md5($_POST['url']));
			if(DB::update($params))
				return getAll();
			else return 0;
		}
		else 
			return 0;
	}

	function deleteContent()
	{
		if (isset($_SESSION['loged_in']) && ($_SESSION['loged_in'] == getenv('ADD_TOKEN')))
		{
			if(DB::delete($_GET['id']))
				return getAll();
			else return 0;
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
	<link rel="icon" href="bluehex.png"><!-- 16by 16 icon that appear on your tab -->
	<!-- <link rel="stylesheet" type="text/css" href="default_style.css"> -->
<!-- 	<link rel="stylesheet" type="text/css" media="only screen and (min-width:320px) and (max-width:688x)" href="mobile_style.css"> -->
	 
	 <!-- JQuery Mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Custom -->
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<script src="js/shared_functions.js"></script>
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
				ajaxCustom("all_in_db.php","POST",{method:"clearSession",redirect:"all_in_db.php"},"",
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
		formatedResult = results['total'] + " Result Found <hr/>";
		$.each( results['contents'], function( i, l ){
        formatedResult+= 
         '<div id='+l['id']+ ' style='+'"border-bottom: 6px solid hsl('+hsl_rating(l['rating'])+', 100%, 50%);\
                      background-color: lightgrey;\
                      margin-bottom: 10px;\
                      box-shadow: 5px 5px 5px #888888;">'+
                      'Title: <span name="title">'+ l['title'] + '</span><br>'+
                      'Rating: <span name="rating">'+ l['rating'] + '</span><br>'+
                      'URL: <span name="url"><a target="_blank" href="'+ l['url'] +'">'+l['url']+'</a> </span><br>'+
                      'Keywords: <span name="keywords">'+ l['keywords'] + '</span><br>'+
                      'Description: <span name="description">'+ l['description'] + '</span><br>'+
                      '<button class="ui-btn ui-btn-inline" onclick="deleteItem($(this).parent());">Delete</button>\
                  	  <button class="ui-btn ui-btn-inline" onclick="setUpUpdatePopUp($(this).parent());">Update</button>'+
          '</div>';
        });
		return formatedResult;
	}
	
	function ajaxCustom(url,type,data,data_type="",success="",failure="") {
		return $.ajax({
			url:url,
            type:type,
            data:data,
            dataType:data_type,
            success:success, 
            error:failure
		});
	}

	function updateItem(form) {
		$.mobile.loading( "show");
		var data = {};
		form.find('[name]').each(function (index, value) {
            var name = $(this).attr('name'),
                value = $(this).val();
                data[name] = value;
                // console.log(name+":" + value);

        });
        data['method']="updateContent";

        $( "#updatePopup" ).popup( "close" );

        ajaxCustom("all_in_db.php","POST",data,"json",
			function (datas,textStatus,jqXHR) {
				alert("sucess Update");
				$("#main_result_div").html(formatResults(datas));
				$.mobile.loading( "hide");
			},
			function (jqXHR, exception) {
				$.mobile.loading( "hide");
				alert("error in Update ");
			}
		);

        return false;
	}

	function setUpUpdatePopUp(parentDiv) {
		 parentDiv.find('span[name]').each(function (index, value) {
                // console.log("name:"+$(this).attr('name')+" / val:"+$(this).text());

                if($(this).attr('name')== "rating")
                {
                	$("input[name="+$(this).attr('name')+"]").val($(this).text()).slider("refresh");
                }
                else if($(this).attr('name')== "description")
                {
                	$("textarea[name="+$(this).attr('name')+"]").val($(this).text());
                }
                else $("input[name="+$(this).attr('name')+"]").val($(this).text());
        });

		 $("form input[name=id]").val(parentDiv[0].id); 
		 // console.log($("form input[name=id]").val());


		$( "#updatePopup" ).popup( "open" );
		return false;
	}

	function deleteItem(parentDiv) {
		$.mobile.loading( "show");
		// console.log(parentDiv);
		// alert(parentDiv[0].id);
		ajaxCustom("all_in_db.php","GET",{method:"deleteContent",id:parentDiv[0].id},"json",
			function (datas,textStatus,jqXHR) {
				// console.log(datas);
				alert("successfull Delete");
				$("#main_result_div").html(formatResults(datas));
				$.mobile.loading( "hide");
			},
			function (jqXHR, exception) {
				$.mobile.loading( "hide");
				alert("error Deleting");
			}
		);
		return false;
	}

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

    		<div id="updatePopup" data-role="popup" data-dismissible="false">
    			<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
	 			<form id="finalAddForm" method="POST" action="all_in_db.php" class="ajax" style="padding:10px 20px;" onsubmit="updateItem($(this));">
				
					<!-- <div class="ui-field-contain"> -->
					<div class="ui-field-contain">
					    <label for="title">Title:</label>
					    <input type="text" name="title" id="title" data-clear-btn="true" required>
					</div>

					<div class="ui-field-contain">
					    <label for="keywords">Keywords:</label>
					    <input type="text" name="keywords" id="keywords" data-clear-btn="true">
					</div>

					<div class="ui-field-contain">
					    <label for="url">Url: <span id="urlErr" style="color: red">  </span> </label>
					     <input  name="url" id="url" data-clear-btn="true" required>
					</div>

					<div class="ui-field-contain">
						 <label for="rating">Rating:</label>
	        			<input name="rating" type="range"  id="slider-rating" value="0" min="0" max="5" step=".1" data-highlight="true" data-theme="b" data-track-theme="b">
					</div>
					<div class="ui-field-contain">
					    <label for="description">Description:</label>
						<textarea cols="40" rows="8" name="description" id="textarea"  ></textarea>
					</div>

					<input type="hidden" name="id" value="">

					<input type="submit" data-inline="true" value="Update" data-icon="plus">
					<input type="button" data-inline="true" value="Google" data-icon="search" onclick="window.open('http://www.google.com', '_system');">
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

