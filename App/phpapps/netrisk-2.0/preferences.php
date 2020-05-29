<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

  	$tpl = new TemplatePower('./templates/preferences.tpl');
  	$tpl->prepare();	

  	//Get Player's Current Mail Updates for Individual Game?
	$query = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = {$_SESSION['gid']} AND pname = '{$_SESSION['username']}' ");
  	$query_row = mysql_fetch_assoc($query);
  	$pmail = $query_row['pmail'];

  	if($pmail == 1){
  		$tpl -> assign("CheckYes", 'checked');
	  	$tpl -> assign("CheckNo", '');  
  	} else {
	  	$tpl -> assign("CheckYes", '');
	  	$tpl -> assign("CheckNo", 'checked');  
  	}

	$tpl->printToScreen();
?>