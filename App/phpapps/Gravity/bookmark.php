<?php

/*****************************************************************************/
/* bookmark.php                                                              */
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
//This script displays a list of all members on the board, giving some       //
//information about each member.                                             //
///////////////////////////////////////////////////////////////////////////////

include_once("global.php");

if($_SESSION['sr'] == '2')
{

	$count = '';

?>

<div class="headermid">

<div id="header_bookmarks" class="header">
  <span class="headerfont">Personal Bookmarks</span>
</div>

<div class="content">

<?php
    if(isset($_GET['bm']))
    {
        if($_GET['bm'] == 'add')
        {
            //Add the requested bookmark
            $bmquery = mysql_query("SELECT bookmarks FROM " . $prefix . "members WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("GBX was unable to get your bookmarks: " . mysql_error());
            list($oldbm) = mysql_fetch_row($bmquery);
            
            $oldbookmarks = explode(",", $oldbm);

            if(!in_array($_GET['thread_id'], $oldbookmarks))
            {
                $newbookmarks = $oldbm . $_GET['thread_id'] . ',';
                mysql_query("UPDATE " . $prefix . "members SET bookmarks = '$newbookmarks' WHERE memberid = '{$_SESSION['memberid']}' ") OR DIE("Gravity Board X was unable to update your bookmarks: " . mysql_error());
                echo 'Bookmark added successfully.';
	         }else
	         {
	             echo '<tr><td><p><font color="#FF0000"><b>You have already bookmarked this thread.</b></font></p></td></tr>';
	         }
        }
    }else
    {
        //Display a list of the user's bookmarks
        $bmquery = mysql_query("SELECT bookmarks FROM " . $prefix . "members WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("GBX was unable to get your bookmarks: " . mysql_error());

        list($bm) = mysql_fetch_row($bmquery);
        if($bm == '')
        {
            echo '<tr><td><p><b>You have no bookmarks at this time.</b></p></td></tr>';
        }else
        {
?>
<div class="row">
  <div style="font-weight: bold;">
        <div class="small" style="width: 56.1%; float: left; margin-left: 22px;">Thread Subject</div><div class="small" style="width: 15%; float: left;">Author</div><div class="small" style="width: 4.8%; float: left;">Replies</div><div class="small" style="width: 6%; float: left;">Views</div><div class="small" style="width: 15%; float: left;">Last Post</div>
  </div>
</div>
<?php

	getThreads($bm);

        }
    }
}else
{
    accessdenied();
}

?>

  <div class="pages">
	<span>Page:
	
<?php

$pages = ceil($count / $_SESSION['tperpage']);
if($pages == 0){ echo 'Empty'; }
for($pagenum = 1; $pagenum <= $pages; $pagenum++)
{
    if($pagenum != 1){ echo ' | '; }
    echo '<a href="index.php?action=bookmark&amp;page=' . $pagenum . '">';
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
