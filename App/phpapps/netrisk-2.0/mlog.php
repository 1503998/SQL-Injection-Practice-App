<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
		
  	// Mission Game Log  
  	$tpl = new TemplatePower( "./templates/mlog.tpl" );
  	$tpl -> prepare();

  	//This is needed to reset the Game ID so that the chats post correctly
  	if(isset($_SESSION['gid'])){
  		unset($_SESSION["gid"]);
  		$location = $_SERVER['REQUEST_URI'];
   		header("Location: $location");
	}
   
	$tpl -> printToScreen(); 
?>