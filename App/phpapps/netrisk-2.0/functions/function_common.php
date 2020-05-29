<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail.com>
	License	GPL

	**************************************************/
	
	require(dirname(__FILE__) . './../config.php');

	$_G = array(
		'mysql_prefix' => $mysql_prefix,
		'game_path' => $game_path,
    	'empty' => 'future_use');

//Common functions used in multiple game functions

function config_defaults()
{
	global $_G;
	
	$query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."config");	
	$rows = mysql_num_rows($query);
	
	while($row = mysql_fetch_assoc($query)){
		$conf_name[] = $row['conf_name'];
		$conf_value[] = $row['conf_value'];	
		
		$defaults = array_combine($conf_name, $conf_value);	
	}
	return $defaults;
}

function SendEmail($strEmailTo,$strEmailFrom,$strEmailFromName,$strEmailSubject,$strEmailMessage)
{
	global $_G;

	$email_from = $strEmailFrom;
	$email_subject = $strEmailSubject;
	$email_message = $strEmailMessage;
	$email_to = $strEmailTo;
	
	$semi_rand = md5(time()); 
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

	$headers = "From:" . $strEmailFrom;
	$headers .= "\nMIME-Version: 1.0\n" . 
	"Content-Type: multipart/mixed;\n" . 
	" boundary=\"{$mime_boundary}\""; 

	$email_message .= "This is a multi-part message in MIME format.\n\n" . 
	"--{$mime_boundary}\n" . 
	"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
	"Content-Transfer-Encoding: 7bit\n\n" . 
	$email_message . "\n\n"; 

	// send mail //
	mail($email_to, $email_subject, $email_message, $headers); 
}

function seconds_to_HMS($time_in_secs)
{       
   		$secs = $time_in_secs % 60;
   		$time_in_secs -= $secs;
   		$time_in_secs /= 60;
  
	   	$mins = $time_in_secs % 60;
   		$time_in_secs -= $mins;
   		$time_in_secs /= 60;
  
   		$hours = $time_in_secs;   

   		return str_pad($hours,2,'0',STR_PAD_LEFT) . " Hrs " . str_pad($mins,2,'0',STR_PAD_LEFT) . " Mins";
}

//Get the Next Game ID for a new game creation
function get_next_gid(){
	global $_G;
	
	$query1 = mysql_query("SELECT conf_value FROM ". $_G['mysql_prefix'] ."config WHERE conf_name = 'conf_next_gid' ");	
	$row = mysql_fetch_assoc($query1);
	$gid = $row['conf_value'];
	
	$next_id = $gid + 1;
	$query2 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."config SET conf_value = {$next_id} WHERE conf_name = 'conf_next_gid' ");	
	
	return $gid;	
}

function random_start($game_id){
	
	global $_G;
	//Random Start the Game
	$query1 = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$game_id}") or die(mysql_error());
	while($row = mysql_fetch_assoc($query1)){
	$players[] = $row['pname'];
	}

	shuffle($players);
	$now = time();
	
	$query2 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_info SET gstate = 'Playing', last_move = {$now} WHERE gid = {$game_id} ") or die(mysql_error()); 	
	$query3 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pstate = 'trading' WHERE gid = {$game_id} AND pname = '{$players[0]}' ") or die(mysql_error()); 		
}

/* Used in function_trading to return the next value in US Card Rules*/
function next_trade_value( $needle, $haystack )
{
    global $_G;
    
	$found = FALSE;
    foreach ( $haystack as $value )
    {
        if ( $value == $needle AND !$found )
        {
            $found = TRUE;
        }
        elseif ( $found )
        {
            break;
        }
    }
    return $value;
}  

