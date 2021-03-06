<?php
	session_start();
	include('..\env.php');
	include('share_cls_&_fnc.php');
	if(isset($_SESSION['admin'])) {
		if($_SESSION['admin'] != getenv('ADD_TOKEN'))
		{
			// remove all session variables
			session_unset();
			// destroy the session
			session_destroy();
			header("Location: index.php");
		}else if(isset($_GET['method']))
		{
			return $_GET['method']();
		}
	}
	else header("Location: index.php");

	function find_title_and_keyword ()
	{
		$m = new URLMeta($_GET['url']);
		$responce = array();
	  if ($resp = $m->parse()) {
	  	$responce['title'] = $resp->title;
	  	$responce['keywords'] =  implode(",", $resp->keywords);
	  } 
	  // else {
	  //    printf("FAILED\nERROR CODE:%s\nRESPONSE: %s", $m->error_code, $m->error_response);
	  //   echo "";
	  // }
	  $responce['length'] = count($responce);
	  echo json_encode($responce);
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
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<!-- Custom -->
<!-- 	<link rel="stylesheet" href="themes/custom_c_d.min.css" />
	<link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" /> -->
	<!-- End Custom -->
<!-- 	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>


	<script src="jquery_mobile.js"></script> -->


    <!-- JQuery Mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Custom -->
    <link rel="stylesheet" href="themes/custom_c_d.min.css" />
    <link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
    <!-- End Custom -->

	<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="kartik-v-bootstrap-star-rating-v4.0.1-30-ga779c97/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" rel="stylesheet"/>

    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="kartik-v-bootstrap-star-rating-v4.0.1-30-ga779c97/js/star-rating.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="js/shared_functions.js"></script>
	<!-- Tag system begin -->
	<script src="tagsystem/tags.js"></script>
	<!-- Tag system end -->
    <script src="js/jquery_mobile.js"></script>




<!--     <script src="jquery_mobile.js"></script> -->



	<title>Every Body Knows</title>

</head>
<body>

	<!-- Start of first page -->
	<div data-role="page" id="p1">

		<div data-role="header" >
		<button id = "info" class="ui-btn-right ui-btn ui-btn-d ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-info ui-btn-icon-notext">Info</button>
			<h1>E.K.W Search</h1>
<!-- 			        <a href="#" class="ui-btn-right ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-gear">Options</a>
 -->

			<!-- <a href="#" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-btn-b ui-mini">icon only button</a> -->


		</div><!-- /header -->


		<div role="main" class="ui-content" >
			<!--     <iframe src="https://www.google.com/search?q=shrimp+slavery&oq=shrimp+slavery&aqs=chrome..69i57.3895j0j8&sourceid=chrome&ie=UTF-8" width="480" height="320" seamless=""></iframe> -->

			<form id="searchForm" method="GET" action="engine.php" >
				<!-- <button data-role="none" style="width:30%">Button</button> -->
				<div class="ui-grid-b ui-responsive">
					<div class="ui-block-a">
					<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" >
				        <select  name="order-choice" id="order-choice" data-mini="true" data-theme="b" data-inline="true" style="color:gray">
				            <option value="DESC">Order</option>
							<option value="DESC">Good to Bad</option>
				            <option value="ASC">Bad to Good</option>
				        </select>
					</fieldset>

					</div>
					<div class="ui-block-b ">
						<input type="submit" value="Search">
					</div>
					<div class="ui-block-c"></div>
				</div>

				<input type="search" name="query" id="search" placeholder="Search..." required>
				<ul data-inset="true" data-role="listview" data-input="#search" id="search-autoComplete" class="ui-listview ui-listview-inset ui-corner-all ui-shadow"></ul>
				<!-- <input id = "mPresult-slider"  type="range" disabled="disabled" name="slider-fill" value="" min="0" max="5" step="1" data-highlight="true" data-theme="b" data-track-theme="b"> -->

								<!-- Practice with width percentage etc... -->
<!-- 				<div class="ui-grid-a">
				    <div class="ui-block-a" style="width:80%"><input type="search" name="query" id="search" placeholder="Search..." required></div>
				    <div class="ui-block-b" style="width:5%; height: 40px"> -->
						 <!-- <div  style="margin: 0px 0 0 0;"> -->
			        		<!-- <input type="submit" data-icon="search" > -->
			        		<!-- <input data-icon="search" data-iconpos="notext" value="Icon only" type="submit" value="Search"> -->
			        	 <!-- </div> -->
<!-- 				    </div>
				</div> -->

				<!-- <fieldset class="ui-grid-a"> -->
<!--                   <div class="ui-block-a" style="width:80%"><input type="search" name="query" id="search" placeholder="Search..." required></div>
                  <div class="ui-block-b" style="width:20%"><input type="submit" value="Submit" data-theme="a"></div> -->
			   <!-- </fieldset> -->

			   <div class="text-center" align="center">
			   		<input id="mPresult-stars" value=""  data-role="none" type="text" class="" data-min=0 data-max=5 data-step=0.1 data-size="md" data-show-caption="true" data-display-only="true">
        			<div class="clearfix"></div>
			   </div>
			</form>

<!--     			      <a href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-mini ui-shadow ui-btn-inline" data-rel="popup">Slide down</a>
      <div data-role="popup" id="transitionExample" class="ui-content" data-arrow="t" data-theme="a">
          <p>I'm a simple popup.</p>
      </div> -->

<!-- 			<a href="http://www.google.com/" onclick="window.open(this.href,'_system'); return false;">Google</a> -->


		</div><!-- /content -->

		<div data-role="footer" data-position="fixed">
			<h4>Page Footer</h4>
		</div><!-- /footer -->
	</div><!-- /page -->











	<!-- Start of second page -->
	<div data-role="page" id="pResults">

		<div id = "rH" data-role="header"  data-position="fixed">
			<h1>Result Page</h1>
			<button id = "toTop" onclick="$.mobile.silentScroll(0)" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-carat-u ui-btn-icon-notext">Top</button>
		</div><!-- /header -->

		<div id = "rMainTop" role="main" class="ui-content">

		</div><!-- /content -->

		<div data-role="footer" data-position="fixed">
			<h4>Result Page Footer</h4>
		</div><!-- /footer -->
	</div><!-- /page -->


	<!-- Start of add page -->
	<div data-role="page" id="pAdd" >

		<div data-role="header">
			<h1>Add to E.K.W</h1>
		</div><!-- /header -->

		<div role="main" class="ui-content">

			<!-- <form id="addForm" method="POST" data-ajax='false' action="tagsystem/search.php"> -->
			<form id="addForm" method="POST" action="add.php">
				<span style="color: red">*</span> = required fields
      	<input type="hidden" name="validation" value=<?php echo getenv('ADD_TOKEN');?>>
				<!-- <div class="ui-field-contain"> -->
				<div class="ui-field-contain">
				    <label for="title">Title: <span style="color: red">*</span></label>
				    <input type="text" name="title" id="title" data-clear-btn="true" required>
				</div>

<!-- 				<div class="ui-field-contain">
				    <label for="keywords">Keywords:</label>
				    <input type="text" name="keywords" id="keywords" data-clear-btn="true">
				</div> -->				

				<div class="ui-field-contain">
					<!-- <fieldset data-role="controlgroup" data-mini="true" >  -->
				    <label for="url">Url: <span style="color: red">*</span></label>
				     <!-- <legend style="font-family:sans-serif;font-size: 16px;max-width:0%; margin-bottom: 0px">Url:</legend> -->
				     <input  type="url" name="url" id="url"  data-clear-btn="true" required>
				    <!-- <input type="url" name="url" id="url" data-clear-btn="true" required pattern="https?://.+"> -->
				    <!-- <input type="url" name="url" id="url" data-clear-btn="true" required pattern="^(https?://)?([a-zA-Z0-9]([a-zA-ZäöüÄÖÜ0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$"> -->
				    <!-- <input type="text" id="titleKeyGen" data-theme="b" value="Generate Title and Some Tags " data-icon="recycle"> -->
					<!-- </fieldset> -->
				</div>

				<div class="ui-field-contain">
				    <label></label> <!-- (hack) Need to do this, so it will make the button move dynamicly like the other inputs -->
				    <button id="titleKeyGen" class="ui-shadow ui-btn ui-btn-b ui-corner-all ui-icon-recycle ui-btn-icon-right">Generate Title and Some Tags</button>
				</div>

        <div class="ui-field-contain">
				    <label for="tags">Who is being rated tags: <span style="color: red">*</span> <br/> (MAX = 10)</label>
				    <input type="text" name="tags-main-input" data-name="tags" id="tags" data-clear-btn="true" value="">
				</div>

				<div class="ui-field-contain">
				    <!-- <label for="slider-rating">Rating:</label> -->
<!-- 					<select name="slider-rating" id="slider-rating" data-role="slider">
						 <option value="0">Bad</option>
					    <option value="5">Good</option>
					</select> -->
				 	<fieldset data-role="controlgroup">
						<legend style="font-family:sans-serif;font-size: 16px;max-width:0%; margin-bottom: 0px">Rating:</legend>
			   			<input id="rating-star"  value="0"  data-role="none" type="text" class="" data-min=0 data-max=5 data-step=0.1 data-size="md">
        			<!-- <div class="clearfix"></div> -->
        			<div id="slider-div">
				    	<input type="range"  name="slider-rating" id="slider-rating" value="0" min="0" max="5" step=".1" data-highlight="true" data-theme="b" data-track-theme="b">
        			</div>

				    </fieldset>
				</div>

				<div class="ui-field-contain">
				    <label for="textarea">Description:</label>
					<textarea name="description" id="textarea"></textarea>
				</div>
				<div data-mini="true" data-role="controlgroup" data-type="horizontal">
					<input  type="submit" data-inline="true" value="Request Add" data-icon="plus">
					<input type="button" data-inline="true" value="Google" data-icon="search" id="googleButton">
					<!-- <input type="button" data-inline="true" value="Clear All" data-icon="delete" onclick="document.getElementById('addForm').reset();">				 -->
					<input  type="reset" data-inline="true" value="Clear All" data-icon="delete" id="addClearAll"  >
				</div>
			</form>

			<!-- <div id ="popup-area"> </div> --> <!-- for good or bad responce for add -->
<!-- 			<div data-role="popup" id="addPopupDiv" class="ui-content" data-theme="c" >
				<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
				<p id = "addStatus"></p>
			</div> -->

		</div><!-- /content -->

		<div data-role="footer" data-position="fixed">
			<h4>Page Footer</h4>
		</div><!-- /footer -->
	</div><!-- /page -->

<!-- 				<div id ="popup-area"> </div> --> <!-- for good or bad responce for add -->
<!-- 			<div data-role="popup" id="addPopupDiv" class="ui-content" data-theme="c" >
				<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
				<p id = "addStatus"></p>
			</div> -->



				<!-- <div id ="popup-area"> </div> --> <!-- for good or bad responce for add -->
				<!-- <div id ="popup-area" data-rel="popup" data-position-to="window"> </div>  --><!-- for good or bad responce for add -->

</body>
</html>
