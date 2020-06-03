<?php

/*****************************************************************************/
/* global.php                                                                */
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
//This script contains some globally used functions for the board.           //
///////////////////////////////////////////////////////////////////////////////

if(file_exists("getthreads.php")){ include_once("getthreads.php"); }

//Declare globally used variables
global $userRank;

//This function sanitizes input to make it safe to be run in a mySQL query, preventing SQL injection attacks
function clean_input($input)
{
	//Escape quote characters
	if(!get_magic_quotes_gpc())
	{
		$input = addslashes($input);
	}

	$input = mysql_real_escape_string($input);

	//$input = htmlspecialchars($input);

	return $input;
}

//GET THE GLOBAL ADMINISTRATOR AND MODERATOR COLORS FROM THE DATABASE
$globalsettingsquery = mysql_query("SELECT * FROM " . $prefix . "settings") OR DIE("Gravity Board X was unable to retrieve the board settings: " . mysql_error());

while($globalsettings = mysql_fetch_assoc($globalsettingsquery))
{
    $admincolor = $globalsettings['admincolor'];
    $modcolor = $globalsettings['modcolor'];
    $currentskin = $globalsettings['currentskin'];
}

//This function is the error message that is displayed when access is denied to a page due to incorrect permissions
function accessdenied()
{
    echo '<div class="header">
<span class="headerfont">Access Denied</span>
</div>
<div class="row3">
            <font size="2" color="#FF0000"><b>Access denied.  You are not allowed to access this page.  This may be for one of the following reasons:<ul>';
            if($_SESSION['sr'] !== '2')
            {
                echo '<li>You are not logged in</li>';
            }
            if($_SESSION['perm'] == '0')
            {
                echo '<li>You do not have the correct permissions</li>';
            }
    echo '</ul></b></font></div>';
}

//RETRIEVE CENSORED WORDS
$censorquery = mysql_query("SELECT * FROM " . $prefix . "censor WHERE id = '1'") OR DIE("Gravity Board X was unable to retrieve the censored word list: " . mysql_error());

while($censor = mysql_fetch_assoc($censorquery))
{
    if($censor['enabled'] == 1)
    {
        $censoredwords = $censor['wordlist'];
	$docensor = true;
    }else
    {
        $censoredwords = '';
	$docensor = false;
    }
}

//This function censors words in the variable passed to the function
function censor($tocensor)
{
	global $censoredwords;
	global $censor;
	global $docensor;

	if($docensor)
	{
		$censorvar = explode(",", $censoredwords);

		foreach ($censorvar AS $word)
		{
			$tocensor = preg_replace("/".preg_quote($word, "/")."/i", "**bleep**", $tocensor);
		}
		return $tocensor;
	}else
	{
		return $tocensor;
	}
}

//This function generates a random string
function randstring()
{
    return MD5(rand(1000000000, 9999999999));
}

//This function checks the current user's login information in the database
function loginuser($chemail, $chpw)
{
    global $prefix;
    global $debug_login_result;
    global $savetime;

    //CHECK TO SEE IF THE EMAIL IS IN A VALID FORMAT
    if(eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $chemail) || $chemail == 'admin')
    {
        //Do nothing and proceed
    }else{
        //SET THE DEBUG LOGIN RESULT VARIABLE TO 4; NOT A VALID EMAIL ADDRESS
        $_SESSION['debug_login_result'] = 4;

	//End function
	return;
    }

    //SANITIZE INPUT TO BE SENT TO A MYSQL QUERY
    $chemail = clean_input($chemail);
    $chpw = clean_input($chpw);

    //CHECK TO SEE IF EMAIL EXISTS IN DATABASE
    $userquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE email = '$chemail'") OR DIE("Gravity Board X experienced an error while trying to verify your user data (global.php 1): " . mysql_error());

    if(mysql_num_rows($userquery) == 1)
    {
        while($userdata = mysql_fetch_assoc($userquery))
        {
            if($chpw == $userdata['pw'])
            {
                if($userdata['verified'] == 0)
                {
                    //SET THE DEBUG LOGIN RESULT VARIABLE TO 3; USER NOT VALIDATED
                    $_SESSION['debug_login_result'] = 3;
                }else
                {
                    $_SESSION['email'] = $chemail;
                    $_SESSION['pw'] = $chpw;
		    $_SESSION['memberid'] = $userdata['memberid'];
			$_SESSION['usecookie'] = $userdata['usecookie'];
                
                    //RECORD USER LOGIN DATA
                    mysql_query("INSERT INTO " . $prefix . "tracking (memberid, logintime, logouttime, loginip, logoutip) VALUES ('{$userdata['memberid']}', '$savetime', '', '{$_SERVER['REMOTE_ADDR']}', '')");
                    
                    $_SESSION['debug_login_result'] = 0;
                }
            }else
            {
                //SET THE DEBUG LOGIN RESULT VARIABLE TO 1; INCORRECT PASSWORD
                $_SESSION['debug_login_result'] = 1;
            }
        }
    }else
    {
        //SET THE DEBUG LOGIN FAILED VARIABLE TO 2; EMAIL NOT IN DATABASE (LIKELY) OR MULTIPLE DATABASE ENTRIES
        $_SESSION['debug_login_result'] = 2;
    }

}

