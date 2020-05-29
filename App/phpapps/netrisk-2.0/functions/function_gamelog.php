<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	require(dirname(__FILE__) . './../config.php');
	
	$_G = array(
		'mysql_prefix' => $mysql_prefix,
		'game_path' => $game_path,
    	'empty' => 'future_use');

	function game_log($log_state, $ginfo, $pinfo)
	{
		global $_G;
    
		$now = time();
		$glog_state = $log_state;
	
		$gid    = $ginfo[0];
		$gname  = $ginfo[1];
	
		$pname  = $pinfo[0];
		$pstate = $pinfo[1];
		$pcolor = $pinfo[2];
	

		switch($glog_state){
			case 'trading':
				$armies = $_SESSION["armies"];
				$bid = $_SESSION['bid'];		
				$bname = $_SESSION['bname'];		
				if($bid > 0){
					$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> received a 2 army trade in bonus on  {$bname}.<hr/> ";
					$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				}
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> received $armies armies when trading cards.<hr/> ";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;

			case 'calculate':
				$armies = $_SESSION['armies'];
				$bonus = $_SESSION['bonus'];
				$num_countries = $_SESSION['num_countries'];
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> received {$armies} armies for occupying {$num_countries} countries.<hr/> ";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				if($bonus > 0){
					$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> received {$bonus} armies in Continent Bonuses.<hr/> ";
					$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				}
				break;
		
			case 'addarms':
				$add_army = $_SESSION['armies'];
				$country = $_SESSION['place_country'];
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> placed {$add_army} armies in {$country}.<hr/> ";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;
		
			case 'attack':
				$defender = $_SESSION['conquered_player'];
				$op_pcolor = $_SESSION['op_pcolor'];
				$from_country = $_SESSION['from_country'];
				$to_country = $_SESSION['to_country'];
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> attacked {$to_country} from {$from_country} and conquered it from <strong class=\"{$op_pcolor}\">{$defender}</strong>.<hr/> ";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;

			case 'fortify':
				$now = $now + 1;
				$num_armies = $_SESSION['armies'];
				$from_country = $_SESSION['from_country'];
				$to_country = $_SESSION['to_country'];
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> fortified {$num_armies} armies from {$from_country} to {$to_country}.<hr/>";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());		
				break;
		
			case 'endturn':
				$now = $now + 10;
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> ended their turn.<hr/>";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;
		
			case 'dead':
				$defender = $_SESSION['conquered_player'];
				$cards = $_SESSION['num_cards'];
				$op_pcolor = $_SESSION['op_pcolor'];
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> defeated <strong class=\"{$op_pcolor}\">{$defender}</strong> and obtained {$cards} cards.<hr/>";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;
			
			case 'awarded':
				$now = $now + 5;
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> received 1 card.<hr/>";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;
		
			case 'concede':
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> Conceded the Game.<hr/>";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;
		
			case 'winner':
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> Won the Game !!<hr/>";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;
		
			case 'skipped':
				$text = "<strong class=\"{$pcolor}\"> {$pname}</strong> turn was skipped<hr/>";
				$query = mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_log (gid, gname, time, text) VALUES ($gid, '{$gname}', $now, '{$text}') ") or die(mysql_error());
				break;
			}
	}
?>
