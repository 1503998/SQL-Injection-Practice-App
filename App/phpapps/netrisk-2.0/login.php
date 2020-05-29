<?php

	/**************************************************

		Project	NetRisk <http://netrisk.sourceforge.net>
		Author	PMuldoon <ptmuldoon@gmail.com>
		License	GPL

	 **************************************************/ 
	 
	include('./includes/config.php');
	
	session_start();
	$id = $_GET['id'];
	
	//Get Game Information, Game ID, etc. 
	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix . "game_info WHERE gid = {$id} ") or die(mysql_error()); 
	$query1_row = mysql_fetch_assoc($query1);
	
	//Maybe put all the session Data into 1 Big Array using foreach?
	$_SESSION['gid'] = $id;
	$_SESSION['gname'] = $query1_row['gname'];
	$_SESSION['cardrules'] = $query1_row['card_rules'];
	$_SESSION['blindman'] = $query1_row['blindMan'];
	$_SESSION['maptype'] = $query1_row['map_type']; 
	$_SESSION['css_style'] = $query1_row['css_style'];
	$_SESSION['unit_type'] = $query1_row['unit_type'];
	$_SESSION['gstate'] = $query1_row['gstate'];
	$_SESSION['gpass'] = $query1_row['gpass'];
	$_SESSION['players'] =  $query1_row['players'];
	$_SESSION['capacity'] =  $query1_row['capacity'];
	$_SESSION['trade_value'] =  $query1_row['trade_value'];

	//Make Sure the Player is Logged in
	if(!$_SESSION['username']){
		browser_error_header("You need to login before playing.");
		exit;
	} else {
		//They are logged In, So Check if they are a player in the game.
		$_SESSION['player_name'] = $_SESSION['username'];
		
		$query2 = mysql_query("SELECT * FROM ". $mysql_prefix . "game_players WHERE gid = {$id} ") or die(mysql_error()); 
		$num_players = mysql_num_rows($query2);
		
		while($row = mysql_fetch_assoc($query2)){
		$zplayers[] = $row['pname'];
		}
		
		if (in_array($_SESSION['username'], $zplayers)) {
			// Player is in the game, direct to the game

			header("Location: index.php?p=game&id=$id");
			
		} elseif ($num_players < $_SESSION['capacity'] && $_SESSION['gstate'] == 'Waiting') {
			//Game is full, So indicate Player Can not join it, and to view Kibitz if Game is not Locked?
			header("Location: index.php?p=join");
			//Not a Player in the game, so go to join screen.
			
		} else {
			header("Location: index.php?p=game&id=$id");
			//browser_error_header("Sorry, Game is Full or has already started. Use Kibtz to view game.");
			exit;
		}	
		
	}  
?>