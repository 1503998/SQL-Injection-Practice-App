<?php

/*****************************************************************************/
/* censor.php                                                                */
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

?>

<div class="station">

<div id="header_banform" class="header">
<span class="headerfont">Account Verification</span>
</div>

<?php

//
//This script verifies a user after registration, and clicking the verify link in their email
//

//Get verification ID for this email address from the database
$tempemail = $_GET['emailverify'];
$tempvid = $_GET['verifyid'];
$emailquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE email = '$tempemail' AND verifyid = '$tempvid'") OR DIE("An error occurred while attempting to verify this account: " . mysql_error());

//If mysql_num_rows($emailquery) == 0 then there is no email match in the database
if(mysql_num_rows($emailquery) == 0)
{
    echo 'The email address and verification ID provided do not match.  Please try again.';
}else
{
    //Update the database so that the new user is "verified"
    mysql_query("UPDATE " . $prefix . "members SET verified = 1 WHERE email = '$tempemail' AND verifyid = '$tempvid'") OR DIE("An error occurred while attempting to verify this account: " . mysql_error());

    echo 'Congratulations, your account is now verified!  You may now login with the email address and password supplied during registration.';
}

?>

</div>