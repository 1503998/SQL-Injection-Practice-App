<?php

/*****************************************************************************/
/* inbox.php                                                                 */
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

//TEMP VARIABLES
$fixHeightArr = '';

$imquery = mysql_query("SELECT * FROM " . $prefix . "ims WHERE imto='{$_SESSION['memberid']}' AND im_parent='0' ORDER BY imdate DESC") OR DIE("Gravity Board X was unable to retrieve your personal messages from the database: " . mysql_error());

?>

<html>

<head>
<title>PM Inbox</title>

<link rel="stylesheet" type="text/css" href="skins/<?php echo $currentskin; ?>/skin.css">

<script type="text/javascript" src="fixheight.js"></script>

</head>
<body style="margin: 0 0 40px 0;">

<?php

if($_SESSION['banned'] == '1')
{

?>

<div class="im_mid">
  <div class="im_top">
    <span class="headerfont">Access Denied</span>
  </div>
  <div class="content">
    <span color="#FF0000"><b>We're sorry, but you are currently banned from using this forum.</b></span>
  </div>
  <div class="header_bot">
  </div>
</div>

<?php
}else
{
    switch($_GET['action'])
    {
        case 'newpmsend':
        include("imsend.php");
        break;
        default:

        if($_SESSION['sr'] == '2')
        {
            if(isset($inboxsubmit))
            {
                //mysql_query("DELETE FROM " . $prefix . "ims WHERE imid = ''") OR DIE("Gravity Board X was unable to delete the selected PMs: " . mysql_error());
            }

?>

<div class="im_mid">

  <div class="im_top">
    <span class="headerfont">Inbox</span>
  </div>

  <div class="content">

  <div class="row">

    <div style="width: 18px; float: left;">
      <p align="center"><font class="small">Read</font>
    </div>

    <div style="width: 299px; float: left;">
      <p align="center"><font class="small">Subject</font>
    </div>

    <div style="width: 118px; float: left;">
      <p align="center"><font class="small">From</font>
    </div>

    <div style="width: 84px; float: left;">
      <p align="center"><font class="small">Date Sent</font>
    </div>

    <div style="width: 18px; float: left;">
      <p align="center"><font class="small">X</font>
    </div>

  </div>

<form method="POST" action="<?php echo $PHP_SELF; ?>" name="pmform">
<input type="hidden" name="inboxsubmit">
<?php

            while($imstat = mysql_fetch_assoc($imquery))
            {
                $dnquery = mysql_query("SELECT memberid, displayname FROM " . $prefix . "members WHERE memberid = '{$imstat['imfrom']}'") OR DIE("Gravity Board X was unable to identify the senders of your personal messages: " . mysql_error());
                while($imuserstats = mysql_fetch_assoc($dnquery))
                {
			rank($imuserstats['memberid']);
?>

  <div class="row">

    <div class="row3" id="read_<?php echo $imstat['imid']; ?>" style="width: 18px; float: left; margin: 0 0 2px 0; padding: 0;">
      <p align="center"><?php if($imstat['imread'] == '0') { echo '<img src="images/uncheck.gif" alt="You have not read this message yet">'; } else { echo '<img src="images/check.gif" alt="You have already read this message">'; } ?>
    </div>

    <div class="row1" id="subject_<?php echo $imstat['imid']; ?>" style="width: 299px; float: left; margin: 0 0 2px 2px;">
      <font class="mainlinkfont"><a href="?action=viewpm&imid=<?php echo $imstat['imid']; ?>"><?php echo $imstat['imsubject']; if($imstat['imread'] == 0){ echo ' (new)'; } ?></a></font>
    </div>

    <div class="row1" id="from_<?php echo $imstat['imid']; ?>" style="width: 118px; float: left; margin: 0 0 2px 2px;">

      <p align="center"><a target="_top" href="index.php?action=viewprofile&memberid=<?php echo $imstat['imfrom']; ?>"><span style="font-size: 1em; color: <?php echo $userRank['color']; ?>;"><?php echo $imuserstats['displayname']; ?></a></span>

    </div>

    <div class="row1" id="date_<?php echo $imstat['imid']; ?>" style="width: 84px; float: left; margin: 0 0 2px 2px;">
      <p align="center"><span style="font-size: .8em;">

<?php

                $datesent = date ("m/d/Y h:i:s A",$imstat['imdate'] + $timeadjust);
                echo $datesent;

?>

      </span>
    </div>

    <div class="row3" id="check_<?php echo $imstat['imid']; ?>" style="width: 18px; float: left; margin: 0 0 2px 2px; padding: 0 0 0 0;">
        <p align="center"><input type="checkbox" name="<?php echo $imstat['imid']; ?>" value="checked">
    </div>

  </div>
  
<?php

$fixHeightArr .= "fixHeight('read_" . $imstat['imid'] . ",subject_" . $imstat['imid'] . ",from_" . $imstat['imid'] . ",date_" . $imstat['imid'] . ",check_" . $imstat['imid'] . "');\n";

                }
            }
	
?>

  <p align="center"><input class="button" type="submit" value="Delete Selected Messages"/>

</form>

<script type="text/javascript">
function GBXOnLoad()
{
<?php echo $fixHeightArr; ?>
}
window.onload = GBXOnLoad();
</script>

<?php

        }else
        {
            accessdenied();
        }
    }

?>

  </div>

  <div class="im_bot">
  </div>

</div>

<div class="row3" style="position: fixed; bottom: 0px; clear: both; text-align: center; width: 590px;">
  <span class="navfont"><a href="index.php?action=inbox">Inbox</a> | <a href="index.php?action=outbox">Outbox</a> | <a href="index.php?action=newpm">New PM</a></span>
</div>

<?php

}

?>

</body>
</html>