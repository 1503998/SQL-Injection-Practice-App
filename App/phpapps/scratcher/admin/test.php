<?php
		
		$str=dirname($_SERVER['PHP_SELF']);
		$array=explode("/",$str);
		$count=count($array);
		echo $array[$count-2];
		
?>