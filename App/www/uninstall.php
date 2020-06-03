<?php

/*****************************************************************************/
/* uninstall.php                                                             */
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
//This script uninstalls all MySQL data
//

?>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="submit" name="btndelete" value="Yes, DELETE my copy of GBX">
<input type="hidden" name="deleteyes">
</form>

<?php

if(isset($_POST['deleteyes'])){

//Include configurations
include("config.php");
$connection = dbconnect($hostname, $username, stripslashes($password), $dbname);

//Drop all tables from the database

mysql_query("DROP TABLE " . $prefix . "announcements", $connection) OR DIE("Gravity Board X was unable to delete the announcements table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "banned") OR DIE("Gravity Board X was unable to delete the banned table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "boards") OR DIE("Gravity Board X was unable to delete the boards table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "categories") OR DIE("Gravity Board X was unable to delete the categories table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "censor") OR DIE("Gravity Board X was unable to delete the censor table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "ims") OR DIE("Gravity Board X was unable to delete the ims table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "membergroups") OR DIE("Gravity Board X was unable to delete the membergroups table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "members") OR DIE("Gravity Board X was unable to delete the members table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "memberstats") OR DIE("Gravity Board X was unable to delete the memberstats table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "message_logs") OR DIE("Gravity Board X was unable to delete the message_logs table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "online") OR DIE("Gravity Board X was unable to delete the online table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "posts") OR DIE("Gravity Board X was unable to delete the posts table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "pwreset") OR DIE("Gravity Board X was unable to delete the pwreset table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "ranks") OR DIE("Gravity Board X was unable to delete the ranks table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "settings") OR DIE("Gravity Board X was unable to delete the settings table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "stats") OR DIE("Gravity Board X was unable to delete the stats table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "threads") OR DIE("Gravity Board X was unable to delete the threads table: " . mysql_error());

mysql_query("DROP TABLE " . $prefix . "tracking") OR DIE("Gravity Board X was unable to delete the tracking table: " . mysql_error());

//Close MySQL database connection
mysql_close($connection);

echo "The SQL data of Gravity Board X has been successfully uninstalled. To complete the uninstallation, you may now remove the scripts from your web directory.";

}

?>

