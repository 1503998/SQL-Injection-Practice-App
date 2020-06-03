<?php

/*****************************************************************************/
/* sessionlogout.php                                                         */
/*****************************************************************************/
/* Gravity Board X                                                           */
/* Open-Source Project started by Jonathan Taft (admin@gravityboardx.com)    */
/* Software Version: GBX Version 2.0                                         */
/* ========================================================================= */
/* Copyright (c) 2002-2007 Gravity Board X Developers. All Rights Reserved   */
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

//RECORD USER LOGOUT DATA
mysql_query("UPDATE " . $prefix . "tracking SET logouttime = '$savetime', logoutip = '{$_SERVER['REMOTE_ADDR']}' WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to update your tracking status: " . mysql_error());

//DELETE FROM ONLINE TABLE
mysql_query("UPDATE " . $prefix . "online SET memberid = '' WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to update your online status: " . mysql_error());

if(isset($_SESSION['usecookie']) && $_SESSION['usecookie'] == 1)
{
	//DESTROY COOKIE
	setcookie("gbxemail", "", time()-3600, "/", $_SERVER['HTTP_HOST'], 0);
	setcookie("gbxpw", "", time()-3600, "/", $_SERVER['HTTP_HOST'], 0);
}

//DESTROY SESSION
session_destroy();

echo 'Logging out...';
header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");

?>
