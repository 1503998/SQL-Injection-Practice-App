<?php

/*****************************************************************************/
/* checkcookie.php                                                           */
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
//This script tracks the cookies set in the user's browser. It remembers     //
//their login information when they view the message board.                  //
///////////////////////////////////////////////////////////////////////////////

session_start();

$_REAL_SCRIPT_DIR = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
$_REAL_BASE_DIR = realpath(dirname(__FILE__));
$CURRENTPATH = substr($_REAL_SCRIPT_DIR, strlen($_REAL_BASE_DIR));

if(isset($_SESSION['sr']) && $_SESSION['sr'] == 2 && isset($_SESSION['email']) && isset($_SESSION['pw']))
{

	if(isset($_SESSION['usecookie']) && $_SESSION['usecookie'] == 1)
	{
		setcookie("gbxemail", base64_encode($_SESSION['email']), time()+604800, $CURRENTPATH, $_SERVER['HTTP_HOST'], 0);
		setcookie("gbxpw", base64_encode($_SESSION['pw']), time()+604800, $CURRENTPATH, $_SERVER['HTTP_HOST'], 0);
	}

}elseif(isset($_COOKIE['gbxemail']) && isset($_COOKIE['gbxpw']))
{
	$_SESSION['email'] = base64_decode($_COOKIE['gbxemail']);
	$_SESSION['pw'] = base64_decode($_COOKIE['gbxpw']);

	setcookie("gbxemail", base64_encode($_SESSION['email']), time()+604800, $CURRENTPATH, $_SERVER['HTTP_HOST'], 0);
	setcookie("gbxpw", base64_encode($_SESSION['pw']), time()+604800, $CURRENTPATH, $_SERVER['HTTP_HOST'], 0);
}

?>