/* Determines the Last Player, Current Player and Next Player in a Game*/
function get_last_current_next_player($game_id)
{
	global $_G;
	
	$sql2 = "SELECT * FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$game_id} AND pstate != 'dead' ORDER by pid";
	$result2 = mysql_query($sql2);

	while ($row = mysql_fetch_assoc($result2)) {
		$pdata[] = $row;
		$rows = count($pdata);
		}
        
    	$prev = null;
		$next = null;
		$cur = null;
	
	for ($i = 0; $i < $rows; $i++){
    	if ($pdata[$i]['pstate'] == 'inactive'){
        	continue;
    	}
    
    	$cur = $pdata[$i];
    	$cplayer = $cur['pname'];
    	$cstatus = $cur['pstate'];
    	$ccolor = $cur['pcolor'];
    
    	//Get the Last Player
    	if ($i - 1 >= 0){
        	$prev = $pdata[$i - 1];
        	$lplayer = $prev['pname'];
    	} else {
	    	$prev = $pdata[$rows - 1];
	    	$lplayer = $prev['pname'];
    	}
    
    	//Get the Next Player
    	if ($i + 1 < $rows){
	        $next = $pdata[$i + 1];
	        $nplayer = $next['pname'];
	        $npmail = $next['pmail'];
    	} else {
	    	$next = $pdata[0];
	    	$nplayer = $next['pname'];
	    	$npmail = $next['pmail'];
		}
    break;
	}
	return array($lplayer,$cplayer,$cstatus,$nplayer,$npmail,$ccolor);
}

function get_players($gid)
{
	global $_G;
	
	$query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = $gid "); 
	$alive = null;
    $dead = null;
	$players = null;//just reset it to null here
		
	while($row = mysql_fetch_assoc($query)){
    $status = $row['pstate'];
	$player = $row['pname'];
	    
    	if ($status == 'dead') {
     		$dead .= "<span class=\"dead\"><del>\n" . $player . "</del></span>";
    	} else {
    		$alive .= "<span class=\"alive\">\n" . $player . "</span>";
    	}
    	$players =  $alive . $dead;
	}
	return $players;
}


function update_last_move($game_id)
{
	global $_G;
	
	$now = time();
	$query = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_info SET last_move = {$now} WHERE gid = {$game_id} ");
}

function skip_player() 
{
	global $_G;
	
	//Get all the ACTIVE ONLY Games with a time limit
	$now = time();	
	$query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_info WHERE time_limit > 0 AND gstate = 'Playing' ORDER by gid");
	while ($row = mysql_fetch_assoc($query)) {
		$gid = $row['gid'];
		$gname = $row['gname'];
		$timelimit = $row['time_limit'];
		$lastMove = $row['last_move'];
		//Compare Current Time to LastMove
		$elapsed = $now - $lastMove;
		if($elapsed >= $timelimit){
			//Get the Last, Current, and Next Players
			$players = get_last_current_next_player($gid);
			
			//End Current Players Turn
			$cplayer = $players[1];
			$cstate = $players[2];
			$ccolor = $players[5];
			
			$nplayer = $players[3];
			$npmail = $players[4];
			//Includes Reset of Players armies to 0 if they had any
			$query2 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pstate = 'inactive', pnumarmy = 0 WHERE gid = {$gid} AND pname = '{$cplayer}' ") or die(mysql_error()); 	
			
			//Add Entry to Game Log
			$ginfo = array($gid, $gname);
			$pinfo = array($cplayer, $cstate, $ccolor);
			require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
			game_log('skipped',$ginfo,$pinfo);		
			
			//Put Next Player in Trading State, and update their time
			$query3 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pstate = 'trading' WHERE gid = {$gid} AND pname = '{$nplayer}' ") or die(mysql_error()); 	
			
			//Send Email to Next Player
			if($npmail == 1 ){  //0 is no updates, 1 is mail them
				//Query Users for Email address of next player
				$query4 = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."users WHERE login = '{$nplayer}' ") or die(mysql_error());
				$query4_row = mysql_fetch_assoc($query4);
				$next_email = $query4_row['email']; 	
		
				$EmailTo = $next_email;
				$EmailFrom = "WebMaster@{$_SERVER['SERVER_NAME']}";
				$FromName = "Webmaster";
				$EMailSubject = "NetRisk Game: {$gname}";
		
				$Glink = "<a href=\"{$_SERVER['SERVER_NAME']}". $_G['game_path'] ."login.php?id={$gid} \">Here</a>";
				$EmailMessage = "It is your turn in the NetRisk Game: {$gname}.<br />";
				$EmailMessage .= "You can visit the game here: {$Glink}.<br />";

				SendEmail($EmailTo,$EmailFrom,$FromName,$EMailSubject,$EmailMessage);
			} 	
				
			//Update LastMove Time
			update_last_move($gid);
		}
	}
}

