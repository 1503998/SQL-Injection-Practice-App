<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
  
  	$tpl = new TemplatePower( "./templates/user_login.tpl" );
  	$tpl->prepare();

  	$login = displayLogin();
	  
  	$tpl->assign("LoginForm", $login );
  	$tpl->printToScreen(); 

?>