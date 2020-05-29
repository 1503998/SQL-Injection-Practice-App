<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	$tpl = new TemplatePower('./templates/delete_game.tpl');
	$tpl->prepare();	

	//Get the Player Info to Verify they are the winner
	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix . "game_players WHERE pname = '".$_SESSION['player_name']."' AND gid = {$_SESSION['gid']} ") or die(mysql_error()); 
	$query1_row = mysql_fetch_assoc($query1);
	$pname = $query1_row['pname'];
	$pstate = $query1_row['pstate'];

	if($pstate == 'winner'){
		// Let User Start the Game
		$tpl -> assign("Winner", "Congrations, you won the game!!");
		$tpl -> assign("DeleteGame", '<input class="button" type="submit" value="Delete Game" />');
	} else {	
		$tpl -> assign("Winner", "The Game is not over");
	}

	$tpl->printToScreen();
?>