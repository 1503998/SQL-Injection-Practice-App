<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('../includes/config.php');

	// GET GAME SETTINGS
	$gname = mysql_real_escape_string($_POST['gname']);
	$GamePass = mysql_real_escape_string($_POST['gpass']); // can correctly eval gamepass as true or false since '' is false
	if($GamePass) $gpass = md5($GamePass); // encode it otherwise do nothing
	$gstate = 'Waiting'; //Default, Can only be Initial, Placing, Playing, Finished
	$gmode = 'Individual'; //Static for now, to update for team play in the future
	$gtype = 'Domination'; //Static for now, to update for mission and captial
	$unit_type = $_POST['unit_type'];
	$blind_man = 0;  //Static PlaceHolder for Future Implementation
	$players = 1; //Default is 1 for the Game Creator
	$capacity = $_POST['num_players']; 
	$kibitz = $_POST['kibitz']; if($kibitz != 1) $kibitz = 0; // unchecked so make it false
	$card_rules = $_POST['card_rules'];
	$trade_value = 4; //Default for US card rules
	$map_type = $_POST['map_type'];
	$css_style = 'org'; //Default for now.  To add to form later
	$last_move = time();
	$time_limit = $_POST['time_limit'];
	$custom_rules = ''; //Default for now, To add to form later
	$invites = array($_POST['player1'], $_POST['player2'],$_POST['player3'],$_POST['player4'],$_POST['player5'],$_POST['player6'],$_POST['player7'],$_POST['player8']);

	$mailupdates = $_POST['mailupdates']; if($mailupdates != 1) $mailupdates = 0; // unchecked so make it false

	//$manualplace = $_POST['manualplace']; if($manualplace != 1) $manualplace = 0; // checked so make it true

	//Get the gid from the id_seq to ensure the GID's match everywhere, maybe put this in the config file to eliminate an extra table
	$gid = get_next_gid();

	//Insert into game_info table, $gid should auto increment
	$query1 = mysql_query("INSERT INTO ". $mysql_prefix ."game_info (gid, gname, gpass, gstate, gmode, gtype, unit_type, blind_man, players, capacity, kibitz, card_rules, trade_value, map_type, css_style, last_move, time_limit, custom_rules) VALUES ($gid, '{$gname}', '{$gpass}', '{$gstate}', '{$gmode}', '{$gtype}', $unit_type, $blind_man, $players, $capacity, $kibitz, '{$card_rules}', $trade_value, '{$map_type}', '{$css_style}', $last_move, $time_limit, '{$custom_rules}') ") or die(mysql_error());

	//Insert into game_chat, id should autoincrement here.
	$player = $_POST['player'];
	$pcolor = $_POST['pcolor'];
	$name = 'Host';
	$text = 'Game Has not started, be first to post.';


	$query2 = mysql_query("INSERT INTO ". $mysql_prefix ."game_chat (time, gid, gname, name, text) VALUES ($last_move, $gid, '{$gname}', '{$name}', '{$text}') ") or die(mysql_error());

	//Insert into game_players
	$query3 = mysql_query("INSERT INTO ". $mysql_prefix ."game_players (gid, gname, pid, pname, phost, pcolor, pstate, pmail) VALUES ($gid, '{$gname}', 1, '{$player}', 1, '{$pcolor}', 'waiting', $mailupdates) ") or die(mysql_error());

	//Send Game Invites to each player
	foreach($invites as $invite){
	
		$EmailTo = "{$invite}";
		$EmailFrom = "WebMaster@{$_SERVER['SERVER_NAME']}";
		$EMailFromName = "Webmaster";
		$EMailSubject = "NetRisk Game Invite";
		$EmailMessage = "You have been invited by {$player} to play in the NetRisk Game: {$gname}. <br /><br />";
		if($_POST['gpass']){
			$EmailMessage .= "This is a password protected game.  The password to join is: {$GamePass}. <br /><br />";
		}
		
		$Glink = "<a href=\"{$_SERVER['SERVER_NAME']}/{$game_path}/login.php?id={$gid} \">Here</a>";
		$EmailMessage .= "You can join the game by clicking on the following link: {$Glink}. <br />";

		SendEmail($EmailTo,$EmailFrom,$EmailFromName,$EMailSubject,$EmailMessage);  //Common Function
	}

	//Add First Entry to Game Log
	$text = "<strong>The Game has not started</strong><hr/>";
	$query4 = mysql_query("INSERT INTO ". $mysql_prefix ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $last_move, '{$text}') ") or die(mysql_error());

	header("Location: ../index.php?p=browser");
?>