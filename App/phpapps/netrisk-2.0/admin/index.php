<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Admin Index.php 
	include('./../includes/config.php');

	$tpl = new TemplatePower('./templates/admin/admin_index.tpl');
	$tpl->prepare();	

	//Create a list of all of the games

 	$query = mysql_query("SELECT * FROM ". $mysql_prefix ."game_info ORDER by gid");
 
	 while($row = mysql_fetch_assoc($query)){
		//create a new TopTen block
		$tpl->newBlock("GameList");
		//assign values
		$tpl -> assign("GID", $row['gid']);		
		$tpl -> assign("GName", $row['gname']);
	 }

	$tpl->printToScreen();
?>