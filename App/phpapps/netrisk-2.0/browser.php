<?php


	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
  	$tpl = new TemplatePower( "./templates/browser.tpl" );
  	$tpl->prepare();
  
  	//Page Title
  	$tpl -> assign("PageTitle", "Game Browser"); 
  
  	//This is needed to reset the Game ID so that the chats post correctly
  	if(isset($_SESSION['gid'])){
  		unset($_SESSION["gid"]);
  		$location = $_SERVER['REQUEST_URI'];
   		header("Location: $location");
	}
  
  	//Assign the GameCSS when Game is called
  	$tpl -> assign("BaseCss", "base"); 
  
  	$tpl->printToScreen(); 
?>