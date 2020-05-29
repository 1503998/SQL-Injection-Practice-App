<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	  
  	$tpl = new TemplatePower( "./templates/chat.tpl" );
  	$tpl -> prepare();
 
	$chat = jal_get_shoutbox();
 
	$tpl -> assign("Chat", $chat);
 
  	$tpl -> printToScreen(); 
?>