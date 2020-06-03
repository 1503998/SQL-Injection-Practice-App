<?php

/*****************************************************************************/
/* messagelogend.php                                                         */
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

if($_SESSION['sr'] == '2')
{
    if(!in_array($postrow['msg_id'], $logarray))
    {
	     $nlp = array($loggedposts, $postrow['msg_id']);
	     $newloggedposts = implode(",", $nlp);
	     mysql_query("UPDATE " . $prefix . "message_logs SET messagesread='$newloggedposts' WHERE memberid = '" . $_SESSION['memberid'] . "'") OR DIE("Gravity Board X was unable to log your post view: " . mysql_error());
	 }
}

?>