function auto_game_delete()
{
	global $_G;
	
	//Get all the FINISHED Games
	$now = time();	
	$query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_info WHERE gstate = 'Finished' ORDER by gid") or die(mysql_error());
	if(mysql_num_rows($query) > 0){
		while($row = mysql_fetch_assoc($query)) {
			//Get Last Move Time
			$lastmove = $row['last_move'];  // $row['last_move'] is a unix timestamp
			$elapsed = $now - $lastmove;
			//Compare Auto Delete Period to Time Elapsed
			$AutoPeriod = 259200; //Currently 3 Days.  Need to put this in a database;
			if($elapsed >= $AutoPeriod){
				//Delete the Game
				$query1 = mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."game_info WHERE gid = {$row['gid']} ") or die(mysql_error());
				$query2 = mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$row['gid']} ") or die(mysql_error());
				$query3 = mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."game_data WHERE gid = {$row['gid']} ") or die(mysql_error());
				$query4 = mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."game_log WHERE gid = {$row['gid']} ") or die(mysql_error());
				$query5 = mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."game_chat WHERE gid = {$row['gid']} ") or die(mysql_error());
				$query6 = mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."game_cards WHERE gid = {$row['gid']} ") or die(mysql_error());
			}
		}
	}
}


function check_dead_player($game_id, $gname, $pcolor, $attack_id, $attack_name, $opposing_id, $opposing_player)
{
	global $_G;
	
	$query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_data WHERE gid = {$game_id} AND pid = {$opposing_id} ") or die(mysql_error()); 
	$opposing_countries = mysql_num_rows($query);

		if($opposing_countries < 1 ){
   			//Change Player State to dead
   			$query = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pstate = 'dead' WHERE gid = {$game_id} AND pid = {$opposing_id} ") or die(mysql_error()); 	
   
   			// ******************************** //
   			// Transfer Any Cards they may have //
   			// ******************************** //
   
   			//First count how many cards they have.  Need to know this for entry to the game log
   			$query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_cards WHERE gid = {$game_id} AND pid = {$opposing_id} ") or die(mysql_error()); 
   			$num_cards = mysql_num_rows($query);
   			if($num_cards >= 1 ){
	    		//Transfer their cards
   				$query = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_cards SET pid = {$attack_id}, pname = '{$attack_name}' WHERE gid = {$game_id} AND pid = {$opposing_id} ") or die(mysql_error());
   				
   				//Add an entry to the Game Log
   				$_SESSION['num_cards'] = $num_cards;
   				
				$ginfo = array($game_id, $gname);
				$pinfo = array($attack_name, 'attacking', $pcolor);
				require_once(dirname(__FILE__) . '/function_gamelog.php'); //Move to config for common 
				game_log('dead',$ginfo,$pinfo);
			}
			
			//Update Player Rankings Info.  All Ranking Should Post to player AFTER they are dead or Game is Complete
			
			//Add 1 Kill to the Conqueror
			$query = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pkills = pkills + 1 WHERE gid = {$game_id} AND pid = {$attack_id} ") or die(mysql_error());
			
			//Get Points of the Defeated
			$querydead = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$game_id} AND pstate = 'dead' ") or die(mysql_error());
			$num_dead = mysql_num_rows($querydead);
			
			//Set Points Finish of the Defeated player
			$query = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET ppoints = $num_dead WHERE pid = {$opposing_id} ") or die(mysql_error());
			
			//Get Kills of Defeated Player
			$querykills = mysql_query("SELECT pkills FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$game_id} AND pid = {$opposing_id} ") or die(mysql_error());
			$query1_row = mysql_fetch_assoc($querykills);
			$kills = $query1_row['pkills'];		
			$pmail = $query1_row['pmail'];		
			
			//Add 1 Gamed Played 1 Loss, Points, Kill, and Total Players to the Defeated Player
			$query_update = mysql_query("UPDATE ". $_G['mysql_prefix'] ."users SET games_played = games_played + 1, loss = loss + 1, points = points + {$num_dead}, kills = kills + {$kills}, total_players = total_players + {$_SESSION['players']}  WHERE login = '{$opposing_player}' ") or die(mysql_error());
			
			//Send Email to Dead Player
			if($pmail == 1 ){  //0 is no updates, 1 is mail them
				//Query Users for Email address of next player
				$query4 = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."users WHERE login = '{$opposing_player}' ") or die(mysql_error());
				$query4_row = mysql_fetch_assoc($query4);
				$dead_email = $query4_row['email']; 	
		
				$EmailTo = $dead_email;
				$EmailFrom = "WebMaster@{$_SERVER['SERVER_NAME']}";
				$FromName = "Webmaster";
				$EMailSubject = "NetRisk Game: {$gname} Player Defeated";
		
				$Glink = "<a href=\"{$_SERVER['SERVER_NAME']}". $_G['game_path'] ."index.php?p=create \">Create a New Game</a>";
				$EmailMessage = "Were sorry to inform you that your playder has died in the the NetRisk Game: {$gname}.<br />";
				$EmailMessage .= "Perhaps you would like to {$Glink}?<br />";

				SendEmail($EmailTo,$EmailFrom,$FromName,$EMailSubject,$EmailMessage);
			}
			
			//Check for Force Trade
			force_card_trade($game_id, $attack_name);
		} else {
			//Return player to attacking state
			$query_return = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pstate = 'attacking' WHERE gid = {$game_id} AND pid = {$attack_id} ") or die(mysql_error());
		}
		
}

