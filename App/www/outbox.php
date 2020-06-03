<?php

/*****************************************************************************/
/* imoutbox.php                                                              */
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

$imquery = mysql_query("SELECT * FROM " . $prefix . "ims WHERE imfrom = '{$_SESSION['memberid']}' ORDER BY imdate DESC") OR DIE("Gravity Board X was unable to retrieve your sent personal messages from the database: " . mysql_error());

?>

<html>

<head>
<title>PM Outbox</title>

<link rel="stylesheet" type="text/css" href="skins/<?php echo $currentskin; ?>/skin.css">

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

?>

<div class="im_mid">

  <div class="im_top">
    <span class="headerfont">Outbox</span>
  </div>

  <div class="content">

<table>

<?php

    if($_SESSION['sr'] == '2')
    {

?>
  <tr>
    <td width="15px">
      <p align="center"><font class="small">Read</font></p>
    </td>
    <td width="15px">
      <p align="center"><font class="small">Reply</font></p>
    </td>
    <td width="250px">
      <p align="center"><font class="small">Subject</font></p>
    </td>
    <td width="114px">
      <p align="center"><font class="small">To</font></p>
    </td>
    <td width="86px">
      <p align="center"><font class="small">Date</font></p>
    </td>
    <td width="12px">
      <p align="center"><font class="small">X</font></p>
    </td>
  </tr>
  
<?php

        while($imstat = mysql_fetch_assoc($imquery))
        {
            $dnquery = mysql_query("SELECT displayname FROM " . $prefix . "members WHERE memberid = '{$imstat['imto']}'") OR DIE("Gravity Board X was unable to identify the senders of your personal messages: " . mysql_error());
            list($recipient) = mysql_fetch_array($dnquery);
		rank($imstat['imto']);
?>

  <tr>
    <td class="row1" width="15px">
      <p align="center"><?php if($imstat['imread'] == '0') { echo '<img src="images/uncheck.gif" alt="This message has not yet been read by the recipient">'; } else { echo '<img src="images/check.gif" alt="The recipient has read this message">'; } ?>
    </td>
    <td class="row1" width="15px">
      <p align="center"><?php if($imstat['reply'] == '0') { echo '<img src="images/uncheck.gif" alt="The recipient has not yet replied to this message">'; } else { echo '<img src="images/check.gif" alt="The recipient has replied to this message">'; } ?>
    </td>
    <td class="row1" width="250px">
      <span class="mainlinkfont"><a href="?action=viewpm&imid=<?php echo $imstat['imid']; ?>"><?php echo $imstat['imsubject']; ?></a></span>
    </td>
    <td class="row1" width="114px">
      <p align="center"><a href="index.php?action=viewprofile&memberid=<?php echo $imstat['imto']; ?>"><span style="font-size: 1em; color: <?php echo $userRank['color']; ?>;"><?php echo $recipient; ?></span></a>
    </td>
    <td class="row1" width="86px">
      <p align="center"><span style="font-size: .8em;">
      
<?php

            $datesent = date ("m/d/Y h:i:s A",$imstat['imdate'] + $timeadjust);
            echo $datesent;

?>

</span>
    </td>
    <td class="row1" width="12px">
    <p align="center"><input type="checkbox" name="<?php echo $imstat['imid']; ?>">
    </td>
  </tr>
  
<?php
        }
    }else
    {

?>

  <tr>
    <td>
    <font class="small" color=#FF0000><b>You must be logged in to view your outbox. If you do not have an account, <a href="index.php?action=register">Click Here</a> to register.</b></font>
    </td>
  </tr>
  
<?php

    }
}
?>
</table>

</div>

<div class="im_bot">
</div>

</div>

<div class="row3" style="position: fixed; bottom: 0px; clear: both; text-align: center; width: 590px;">
  <span class="navfont"><a href="index.php?action=inbox">Inbox</a> | <a href="index.php?action=outbox">Outbox</a> | <a href="index.php?action=newpm">New PM</a></span>
</div>

</body>
</html>