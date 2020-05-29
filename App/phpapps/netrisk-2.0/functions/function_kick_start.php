<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Start A New Game Function
	include('../includes/config.php');
	//Get form variables
	$kickpid = $_POST['kickpid'];

	//Update the Player that voted, and add 1 to the Player to be kicked.
	$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pkick = 1 WHERE gid = {$_SESSION['gid']} AND pid = {$kickpid} ") or die(mysql_error()); 	
	$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pvote = 1 WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ") or die(mysql_error()); 	
	
	header("Location: ../index.php?p=game&id={$_SESSION['gid']}&display=status");
?>
