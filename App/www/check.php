<?php

/*****************************************************************************/
/* check.php                                                                 */
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

///////////////////////////////////////////////////////////////////////////////
//----------------------------SCRIPT INFORMATION-----------------------------//
//This script keeps track of all the global session variables and values.    //
//                                                                           //
//$_SESSION['sr'] - Holds the user's login status. 1 if the user is not      //
//                  logged in, 2 if the user is logged in and approved by the//
//                  system, 3 if there was an error during their login       //
//                  attempt.                                                 //
//$_SESSION['perm'] - Holds the user's permission status. 0 if the user is a //
//                    standard user, 1 if the user is an administrator, 2 if //
//                    the user is a moderator.                               //
///////////////////////////////////////////////////////////////////////////////

include("global.php");

//DECLARE VARIABLES
if(isset($_SESSION['sr']) && $_SESSION['sr'] != 2)
{
	$_SESSION['myinfo']['displayname'] = '';
	$_SESSION['myinfo']['signature'] = '';
	$_SESSION['myinfo']['rank'] = '';
	$_SESSION['myinfo']['color'] = '';
	$_SESSION['myinfo']['pc'] = '';
	$_SESSION['myinfo']['icon_url'] = '';
	$_SESSION['myinfo']['icon_height'] = '';
	$_SESSION['myinfo']['icon_width'] = '';
	$_SESSION['myinfo']['group_name'] = '';
}

//CHECK TO SEE IF USER IS ALREADY LOGGED IN
if(isset($_SESSION['sr']) && $_SESSION['sr'] == 2)
{
	$memquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE email='{$_SESSION['email']}'");
	while($memberinfo = mysql_fetch_assoc($memquery))
	{
		//Do nothing, user is logged in
		$_SESSION['myinfo']['displayname'] = $memberinfo['displayname'];
		$_SESSION['myinfo']['signature'] = censor(stripslashes($memberinfo['signature']));
		rank($memberinfo['memberid']);
		$_SESSION['myinfo']['rank'] = $userRank['rank'];
		$_SESSION['myinfo']['color'] = $userRank['color'];
		$_SESSION['myinfo']['pc'] = $userRank['pc'];
		$_SESSION['myinfo']['icon_url'] = $memberinfo['icon_url'];
		$_SESSION['myinfo']['icon_height'] = $memberinfo['icon_height'];
		$_SESSION['myinfo']['icon_width'] = $memberinfo['icon_width'];

		$mgquery = mysql_query("SELECT group_name FROM " . $prefix . "membergroups WHERE group_id = '{$memberinfo['memberGroup']}'");
		list($groupname) = mysql_fetch_row($mgquery);
		$_SESSION['myinfo']['group_name'] = $groupname;
	}
}else
{
	//SET SECURITY VARIABLES: This classifies the user as not logged in, not having admin/mod permissions,
	//no email address or password and being non-verified
	$_SESSION['sr'] = 1;
	$_SESSION['perm'] = 0;
	$_SESSION['verified'] = 0;
	$_SESSION['memberid'] = '';
	$_SESSION['debug_login_result'] = '';
	$_SESSION['tperpage'] = '30';
	$_SESSION['mperpage'] = '15';
	
	//CHECK TO SEE IF A LOGIN WAS JUST ATTEMPTED OR COOKIE IS SET
	if(isset($_POST['login_email']) && isset($_POST['login_pw']) || isset($_COOKIE['gbxemail']) && isset($_COOKIE['gbxpw']))
	{
		//A LOGIN WAS JUST ATTEMPTED OR A COOKIE IS PRESENT: VERIFY USER DATA BY ATTEMPTING A LOGIN
		//RESULT IS STORED IN VARIABLE $_SESSION['debug_login_result']
		if(isset($_POST['login_email']) && isset($_POST['login_pw']))
		{
			loginuser($_POST['login_email'], MD5($_POST['login_pw']));
		}elseif(isset($_COOKIE['gbxemail']) && isset($_COOKIE['gbxpw']) && isset($_SESSION['email']) && isset($_SESSION['pw']))
		{
			loginuser($_SESSION['email'], $_SESSION['pw']);
		}

		if($_SESSION['debug_login_result'] == 0)
		{
			//LOGIN IS OK: EMAIL/PASSWORD MATCH AND USER IS VALIDATED
			//SET USER AS LOGGED IN: EMAIL AND PASSWORD ARE SET BY loginuser() FUNCTION
			$_SESSION['sr'] = 2;
			$_SESSION['verified'] = 1;

			//RECORD MEMBER PREFERENCES AS SESSION VALUES
			recprefs($_SESSION['email']);

			//CHECK USER'S PERMISSIONS
			$memquery = mysql_query("SELECT memberGroup FROM " . $prefix . "members WHERE email='{$_SESSION['email']}'");
			list($membergroup) = mysql_fetch_row($memquery);
			$mgquery = mysql_query("SELECT * FROM " . $prefix . "membergroups WHERE group_id = '$membergroup'");
			while($perms = mysql_fetch_assoc($mgquery))
			{
				$_SESSION['myinfo']['group_name'] = $perms['group_name'];

				if($perms['group_type'] == 1)
				{
					//USER HAS ADMINISTRATOR PERMISSIONS
					$_SESSION['perm'] = 1;

				}elseif($perms['group_type'] == 2)
				{
					//USER HAS MODERATOR PERMISSIONS
					$_SESSION['perm'] = 2;

				}
			}

		}elseif($_SESSION['debug_login_result'] == 1)
		{
			//INCORRECT EMAIL OR PASSWORD
		}elseif($_SESSION['debug_login_result'] == 2)
		{
			//EMAIL NOT IN DATABASE OR MULTIPLE ENTRIES FOR SAME EMAIL IN DATABASE
		}elseif($_SESSION['debug_login_result'] == 3)
		{
			//ACCOUNT NOT VALIDATED, BUT CORRECT CREDENTIALS
		}elseif($_SESSION['debug_login_result'] == 4)
		{
			//INVALID EMAIL FORMAT
		}
	}
}

?>
