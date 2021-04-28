<?php
	
	$cron_hari = 13;
	$hari_beli = 12;

	if(($cron_hari % $hari_beli) == 1){
		echo "OKE";
	}else{
		echo "TIDAK";
	}

?>