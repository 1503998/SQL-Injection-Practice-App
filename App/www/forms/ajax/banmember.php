<?php

/*****************************************************************************/
/* banmember.php                                                             */
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

//
//This script bans a member from accessing the forum, either by IP address or email
//

include("ajaxinclude.php");

if($_SESSION['perm'] == 1)
{
	$banuntil = mktime($_POST['banhour'],$_POST['banminute'],$_POST['bansecond'],$_POST['banmonth'],$_POST['banday'],$_POST['banyear']);

	//Record ban to database
	mysql_query("INSERT INTO " . $prefix . "banned (ip, email, bandate, banuntil, banreason) VALUES ('{$_POST['ip']}','{$_POST['banemail']}',time(),'$banuntil','{$_POST['banreason']}')");

	echo '<span class="errortext">Ban information saved successfully!</span>';

}else
{
    accessdenied();
}

?>