//This function converts the code passed to it to displayable text
function stripcode($code)
{
    $replace = array("<", ">", "\"");
    $with = array("&lt;", "&gt;", "&quot;");
    $newcode = str_replace($replace, $with, $code);
    
    return $newcode;
}

//This function is passed a member ID # and returns output with the user's display name being the correct color and containing a link to their profile
function rank($memid)
{
    //INITIALIZE GLOBAL VARIABLES
    global $prefix;
    global $admincolor;
    global $modcolor;
    global $userRank;
    global $memname;
    
    //GET MEMBER INFORMATION
    $memquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE memberid = '$memid'") OR DIE("Gravity Board X was unable to locate member data: " . mysql_error());

    while($mi = mysql_fetch_assoc($memquery))
    {
        //GET NUMBER OF POSTS
        $memberpostquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "posts WHERE memberid = '$memid'") OR DIE("Gravity Board X was unable to locate a member\'s posts: " . mysql_error());
        list($userRank['pc']) = mysql_fetch_row($memberpostquery);

        //GET MEMBER GROUP INFO
        $mgquery = mysql_query("SELECT * FROM " . $prefix . "membergroups WHERE group_id = '$mi[memberGroup]'") OR DIE("Gravity Board X was unable to verify this user\'s member group: " . mysql_error());
        while($mginfo = mysql_fetch_assoc($mgquery))
        {
            $mgtype = $mginfo['group_type'];
        }
        
        $memname = $mi['displayname'];
    }
    
    $rankquery = mysql_query("SELECT * FROM " . $prefix . "ranks") OR DIE("Gravity Board X was unable to load the user ranks: " . mysql_error());

    while($rank = mysql_fetch_assoc($rankquery))
    {
        if($userRank['pc'] >= $rank['postsneeded'])
        {
            $userRank['color'] = $rank['color'];
	    $userRank['rank'] = $rank['rank'];
        }
    }
    
    if($mgtype == '1'){ $userRank['color'] = $admincolor; }elseif($mgtype == '2'){ $userRank['color'] = $modcolor; }

return $userRank;

}

//This function records the user's preferences as session variables
function recprefs($memid)
{
    global $prefix;
    
    //RECORD USER PREFERENCES AS SESSION VALUES
    $mquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE email = '$memid'") OR DIE("Gravity Board X was unable to store your session values: " . mysql_error());
    while($msess = mysql_fetch_assoc($mquery))
    {
        $_SESSION['displayname'] = $msess['displayname'];
        $_SESSION['memberGroup'] = $msess['memberGroup'];
        $_SESSION['timediff'] = $msess['timediff'];
        $_SESSION['messageeditor'] = $msess['messageeditor'];
        $_SESSION['tperpage'] = $msess['tperpage'];
        $_SESSION['mperpage'] = $msess['mperpage'];
    }

    if($_SESSION['mperpage'] == 0){ $_SESSION['mperpage'] = 15; }
    if($_SESSION['tperpage'] == 0){ $_SESSION['tperpage'] = 30; }
}

?>

