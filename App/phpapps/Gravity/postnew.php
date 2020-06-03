<?php

/*****************************************************************************/
/* postnew.php                                                               */
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

	$newmessage = clean_input($_POST['FCKeditorContents']);
	$newsubject = clean_input($_POST['subject']);
	$newbid     = clean_input($_GET['board_id']);

//Insert post data into MySQL posts table
mysql_query("INSERT INTO " . $prefix . "posts (message, dateposted, ip, memberid, board_id, thread_id) VALUES ('$newmessage', '$savetime', '{$_SERVER['REMOTE_ADDR']}', '{$_SESSION['memberid']}', '{$_GET['board_id']}', '0')", $connection) OR DIE ("Gravity Board X was unable to insert your post into the posts database:" . mysql_error());
$midquery = mysql_query("SELECT msg_id FROM " . $prefix . "posts ORDER BY msg_id DESC LIMIT 1") OR DIE("Gravity Board X was unable to retrieve the message id: " . mysql_error());
list($mid) = mysql_fetch_row($midquery);

//Insert thread data into MySQL threads table
mysql_query("INSERT INTO " . $prefix . "threads (subject, first_msg, last_msg_time, board_id, last_msg) VALUES ('$newsubject','$mid', '$savetime', '$newbid', '$mid')") OR DIE ("Gravity Board X was unable to insert your post into the threads database: " . mysql_error());
$tidquery = mysql_query("SELECT thread_id FROM " . $prefix . "threads ORDER BY thread_id DESC LIMIT 1");
list($tid) = mysql_fetch_row($tidquery);

mysql_query("UPDATE " . $prefix . "posts SET thread_id = '$tid' WHERE msg_id = '$mid'") OR DIE("Gravity Board X was unable to revise the thread id in the post database: " . mysql_error());

echo "Saving Post. Please wait...";

header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?action=viewboard&board_id=" . $_GET['board_id'] . "");

}else
{
    accessdenied();
}

?>

