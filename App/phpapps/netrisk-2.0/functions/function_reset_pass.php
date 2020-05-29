<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	require_once('../includes/config.php');

	$uname = mysql_real_escape_string($_POST['user_name']);
	$uemail = mysql_real_escape_string($_POST['user_email']);

	$query = mysql_query("SELECT * FROM ". $mysql_prefix ."users WHERE login = '{$uname}' AND email = '{$uemail}' ") or die(mysql_error());
	if(mysql_num_rows($query) < 1){
		//No match found, give error
		reset_error_header("That username and email was not found!");
		exit;
	} else {
		//Generate New Password
		$newPass = genPassword(7,7,2,2,0);
		$md5Pass = md5($newPass);

		//Update $md5Pass into database
		$query2 = mysql_query("UPDATE ". $mysql_prefix ."users SET password = '{$md5Pass}' WHERE login = '{$uname}' AND email = '{$uemail}' ") or die(mysql_error());

		//Send User an email with their new password
		$EmailTo = "{$uemail}";
		$EmailFrom = "WebMaster@mysite.com";
		$EMailFromName = "Webmaster";
		$EMailSubject = "NetRisk: Info Request";
		$EmailMessage = "A user at this email address requested a password be reset for {$_SERVER['SERVER_NAME']} . <br /><br />";
		$EmailMessage .= "Your new password is: {$newPass}. <br /><br />";
		$EmailMessage .= "It is recommended that you immediately login and change your password. <br />";

		SendEmail($EmailTo,$EmailFrom,$EmailFromName,$EMailSubject,$EmailMessage);  //Common Function
	
		//Redirect to p=reset&mode=success?
		header("Location: ../index.php?p=reset&mode=passchange");
	}
?>