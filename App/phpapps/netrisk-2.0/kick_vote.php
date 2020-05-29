<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

  	$tpl = new TemplatePower('./templates/kick_vote.tpl');
  	$tpl->prepare();	

  	//Should only be able to hold a vote on one player at a time
  	$query_kick = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pkick != 0 ");
  	$qkick = mysql_fetch_assoc($query_kick);
  	$kick_pid = $qkick['pid'];
  	$kick_pname = $qkick['pname'];

  	$tpl->assign( Array( PID => $kick_pid,                  
			 		   PName => $kick_pname
                 	));  
                     
	$tpl->printToScreen();
?>