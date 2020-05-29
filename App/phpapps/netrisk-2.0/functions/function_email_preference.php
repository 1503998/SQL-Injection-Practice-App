<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Start A New Game Function
	include('../includes/config.php');

	//Get form POST data
	$notify = $_POST['notify'];

	//Update the Current Game
	$query = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pmail = {$notify} WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ") or die(mysql_error()); 	

	//If Checked All Games, Update Email Settings of all Games
	if(isset($_POST['all_mail'])){
		$query = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pmail = {$notify} WHERE pname = '{$_SESSION['username']}' ") or die(mysql_error()); 	
	}

	header("Location: ../index.php?p=game&id={$_SESSION['gid']}&display=preferences");
?>
