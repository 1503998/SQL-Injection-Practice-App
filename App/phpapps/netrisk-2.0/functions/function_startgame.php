<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Start A New Game Function
	include('../includes/config.php');


	//Get all the Players in the Game
	$query2 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} ") or die(mysql_error());
	$num_players = mysql_num_rows($query2);
	while($row = mysql_fetch_assoc($query2)){
		$gplayers[] = $row;
	}

	//Need to First Ensure there are at least 2 players in the Game
	if($num_players < 2){
		game_error_header("You need at least 2 players to start the game.");
		exit;
	}

	//Will Need to Get the Number of countries in the game, and 'Shuffle the Deck'
	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix ."countries WHERE mtype = '{$_SESSION[maptype]}' ") or die(mysql_error());
	$total_countries = mysql_num_rows($query1);
	$countries = range(1, $total_countries);
	shuffle($countries);

	//Deal the Cards to Each Player.   Need to Loop p 1 through X number of Players
	$current_player = 0;
	foreach($countries as $card)
	{    
		$pid = $gplayers[$current_player]['pid'];
		$pname = $gplayers[$current_player]['pname'];
		$query3 = mysql_query("INSERT INTO ". $mysql_prefix ."game_data (gid, gname, pid, pname, pterritory, parmies) VALUES ({$_SESSION['gid']}, '{$_SESSION['gname']}', $pid, '{$pname}', $card, 1) ") or die(mysql_error());

   		$current_player++;

   		if ($current_player == sizeof($gplayers))
      		$current_player = 0;
	}

	//Update the Game State
	$query4 = mysql_query("UPDATE ". $mysql_prefix ."game_info SET gstate = 'Initial' WHERE gid = {$_SESSION['gid']}") or die(mysql_error()); 	


	//Compute the Starting Armies for each Player
	$numplayers = sizeof($gplayers);
	switch($numplayers){
		case 2:  $armies = 40; break;
		case 3:  $armies = 35; break;
		case 4:  $armies = 30; break;
		case 5:  $armies = 25; break;
		default:  $armies = 20; break; // for 6, 7, 8 and beyond...
	}

	foreach($gplayers as $player){
		$query5 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_data WHERE gid = {$_SESSION['gid']} AND pid = '{$player['pid']}' ") or die(mysql_error());
		$num_countries = mysql_num_rows($query5);
		$newarmies = $armies - $num_countries;
		$query6 = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pstate = 'initial', pnumarmy = {$newarmies} WHERE gid = {$_SESSION['gid']} AND pid = '{$player['pid']}' ") or die(mysql_error()); 	
		}
		
	//Send Emails to each player on game start
	
	header("Location: ../index.php?p=game&id={$_SESSION['gid']}");
?>
