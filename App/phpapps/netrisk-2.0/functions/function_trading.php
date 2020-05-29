<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include(dirname(__FILE__) . './../includes/config.php');

	$card_and_type = $_POST['cardandtype'];

	//Check to make sure they submitted 3 Cards
	$count = count($card_and_type);
	if ($count != 3){
		game_error_header("You did not submit 3 cards.");
		exit;
	}

	foreach($card_and_type as $ct){
		$ct = explode(",",$ct);
		$card_country[] = $ct[0];
		$card_type[] = $ct[1];
	}

	//Need to confirm the users have these cards, and not submitting fake data
	//Get the Players Cards from the database
	$query = mysql_query("SELECT cds.gid, cds.pid, cds.pname, cds.card, plyr.gid, plyr.pid, plyr.pname, plyr.pcolor FROM ". $mysql_prefix ."game_cards cds, ". $mysql_prefix ."game_players plyr WHERE cds.pname = '".$_SESSION['player_name']."' AND cds.gid = {$_SESSION['gid']} AND cds.pname = plyr.pname AND cds.gid = plyr.gid");
	while($row = mysql_fetch_assoc($query)){ 
		$pcolor = $row['pcolor'];	
		$plyr_cards[] = $row['card'];
	}

	//Compare the Arrays.  Should have 0 differences if they have all three cards
	$diff = array_diff($card_country,$plyr_cards);
	$cdiff = count($diff);

	if($cdiff > 0) {
		game_error_header("You do not own those three cards.");
		exit;
	} else {
		$gid = $_SESSION['gid'];
		$rules = $_SESSION['cardrules'];

		switch($rules){
			case 'US':
			//Check for Matches
			if( ($card_type[0] == $card_type[1] && $card_type[1] == $card_type[2]) || (in_array(1, $card_type) && in_array(2, $card_type) && in_array(3, $card_type))){
				//Get and Award the Current Trade in
				$query1 = mysql_query("SELECT trade_value FROM ". $mysql_prefix ."game_info WHERE gid = $gid") or die(mysql_error()); 
				$query1_row = mysql_fetch_assoc($query1);
				$ctrade = $query1_row['trade_value'];
				$query2 = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pnumarmy = pnumarmy + $ctrade WHERE pname = '".$_SESSION['player_name']."' AND gid = $gid ");
				$_SESSION["armies"] = $ctrade;		
				//Update the value of the Next Trade in.
				$cardbonus = array(4,6,8,10,12,15,20,25);
				$ntrade = next_trade_value($ctrade, $cardbonus);
				$query3 = mysql_query("UPDATE ". $mysql_prefix ."game_info SET trade_value = $ntrade WHERE gid = $gid ");
		
			} else {
				game_error_header("Incorrect Match");
				exit;
			} 
			break;
	
			case 'UK':
			//Check for Matches
			if($card_type[0] == 1 && $card_type[1] == 1 && $card_type[2] == 1){
				$_SESSION["armies"] = 4;		
				$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pnumarmy = pnumarmy + 4 WHERE pname = '".$_SESSION['player_name']."' AND gid = $gid ");
			} else if ($card_type[0] == 2 && $card_type[1] == 2 && $card_type[2] == 2){
				$_SESSION["armies"] = 6;		
				$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pnumarmy = pnumarmy + 6 WHERE pname = '".$_SESSION['player_name']."' AND gid = $gid ");
			} else if ($card_type[0] == 3 && $card_type[1] == 3 && $card_type[2] == 3){
				$_SESSION["armies"] = 8;		
				$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pnumarmy = pnumarmy + 8 WHERE pname = '".$_SESSION['player_name']."' AND gid = $gid ");
			} else if (in_array(1, $card_type) && in_array(2, $card_type) && in_array(3, $card_type)){
				$_SESSION["armies"] = 10;		
				$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pnumarmy = pnumarmy + 10 WHERE pname = '".$_SESSION['player_name']."' AND gid = $gid ");
			} else {
				game_error_header("Incorrect Match");
				exit;
			}
			break;
	
			case 'Random':
			//Check for Matches
			if( ($card_type[0] == $card_type[1] && $card_type[1] == $card_type[2]) || (in_array(1, $card_type) && in_array(2, $card_type) && in_array(3, $card_type))){
				$tradein = array ("4", "6", "8", "10", "12", "15", "4", "6", "8", "10", "12", "15", "4", "6", "8", "10", "12", "15", "4", "6", "8", "10", "12", "15", "4", "6", "8", "10", "12", "15", "4", "6", "8", "10", "12", "15", "4", "6", "8", "10", "12", "15", "4", "6", "8", "10", "12", "15", "20", "25");
				$random = rand(0,count($tradein)-1);
				$card_bonus = $tradein[$random];
				$_SESSION["armies"] = $card_bonus;
				$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pnumarmy = pnumarmy + {$card_bonus} WHERE pname = '".$_SESSION['player_name']."' AND gid = {$gid} ");		
				//echo 'Congrats you have a Match !!! <br />';
			} else {
				game_error_header("Incorrect Match");
				exit;
			}
			break;
		}
	}

	//Add 2 Armies to the bonus State
	$bonus = $_POST['bonus'];
	if(in_array($bonus,$card_country)){
		$query4 = mysql_query("SELECT gd.gid, gd.pid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM ". $mysql_prefix ."countries cty, ". $mysql_prefix ."game_data gd WHERE gd.gid = {$_SESSION['gid']} AND cty.mtype = '{$_SESSION['maptype']}' AND gd. pterritory = {$bonus} AND gd.pterritory = cty.id") or die(mysql_error());
		$query4_row = mysql_fetch_assoc($query4);
		$oldarmy = $query4_row['parmies'];
		$bname = $query4_row['name'];
	
		$newarmy = $oldarmy + 2;
		$query5 = mysql_query("UPDATE ". $mysql_prefix ."game_data SET parmies = $newarmy WHERE gid = $gid AND pterritory = $bonus ");
		$_SESSION["bid"] = $bonus;
		$_SESSION["bname"] = $bname;
	}

	//Update the GameLog for the Trade In
	$_SESSION["gamelog"] = 'trading';


	$ginfo = array($_SESSION['gid'], $_SESSION['gname']);
	$pinfo = array($_SESSION['username'], 'trading', $pcolor);

	require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
	game_log('trading', $ginfo, $pinfo);

	//Remove the Players Cards and add them to the deck of cards
	foreach($card_country as $country){
		$query2 = mysql_query("DELETE FROM ". $mysql_prefix ."game_cards WHERE gid = $gid AND card = $country ");
	}

	header("Location: ../index.php?p=game&id=$gid");
?>