function check_winner($game_id, $pid, $pname)
{
	global $_G;
	
	//Verify Only 1 Player is Alive
	$querydead = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$game_id} AND pstate != 'dead' ") or die(mysql_error());
	$num_dead = mysql_num_rows($querydead);
	if($num_dead <= 1){
		
		//Get Kills of Player
		$querykills = mysql_query("SELECT pkills FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$game_id} AND pid = {$pid} ") or die(mysql_error());
		$query1_row = mysql_fetch_assoc($querykills);
		$kills = $query1_row['pkills'];
		
		//Set Game State to Finished
		$query1 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_info SET gstate = 'Finished' WHERE gid = '{$game_id}' ") or die(mysql_error());
		
		//Set Player State to Winner
		$query2 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pstate = 'winner', ppoints = {$_SESSION['players']} WHERE gid = {$game_id} AND pid = {$pid} ") or die(mysql_error());
		
		//Add 1 Games Played, 1 Win, Points, Kills, and Total Players to the Defeated Player
		$query3 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."users SET games_played = games_played + 1, win = win + 1, points = points + {$_SESSION['players']}, kills = kills + {$kills}, total_players = total_players + {$_SESSION['players']}  WHERE login = '{$pname}' ") or die(mysql_error());
		
		return 1;  //Yes, There is a winner
	} else {
		return 0;  // 0 Is no Winner Yet
	}
			
}

function update_historical_glog($game_id)
{
	global $_G;
	
	//Get all Game Data
	$query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_info WHERE gid = {$game_id}") or die(mysql_error());
	while($row = mysql_fetch_assoc($query)){
			$gid = $row['gid'];
			$gname = $row['gname'];
			$players = $row['players'];
			$card_rules = $row['card_rules'];
			$map_type = $row['map_type'];
			$time = $row['last_move'];
			$game_type = $row['gtype'];
	}
	
	for($i = 1; $i <= $players; $i++){
	
		//Get the Players and Finish Order
		$qplayers = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$game_id} AND ppoints = {$i} ") or die(mysql_error());
		while ($player = mysql_fetch_assoc($qplayers)){
	
			//Points is their Finish.  1 = Last; $players is First		
			if($player['points'] = $players){
				$first = $player['pname'];
				$points = $player['ppoints'];
				$kills = $player['pkills'];	
			}
		
			$x = null;
			$x = $i;
			if($player['points'] = ($players - $x)) {$second = $player['pname'];}
			
			//Set Default 3rd through 8
			$third = ' -----';
			$fourth = ' -----';
			$fifth = ' -----';
			$sixth = ' -----';
			$seventh = ' -----';
			$eighth = ' -----';
			// If players exist, get the player  	
			if($player['points'] = ($players - $x)) {$third = $player['pname'];}	
			if($player['points'] = ($players - $x)) {$fourth = $player['pname'];}
			if($player['points'] = ($players - $x)) {$fifth = $player['pname'];}
			if($player['points'] = ($players - $x)) {$sixth = $player['pname'];}
			if($player['points'] = ($players - $x)) {$seventh = $player['pname'];}
			if($player['points'] = ($players - $x)) {$eighth = $player['pname'];}	
		}
	}		
	
	//Update Historical Game Log
	$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."historical_log (gid, gname, players, card_rules, map_type, first, second, third, fourth, fifth, sixth, seventh, eighth, points, kills, time) VALUES ({$gid}, '{$gname}', {$players}, '{$card_rules}', '{$map_type}', '{$first}', '{$second}', '{$third}', '{$fourth}', '{$fifth}', '{$sixth}', '{$seventh}', '{$eighth}',{$points}, {$kills}, {$time}) ") or die(mysql_error());
}

