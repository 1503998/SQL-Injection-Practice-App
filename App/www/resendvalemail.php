<?php

/*****************************************************************************/
/* resendvalemail.php                                                        */
/*****************************************************************************/
/* Gravity Board X                                                           */
/* Open-Source Project started by Jonathan Taft (admin@gravityboardx.com)    */
/* Software Version: GBX Version 2.0                                         */
/* ========================================================================= */
/* Copyright (c) 2002-2006 Gravity Board X Developers. All Rights Reserved   */
/* Software by: The Gravity Board X Development Team                         */
/*****************************************************************************/
/* This program is free software; you can redistribute it and/or modify it   */
/* under the terms of the GNU General Public License as published by the     */
/* Free Software Foundation; either version 2 of the License, or (at your    */
/* option) any later version.                                                */
/*                                                                           */
/* This program is distributed in the hope that it will be useful, but       */
/* WITHOUT ANY WARRANTY; without even the implied warranty of                */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General */
/* Public License for more details.                                          */
/*                                                                           */
/* The GNU GPL can be found in gpl.txt, which came with your download of GBX */
/*****************************************************************************/

//
//This script resends the validation email to a user that has previously registered.
//

?>

<div class="header">
  <p align="center"><font class="headerfont">Resend Validation Email</font>
</div>

<?php
//CHECK TO SEE IF ACCOUNT IS ALREADY VERIFIED
//Clean input
$newemail = clean_input($_POST['validationemail']);
$valcheck = mysql_query("SELECT * FROM " . $prefix . "members WHERE email='$newemail' AND verified = '0'") OR DIE("Gravity Board X was unable to verify the specified account: " . mysql_error());
if(mysql_num_rows($valcheck) == 0)
{
	echo 'The user account <b>' . $_POST['validationemail'] . '</b> either does not exist or is already verified.';
}else
{

while($tempcheck = mysql_fetch_assoc($valcheck))
{
	$verifyid = $tempcheck['verifyid'];
	$tempusername = $tempcheck['displayname'];
}

//Set email headers
$header = "From: \"$boardname\" <gravityboardx@" . $_SERVER['HTTP_HOST'] . ">\nX-Mailer: Gravity Board X Message Board System";

//Set email subject
$subject = "" . $boardname . " Registration";

//Set email message
$regquery = mysql_query("SELECT regemail FROM " . $prefix . "settings") OR DIE("Gravity Board X was unable to retrieve the registration information from the database: " . mysql_error());
list($message) = mysql_fetch_row($regquery);
$message .= "\n\nForum Name: " . $boardname . " (Powered By Gravity Board X)\nEmail: " . $newemail . "\nUsername: " . $tempusername . "\n\nTo complete your registration, please visit the following link: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?action=verify&emailverify=" . $_POST['validationemail'] . "&verifyid=" . $verifyid . "";

//Send confirmation email
if(mail($newemail, $subject, $message, $header))
{
	echo 'Your validation email has been resent.  Please check your email to verify your account before logging in.';
}else
{
	echo 'An error occurred while sending your registration email.  Please contact the site administrator.';
}

}

?>

