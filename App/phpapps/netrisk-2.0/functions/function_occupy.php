<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('../includes/config.php');

	//Get the Form Data
	$armies = $_POST['armies'];
	$min = $_POST['min_occupy'];
	$max = $_POST['max_occupy'];
	$from_country_tid = $_POST['from_id'];
	$to_country_tid = $_POST['to_id'];

	//Check to ensure player is transferring at least the minimum number of armies
	if($armies < $min && $max > $armies){
		game_error_header("You need to transfer a minimum of $min armies.");
		exit;
	}

	//Get Armies for From Country and Update
	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_data WHERE gid = {$_SESSION['gid']} AND pterritory = {$from_country_tid} ") or die(mysql_error());
	$query1_row = mysql_fetch_assoc($query1);
	$pid = $query1_row['pid'];
	$host_armies = $query1_row['parmies'];
	$new_host_armies = $host_armies - $armies;
	
	//Confirm Player is leaving at least 1 army behind.
	if($new_host_armies < 1){
		game_error_header("You need to leave at least 1 army behind.");
		exit;
	}

	$query2 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET parmies = {$new_host_armies} WHERE gid = {$_SESSION['gid']} AND pterritory = {$from_country_tid} ") or die(mysql_error()); 

	//Add the Armies to the newly conquered country
	$query3 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET parmies = {$armies} WHERE gid = {$_SESSION['gid']} AND pterritory = {$to_country_tid} ") or die(mysql_error()); 
	
	//Check for Player Dead, # of Cards, and which State to Force Trade if needed
	check_dead_player($_SESSION['gid'], $_SESSION['gname'], $_SESSION['pcolor'], $_SESSION['attack_id'], $_SESSION['attack_name'], $_SESSION['opposing_id'], $_SESSION['opposing_player']);

	header("Location: ../index.php?p=game&id={$_SESSION['gid']}&display=status");
?>
