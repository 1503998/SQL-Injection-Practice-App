<?php

/*****************************************************************************/
/* banned.php                                                                */
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

///////////////////////////////////////////////////////////////////////////////
//----------------------------SCRIPT INFORMATION-----------------------------//
//This script establishes whether or not the user viewing the board is       //
//banned.                                                                    //
///////////////////////////////////////////////////////////////////////////////

if(!isset($_SESSION['banned']))
{
    $_SESSION['banned'] = '';
}

$banuntil = '';

$bannedquery = mysql_query("SELECT * FROM " . $prefix . "banned") OR DIE("Gravity Board X was unable to authenticate your access privelages: " .mysql_error());

while($bannedtemp = mysql_fetch_assoc($bannedquery))
{
    $banreason = $bannedtemp['banreason'];
    $banuntil = $bannedtemp['banuntil'];
    if($bannedtemp['ip'] == $_SERVER['REMOTE_ADDR'] && $bannedtemp['banuntil'] >= (time() + $timeadjust) )
    {
        $_SESSION['banned'] = 1;
    }elseif($bannedtemp['email'] == $_SESSION['email'] && $bannedtemp['banuntil'] >= (time() + $timeadjust) )
    {
        $_SESSION['banned'] = 1;
    }else
    {
        $_SESSION['banned'] = 0;
    }
}

$timeleft = $banuntil - time() + $timeadjust;

?>
