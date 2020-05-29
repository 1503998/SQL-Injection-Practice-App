<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Start A New Game Function
	include('../includes/config.php');
	//Get form variables
	$kickvote = $_POST['kickvote'];
	$kickpid = $_POST['kickpid'];

  	//Update Player so they can onl vote once, and update player being voted/kicked
  	if($kickvote == 1){
  		$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pkick = pkick + 1 WHERE gid = {$_SESSION['gid']} AND pid = {$kickpid} ") or die(mysql_error()); 	
  	}
  	
  	$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pvote = 1 WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ") or die(mysql_error()); 	

  	//Now Check for latest Poll Results.  Need ALL Players to decide to kick?  Or Maybe 2/3 Vote?
  	$query_votes = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pid = {$kickpid} ");
  	$qvotes = mysql_fetch_assoc($query_votes);
  	$votes = $qvotes['pkicks'];  //Check the number Kick Votes the Player received
  
  	//Compare the total Kick Votes to the total Players
  	$votes_needed = $_SESSION['players'] - 1;  //Minus 1 because assumes player will vote not kick themselves
  
  	if($votes >= $votes_needed){
		//Kill the Player
	  	$query_dead = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pstate = 'dead' WHERE gid = {$_SESSION['gid']} AND pid = {$kickpid} ") or die(mysql_error()); 	
	  	//Update their Stats?	  
	  
	  	//Reset all Votes and Kicks back to 0 to allow another vote.
	  	$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pkick = 0, pvote = 0 WHERE gid = {$_SESSION['gid']} ") or die(mysql_error()); 	
  	}

	header("Location: ../index.php?p=game&id={$_SESSION['gid']}&display=status");
?>
