<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./config.php');
	
  	$tpl = new TemplatePower( "./templates/users_online.tpl" );
  	$tpl->prepare();

  	$query = mysql_query("SELECT usr.id, usr.login, active.username FROM ". $mysql_prefix . "users usr, ". $mysql_prefix . "active_users active WHERE active.username = usr.login") or die(mysql_error());
	$num_rows = mysql_num_rows($query);
	
	if(!$query || ($num_rows < 0)){
   	//	echo "Error displaying info";
	} else if($num_rows > 0){
   		// Get the Users in the Table
   		while($users = mysql_fetch_assoc($query)){
			$tpl -> newBlock("ActiveUsers"); 	
			$tpl -> assign("ID", $users['id']);
			$tpl -> assign("Users", $users['username']);
  		}
	}
  		  	
  	$tpl->printToScreen(); 
  
?>