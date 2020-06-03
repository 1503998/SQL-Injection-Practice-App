<?php

/*****************************************************************************/
/* index.php                                                                 */
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
//This is the main script that holds everything together.  It calls all other//
//scripts and is loaded each time a page is viewed.  Depending on the        //
//variables in the URL and what is sent by forms, it determines what is to be//
//shown to the user.                                                         //
///////////////////////////////////////////////////////////////////////////////

//error_reporting(E_ALL);
error_reporting(0);

$board_version = 'v2.0 BETA';

//START SCRIPT TIMER
//This times how long it takes to execute the page loading, and is displayed at the bottom of each page
$timeparts = explode(' ',microtime());
$starttime = $timeparts[1].substr($timeparts[0],1);
//END SCRIPT TIMER

//Initialize $sr session variable
$_SESSION['sr'] = '';

//INCLUDE BOARD CONFIGURATION FILE - SEE CONFIG.PHP FOR MORE INFO
include("config.php");
$connection = dbconnect($hostname, $username, $password, $dbname);

include("gzip.php");      //ENABLES GZIP PAGE COMPRESSION
include("checkcookie.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

//Gets rid of E_ALL notice of undefined index: action
if(!isset($_GET['action']))
{
    $_GET['action'] = '';
}

//if($_GET['action'] == 'logout')
//{
//    setcookie("gbx[email]", $_SESSION['email'], time()-3600, "/", "", 0);
//    setcookie("gbx[pw]", $_SESSION['pw'], time()-3600, "/", "", 0);
//}

//INCLUDE NEEDED FILES - SEE INDIVIDUAL FILES FOR MORE INFO
include("check.php");     //HANDLES SESSION INFORMATION
include("time.php");      //COORDINATES THE TIME FOR THE BOARD
include("tracking.php");  //TRACKS USER ACTIONS THROUGHOUT THE BOARD
include("banned.php");    //KEEPS A WATCH FOR BANNED MEMBERS

//STATISTICS - INCREMENT TOTAL CLICKS BY ONE
mysql_query("UPDATE " . $prefix . "stats SET totalclicks=totalclicks+1") OR DIE("Gravity Board X was unable to log your page view: " . mysql_error());

//MEMBER STATISTICS - IF MEMBER IS LOGGED IN, INCREMENT THEIR TOTAL CLICKS BY ONE
if($_SESSION['sr'] == '2'){
mysql_query("UPDATE " . $prefix . "members SET totalclicks=totalclicks+1 WHERE memberid='{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to log your member page view: " . mysql_error());
}

//IF REQUESTED, DISPLAY THE FOLLOWING INSTEAD OF THE MAIN PAGE:
//INBOX

if($_GET['action'] == 'inbox')
{
	include("inbox.php");
//OUTBOX
}elseif($_GET['action'] == 'outbox')
{
	include("outbox.php");
//NEW PM PAGE
}elseif($_GET['action'] == 'newpm')
{
	include("newpm.php");
//DELETE A PM SCRIPT
}elseif($_GET['action'] == 'pmdelete')
{
	include("pmdelete.php");
//READ A PM
}elseif($_GET['action'] == 'viewpm')
{
	include("viewpm.php");
//OTHERWISE CONTINUE, DISPLAYING THE MAIN PAGE
}else
{

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

    <title><?php echo stripslashes($boardname); ?> (Powered By Gravity Board X)</title>

    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

    <link rel="stylesheet" type="text/css" href="skins/<?php echo $currentskin; ?>/skin.css"/>
    <link rel="GBX Icon" href="favicon.ico"/>

    <script type="text/javascript" src="validate/yav.js"></script>
    <script type="text/javascript" src="validate/yav-config.js"></script>

    <script type="text/javascript" src="ajax/prototype.js"></script>
    <script type="text/javascript" src="ajax/scriptaculous.js"></script>

    <script type="text/javascript" src="post/FCKeditor/fckeditor.js"></script>

    <script type="text/javascript" src="fixheight.js"></script>
    <script type="text/javascript" src="ajax/gbxAjaxReq.js"></script>
    <script type="text/javascript" src="gbxEdit.js"></script>
    <script type="text/javascript" src="ajax/gbxJSlib.js"></script>
    <?php include("js.php"); ?>

</head>

<body>

<div id="digg"><script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script></div>

  <div id="main">
    
<?php include("headernav.php"); ?>

<div id="screen">&nbsp;</div>
<div id="loading">Loading... Please wait.</div>

<?php

//START BAN CHECK
//CHECK TO SEE IF THE USER IS BANNED. IF THEY ARE, DISPLAY A BANNED MESSAGE
if($_SESSION['banned'] == '1')
{

?>
<div class="header">
<span class="headerfont">Access Denied</span>
</div>
        <table class=station width="100%">
          <tr>
            <td>
              <font color="#FF0000"><b>We're sorry, but you are currently banned from using this forum.</b></font>
              <p>The current time is <?php echo $time; //SHOW THE CURRENT TIME ?>.</p>
              <p>You are banned until <?php echo date ("l, F d Y, h:i:s a",$banuntil); //SHOW WHEN THE USER'S BAN IS LIFTED ?>.</p>
              <p>Countdown: <b><?php echo $timeleft; //SHOW SECONDS LEFT UNTIL THE USER'S BAN IS OVER ?></b> seconds left.
              <p>The reason for your ban is: <?php echo $banreason; //SHOW THE REASON FOR THE USER'S BAN ?></p>
            </td>
          </tr>
        </table>
<?php

//END BAN CHECK
}else
{
    //SHOW THE BOARD'S ANNOUNCEMENTS (IF APPLICABLE)
    include("announcedisplay.php");

    //INCLUDE THE SWITCH SCRIPT. THIS DETERMINES WHAT IS SHOWN ON THE PAGE. SEE SWITCH.PHP FOR MORE INFO.
    include("switch.php");

?>

    <br/><br/>
    <div id="footer">
      <span class="small">
      Powered By <b><a href="http://www.gravityboardx.com">Gravity Board X</a></b> <?php echo $board_version; ?> | &copy;2002-2007 Gravity Board X Development Team.<br/>
      <?php //echo 'Executed in';
    
//START SCRIPT TIMER CALCULATOR
$timeparts = explode(' ',microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
$exectime = substr($endtime - $starttime, 0, 6);
//END SCRIPT TIMER CALCULATOR
    
//DISPLAY THE TIME IT TOOK TO EXECUTE THE PAGE
//echo $exectime;
//echo 'seconds |';    
    
//DISPLAY WHETHER DEFLATE OR GZIP COMPRESSION ARE ENABLED OR DISABLED
//if($support_deflate){ echo 'deflate enabled'; }elseif($support_gzip){ echo 'gzip enabled<br/>'; }else{ echo 'gzip disabled<br/>'; }
    
?>

      <b>This software is licensed under the <a href="index.php?action=gpl">GNU General Public License</a>.</b>
      </span>
  </div>

  </div>

</body>

</html>
<?php

	}
}
//CHECK TO SEE IF DEBUG MODE IS ENABLED
$debquery = mysql_query("SELECT debugon FROM " . $prefix . "settings") OR DIE("Debugging error: " . mysql_error());
list($debug) = mysql_fetch_row($debquery);

//IF ENABLED, INCLUDE THE BOARD DEBUGGER
if($debug == true)
{
    include("debugger.php");
}

?>
