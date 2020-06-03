<?php

/*****************************************************************************/
/* configure.php                                                             */
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
//This script modifies the board configuration file
//

session_start();

if($_SESSION['perm'] == '1')
{

if($filepointer = fopen("../../config.php", "w"))
{
    fputs($filepointer, '<? $hostname="' . $_POST['hostname'] . "\";\r\n");
    fputs($filepointer, '$prefix="' . $_POST['prefix'] . "\";\r\n");
    fputs($filepointer, '$username="' . $_POST['username'] . "\";\r\n");
    fputs($filepointer, '$password="' . $_POST['password'] . "\";\r\n");
    fputs($filepointer, '$dbname="' . $_POST['dbname'] . "\";\r\n");
    fputs($filepointer, '$boardname="' . $_POST['boardname'] . "\";\r\n");
    fputs($filepointer, "function dbconnect(){global \$hostname;global \$username;global \$password;global \$dbname;\$connection = MYSQL_CONNECT(\$hostname,\$username,\$password) OR DIE(\"Gravity Board X was unable to connect to the specified database. Please go back and double-check the database you supplied.\");\r\n @mysql_select_db(\"\$dbname\") OR DIE(\"Gravity Board X was able to connect to your SQL host, but had difficulties selecting the specified database.  Please go back and double-check the database you supplied.\");\r\n return \$connection;}?>");
    fclose($filepointer);
    echo '<span class="errortext">The configuration file was saved successfully!</span>';
}else
{
    echo '<span class="errortext">Gravity Board X was unable to save changes to the configuration file.</span>';
}

}else
{
    //Do nothing
}

?>