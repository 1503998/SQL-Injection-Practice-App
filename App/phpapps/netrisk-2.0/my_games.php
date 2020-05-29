<?php
	
	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	  
  	$tpl = new TemplatePower( "./templates/my_games.tpl" );
  	$tpl -> prepare();
  	$query = mysql_query("Select * FROM ". $mysql_prefix . "game_players WHERE pname = '".$_SESSION['username']."' ");
  
  	while ($row = mysql_fetch_assoc($query)){  
	 	$tpl -> newBlock("MyGames");
	  	$tpl -> assign("GID", $row['gid'] );
	  	$tpl -> assign("GName", $row['gname'] );
	  	$tpl -> assign("pstate", $row['pstate'] );
  	}
	
  	$tpl -> printToScreen(); 
?>