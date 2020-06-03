<?php

/*****************************************************************************/
/* createmembergroup.php                                                     */
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
//This script creates a new membergroup
//

include_once("global.php");

if($_SESSION['perm'] == '1')
{
    //Add new membergroup to the database
    mysql_query("INSERT INTO " . $prefix . "membergroups (group_name, group_type) VALUES ('{$_POST['group_name']}','{$_POST['group_type']}')") OR DIE ("Gravity Board X was unable to create a new member group: " . mysql_error());

    echo 'Creating Member Group. Please wait...';
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?action=editmembergroup");

}else
{
    accessdenied();
}

?>

