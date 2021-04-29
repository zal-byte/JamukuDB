<?php

	function curl(){
		$param = array("Request"=>"buyProduct","ProdID"=>"17","ProdImage"=>"testo.jpg","Quantity"=>"46","Address"=>"Indonesia","PUsername"=>"test");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://localhost/jamuku/jamukudb/index.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);

		// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
		$output = curl_exec($ch);
		curl_close($ch);
		print_r($output);

	}
	curl();
	// date_default_timezone_set("Asia/Jakarta");
	// $cron_day = date("d");
	// $product_day = "2020-02-30";
	// $ex = explode("-", $product_day);
	// // echo $ex[2]." / ".$cron_day;
	// if( ( $ex[2] % $cron_day ) >= 1 ){
	// 	echo $ex[2] % $cron_day."\n";
	// }
?>