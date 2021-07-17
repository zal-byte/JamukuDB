<?php
	include 'lib/image.php';
	$img = Image::getInstance();
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="zappan">
	<meta name="keyword" content="Jamuku">
	<meta name="description" content="View of jamuku">
	
	<title>View Of Jamuku</title>
</head>
<body>
<form method="post" action="" enctype="multipart/form-data">
	<p> ProdName </p>
	<input type="text" name="ProdName"><br/>
	<p> ProdDesc </p>
	<input type="text" name="ProdDesc"><br/>
	<p> ProdWeight </p>
	<input type="text" name="ProdWeight"><br/>
	<p> ProdPrice </p>
	<input type="text" name="ProdPrice"><br/>
	<p> ProdQuantity </p>
	<input type="text" name="ProdQuantity"><br/>
	<p> ProdPict </p>
	<input type="file" name="ProdPict"><br/>
	<input type="text" name="Request" value="newProduct" hidden="true">

	<hr>
	<input type="submit" name="submit">
</form>
</body>
</html>

<?php

	if(strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
		if(isset($_POST['submit'])){
			$ProdName = $_POST['ProdName'];
			$ProdDesc = $_POST['ProdDesc'];
			$ProdWeight = $_POST['ProdWeight'];
			$ProdPrice = $_POST['ProdPrice'];
			$ProdQuantity = $_POST['ProdQuantity'];
			$ProdPict = $_FILES['ProdPict'];

			$handler = fopen($ProdPict['tmp_name'], 'rb');
			$contents = stream_get_contents($handler);
			fclose($handler);
			$based = base64_encode($contents);

			$data = array('imageData'=>base64_decode($based),'filename'=>$ProdPict['name']);
			$img->ImageQuality(null, Image::JPG_MIDDLE, $data);

			echo "<pre>";
			print_r($_POST);
			echo "<hr>";
			print_r($_FILES);
			echo "</pre>";
		}
	}

?>