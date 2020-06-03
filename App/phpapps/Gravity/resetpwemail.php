<?php

/*****************************************************************************/
/* resetpwemail.php                                                          */
/*****************************************************************************/
/* Gravity Board X                                                           */
/* Open-Source Project started by Jonathan Taft (admin@gravityboardx.com)    */
/* Software Version: GBX Version 2.0                                         */
/* ========================================================================= */
/* Copyright (c) 2002-2005 Gravity Board X Developers. All Rights Reserved   */
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

///////////////////////////////////////////////////////////////////////////////
//----------------------------SCRIPT INFORMATION-----------------------------//
//This script sends an email to the user which requested a password reset.   //
///////////////////////////////////////////////////////////////////////////////

?>

<div class="headermid">

<div class="header">
  <span class="headerfont">Reset Your Password</span>
</div>

<div class="content">

<?php

$findemail = mysql_query("SELECT * FROM " . $prefix . "members WHERE email = '{$_POST['emailreset']}'") OR DIE("An error occurred while checking for existing accounts: " . mysql_error());

if(mysql_num_rows($findemail) == 0)
{
	echo '<span><font color="#FF0000"><b>The email address you entered does not exist.  Please try again.</b></font></span>';
}else
{

	//Generate random verification ID
	$resetid = randstring();

	//Add reset data to database
	$cleanemail = clean_input($_POST['emailreset']);
	mysql_query("INSERT INTO " . $prefix . "pwreset (resetemail, resetid) VALUES ('$cleanemail','$resetid')") OR DIE("Gravity Board X was unable to record password reset information in the database: " . mysql_error());

	//Set email headers
	$header = "From: \"$boardname\" <gravityboardx@" . $_SERVER['HTTP_HOST'] . ">\nX-Mailer: Gravity Board X Message Board System";

	//Set email subject
	$subject = "" . $boardname . " Password Reset Link";

	//Set email message
	$message = "It has been requested that the person using this email address reset their password to the following message board: " . $boardname . "\n\nIf this email has been sent by accident, please ignore it.  Otherwise, to reset your password please visit the following link: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?action=resetpwnow&emailreset=" . $_POST['emailreset'] . "&resetid=" . $resetid . "";

	//Send reset email
	mail($_POST['emailreset'], $subject, $message, $header);

	echo '<span>An email has been sent to you with a link to reset your password.</span>';

}

?>

</div>

<div class="headerbot">
</div>

</div>