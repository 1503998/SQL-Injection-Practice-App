<?php

/*****************************************************************************/
/* postreply.php                                                             */
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

include_once("global.php");

if($_SESSION['sr'] == '2')
{
    //Check for lock status

    if($_SESSION['perm'] != '1' && $_SESSION['perm'] != '2')
    {
	    $thread_id = clean_input($_GET['thread_id']);
            $lockquery = mysql_query("SELECT locked FROM " . $prefix . "threads WHERE thread_id='$thread_id'") OR DIE("Gravity Board X was unable to verify posting permissions for this thread: " . mysql_error());
            $locked = mysql_fetch_array($lockquery);
            if($locked[0] == '1')
            {
                echo '<font color="#FF0000">Access denied. This thread is locked. You may not reply to it.</font>';
                exit;
            }
    }

	$newmessage = clean_input($_POST['FCKeditorContents']);
	$newbid     = clean_input($_GET['board_id']);
	$newtid     = clean_input($_GET['thread_id']);

    //Insert post data into MySQL posts table
    mysql_query("INSERT INTO " . $prefix . "posts (message, dateposted, ip, memberid, board_id, thread_id) VALUES ('$newmessage','$savetime','{$_SERVER['REMOTE_ADDR']}','{$_SESSION['memberid']}','$newbid','$newtid')") OR DIE("Gravity Board X was unable to insert your post into the posts database: " . mysql_error());

    $midquery = mysql_query("SELECT msg_id FROM " . $prefix . "posts ORDER BY msg_id DESC LIMIT 1") OR DIE("Gravity Board X was unable to retrieve the message id: " . mysql_error());
    list($msgid) = mysql_fetch_row($midquery);

    //Update thread info
    mysql_query("UPDATE " . $prefix . "threads SET reply_num=reply_num+1, last_msg_time= '$savetime', last_msg='$msgid' WHERE thread_id='$newtid'") OR DIE("Gravity Board X was unable to update the thread information: " . mysql_error());

    echo 'Saving Post. Please wait...';
    
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?action=viewthread&thread_id=" . $_GET['thread_id'] . "&board_id=" . $_GET['board_id'] . "");

}else
{
    accessdenied();
}

?>

