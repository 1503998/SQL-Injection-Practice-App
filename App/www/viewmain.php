<?php

/*****************************************************************************/
/* viewmain.php                                                              */
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
//This script displays categories, boards, and information for them on the   //
//main page of the board, such as latest post, stats, etc.                   //
///////////////////////////////////////////////////////////////////////////////

//TEMP VARIABLES
$fixHeightArr = '';
$sortArr = '';

if($_SESSION['debug_login_result'] == 1)
{
    echo '<font color="#FF0000"><b>An error occured while logging in: Invalid username or password.</b></font>';
}elseif($_SESSION['debug_login_result'] == 2)
{
    echo '<font color="#FF0000"><b>An error occured while logging in.</b></font>';
}elseif($_SESSION['debug_login_result'] == 3)
{
    echo '<font color="#FF0000"><b>An error occured while logging in. You have not yet validated your account.</b></font>';
}

?>

<div id="viewmain">

  <div id="content" class="board_cont">

<!--Start main table-->
      <!--Start boards table-->

<?php

//GET LIST OF CATEGORIES FROM DATABASE
$categories = mysql_query("SELECT * FROM " . $prefix . "categories ORDER BY catorder", $connection);

//DISPLAY CATEGORIES, ONE AT A TIME
while ($row = mysql_fetch_assoc($categories))
{

    //FOR THIS CATEGORY, GET A LIST OF BOARDS ASSOCIATED WITH IT
    $catresult = mysql_query("SELECT * FROM " . $prefix . "boards WHERE cat_id = '$row[cat_id]' ORDER BY boardorder") OR DIE("Gravity Board X was unable to execute the view boards script: " . mysql_error());

    //DISPLAY A CATEGORY

?>

  <div class="category">
    <span class="categoryfont"><?php echo stripslashes($row['catname']); ?></span>
  </div>

<div id="catcontainer_<?php echo $row['cat_id']; ?>">

<?php

$sortArr .= "Sortable.create(\"catcontainer_" . $row['cat_id'] . "\",{tag:'div',handle:'board_move'});\n";

    //DISPLAY BOARDS, ONE AT A TIME
    while($boardrow = mysql_fetch_assoc($catresult))
    {
        //Get number of threads in the board
	     $tcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "threads WHERE board_id = '{$boardrow['board_id']}'") OR DIE("Gravity Board X was unable to select the thread count for board " . $board_id . ": " . mysql_error());
        //Get number of posts in the board
	     $pcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "posts WHERE board_id = '{$boardrow['board_id']}'") OR DIE("Gravity Board X was unable to select the post count for board " . $board_id . ": " . mysql_error());

        //Count threads
	     list($threads) = mysql_fetch_row($tcquery);
	     //Count posts
	     list($posts) = mysql_fetch_row($pcquery);

	     $tiquery = mysql_query("SELECT * FROM " . $prefix . "threads WHERE board_id = '{$boardrow['board_id']}' ORDER BY last_msg_time DESC LIMIT 1") OR DIE("Gravity Board X was unable to select the most recent thread info from the database: " . mysql_error());
	
	     if(mysql_num_rows($tiquery) == 0)
	     {
            //NO RECENT POSTS IN BOARD
?>

  <div class="board_row" id="board_row_<?php echo $boardrow['board_id']; ?>">

    <div class="board" id="board_<?php echo $boardrow['board_id']; ?>">
      <span class="mainlinkfont">
      <a href="index.php?action=viewboard&amp;board_id=<?php echo $boardrow['board_id']; ?>"><?php echo stripslashes($boardrow['name']); ?></a></span>
      <span class="small"><br/><?php echo $boardrow['description']; ?></span>
      <span class="small"><br/><b>Most Recent</b> - No recent posts.</span>
    </div>

    <div class="board_threads" style="height: 100%;" id="board_threads_<?php echo $boardrow['board_id']; ?>">
      <span class="boardfont">Board Empty</span>
    </div>

  </div>

<?php

$fixHeightArr .= "fixHeight('board_" . $boardrow['board_id'] . ",board_threads_" . $boardrow['board_id'] . "');\n";

	     }else
        {		
            while($threadinfo = mysql_fetch_assoc($tiquery))
            {

?>

  <div class="board_row">

    <div class="board" id="board_<?php echo $boardrow['board_id']; ?>">
      <span class="mainlinkfont">
      <a href="index.php?action=viewboard&amp;board_id=<?php echo $boardrow['board_id']; ?>"><?php echo stripslashes($boardrow['name']); ?></a></span>
<?php

                //CENSOR THE MOST RECENT POST
                $threadinfo['subject'] = stripslashes($threadinfo['subject']);
                $threadinfo['subject'] = censor($threadinfo['subject']);

?>
      <span class="small"><br/><?php echo $boardrow['description']; ?></span>
      <span class="small"><br/><b>Most Recent</b> - <a href="index.php?action=viewthread&amp;thread_id=<?php echo $threadinfo['thread_id']; ?>&amp;board_id=<?php echo $boardrow['board_id']; ?>"><?php echo $threadinfo['subject']; ?></a> on <?php echo date("M j \'y g:ia", $threadinfo['last_msg_time'] + $timeadjust); ?></span>
    </div>

    <div class="board_threads" id="board_threads_<?php echo $boardrow['board_id']; ?>">
      <span class="boardfont"><?php echo number_format($threads); ?> Topics</span>
      <br/>
      <span class="boardfont"><?php echo number_format($posts); ?> Posts</span>
    </div>

  </div>

<?php

$fixHeightArr .= "fixHeight('board_" . $boardrow['board_id'] . ",board_threads_" . $boardrow['board_id'] . "');\n";

            }
        }
    //END BOARDS WHILE LOOP
    }

?>

  <div class="category_bot">

  </div>

</div>

<?php

//END CATEGORIES WHILE LOOP
}

?>

  </div>
      <!--End boards table-->

  <div id="sidemain" class="sidebar">

<!--Start dashboard-->

<?php if($_SESSION['sr'] == 2)
{
	include("forms/dashboard.php");
?>

<!--End dashboard-->

<br/>

<!--Start recent messages-->

<?php include("forms/dashims.php"); ?>

<!--End recent messages-->

<br/>

<?php

}elseif($_SESSION['sr'] != 2)
{
	include("forms/dashregister.php");
?>

<!--End dashboard-->

<br/>

<?php } ?>

<!--Start users online table-->

<?php include("forms/dashusersonline.php"); ?>

<!--End users online table-->

<br/>

<!--Start board stats table-->

<?php include("forms/stats.php"); //Include statistics file ?>

<!--End board stats table-->

<!--End main table-->

  </div>
</div>

<script type="text/javascript">
function GBXOnLoad()
{
<?php echo $fixHeightArr; ?>
}
window.onload = GBXOnLoad();
</script>