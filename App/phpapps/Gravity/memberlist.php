<?php

/*****************************************************************************/
/* memberlist.php                                                            */
/*****************************************************************************/
/* Gravity Board X                                                           */
/* Open-Source Project started by Jonathan Taft (admin@gravityboardx.com)    */
/* Software Version: GBX Version 2.0                                         */
/* ========================================================================= */
/* Copyright (c) 2002-2005 Gravity Board X Developers. All Rights Reserved   */
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
//This script displays a list of all members on the board, giving some       //
//information about each member.                                             //
///////////////////////////////////////////////////////////////////////////////

?>

<div class="headermid">

  <div class="header">
    <span class="headerfont">Member List</span>
  </div>

<div class="content">

  <div style="width: 4%; float: left;">
    <span class="medium"><b>#</b></span>
  </div>

  <div style="width: 52%; float: left;">
    <span class="medium"><b>Username</b></span>
  </div>

  <div style="width: 22%; float: left;">
    <span class="medium"><b>Registered</b></span>
  </div>

  <div style="width: 8.5%; float: left;">
    <span class="medium"><b>Posts</b></span>
  </div>

  <div style="width: 13.5%; float: left;">
    <span class="medium"><b>Rank</b></span>
  </div>

<?php

//GET MEMBERLIST FROM DATABASE
$membersquery = mysql_query("SELECT * FROM " . $prefix . "members ORDER BY memberid") OR DIE("Gravity Board X was unable to retrieve the member list from the database: " . mysql_error());

$count = mysql_num_rows($membersquery);

while($meminfo = mysql_fetch_assoc($membersquery))
{

?>

<div class="row">
  
    <div class="row1" style="width: 2%; float: left; padding: .5%;">
      <b><font class="small"><?php echo $meminfo['memberid']; ?></font></b>
    </div>
    
<?php

    //GET MEMBER GROUP INFO
    $mgquery = mysql_query("SELECT * FROM " . $prefix . "membergroups WHERE group_id = '$meminfo[memberGroup]'") OR DIE("Gravity Board X was unable to verify this user\'s member group: " . mysql_error());
    while($mginfo = mysql_fetch_assoc($mgquery))
    {
        $mgtype = $mginfo['group_type'];
    }

rank($meminfo['memberid']);

?>

    <div class="row1" style="width: 50%; float: left; margin-left: .25%; padding: .5%;">
      <b><a href="index.php?action=viewprofile&member_id=<?php echo $meminfo['memberid']; ?>"><span class="membername" style="color: <?php echo $userRank['color']; ?>"><?php echo $meminfo['displayname']; ?></span></a></b>
    </div>
    
    <div class="row1" style="width: 19.4%; float: left; margin-left: .25%; padding: .5%;">
      <b><font class="small"><?php echo date("m/d/Y h:i:s A",$meminfo['dateregistered']); ?></font></b>
    </div>
    
<?php

    //GET NUMBER OF POSTS
    $memberpostquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "posts WHERE memberid = '$meminfo[memberid]'") OR DIE("Gravity Board X was unable to locate a member\'s posts: " . mysql_error());
    list($pc) = mysql_fetch_row($memberpostquery);

?>

    <div class="row1" style="width: 7%; float: left; margin-left: .25%; padding: .5%;">
      <b><font class="small"><?php echo $pc; ?></font></b>
    </div>

    <div class="row1" style="width: 13%; float: left; margin-left: .25%; padding: .5%;">
      <b><font class="small"><?php echo $userRank['rank']; ?></font></b>
    </div>

</div>
  
<?php

}

?>

<div class="pages">
	   <span>Page:
	
<?php

$pages = ceil($count / 100);
if($pages == 0){ echo 'Empty'; }
for($pagenum = 1; $pagenum <= $pages; $pagenum++)
{
    if($pagenum != 1){ echo ' · '; }
    echo '<a href="index.php?action=memberlist&page=' . $pagenum . '">';
    echo $pagenum;
    echo '</a>';
}

?>
      </span>
</div>

</div>

<div class="headerbot">
</div>

</div>
</table>