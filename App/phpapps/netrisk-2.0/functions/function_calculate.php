<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./../config.php');

	//Count the number of Countries the Player Has
	$query = mysql_query("SELECT * FROM ". $mysql_prefix ."game_data gd WHERE gid = {$_SESSION['gid']} AND pname = '".$_SESSION['player_name']."' ") or die(mysql_error()); 
	$num_rows = mysql_num_rows($query);

	if ($num_rows <=9){
		$armies = 3;
	} else {
		$armies = floor($num_rows / 3);
	}

	//Check their countries for any continent bonuses
	while($row = mysql_fetch_array($query)){
		$countries[] = $row['pterritory'];
	}
	
	$query2 = mysql_query("SELECT * FROM ". $mysql_prefix ."continents WHERE mtype = '{$_SESSION['maptype']}' ") or die(mysql_error()); 
	while($row2 = mysql_fetch_array($query2)){
		$states = explode(",",$row2['states']);
	
		$arraydiffs = array_diff($states,$countries);
			if(count($arraydiffs) == 0){ // No Differences, so they have all the countries for that continent.
				$bonus += $row2['bonus'];			
			}	
	}
	
	$calc_army = $armies + $bonus;

	//Get any Armies from an existing Card Trade In.
	$query2 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pname = '".$_SESSION['player_name']."' ") or die(mysql_error());
	$query2_row = mysql_fetch_assoc($query2);
	$carmy = $query1_row['pnumarmy'];
	$pcolor = $query1_row['pcolor'];
	$new_armies = $carmy + $calc_army;

	//Set New Armies, and reset the AttackCard to 0
	$query3 = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pnumarmy = {$new_armies}, pattackcard = 0 WHERE gid = {$_SESSION['gid']} AND pname = '".$_SESSION['player_name']."' ") or die(mysql_error()); 	

	//Require Function Game Log to post Armies to Log
	$_SESSION["gamelog"] = 'calculate';
	$_SESSION['armies'] = $armies;
	$_SESSION['bonus'] = $bonus;
	$_SESSION['num_countries'] = $num_rows;

	$ginfo = array($_SESSION['gid'], $_SESSION['gname']);
	$pinfo = array($_SESSION['username'], 'trading', $pcolor);

	require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
	game_log('calculate', $ginfo, $pinfo);

?>
