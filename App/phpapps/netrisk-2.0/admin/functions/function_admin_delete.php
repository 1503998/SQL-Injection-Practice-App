<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	 
	include(dirname(__FILE__) . '/../../includes/config.php');

	// GET GAME ID
	$gid = $_GET['gid'];

	//Delete the Game Info
	$query1 = mysql_query("DELETE FROM ". $mysql_prefix ."game_info WHERE gid = {$gid} ") or die(mysql_error());
	$query2 = mysql_query("DELETE FROM ". $mysql_prefix ."game_players WHERE gid = {$gid} ") or die(mysql_error());
	$query3 = mysql_query("DELETE FROM ". $mysql_prefix ."game_data WHERE gid = {$gid} ") or die(mysql_error());
	$query4 = mysql_query("DELETE FROM ". $mysql_prefix ."game_log WHERE gid = {$gid} ") or die(mysql_error());
	$query5 = mysql_query("DELETE FROM ". $mysql_prefix ."game_chat WHERE gid = {$gid} ") or die(mysql_error());
	$query6 = mysql_query("DELETE FROM ". $mysql_prefix ."game_cards WHERE gid = {$gid} ") or die(mysql_error());

	header("Location: ../index.php?p=admin");
?>