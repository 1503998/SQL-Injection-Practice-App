<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	 
	include('../includes/config.php');

	//Get the armies and Country from the form
	$army = $_POST['armies'];
	$country_tid = $_POST['state'];
	$gid = $_POST['GID'];  // Get This from Session?

	//Need to add a check to ensure the user posted a number only, and not a letter, etc.

	// Check to make sure a country was submitted
	if($country_tid == 'invalid') {
		game_error_header("You did not select a country.");
		exit;
	}

	//Get the current armies of the country
	$query1 = mysql_query("SELECT gd.gid, gd.pid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM ". $mysql_prefix ."countries cty, ". $mysql_prefix ."game_data gd WHERE gd.gid = {$_SESSION['gid']} AND cty.mtype = '{$_SESSION['maptype']}' AND gd. pterritory = {$country_tid} AND gd.pterritory = cty.id") or die(mysql_error());
	$query1_row = mysql_fetch_assoc($query1);
	$pid = $query1_row['pid'];
	$cname = $query1_row['name'];
	$oldarmy = $query1_row['parmies'];

	//Add the army and update the Country
	$newarmies = $oldarmy + $army;
	$query2 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET parmies = {$newarmies} WHERE gid = $gid AND pterritory = {$country_tid} ");

	//Remove the Armies from the Player
	$query3 = mysql_query("SELECT pnumarmy, pcolor FROM ". $mysql_prefix ."game_players WHERE pname = '".$_SESSION['username']."' AND gid = $gid ");
	while($row3 = mysql_fetch_assoc($query3)){
		$pcolor = $row3['pcolor'];
		$remaining = $row3['pnumarmy'];
		$update = $remaining - $army;
	
		$query4 = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pnumarmy = {$update} WHERE pname = '".$_SESSION['username']."' AND gid = $gid ");
	}

	//Update the Game Log
	$_SESSION['armies'] = $army;
	$_SESSION['place_country'] = $cname;

	$ginfo = array($gid, $_SESSION['gname']);
	$pinfo = array($_SESSION['username'], 'placing', $pcolor);

	require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
	game_log('addarms', $ginfo, $pinfo);

	//If all Players have placed their Initial armies, Randomly Start the Game
	$gstate = $_SESSION['gstate'];
	if($gstate == 'Initial' && $update <= 0){
		//First Move Current Player to an inactive state
		$query5 = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pstate = 'inactive' WHERE pname = '".$_SESSION['username']."' AND gid = $gid ");
		//Next Count all of the Players still in initial state
		$query6 = mysql_query("SELECT pstate FROM ". $mysql_prefix ."game_players WHERE gid = $gid AND pstate = 'initial' ");
		$row_count = mysql_num_rows($query6);
		if($row_count == 0){
			//All Players have placed, so start the game
			random_start($_SESSION['gid']);
		}
		header("Location: ../index.php?p=game&id=$gid");		
	} else if($update <= 0){
		require('./function_nextstatus.php');
	} else {
		header("Location: ../index.php?p=game&id=$gid");
	}
?>