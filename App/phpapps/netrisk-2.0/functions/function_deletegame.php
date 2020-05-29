<?php

	/**************************************************	

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Start A New Game Function
	include('../includes/config.php');

	$game_id = $_SESSION['gid'];

	//Ensure the Game is Finished
	$queryfinish = mysql_query("SELECT gstate FROM ". $mysql_prefix ."game_info WHERE gid = {$game_id} ") or die(mysql_error());
	$queryf = mysql_fetch_assoc($queryfinish);
	$finish = $queryf['gstate'];  

	if($finish != 'Finished') {
		game_error_header("The Game is not Finished.");
		exit;
	}

	//Ensure the Game Has a Winner
	$querywinner = mysql_query("SELECT pstate FROM ". $mysql_prefix ."game_players WHERE gid = {$game_id} AND pstate = 'winner' ") or die(mysql_error());
	$queryw = mysql_fetch_assoc($querywinner);
	$winner = $queryw['pstate'];  

	if($winner != 'winner') {
		game_error_header("The Game does not have a winner.");
		exit;
	}

	//Delete the Game Info
	$query = mysql_query("DELETE FROM ". $mysql_prefix ."game_info WHERE gid = {$game_id} ") or die(mysql_error());
	$query = mysql_query("DELETE FROM ". $mysql_prefix ."game_players WHERE gid = {$game_id} ") or die(mysql_error());
	$query = mysql_query("DELETE FROM ". $mysql_prefix ."game_data WHERE gid = {$game_id} ") or die(mysql_error());
	$query = mysql_query("DELETE FROM ". $mysql_prefix ."game_log WHERE gid = {$game_id} ") or die(mysql_error());
	$query = mysql_query("DELETE FROM ". $mysql_prefix ."game_chat WHERE gid = {$game_id} ") or die(mysql_error());
	$query = mysql_query("DELETE FROM ". $mysql_prefix ."cards WHERE gid = {$game_id} ") or die(mysql_error());

	//Delete Any Missions
	//Delete Any Capitals

	header("Location: ../index.php?p=browser");
?>
