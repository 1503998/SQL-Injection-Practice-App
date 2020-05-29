<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
	include('../includes/config.php');

	//Get the Form Data
	$armies = $_POST['armies'];
	$from_country_tid = $_POST['fromstate'];
	$to_country_tid = $_POST['tostate'];
	
	//Check to make sure user submitted data
	if($_POST['fromstate'] == 'invalid' || $_POST['tostate'] == 'invalid'){
		game_error_header("You did not select a From and To Country.");
		exit;
	}
	
	if($_POST['fromstate'] == $_POST['tostate']){
		game_error_header("You can not fortify to the same country.");
		exit;
	}
	
	if($_POST['armies'] == 0 || $_POST['armies'] == ''){
		game_error_header("You can not fortify 0 armies.");
		exit;
	}
	
	
	//Get the Host Country Data
	$query1 = mysql_query("SELECT gd.gid, gd.pid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name, plyr.gid, plyr.pid, plyr.pcolor FROM ". $mysql_prefix ."countries cty, ". $mysql_prefix ."game_data gd, ". $mysql_prefix ."game_players plyr WHERE gd.gid = {$_SESSION['gid']} AND gd.gid = plyr.gid AND cty.mtype = '{$_SESSION['maptype']}' AND gd.pid = plyr.pid AND gd.pterritory = {$from_country_tid} AND gd.pterritory = cty.id") or die(mysql_error());
	$query1_row = mysql_fetch_assoc($query1);
	$pid = $query1_row['pid'];
	$from_name = $query1_row['name'];
	$pcolor = $query1_row['pcolor'];
	$host_armies = $query1_row['parmies'];
	$max = $host_armies - 1;
	$new_host_armies = $host_armies - $armies;

	//Get Armies from the Receiving Country
	$query2 = mysql_query("SELECT gd.gid, gd.pid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM ". $mysql_prefix ."countries cty, ". $mysql_prefix ."game_data gd WHERE gd.gid = {$_SESSION['gid']} AND cty.mtype = '{$_SESSION['maptype']}' AND gd. pterritory = {$to_country_tid} AND gd.pterritory = cty.id") or die(mysql_error());
	$query2_row = mysql_fetch_assoc($query2);
	$to_name = $query2_row['name'];
	$old_armies = $query2_row['parmies'];
	$new_armies = $old_armies + $armies;
	

	//Check to ensure player is leaving at least 1 army on country
	if($armies > $max || $new_host_armies < 1 ){
		game_error_header("You must leave at least 1 army behind.");
		exit;
	}

	//Check to Make sure countries are adjacent to each other
	$query3 = mysql_query("SELECT * FROM ". $mysql_prefix ."countries WHERE mtype = '{$_SESSION['maptype']}' AND id = {$from_country_tid} ") or die(mysql_error()); 
	$query3_row = mysql_fetch_assoc($query3);
	$from_name = $query3_row['name'];
	$adjacency = $query3_row['adjacencies'];
	$adjacencies = explode(",", $adjacency);
	if(!in_array($to_country_tid, $adjacencies)){
		game_error_header("Those countries are not adjacent.");
		exit;
	} else {
		//Transfer the Armies
		$query3 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET parmies = {$new_host_armies} WHERE gid = {$_SESSION['gid']} AND pterritory = {$from_country_tid} ") or die(mysql_error()); 
		$query3 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET parmies = {$new_armies} WHERE gid = {$_SESSION['gid']} AND pterritory = {$to_country_tid} ") or die(mysql_error()); 
	}

	//Update the Game log
	$_SESSION['armies'] = $armies;
	$_SESSION['from_country'] = $from_name;
	$_SESSION['pcolor'] = $pcolor;
	$_SESSION['to_country'] = $to_name;

	$ginfo = array($_SESSION['gid'], $_SESSION['gname']);
	$pinfo = array($_SESSION['username'], 'fortifying', $pcolor);

	require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
	game_log('fortify', $ginfo, $pinfo);

	//Change the player state, possibly allow multiple unlimited fortifys
	header("Location: ./function_nextstatus.php");

?>
