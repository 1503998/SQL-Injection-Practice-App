<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Start A New Game Function
	include('../includes/config.php');

	//Query Player and Ensure Player State is inactive and not already Dead
	$query = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ");
	$query_row = mysql_fetch_assoc($query);
	$pid = $query_row['pid'];
	$pname = $query_row['pname'];
	$pcolor = $query_row['pcolor'];
	$pstate = $query_row['pstate'];

	//Make Sure its not their turn
	if($pstate != 'inactive' && $pstate != 'dead'){
		game_error_header("You can not conceded during your turn");
		exit;
	}

	//Make Sure Player is not already dead
	if($pstate == 'dead'){
		game_error_header("You are already dead.  You can not concede");
		exit;
	}

	//Confirm Player checked the check box
	if(!isset($_POST['concede'])){
		game_error_header("You did not check the concede button");
		exit;
	} else {
		//Set Player to Dead.  Use a Function?
   		$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pstate = 'dead' WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ") or die(mysql_error()); 	
	
   		//Update Player Rankings Info.  All Ranking Should Post to player AFTER they are dead or Game is Complete
			
		//Get Points of the Defeated
		$querydead = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pstate = 'dead' ") or die(mysql_error());
		$num_dead = mysql_num_rows($querydead);
	
		//Set Points Finish of the Defeated player
		$querypoints = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET ppoints = {$num_dead} WHERE pname = '{$_SESSION['username']}' ") or die(mysql_error());
			
		//Get Kills of Defeated Player
		$querykills = mysql_query("SELECT pkills FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ") or die(mysql_error());
		$qkills = mysql_fetch_assoc($querykills);
		$kills = $qkills['pkills'];
			
		//Add 1 Gamed Played 1 Loss, Points, Kill, and Total Players to the Defeated Player
		$qupdate = mysql_query("UPDATE ". $mysql_prefix ."users SET games_played = games_played + 1, loss = loss + 1, points = points + {$num_dead}, kills = kills + {$kills}, total_players = total_players + {$_SESSION['players']}  WHERE login = '{$_SESSION['username']}' ") or die(mysql_error());
   	
   		//Update the Game Log
   		$ginfo = array($_SESSION['gid'], $_SESSION['gname']);
		$pinfo = array($_SESSION['username'], 'dead', $pcolor);

		require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
		game_log('concede', $ginfo, $pinfo);
	}
	
	//Delete Any Missions
	//Delete Any Capitals

	header("Location: ../index.php?p=game&id={$_SESSION['gid']}&display=status");
?>
