<?php

/*****************************************************************************/
/* SCRIPT_NAME.php                                                           */
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
//This script modifies a topic's properties so that it is displayed above all other topics, called elevating, or floating
//However if the topic is already floated, it is unfloated, or sunk
//

include_once("global.php");

if($_SESSION['perm'] == '1' || $_SESSION['perm'] == '2')
{
    $equery = mysql_query("SELECT * FROM " . $prefix . "threads WHERE thread_id = '{$_GET['thread_id']}'", $connection) OR DIE("Gravity Board X experienced an error while checking the elevation status of the thread: " . mysql_error());
    while($estatus = mysql_fetch_assoc($equery))
    {
	     if($estatus['sticky'] == '0')
        {
            mysql_query("UPDATE " . $prefix . "threads SET sticky = '1' WHERE thread_id = '{$_GET['thread_id']}'") OR DIE("Gravity Board X experienced an error while attempting to elevate the thread: " . mysql_error());
            echo 'Floating thread...';
        }else
        {
            mysql_query("UPDATE " . $prefix . "threads SET sticky = '0' WHERE thread_id = '{$_GET['thread_id']}'") OR DIE("Gravity Board X experienced an error while attempting to elevate the thread: " . mysql_error());
            echo 'Sinking thread...';
	     }
    }
    
?>

<META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php?action=viewboard&board_id=<?php echo $_GET['board_id']; ?>">

<?php

}else
{
    accessdenied();
}

?>
