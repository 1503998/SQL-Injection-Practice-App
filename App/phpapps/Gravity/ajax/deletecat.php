<?php

/*****************************************************************************/
/* deletecat.php                                                             */
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

include("../ajaxinclude.php");

if($_SESSION['perm'] == '1')
{
    //Make changes to the board in the database
    mysql_query("DELETE FROM " . $prefix . "categories WHERE cat_id = '{$_POST['cat_id']}'") OR DIE("Gravity Board X was unable to save changes to the database: " . mysql_error());
    mysql_query("DELETE FROM " . $prefix . "boards WHERE cat_id = '{$_POST['cat_id']}'") OR DIE("Gravity Board X was unable to save changes to the database: " . mysql_error());
    mysql_query("DELETE FROM " . $prefix . "posts WHERE cat_id = '{$_POST['cat_id']}'") OR DIE("Gravity Board X was unable to save changes to the database: " . mysql_error());

    echo '<span class="errortext">Category deleted successfully!</span>';

}else
{
    accessdenied();
}

?>