function award_card($pid, $pname, $game_id, $maptype)
{
	global $_G;
	
	$query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_players WHERE gid = {$game_id} AND pid = {$pid} ") or die(mysql_error());
	$query_row = mysql_fetch_assoc($query);
	$attackcard = $query_row['pattackcard'];
	if($attackcard == 1){
		//Get all the current dealt out cards
		$query2 = mysql_query("SELECT card FROM ". $_G['mysql_prefix'] ."game_cards WHERE gid = {$game_id} ORDER by card") or die(mysql_error()); 
   		$num_cards = mysql_num_rows($query2);
   		if($num_cards > 0){
   			while($dealt_cards = mysql_fetch_assoc($query2)){
		   		$cards[] = $dealt_cards['card'];
   			}
		} else {
			//No Cards dealt out, so array is 0
	   		$cards[] = 0;
   		}
   	
   		//Get all the Cards for the Game
   		$query3 = mysql_query("SELECT id FROM ". $_G['mysql_prefix'] ."countries WHERE mtype = '{$maptype}' ORDER by id") or die(mysql_error()); 
   		$game_cards = mysql_num_rows($query3);
   		while($row = mysql_fetch_assoc($query3)){
	   		$gcards[] = $row['id'];
   		}
   	
   		//Compare the two arrays and output the differences only
		$available = array_merge(array_diff($gcards,$cards));
		
		//Select a random card
		shuffle($available);
		$next_card = $available[0];
	
		//Award the Card to the player, Add a row to the card table
		$query4 = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_cards (gid, pid, pname, card) VALUES ({$game_id}, {$pid}, '{$pname}', {$next_card}) ") or die(mysql_error());
	}
	//return array($game_cards, $num_cards, $avail_cards, $next_card);
}

function compute_ranking($player)
{
	global $_G;
	
	$query1 = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."users WHERE login = '{$player}' ") or die(mysql_error()); 	
	$row = mysql_fetch_assoc($query1);
	
	//$ranking = ($row['win'] - $row['loss']);
	$ranking = ( ( ( ($row['points'] + $row['kills']) * $row['win'] * 0.1 ) / ( $row['games_played'] + .0001 ) ) / 15 );
	
	$rank = number_format(($ranking),3);
	
	return $rank;
}

function force_card_trade($game_id, $pname)
{
	global $_G;
	
	//Get the Cards for the Player
	$query = mysql_query("SELECT cds.gid, cds.pid, cds.pname, cds.card, plyr.gid, plyr.pid, plyr.pname, plyr.pstate, plyr.pcolor FROM ". $_G['mysql_prefix'] ."game_cards cds, ". $_G['mysql_prefix'] ."game_players plyr WHERE cds.pname = '{$pname}' AND cds.gid = {$game_id} AND cds.pname = plyr.pname AND cds.gid = plyr.gid");
	$pcards = mysql_num_rows($query);
	$row = mysql_fetch_assoc($query);
	$pstate = $row['pstate'];
	
	//Need to Check for 5 or greater at Start of turn, and at XX cards during a force trade in
	if ($pstate != 'trading' && $pcards >= 6){
		//Change State to Force Trade
		$query2 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pstate = 'forcetrade' WHERE gid = {$game_id} AND pname = '{$pname}' ") or die(mysql_error()); 		
	} else if($pstate == 'trading' && $pcards >= 5) {
		game_error_header("You have {$pcards} cards and must trade in at this time.");
		exit;
	} else {
		// return player to attack state
		$query3 = mysql_query("UPDATE ". $_G['mysql_prefix'] ."game_players SET pstate = 'attacking' WHERE gid = {$game_id} AND pname = '{$pname}' ") or die(mysql_error()); 		
	}
}

