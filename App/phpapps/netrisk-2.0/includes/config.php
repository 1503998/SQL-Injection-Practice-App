<?php

	/**************************************************

		Project	NetRisk <http://netrisk.sourceforge.net>
		Author	PMuldoon <ptmuldoon@gmail@gmail.com>
		License	GPL

	 **************************************************/
	
	session_start();	
	// import some important stuff
	require(dirname(__FILE__) . '/../config.php');
	require_once(dirname(__FILE__) . '/mysql.php');
 	require_once(dirname(__FILE__) . '/../functions/function_common.php');
 	require_once(dirname(__FILE__) . '/errors.php');
 	require_once(dirname(__FILE__) . '/login.php');
	require_once(dirname(__FILE__) . '/chat.php');
 	require_once(dirname(__FILE__) . '/class.TemplatePower.inc.php');	
	
?>