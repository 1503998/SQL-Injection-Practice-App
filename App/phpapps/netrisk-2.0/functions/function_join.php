<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Get the Form Data
	include('../includes/config.php');

	// GET GAME SETTINGS
	$gid = $_SESSION['gid'];
	$gname = mysql_real_escape_string($_SESSION['gname']);

	//Check for Password Protected Game
	if($_SESSION['gpass'] != ''){
		//Game is Locked/Password Protected
		//Get Password
		$pass = mysql_real_escape_string($_POST['password']);
		$password = md5($pass);
		if($password != $_SESSION['gpass']){
			join_error_header("Incorrect Game Password.");
			exit;
		}
	}

	if($_POST['pcolor'] == ''){
		//Color not submitted
		join_error_header("Please chose a player color.");
		exit;
	}
	
	$mailupdates = $_POST['mailupdates']; if($mailupdates != 1) $mailupdates = 0; // unchecked so make it false

	//Need to get total rows for PID, or add to Join and use in hidden
	$next_pid = mysql_real_escape_string($_POST['npid']);
	$player = mysql_real_escape_string($_POST['player']);
	$pcolor = mysql_real_escape_string($_POST['pcolor']);

	//Insert into game_players
	$query1 = mysql_query("INSERT INTO ". $mysql_prefix ."game_players (gid, gname, pid, pname, pcolor, pstate, pmail) VALUES ($gid, '{$gname}', $next_pid, '{$player}', '{$pcolor}', 'waiting', $mailupdates) ") or die(mysql_error());

	//Update game players for player added
	$query1 = mysql_query("UPDATE ". $mysql_prefix ."game_info SET players = players + 1 WHERE gid = {$gid} ") or die(mysql_error());

	//AutoStart the Game if it is full
	$query2 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = $gid ") or die(mysql_error());
	$num_players = mysql_num_rows($query2);
	if($num_players == $_SESSION['capacity']){
		require ('./function_startgame.php');
	} else {
		header("Location: ../index.php?p=game&id=$gid");
	}
?>
