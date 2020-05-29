<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include(dirname(__FILE__) . '/../includes/config.php');

	//Get the Attacking Player, Country and Armies
	$attacking_armies = $_POST['armies']; // need to do number check
	$from_country_info = explode(",", $_POST['fromstate']);
	$from_country_selectnum = $from_country_info[0];
	$from_country_attackable = $from_country_info[1];
	$from_country_tid = $from_country_info[2];

	//Get the Defending Country
	$to_country_info = explode(",",$_POST['tostate']);
	$to_country_selectnum = $to_country_info[0];
	$to_country_tid = $to_country_info[2];

	// Check to ensure they chose a From State
	if($_POST['fromstate'] == 'invalid') {
		game_error_header("Please Select a From State.");
		exit;
	}

	// Check to ensure they chose a To State
	if($_POST['tostate'] == 'invalid') {
		game_error_header("Please Select a To State.");
		exit;
	}

	//Check to ensure country has at least 2 armies
	if($attacking_armies < 1){ // There is only 1 Army on country, you can not attack from this country
		game_error_header("This country has 1 army and can not be used to attack.");
		exit;
	}

	// Check to ensure player not attempting to attack with 3 armies, when only 2 available, etc.
	if($attacking_armies > $from_country_attackable) {
		game_error_header("You do not have that many armies to attack with.");
		exit;
	}

	//Get the Attacking Countries Info
	$query1 = mysql_query("SELECT gd.gid, gd.pid, gd.pterritory, gd.parmies, plyr.gid, plyr.pid, plyr.pname, plyr.pcolor FROM ". $mysql_prefix ."game_data gd, ". $mysql_prefix ."game_players plyr WHERE gd.gid = {$_SESSION['gid']} AND gd.gid = plyr.gid  AND gd.pid = plyr.pid AND gd.pterritory = {$from_country_tid}") or die(mysql_error()); 
	$query1_row = mysql_fetch_assoc($query1);
	$attack_id = $query1_row['pid'];
	$attack_name = $query1_row['pname'];
	$pcolor = $query1_row['pcolor'];
	$parmies = $query1_row['parmies'];

	//Get the Defending Countries and Player Info
	$query2 = mysql_query("SELECT gd.gid, gd.pid, gd.pterritory, gd.parmies, plyr.gid, plyr.pid, plyr.pname, plyr.pcolor FROM ". $mysql_prefix ."game_data gd, ". $mysql_prefix ."game_players plyr WHERE gd.gid = {$_SESSION['gid']} AND gd.gid = plyr.gid  AND gd.pid = plyr.pid AND gd.pterritory = {$to_country_tid}") or die(mysql_error()); 
	$query2_row = mysql_fetch_assoc($query2);
	$op_pcolor = $query2_row['pcolor'];
	$opposing_armies = $query2_row['parmies'];
	$opposing_id = $query2_row['pid'];
	$opposing_player = $query2_row['pname'];
	if ($opposing_armies >= 3){
		$defendable = 2;
	} else {
		$defendable = $opposing_armies;
	}

	//Check to ensure the two countries are adjacent to each other before attacking.
	$query3 = mysql_query("SELECT * FROM ". $mysql_prefix ."countries WHERE mtype = '{$_SESSION['maptype']}' AND id = {$from_country_tid} ") or die(mysql_error()); 
	$query3_row = mysql_fetch_assoc($query3);
	$from_name = $query3_row['name'];
	$adjacency = $query3_row['adjacencies'];
	$adjacencies = explode(",", $adjacency);
	if(!in_array($to_country_tid, $adjacencies)){
		game_error_header("Those countries are not adjacent.");
		exit;
	} else {
		$query4 = mysql_query("SELECT * FROM ". $mysql_prefix ."countries WHERE mtype = '{$_SESSION['maptype']}' AND id = {$to_country_tid} ") or die(mysql_error()); 
		$query4_row = mysql_fetch_assoc($query4);
		$to_name = $query4_row['name'];
	}

	// NOW DO BATTLE
	$attackrolls = array(0,0,0);
	$defendrolls = array(0,0);

	// reset Session data
	$_SESSION['defend_rolls'] = $defendrolls;
	$_SESSION['attack_rolls'] = $attackrolls;

	// generate first roll
	$attackrolls[0] = dice_roll();
	$defendrolls[0] = dice_roll();

	// generate additional rolls if necessary
	if($defendable > 1){
		$defendrolls[1] = dice_roll( ); // default defend with 2 which is max if there are 2
	}

	if($attacking_armies > 1){ 
		$attackrolls[1] = dice_roll( ); // generate 2nd attack roll
		if($attacking_armies > 2){
			$attackrolls[2] = dice_roll( ); // generate 3rd attack roll
		}
	}

	rsort($attackrolls); // sort highest to lowest
	rsort($defendrolls);

	// Save the die rolls as Session data so that it can pass to the Dice File
	$_SESSION['attack_rolls'] = $attackrolls;
	$_SESSION['defend_rolls'] = $defendrolls;

	$attackcasualties = 0;
	$defendcasualties = 0;

	$fighters = 1; // keeps track of what rolls are fighting, subtract 1 to get at correct array index
	while($fighters <= 2 && $fighters <= $defendable && $fighters <= $attacking_armies){ // to keep 0's from engaging // 2 instead of attack armies cause
		if($attackrolls[$fighters-1] > $defendrolls[$fighters-1]){	// you cant have 3 fighting ever in Risk
			$defendcasualties = $defendcasualties + 1;
		} else {
			$attackcasualties = $attackcasualties + 1;
		}
		$fighters++;
	}

	// subtract casualties
	$available_armies = $parmies - $attackcasualties;
	$opposing_armies = $opposing_armies - $defendcasualties; 

	//Update the Attacker
	if($attackcasualties > 0){
		$query5 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET parmies = {$available_armies} WHERE gid = {$_SESSION['gid']} AND pterritory = {$from_country_tid} ") or die(mysql_error()); 
	}

	//Update the Defender
	if($defendcasualties > 0){
		//First Check if Defender has 0 or less armies less, to change ownership
		if($opposing_armies <= 0){
			//Territory was conquered, so change ownership and transfer armies
			$query6 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET pid = {$attack_id}, pname = '{$attack_name}', parmies = 0 WHERE gid = {$_SESSION['gid']} AND pterritory = {$to_country_tid} ") or die(mysql_error());
		
			//Create Conquered Session Data
			$_SESSION['min_armies'] = $attacking_armies;
			$_SESSION['transferable_armies'] = $available_armies - 1; //Have to leave 1 army on host country.
			$_SESSION['from_id'] = $from_country_tid;
			$_SESSION['to_id'] = $to_country_tid;
			$_SESSION['conquered_player'] = $opposing_player;
			$_SESSION['from_country'] = $from_name;
			$_SESSION['to_country'] = $to_name;
			$_SESSION['op_pcolor'] = $op_pcolor;
		
			//Add an entry to the Game Log
			$ginfo = array($_SESSION['gid'], $_SESSION['gname']);
			$pinfo = array($attack_name, 'attacking', $pcolor);
			require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
			game_log('attack',$ginfo,$pinfo);
		
			//Add 1 to their attack card to award a card at end of turn.  Should only run the first time
			$query_attack = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pattackcard = 1 WHERE gid = {$_SESSION['gid']} AND pid = {$attack_id} ") or die(mysql_error()); 			
		
			//Change Player State to Occupy, Include Info for Check Dead Player
			$_SESSION['pcolor'] = $pcolor;
			$_SESSION['attack_id'] = $attack_id;
			$_SESSION['attack_name'] = $attack_name;
			$_SESSION['opposing_id'] = $opposing_id;
			$_SESSION['opposing_player'] = $opposing_player;
			
			$query_occupy = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pstate = 'occupy' WHERE gid = {$_SESSION['gid']} AND pid = {$attack_id} ") or die(mysql_error()); 	
		} else {	
			$query6 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET parmies = {$opposing_armies} WHERE gid = {$_SESSION['gid']} AND pterritory = {$to_country_tid} ") or die(mysql_error()); 
		}
	}

	function dice_roll()
	{
		mt_srand((double) microtime() * 1000000);
  		return mt_rand(1,6);
	}

	header("Location: ../index.php?p=game&id={$_SESSION['gid']}&display=status&tostate={$to_country_selectnum}&fromstate={$from_country_selectnum}&armies={$attacking_armies}&$gets");
?>
