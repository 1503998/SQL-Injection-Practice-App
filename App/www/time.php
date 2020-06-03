<?php

/*****************************************************************************/
/* time.php                                                                  */
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

///////////////////////////////////////////////////////////////////////////////
//----------------------------SCRIPT INFORMATION-----------------------------//
//This script holds information regarding the time for the message board,    //
//storing the information in variables.  They are as follows:                //
//$hourdiff - Holds the time zone hour difference for the logged in user.  If//
//no user is logged in, it is set to the board default.                      //
//$timeadjust - Holds the time adjustment in seconds needed to go from the   //
//board default time to the user's time.                                     //
//$time - Current date/time in DayOfWeek, Month/DD/YYYY HH:MM:SS AM/PM format//
//$savetime - Holds the current time in unix timestamp format.               //
//These variables may be accessed at any time in any script included after   //
//time.php in the main script index.php.                                     //
///////////////////////////////////////////////////////////////////////////////

//Check if user is logged in
if($_SESSION['sr'] == '2')
{
    //Get the user's time difference from the database
    $timequery = mysql_query("SELECT timediff FROM " . $prefix . "members WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to verify your time zone: " . mysql_error());

    //Assign user's time difference to $hourdiff
    list($hourdiff) = mysql_fetch_row($timequery);
}else
{
    //If user is not logged in, set the time difference to 0
    $hourdiff = "0";
}

//Adjust the hour difference to seconds, which compensates for the unix timestamp that is in seconds
$timeadjust = ($hourdiff * 60 * 60) - date("Z", time());

//Store current time in $time
$time = date("l, F d Y, h:i:s a", time());

//$savetime is a global variable that holds the current time in unix timestamp format
$savetime = time();

?>

