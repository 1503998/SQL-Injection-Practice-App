<?php

/*****************************************************************************/
/* SCRIPT_NAME.php                                                           */
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

//
//This script locks or unlocks a thread, which prevents or enables posting replies
//

include_once("global.php");

if($_SESSION['perm'] == '1' || $_SESSION['perm'] == '2')
{
    $lquery = mysql_query("SELECT * FROM " . $prefix . "threads WHERE thread_id = '{$_GET['thread_id']}'", $connection) OR DIE("Gravity Board X experienced an error while checking the elevation status of the thread: " . mysql_error());
    while($lstatus = mysql_fetch_assoc($lquery))
    {
	     if($lstatus['locked'] == '0')
        {
            mysql_query("UPDATE " . $prefix . "threads SET locked = '1' WHERE thread_id = '{$_GET['thread_id']}'") OR DIE("Gravity Board X experienced an error while attempting to lock the thread: " . mysql_error());
            echo 'Locking thread...';
        }else
        {
            mysql_query("UPDATE " . $prefix . "threads SET locked = '0' WHERE thread_id = '{$_GET['thread_id']}'") OR DIE("Gravity Board X experienced an error while attempting to unlock the thread: " . mysql_error());
            echo 'Unlocking thread...';
	     }
    }
}else
{
    accessdenied();
}
?>
<META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php?action=viewthread&thread_id=<?php echo $_GET['thread_id']; ?>&board_id=<?php echo $_GET['board_id']; ?>">