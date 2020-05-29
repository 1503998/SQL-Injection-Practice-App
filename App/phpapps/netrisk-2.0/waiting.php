<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	$tpl = new TemplatePower('./templates/waiting.tpl');
	$tpl->prepare();	

	//Get the Player Info to Determine if they are the Game Host
	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix . "game_players WHERE pname = '".$_SESSION['player_name']."' AND gid = {$_SESSION['gid']} ") or die(mysql_error()); 
	$query1_row = mysql_fetch_assoc($query1);
	$pname = $query1_row['pname'];
	$phost = $query1_row['phost'];

	if($phost == 1){
		// Let User Start the Game
		$tpl -> assign("Message", "Start the Game");
		$tpl -> assign("StartGame", '<input class="button" type="submit" value="Start Game" />');
	} else {	
		$tpl -> assign("Message", "The Game has not yet started");
	}

	$tpl->printToScreen();
  
?>