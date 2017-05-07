
<?php 

	if (isset($_GET['searchInput'],$_GET['rating'])){
	// if (isset($_GET['searchInput']) && isset($_GET['rating'])){
		$searchInput = $_GET['searchInput'];
		$ratingInput = $_GET['rating'];
			// echo $searchInput . "<br>";
			// echo $ratingInput; 
		$data = array('searchI' => $searchInput,'rate' => $ratingInput); 
			// echo 
			// "<script>
   //  				$('#page1').css('background', 'rgb($ratingInput,0,0)');
			// </script>";
			echo json_encode($data);
		// print_r($_GET);
	}

?>




