<?php

	/**************************************************

		Project	sBLOG <http://sblog.sourceforge.net>
		Author	Servous <servous@gmail.com>
		License	GPL

	 **************************************************/
		 
	$conn = mysql_connect($mysql_hostname, $mysql_username, $mysql_password) or die('MySQL error: ' . mysql_errno() . ' ' . mysql_error());
	mysql_select_db($mysql_database) or die('MySQL error: ' . mysql_errno() . ' ' . mysql_error());
	

?>