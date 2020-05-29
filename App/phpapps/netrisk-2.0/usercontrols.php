<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
  
	//Setup User Controls to get the appropriate form based on the players Game State
  	include('./config.php');
  
  	//Check if a Vote was started to Kick a player
  	$query_kick = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pvote != 0 ");
  	$kick = mysql_num_rows($query_kick);
  
  	//Set vote to 1
  	$pvote = 1;
  	if($kick > 0){
		//Vote was stated, check if player voted
	  	$query_vote = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ");
  	  	$qvote = mysql_fetch_assoc($query_vote);
  	  	$pvote = $qvote['pvote'];
  	}
	  
  	$display = $_GET['display'];
  	
  	if ($display == 'options') {
  		require_once ('options.php');
  	} else if ($display == 'concede') {
  		require_once ('concede.php');
  	} else if ($display == 'kick') {
  		require_once ('kick_start.php');
  	} else if ($display == 'preferences') {
  		require_once ('preferences.php');	
  	} else if ($pvote < 1){  //Less than 1, so must be they have not voted yet
  		require_once ('kick_vote.php');	
  	} else {
  		//Get the Player State
  		$sql = "Select * FROM ". $mysql_prefix . "game_players WHERE pname = '".$_SESSION['player_name']."' AND gid = {$_SESSION['gid']} ";
  		$query = mysql_query($sql);
  		while ($row = mysql_fetch_assoc($query)){
 			$pstate = $row['pstate'];
 		
 			if($pstate != 'attacking' && $pstate != 'occupy'){
	 			 unset($_SESSION['attack_rolls']);
	 		 	unset($_SESSION['defensd_rolls']);
 		 	}
 	
	 		switch($pstate){
			 	case 'waiting': 	require_once ('waiting.php');
									break;
				case 'initial': 	require_once ('placing.php');
									break;
				case 'capital': 	require_once ('place_capital.php');
									break;		
				case 'inactive': 	require_once ('inactive.php');
									break;	
				case 'trading': 	require_once ('trading.php');
									break;
				case 'placing': 	require_once ('placing.php');
									break;
				case 'attacking': 	require_once ('attacking.php');
									break;
				case 'occupy': 		require_once ('occupy.php');
									break;
				case 'fortifying': 	require_once ('fortifying.php');
									break;
				case 'forcetrade': 	require_once ('trading.php');
									break;
				case 'forceplace': 	require_once ('placing.php');
									break;
				case 'winner': 		require_once ('delete_game.php');
									break;
			}
		}
 	}

?>
 
 