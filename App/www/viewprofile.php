<?php

/*****************************************************************************/
/* viewprofile.php                                                           */
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
//This script displays information about a specific member of the forum.     //
///////////////////////////////////////////////////////////////////////////////

if(!isset($_POST['edit']))
{
    $_POST['edit'] = '';
}

$tempmid = $_GET['member_id'];
$dnquery = mysql_query("SELECT displayname FROM " . $prefix . "members WHERE memberid = '$tempmid'") OR DIE("Gravity Board X was unable to retrieve the user\'s display name: " . mysql_error());
list($userdisplayname) = mysql_fetch_row($dnquery);

?>

<div class="headermid">

<div id="header_yourinfo" class="header">
  <a href="index.php"><span class="headerfont"><?php echo $boardname; ?></span></a> <span class="headerfont">> Member Profile > <?php echo $userdisplayname; ?></span>
</div>

<div class="content">

<!--Begin view profile table-->
<table>
  
<?php

if($_SESSION['perm'] == '1' && $_POST['edit'] == '1')
{
    echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '?action=viewprofile&member_id=' . $member_id . '">';
    if(isset($_POST['formsent']))
    {
        //UPDATE MEMBERS PROFILE
        mysql_query("UPDATE " . $prefix . "members SET realname = '{$_POST['realname']}', displayname = '{$_POST['userdisplayname']}', icon_url = '{$_POST['icon_url']}', email = '{$_POST['email_profile']}', aim_id = '{$_POST['aim_id']}', msn_id = '{$_POST['msn_id']}', yahoo_id = '{$_POST['yahoo_id']}', icq_id = '{$_POST['icq_id']}', homepage = '{$_POST['homepage']}', homepage_link = '{$_POST['homepage_link']}', memberGroup = '{$_POST['memberGroup']}', location = '{$_POST['location']}', signature = '{$_POST['signature']}', otherinfo = '{$_POST['otherinfo']}' WHERE memberid = '{$_GET['member_id']}'") OR DIE("Gravity Board X was unable to update the profile: " . mysql_error());

        echo '<tr><td colspan=3><font color="#FF0000"><b>Changes made to ' . $userdisplayname . '\'s profile were successfully saved to the database.</b></font></td></tr>';
    }
}

$pcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "posts WHERE memberid = '{$_GET['member_id']}'") OR DIE("Gravity Board X failed to retrieve the specified member\'s post count: " . mysql_error());
list($pc) = mysql_fetch_row($pcquery);

$miquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE memberid = '{$_GET['member_id']}'") OR DIE("Gravity Board X was unable get the member data requested from the members database: " . mysql_error());
while($mi = mysql_fetch_assoc($miquery))
{
    $dateregistered = date ("M d, Y h:i:s A",$mi['dateregistered'] + $timeadjust);
	 $realname = $mi['realname'];
	 //Helps to prevent email spam bots
	 $estring = array('@', '.', 'c', 'o', 'm', 'n', 'e', 't', 'r', 'g', 'd', 'u');
	 $ereplace = array('&#64;', '&#46;', '&#99;', '&#111;', '&#109;', '&#110;', '&#101;', '&#116;', '&#114;', '&#103;', '&#100;', '&#117;');
	 $email_profile = str_replace($estring, $ereplace, $mi['email']);
	 $aim_id = $mi['aim_id'];
	 $msn_id = $mi['msn_id'];
	 $yahoo_id = $mi['yahoo_id'];
	 $icq_id = $mi['icq_id'];
	 $homepage = $mi['homepage'];
	 $homepage_link = $mi['homepage_link'];
	 $location = $mi['location'];
	 $signature = stripslashes($mi['signature']);
	 $signature = censor($signature);
	 $otherinfo = stripslashes($mi['otherinfo']);
	 $otherinfo = censor($otherinfo);
	 $membergroupform = $mi['memberGroup'];
}

$mgquery = mysql_query("SELECT * FROM " . $prefix . "membergroups WHERE group_id = '$membergroupform'") OR DIE("Gravity Board X was unable to verify this user\'s member group: " . mysql_error());
while($mginfo = mysql_fetch_assoc($mgquery))
{
    $mgtype = $mginfo['group_type'];
    $mgname = $mginfo['group_name'];
}

rank($_GET['member_id']);

