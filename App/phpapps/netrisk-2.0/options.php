<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	$tpl = new TemplatePower('./templates/options.tpl');
	$tpl->prepare();	
 
	$gid = $_GET['id'];

	$tpl -> assign("GID", $gid);
 
	$tpl->printToScreen();
?>