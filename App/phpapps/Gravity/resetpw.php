<?php

/*****************************************************************************/
/* resetpw.php                                                               */
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
//This script resets the user's password to a random string.                 //
///////////////////////////////////////////////////////////////////////////////

?>

<div class="headermid">

<div class="header">
  <span class="headerfont">Reset Your Password</span>
</div>

<div class="content">

<?php

//Check data against database
$resetcheck = mysql_query("SELECT * FROM " . $prefix . "pwreset WHERE resetemail = '{$_GET['emailreset']}' AND resetid = '{$_GET['resetid']}'") OR DIE("Gravity Board X was unable to verify the password reset: " . mysql_error());

//If there is a match, reset the password
if(mysql_num_rows($resetcheck) >= 1)
{
    //Generate a new password
    $randpw = randstring();
    $randpw = substr($randpw, 16, 6);
    
    //Update the user's password in the database
    $newencpw = MD5($randpw);
    mysql_query("UPDATE " . $prefix . "members SET pw = '$newencpw' WHERE email = '{$_GET['emailreset']}'") OR DIE("Gravity Board X was unable to reset your password: " . mysql_error());
    
    //Remove password reset information from database for the user's email address
    mysql_query("DELETE FROM " . $prefix . "pwreset WHERE resetemail = '{$_GET['emailreset']}'") OR DIE("Gravity Board X was unable to clear the password reset information: " . mysql_error());
    
    //Send email to user with their new password
    //Set email headers
    $header = "From: \"$boardname\" <gravityboardx@" . $_SERVER['HTTP_HOST'] . ">\nX-Mailer: Gravity Board X Message Board System";
    
    //Set email subject
    $subject = "" . $boardname . " New Login Password";
    
    //Set email message
    $message = "It has been requested that the person using this email address reset their password to the following message board: " . $boardname . "\n\nThe verification process is already complete and your new password has been generated.  You may now login with the following information:\n\nPassword: " . $randpw . "";

    //Send new password email
    mail($_GET['emailreset'], $subject, $message, $header);
    
    echo 'Your password has been successfully reset.  Please check your email for your new password.';
//If there is no match, give an error
}else
{
    echo '<span><font color="#FF0000"><b>An error ocurred while attempting to reset your password: The verification ID and email address given do not match.</b></font></span>';
}

?>

</div>

<div class="headerbot">
</div>

</div>