?>

  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>Display Name</font>
    </td>
    <td class="row1" align=left width="30%">
      <span class="profilefont" style="color: <?php echo $userRank['color']; ?>;"><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<input class="textbox" type="text" name="userdisplayname" value="' . $userdisplayname . '">'; }else{ echo '<b>' . $userdisplayname . '</b>'; } ?></span>
    </td>
    <td class="row1" align=center>
      <font class=profilefont><b>Other Information</b></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>Member Group</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont>
      
<?php

if($_SESSION['perm'] == '1' && $_POST['edit'] == '1')
{
    echo '<select class="textbox" name="memberGroup">';

    $mgquery = mysql_query("SELECT * FROM " . $prefix . "membergroups ORDER BY group_id") OR DIE("Gravity Board X was unable to retrieve member group information about the user selected: " . mysql_error());
	 while($mg = mysql_fetch_assoc($mgquery))
    {
        if($membergroupform == $mg[group_id]){ $selected = ' selected'; }else{ $selected = ''; }
        echo '<option value="' . $mg[group_id] . '"' . $selected . '>' . $mg[group_name] . '</option>';
    }
    echo '</select>';
}else
{
echo $mgname;
}
?>

      </font>
    </td>
    <td class="row1" align=left valign=top rowspan=12>
      <font class=profilefont><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<textarea class="textbox" name="otherinfo" rows="22" cols="55">' . $otherinfo . '</textarea>'; }else{ echo $otherinfo; } ?></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>Date Registered</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><?php echo $dateregistered; ?></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>Real Name</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<input class="textbox" type="text" name="realname" value="' . $realname . '">'; }else{ echo $realname; } ?></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>E-Mail Address</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><a href="mailto:<?php echo $email_profile; ?>"><?php echo $email_profile; ?></a></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>Post Count</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><?php echo $pc; ?></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=leftr width="20%">
      <font class=profilefont>AOL Instant Messenger</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><a href=aim:goim?screenname=<?php echo $aim_id; ?>&message=Hi><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<input class="textbox" type="text" name="aim_id" value="' . $aim_id . '">'; }else{ echo $aim_id; } ?></a></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>MSN Messenger</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><a href=http://members.msn.com/<?php echo $msn_id; ?>><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<input class="textbox" type="text" name="msn_id" value="' . $msn_id . '">'; }else{ echo $msn_id; } ?></a></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>Yahoo! Messenger</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><a href=http://edit.yahoo.com/config/send_webmesg?.target=<?php echo $yahoo_id; ?>><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<input class="textbox" type="text" name="yahoo_id" value="' . $yahoo_id . '">'; }else{ echo $yahoo_id; } ?></a></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>ICQ Messenger</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<input class="textbox" type="text" name="icq_id" value="' . $icq_id . '">'; }else{ echo $icq_id; } ?></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>Homepage</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><a href=<?php echo $homepage_link; ?>><?php echo $homepage_link; ?></a></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left width="20%">
      <font class=profilefont>Location</font>
    </td>
    <td class="row1" align=left width="30%">
      <font class=profilefont><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<input class="textbox" type="text" name="location" value="' . $location . '">'; }else{ echo $location; } ?></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=center width="100%" colspan=3>
      <font class=profilefont><b>Signature</b></font>
    </td>
  </tr>
  
  <tr>
    <td class="row1" align=left colspan=3>
      <font class=profilefont><?php if($_SESSION['perm'] == '1' && $_POST['edit'] == '1'){ echo '<textarea class="textbox" name="signature" rows="10" cols="120">' . $signature . '</textarea>'; }else{ echo $signature; } ?></font>
    </td>
  </tr>
  
<?php

if($_SESSION['perm'] == '1' && $_POST['edit'] == '1')
{
    echo '<tr><td><input type="hidden" name="formsent"><input type="hidden" name="edit" value="1"><input type="submit" class=button value="Save Changes" name="submit"></form></td></tr>';
}elseif($_SESSION['perm'] == '1' && $_POST['edit'] != '1')
{
    echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '?action=viewprofile&member_id=' . $_GET['member_id'] . '"><input type="hidden" name="edit" value="1"><input type="submit" class=button value="Edit Profile" name="submit"></form>';
}

?>

</table>
<!--End view profile table-->

</div>

<div class="headerbot">
</div>

</div>