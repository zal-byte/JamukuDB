<?php
	date_default_timezone_set("Asia/Jakarta");
	$file = fopen("cronjob.txt", "a");
	$append = "[ ".date("h:i:sa")." ]\n[ Data ] : ".uniqid()."\n";
	fwrite($file, $append);
	fclose($file);

?>