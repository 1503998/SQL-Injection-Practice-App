<?php

/*****************************************************************************/
/* whosonline.php                                                            */
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
//This script displays a list of members currently online and gives some     //
//information about them.                                                    //
///////////////////////////////////////////////////////////////////////////////

?>

<div class="headermid">

  <div class="header">
    <span class="headerfont">Who's Online</span>
  </div>

<div class="content">

<!--Begin who's online table-->
<table width="100%">
  <tr>
  
    <td align="center" width="20%">
      <b><span class="medium">Username</span></b>
    </td>
    
    <td align="left" width="30%">
      <b><span class="medium">Email Address</span></b>
    </td>
    
    <td align="left" width="25%">
      <b><span class="medium">First Online</span></b>
    </td>
    
    <td align="center" width="25%">
      <b><span class="medium">Time Online</span></b>
    </td>

<?php

$exptime = time() - 600;
$onlinequery = mysql_query("SELECT * FROM " . $prefix . "online WHERE lastactive >= $exptime ORDER BY firstonline") OR DIE("Gravity Board X was unable to locate the most recent members online: " . mysql_error());

while($olinfo = mysql_fetch_assoc($onlinequery))
{
    $miquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE memberid = '$olinfo[memberid]'") OR DIE("Gravity Board X was unable to retrieve member information from the most recent members online: " . mysql_error());
    while($mi = mysql_fetch_assoc($miquery))
    {
        $olt = $olinfo['lastactive'] - $olinfo['firstonline'];
        $olhours = intval(intval($olt) / 3600);

        $oltime = (false)
        ? str_pad($olhours, 2, "0", STR_PAD_LEFT). ':'
        : $olhours. 'h ';

        $olmins = intval(($olt / 60) % 60);

        $oltime .= str_pad($olmins, 2, "0", STR_PAD_LEFT). 'm ';

        $olsecs = intval($olt % 60);

        $oltime .= str_pad($olsecs, 2, "0", STR_PAD_LEFT). 's';
        
        //Helps to prevent email spam bots
	     $estring = array('@', '.', 'c', 'o', 'm', 'n', 'e', 't', 'r', 'g', 'd', 'u');
	     $ereplace = array('&#64;', '&#46;', '&#99;', '&#111;', '&#109;', '&#110;', '&#101;', '&#116;', '&#114;', '&#103;', '&#100;', '&#117;');
	     $email_profile = str_replace($estring, $ereplace, $mi['email']);

	rank($mi['memberid']);

?>

  <tr>
  
    <td align="center" width="20%">
      <b><a href="?action=viewprofile&member_id=<?php echo $mi['memberid']; ?>"><span class="small" style="color: <?php echo $userRank['color']; ?>;"><?php echo $mi['displayname']; ?></span></a></b>
    </td>

    <td align="left" width="30%">
      <b><font class="small"><a href="mailto:<?php echo $email_profile; ?>"><?php echo $email_profile; ?></a></font></b>
    </td>

    <td align="left" width="25%">
      <b><font class="small"><?php echo date("m/d/Y h:i:s A",$olinfo['firstonline']); ?></font></b>
    </td>

    <td align="center" width="25%">
      <b><font class="small"><?php echo $oltime; ?></font></b>
    </td>
    
<?php

    }
}

?>

  </tr>
</table>
<!--End who's online table-->
</div>

<div class="headerbot">
</div>

</div>