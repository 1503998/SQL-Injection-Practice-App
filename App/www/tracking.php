<?php

/*****************************************************************************/
/* tracking.php                                                              */
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

///////////////////////////////////////////////////////////////////////////////
//----------------------------SCRIPT INFORMATION-----------------------------//
//This script tracks the current user's online status.                       //
///////////////////////////////////////////////////////////////////////////////

$exptime = time() - 900;
$query = mysql_query("DELETE FROM " . $prefix . "online WHERE lastactive < '$exptime'") OR DIE("Gravity Board X was unable to verify the amount of online users: " . mysql_error());

if(!isset($_SESSION['online']))
{
    $exist = mysql_query("SELECT * FROM " . $prefix . "online WHERE memberid = '{$_SESSION['memberid']}'");
    if(mysql_num_rows($exist) == 1)
    {
        mysql_query("UPDATE " . $prefix . "online SET session_id = '".session_id()."', lastactive = '$savetime', member = '{$_SESSION['sr']}', ip_address = '{$_SERVER['REMOTE_ADDR']}' WHERE memberid = '{$_SESSION['memberid']}'");
    }else
    {
        if(!isset($_SERVER['HTTP_REFERER']))
        {
            $referer = '';
        }else
	{
		$referer = $_SERVER['HTTP_REFERER'];
	}
        mysql_query("INSERT INTO " . $prefix . "online (session_id, firstonline, lastactive, memberid, ip_address, refurl, useragent) VALUES ('".session_id()."', '$savetime', '$savetime', '{$_SESSION['memberid']}','{$_SERVER['REMOTE_ADDR']}', '$referer', '{$_SERVER['HTTP_USER_AGENT']}')");
    }
    $_SESSION['online'] = '';
}else
{
    mysql_query("UPDATE " . $prefix . "online SET lastactive = '$savetime', member = '{$_SESSION['sr']}', memberid = '{$_SESSION['memberid']}' WHERE session_id='".session_id()."'");
}

?>