<?php

/*****************************************************************************/
/* viewpm.php                                                                */
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

?>

<html>

<head>
<title>PM Inbox</title>

<link rel="stylesheet" type="text/css" href="skins/<?php echo $currentskin; ?>/skin.css">

<script type="text/javascript" src="fixheight.js"></script>

</head>
<body topmargin="0" leftmargin="0">

<?php

if($_SESSION['banned'] == '1')
{

?>

<table class="station" width="600px">
  <tr class="header">
    <td height="18">
	   <p align="center"><font class="headerfont">Access Denied</font></p>
    </td>
  </tr>
  <tr>
    <td>
      <font color="#FF0000"><b>We're sorry, but you are currently banned from using this forum.</b></font>
    </td>
  </tr>
</table>

<?php

}else
{
	if($_SESSION['sr'] == '2')
	{
		mysql_query("UPDATE " . $prefix . "stats SET pmsread=pmsread+1");
		mysql_query("UPDATE " . $prefix . "members SET pmsread=pmsread+1 WHERE memberid='{$_SESSION['memberid']}'");

		$imquery = mysql_query("SELECT * FROM " . $prefix . "ims WHERE imid='{$_GET['imid']}' AND imto='{$_SESSION['memberid']}'", $connection) OR DIE("Gravity Board X was unable to retrieve your personal message from the database: " . mysql_error());

		while($iminfo = mysql_fetch_assoc($imquery))
		{
			if($iminfo['imread'] == 0)
			{
				mysql_query("UPDATE " . $prefix . "ims SET imread='1' WHERE imid='{$_GET['imid']}'") OR DIE("Gravity Board X was unable to mark this PM as read: " . mysql_error());
			}

			rank($iminfo['imfrom']);
?>

<div class="row"">
  <div class="row3" style="clear: both;">
    From: <strong><span class="membername" style="color: <?php echo $userRank['color']; ?>;"><?php echo $memname; ?></span></strong> on <?php echo date('M j, \'y @ g:ia', $iminfo['imdate']); ?>
  </div>
  <div class="row1" style="clear: both;">
    <?php echo $iminfo['imbody']; ?>
  </div>
</div>

<?php

		}

	}else
	{
		accessdenied();
	}

}

?>

<div class="row3" style="position: absolute; bottom: 0px; clear: both; text-align: center; width: 590px;">
  <span class="navfont"><a href="index.php?action=inbox">Inbox</a> | <a href="index.php?action=outbox">Outbox</a> | <a href="index.php?action=newpm">New PM</a></span>
</div>

</body>
</html>