<?php

/*****************************************************************************/
/* viewthreads.php                                                           */
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
//This script displays the topics for a board after it has been navigated to.//
//Note: $boardname is the name of the entire message board, while $bn is the //
//      name of the specific board that is being viewed.                     //
///////////////////////////////////////////////////////////////////////////////

//TEMPORARY
$new = '';
$fixHeightArr = '';
if(!isset($_GET['page']))
{
    $_GET['page'] = '1';
}

$localbid = $_GET['board_id'];

//STATISTICS - INCREMENT GLOBAL BOARD VIEWS BY 1
mysql_query("UPDATE " . $prefix . "stats SET boardviews=boardviews+1") OR DIE("Gravity Board X was unable to log your board view: " . mysql_error());

//STATISTICS - IF MEMBER IS LOGGED IN, INCREMENT THEIR PERSONAL BOARD VIEWS BY 1
if($_SESSION['sr'] == '2')
{
    $tempmid = $_SESSION['memberid'];
    mysql_query("UPDATE " . $prefix . "members SET boardviews=boardviews+1 WHERE memberid='$tempmid'") OR DIE("Gravity Board X was unable to log your member board view: " . mysql_error());
}

//GET THE NAME OF THE SELECTED BOARD AND STORE IN $bn
$boardquery = mysql_query("SELECT name FROM " . $prefix . "boards WHERE board_id = '$localbid'") OR DIE("Gravity Board X was unable to retrieve the board data from the database: " . mysql_error());
list($bn) = mysql_fetch_row($boardquery);

?>

<div class="headermid">

<div id="header_threads" class="header" style="text-align: left;">
  <a href="index.php"><span class="headerfont"><?php echo stripslashes($boardname); ?></span></a> <span class="headerfont">> <?php echo stripslashes($bn); ?></span>
</div>

<div class="content">

  <div>
    <span class="headerfont"><?php if($_SESSION['sr'] == '2') { ?><b><a href="index.php?action=postnew&amp;board_id=<?php echo $localbid; ?>">New Thread</a></b><?php }else{ ?><font color="#FF0000"><b>Please login to post a message.</b></font><?php } ?></span>
  </div>

<div class="row">
  <div style="font-weight: bold;">
        <div class="medium" style="width: 505px; float: left; margin-left: 20px;">Thread Subject</div><div class="medium" style="width: 132px; float: left;">Author</div><div class="medium" style="width: 75px; float: left; text-align: center;">Replies</div><div class="medium" style="width: 74px; float: left; text-align: center;">Views</div><div class="medium" style="width: 127px; float: left;">Last Post</div>
  </div>
</div>

<?php

##############################
##MARK START OF GETTHREADS()##
##############################

$stickyquery = mysql_query("SELECT thread_id FROM " . $prefix . "threads WHERE board_id = '$localbid' && sticky = '1' ORDER BY last_msg_time DESC") OR DIE("Gravity Board X was unable to retrieve the sticky thread data from the database: " . mysql_error());
global $threadArray;
while($threads = mysql_fetch_assoc($stickyquery))
{
	$threadArray = $threadArray . $threads['thread_id'] . ',';
}
getThreads($threadArray);

$num = ($_GET['page'] - 1) * 30;

$tcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "threads WHERE board_id = '$localbid'") OR DIE("Gravity Board X was not able to retrieve a thread count: " . mysql_error());
list($count) = mysql_fetch_row($tcquery);

$threadquery = mysql_query("SELECT thread_id FROM " . $prefix . "threads WHERE board_id = '$localbid' && sticky = '0' ORDER BY last_msg_time DESC LIMIT $num, 30") OR DIE("Gravity Board X was unable to retrieve the thread data from the database: " . mysql_error());

$threadArray = '';

while($threads = mysql_fetch_assoc($threadquery))
{
	$threadArray = $threadArray . $threads['thread_id'] . ',';
}
getThreads($threadArray);

?>

  <div class="pages">
	<span>Page:
	
<?php

$pages = ceil($count / $_SESSION['tperpage']);
if($pages == 0){ echo 'Empty'; }
for($pagenum = 1; $pagenum <= $pages; $pagenum++)
{
    if($pagenum != 1){ echo ' | '; }
    echo '<a href="index.php?action=viewboard&amp;board_id=' . $_GET['board_id'] . '&amp;page=' . $pagenum . '">';
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

<script type="text/javascript">
function GBXOnLoad()
{
<?php echo $fixHeightArr; ?>
}
window.onload = GBXOnLoad();
</script>

<!--End threads primary table-->