function time_since($original) 
{
    global $_G;
    
	// array of time period chunks
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'min'),
    );

    $today = time(); /* Current unix time  */
    $since = $today - $original;

    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {

        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];

        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            // DEBUG print "<!-- It's $name -->\n";
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

    if ($i + 1 < $j) {
        // now getting the second item
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];

        // add second item if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        }
    }
    return $print;
}

function genPassword($min_len = 7, $max_len = 7, $min_numeric = 2, $min_alpha = 2, $min_special = 0, $allow_special = false)
{
    global $_G;
    
	// init
    $numeric = array('1','2','3','4','5','6','7','8','9');
    $alphabetic = array('a','A','b','B','c','C','d','D','e','E','f','F','g','G','h','H','i','j','J', 
        'k','K','L','m','M','n','N','o','p','P','q','Q','r','R','s','S','t','T', 
        'u','U','v','V','w','W','x','X','y','Y','z','Z');
   $special = array('!', '@', '#', '$', '%', '=');
   $password = array();
   $char_count = 0;
   
   // get required numerics
   if ($min_numeric > 0)   {
        for($i = 1; $i <= $min_numeric; $i++)        {
            $password[] = $numeric[rand(0, count($numeric) - 1)];
            $char_count++;
        }
    }
    
    
    // get required alphabetics
    if ($min_alpha > 0)   {
        for($i = 1; $i <= $min_alpha; $i++)        {
            $password[] = $alphabetic[rand(0, count($alphabetic) - 1)];
            $char_count++;
        }
    }
    
    // get required specials
    if ($min_special > 0)   {
        for($i = 1; $i <= $min_special; $i++)        {
            $password[] = $special[rand(0, count($special) - 1)];
            $char_count++;
        }
    }
    
    // merge arrays
    $chars = array_merge($numeric, $alphabetic);
    if ($allow_special) $chars = array_merge($chars, $special);
    
    // determine password length
    if (($min_numeric + $min_alpha + $min_special) > $max_len)    {
        $pwd_len = $min_numeric + $min_alpha + $min_special;
    }
    if ($min_len == $max_len)    {
        $pwd_len = $min_len;
    } else {
        $pwd_len = rand($min_len, $max_len);
    }
    
    // get remaining characters
    if ($pwd_len > $char_count){
        for($i = $char_count + 1; $i <= $pwd_len; $i++){
            $password[] = $chars[rand(0, count($chars) - 1)];
        }
    }   
    
    // shuffle password array
    shuffle($password);
    
    // done
    return implode('', $password);
}

function addActiveUser($username, $time)
{
	global $_G;
	
	$q = mysql_query("UPDATE ". $_G['mysql_prefix'] ."active_users SET timestamp = '{$time}' WHERE username = '{$username}'");
    
	define("TRACK_VISITORS", true);  
	if(!TRACK_VISITORS) return;  
	$q = mysql_query("REPLACE INTO ". $_G['mysql_prefix'] ."active_users VALUES ('$username', '$time')");      
}

function removeActiveUser($username)
{	
	global $_G;
	
	define("TRACK_VISITORS", true);  
	if(!TRACK_VISITORS) return;
	$q = mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."active_users WHERE username = '{$username}'");	
}

/* removeInactiveUsers */
function removeInactiveUsers()
{	
	global $_G;
	
	define("TRACK_VISITORS", true);
	define("USER_TIMEOUT", 10);  
	if(!TRACK_VISITORS) return;
	$timeout = time()-USER_TIMEOUT*60;
	$q = mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."active_users WHERE timestamp < {$timeout} ");	
}

?>