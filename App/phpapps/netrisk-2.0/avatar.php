<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
	include_once('./includes/config.php');

	// get the image from the db
	$sql = "SELECT avatar, image_type FROM ". $mysql_prefix . "users WHERE id='".$_GET['image_id']."'";

	// the result of the query
	$result = mysql_query("$sql") or die("Invalid query: " . mysql_error());

	// set the header for the image
	$row = mysql_fetch_array($result);
   		
	header("Content-type: ".$row['image_type']);    
	echo $row['avatar'];  
?>