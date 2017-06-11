<!-- <html>
<body>

<form action="welcome.php" method="post">
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
<input type="submit">
</form>

</body>
</html>  -->
<?php 
		$token = getenv('ADD_TOKEN');
			echo 
				// '<form method="POST" action="https://everybodyknows.herokuapp.com/add.php?validate='.$token.'" >
				'<form method="POST" action="http://localhost/add.php?validate='.$token.'" >
				    Title:<br>
				    <input type="text" name="title" value="'.$_POST['title'].'" ><br>
				    Keywords:<br>
				    <input type="text" name="keywords" value="'.$_POST['keywords'].'"><br>
				    Url:<br>
				    <input  name="url" value="'.$_POST['url'].'" > <br>
				    Rating: (Bad = 0 / Good = 5 )<br>
				    <input type="text" name="slider-rating" value="'.$_POST['slider-rating'].'" ><br>
				    Description:<br>
					<textarea name="textarea" rows="10" cols="90" >'.$_POST['textarea'].'</textarea><br>
				    <input type="submit" value="Final Add" style = "padding: 25px 50px">
			    </form>
			    ';

 ?>