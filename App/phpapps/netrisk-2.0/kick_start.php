<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	$tpl = new TemplatePower('./templates/kick_start.tpl');
	$tpl->prepare();	

	//Check to see if their is already an existing vote in progress
	$query_kick = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pkick != 0 ");
	$qkick = mysql_num_rows($query_kick);

	if($qkick > 0){
		$tpl -> assign("Message", 'A Vote has already been started.  You can only vote to kick 1 player at a time');
	} else {
		//Check to see if Player already voted.
		$query_vote = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ");
		$qvote = mysql_fetch_assoc($query_vote);
		$pvote = $qvote['pvote'];

		if($pvote == 1){
			//Start and End Select
			$tpl -> assign("Message", 'Sorry, You already voted.');
		} else {
			$tpl -> assign("StartSelect", '<select name="kickpid">');
			$tpl -> assign("EndSelect", '</select>');
			$tpl -> assign("InputButton", '<input type="submit" value="Start Vote" class="button" />');
		
			//Get all Non-Dead Players in the Game
			$query = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pstate != 'dead' AND pname != '{$_SESSION['username']}' ORDER BY pid ");
			while($gplayers = mysql_fetch_assoc($query)){
				$tpl -> newBlock("Players");
		
				$tpl->assign( Array( PID => stripslashes($gplayers['pid']),                  
					 PName => stripslashes($gplayers['pname']), 
                     PHost => stripslashes($gplayers['phost']),
                     PColor => stripslashes($gplayers['pcolor']),  
                     PState => stripslashes($gplayers['pstate']), 
                     PAttackCard => stripslashes($gplayers['pattackcard']),
                     PNumArmy => stripslashes($gplayers['pnumarmy']), 
                     PMission => stripslashes($gplayers['pmission']), 
                     PCapOrg => stripslashes($gplayers['pcaporg']),
                     PMail => stripslashes($gplayers['pmail']), 
                     PVote => stripslashes($gplayers['pvote']), 
                     PKick => stripslashes($gplayers['pkick']),
                     PKills => stripslashes($gplayers['pkills']), 
                     PPoints => stripslashes($gplayers['ppoints'])  
                     ));
			}
		}
	}
  
	$tpl->printToScreen();
?>