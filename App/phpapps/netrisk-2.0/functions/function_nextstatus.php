<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail.com>
	License	GPL

	**************************************************/

	include('../includes/config.php');

	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pname = '".$_SESSION['username']."' ") or die(mysql_error());
	$query1_row = mysql_fetch_assoc($query1);
	$pid = $query1_row['pid']; 	
	$pname = $query1_row['pname']; 	
	$pcolor = $query1_row['pcolor']; 	
	$current_state = $query1_row['pstate']; 	

	switch($current_state){
		case 'trading': 	$next_state = 'placing';
							//Check for a force trade in first before going to calculate
							force_card_trade($_SESSION['gid'], $pname);
							//Require Function Calculate to Add new Armies
							require('./function_calculate.php');  //Includes Attack Card Reset
							break;
		case 'placing': 	$next_state = 'attacking';
							//Ensure all Armies on place.  Necessary to ensure not hit placement repeatedly and skip over attack stage in error
							break;
		case 'attacking': 	$next_state = 'fortifying';
							break;
		case 'fortifying': 	$next_state = 'inactive';
							//Award a Card if Needed
							$cards = award_card($pid, $pname, $_SESSION['gid'], $_SESSION['maptype']);
							$awarded = $cards[3];
							//End Player Turn
							$next = get_last_current_next_player($_SESSION['gid']);
							$nplayer = $next[3];
							$npmail = $next[4];	
							break;
		case 'initial': 	$next_state = 'inactive';
							break;
		case 'forcetrade': 	$next_state = 'forceplace';
							break;
		case 'forceplace': 	$next_state = 'attacking';
							break;
	}

	//Update Last Move in Game Info
	update_last_move($_SESSION['gid']);

	// update current players state in DB
	$query2 = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pstate = '{$next_state}' WHERE gid = {$_SESSION['gid']} AND pid = {$pid} ") or die(mysql_error()); 	

	//Check for Game Winner
	$wincheck = check_winner($_SESSION['gid'], $pid, $_SESSION['username']);
	if($wincheck == 1) {
		//Update Game Game Log for Winner
		$ginfo = array($_SESSION['gid'], $_SESSION['gname']);
		$pinfo = array($_SESSION['username'], 'winner', $pcolor);
		require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
		game_log('winner', $ginfo, $pinfo);
				
		//Update Historical Game Log	
		update_historical_glog($_SESSION['gid']);
	}

	//Add card awarded if they received one
	if($awarded){
		$ginfo = array($_SESSION['gid'], $_SESSION['gname']);
		$pinfo = array($_SESSION['username'], 'inactive', $pcolor);

		require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
		game_log('awarded', $ginfo, $pinfo);
	}

	// Set the Next Players Turn, Includes reset of Player Armies to 0
	if($nplayer){
		$query3 = mysql_query("UPDATE ". $mysql_prefix ."game_players SET pstate = 'trading', pnumarmy = 0 WHERE gid = {$_SESSION['gid']} AND pname = '{$nplayer}' ") or die(mysql_error());
	
		//Email Next Player
		if($npmail == 1 ){  //0 is no updates, 1 is mail them
			//Query Users for Email address of next player
			$query4 = mysql_query("SELECT * FROM ". $mysql_prefix ."users WHERE login = '{$nplayer}' ") or die(mysql_error());
			$query4_row = mysql_fetch_assoc($query4);
			$next_email = $query4_row['email']; 	
		
			$EmailTo = $next_email;
			$EmailFrom = "WebMaster@{$_SERVER['SERVER_NAME']}";
			$EMailFromName = "Webmaster";
			$EMailSubject = "NetRisk Game: {$_SESSION['gname']}";
		
			$Glink = "<a href=\"http://{$_SERVER['SERVER_NAME']}/{$game_path}/login.php?id={$gid} \">Here</a>";
			//Remove http:// from above if not installing in subdomain?
			$EmailMessage = "It is your turn in the NetRisk Game: {$_SESSION['gname']}.<br />";
			$EmailMessage .= "You can visit the game here: {$Glink}.<br />";

			SendEmail($EmailTo,$EmailFrom,$EmailFromName,$EMailSubject,$EmailMessage);  //Common Function
		} 	
	
		//Require Function Game Log
		$ginfo = array($_SESSION['gid'], $_SESSION['gname']);
		$pinfo = array($_SESSION['username'], 'inactive', $pcolor);

		require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
		game_log('endturn', $ginfo, $pinfo);
	
	}

	header("Location: ../index.php?p=game&id={$_SESSION['gid']}");
?>
