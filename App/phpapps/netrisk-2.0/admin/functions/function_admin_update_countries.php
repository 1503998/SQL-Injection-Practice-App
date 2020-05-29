<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Admin Update Game Countries Function
	include(dirname(__FILE__) . '/../../includes/config.php');


	// GET FORM INPUT AND SANITIZE DATA
	$gid = $_POST['gid'];  //Hidden Field.  Can not be changed by user

	$ctid  = $_POST['ctid'];
	$cname  = $_POST['cname'];
	$armies  = $_POST['pterritory'];

	$num_territories = count($ctid);

	for($i=0; $i<$num_territories; $i++){
		$details = null;
		$details .= " parmies='$armies[$i]'";	
		//UPDATE GAME DATA	
		$query = mysql_query("UPDATE ". $mysql_prefix ."game_data SET {$details} WHERE gid = {$gid} AND pterritory = {$ctid[$i]} ");
	}

	header("Location: ../../index.php?p=edit&mode=countries&gid={$gid}");
?>
