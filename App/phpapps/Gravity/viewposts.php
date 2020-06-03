<?php

/*****************************************************************************/
/* viewposts.php                                                             */
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
//This script displays the messages in a topic after it has been navigated   //
//to.                                                                        //
///////////////////////////////////////////////////////////////////////////////

//TEMP VARIABLES
$fixHeightArr = '';

//TEMPORARY
if(!isset($_GET['page']))
{
    $_GET['page'] = '1';
}

mysql_query("UPDATE " . $prefix . "stats SET threadviews=threadviews+1") OR DIE("Gravity Board X was unable to log your thread view: " . mysql_error());
if($_SESSION['sr'] == 2)
{
    mysql_query("UPDATE " . $prefix . "members SET threadviews=threadviews+1 WHERE memberid='{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to log your member thread view: " . mysql_error());
}

mysql_query("UPDATE " . $prefix . "threads SET view_num=view_num+1 WHERE thread_id='{$_GET['thread_id']}'") OR DIE("Gravity Board X failed to update the thread view number: " . mysql_error());

$bq = mysql_query("SELECT * FROM " . $prefix . "boards WHERE board_id = '{$_GET['board_id']}'") OR DIE("Gravity Board X was unable to retrieve the board data from the database: " . mysql_error());
$tqt = mysql_query("SELECT * FROM " . $prefix . "threads WHERE thread_id = '{$_GET['thread_id']}'") OR DIE("Gravity Board X was unable to retrieve the thread data from the database: " . mysql_error());

while($ti = mysql_fetch_assoc($tqt))
{
    $tq = mysql_query("SELECT * FROM " . $prefix . "posts WHERE msg_id = '$ti[first_msg]'") OR DIE("Gravity Board X was unable to retrieve the first post data from the database: " . mysql_error());
    $locked = $ti['locked'];
    $viewnum = $ti['view_num'];
    $threadsubject = $ti['subject'];
}

while($bi = mysql_fetch_assoc($bq))
{
    while($fpi = mysql_fetch_assoc($tq))
    {

?>

<div id="header_posts" style="text-align: left;">
<?php

        //CENSOR FIRST POST
        $threadsubject = censor($threadsubject);

?>
  <span class="headerfont"><a href="index.php"><?php echo stripslashes($boardname); ?></a> > <a href="index.php?action=viewboard&amp;board_id=<?php echo $bi['board_id']; ?>"><?php echo stripslashes($bi['name']); ?></a> > <?php echo trim(stripslashes(substr($threadsubject, 0, 50))); if(strlen($threadsubject) > 50){ echo '...'; } ?></span>
</div>

<?php

if($_SESSION['sr'] != '2' || $locked == '1')
{

?>

  <div class="alert">
    <span><b><?php if($_SESSION['sr'] != '2'){ echo 'You are not logged in.  Please login to post a message.<br/>'; }if($locked == '1' && $_SESSION['perm'] == '0'){ echo 'This thread is locked.  You may longer post messages in it.'; } ?></b></span>
  </div>

<?php

}

if($_SESSION['sr'] == '2')
{

?>
  <div class="row">
  <div>
    <span class="large"><?php if($_SESSION['sr'] == '2') { ?><b>Options: <?php if($locked == '0' || $_SESSION['perm'] == '1' || $_SESSION['perm'] == '2'){ ?><b><a href="#pagebottom" onClick="gbxReply();">Reply</a></b> | <?php } ?><a href="index.php?action=postnew&amp;board_id=<?php echo $_GET['board_id']; ?>">New Thread</a></b> | <b><a href="index.php?action=bookmark&amp;bm=add&amp;thread_id=<?php echo $_GET['thread_id']; ?>">Bookmark Thread</a></b><?php if($_SESSION['perm'] == '1' || $_SESSION['perm'] == '2'){ ?> | <a href="index.php?action=lock&amp;thread_id=<?php echo $_GET['thread_id']; ?>&amp;board_id=<?php echo $_GET['board_id']; ?>"><span class="adminfont">Lock Thread</span></a> | <a href="index.php?action=float&amp;thread_id=<?php echo $_GET['thread_id']; ?>&amp;board_id=<?php echo $_GET['board_id']; ?>"><span class="adminfont">Float Thread</span></a> | <a href="javascript:confirmthreaddelete()"><span class="adminfont">Delete Thread</span></a><?php } } ?></span>
  </div>
  </div>

<?php

}

?>
  <div id="ajaxStatus"></div>

<?php

    }
}

$num = ($_GET['page'] - 1) * 15;
$temp = mysql_query("SELECT * FROM " . $prefix . "posts WHERE thread_id = '{$_GET['thread_id']}' ORDER BY dateposted LIMIT $num, 15") OR DIE("Gravity Board X was unable to retrieve the post information from the database: " . mysql_error());

include("messagelog.php");

while($postrow = mysql_fetch_assoc($temp))
{

if($_SESSION['perm'] == 1)
{

?>

<SCRIPT LANGUAGE="javascript">
<!--
function confirmdelete()
{
	if(confirm("Are you sure you would like to delete this post?\nThis may NOT be undone!"))
	{
		location='delete.php?msg_id=<?php echo $postrow['msg_id']; ?>&amp;board_id=<?php echo $_GET['board_id']; ?>';
	}
}
//-->
</SCRIPT>
<SCRIPT LANGUAGE="javascript">
<!--
function confirmthreaddelete()
{
	if(confirm("Are you sure you would like to delete this thread and all posts associated with it?\nThis may NOT be undone!"))
	{
		location='index.php?action=deletethread&thread_id=<?php echo $_GET['thread_id']; ?>&board_id=<?php echo $_GET['board_id']; ?>';
	}
}
//-->
</SCRIPT>

<?php

}

    mysql_query("UPDATE " . $prefix . "stats SET messageviews=messageviews+1") OR DIE("Gravity Board X was unable to log your post view: " . mysql_error());
    mysql_query("UPDATE " . $prefix . "members SET messageviews=messageviews+1 WHERE memberid='{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to log your member post view: " . mysql_error());

    include("messagelogend.php");

    $posttemp = mysql_query("SELECT * FROM " . $prefix . "members WHERE memberid = '{$postrow['memberid']}'") OR DIE("Gravity Board X was unable to retrieve member information from the requested post: " . mysql_error());
	 while($mpi = mysql_fetch_assoc($posttemp))
    {

        //Check user online status
        $onlinequery = mysql_query("SELECT * FROM " . $prefix . "online WHERE memberid = '{$mpi['memberid']}'") OR DIE("Gravity Board X failed to check the online status of a member: " . mysql_error());
        if(mysql_num_rows($onlinequery) >= '1')
        {
            $online = '<b>Online</b>';
        }else
        {
            $online = 'Offline';
        }

	rank($mpi['memberid']);

        $message = stripslashes($postrow['message']);

        //CENSOR MESSAGE
        $message = censor($message);

?>

  <div class="post_mid">
    <div class="post_top">
    </div>

    <div class="post_row" id="post_row_<?php echo $postrow['msg_id']; ?>">

<?php

        $mgquery = mysql_query("SELECT * FROM " . $prefix . "membergroups WHERE group_id = '{$mpi['memberGroup']}'") OR DIE("Gravity Board X was unable to verify a users member group: " . mysql_error());
        while($mg = mysql_fetch_assoc($mgquery))
        {
	
?>
      <div class="post_info" id="post_info_<?php echo $postrow['msg_id']; ?>">
        <span>
        <div id="member_<?php echo $postrow['msg_id']; ?>"><b><a href="?action=viewprofile&member_id=<?php echo $mpi['memberid']; ?>"><span class="post_username" style="color:<?php echo $userRank['color']; ?>;"><?php echo $mpi['displayname']; ?></span></a></b>
        </div>
        <span class="small"><?php echo $mg['group_name']; ?></span>
        <br/>
        <span class="small"><?php echo $userRank['rank']; ?></span>
        <br/>
        <span class="small">Posts: <?php echo number_format($userRank['pc']); ?></span>
        <br/>
        <span class="small">Status: <?php echo $online; ?></span>
        <br/><br/>

<?php

            if($mpi['icon_url'] != '' && $mpi['icon_height'] != '' && $mpi['icon_width'] != '')
            {
                echo '<img src="' . $mpi['icon_url'] . '" height="' . $mpi['icon_height'] . '" width="' . $mpi['icon_width'] . '">';
            }

?>
        <?php if($_SESSION['perm'] == '1' || $_SESSION['perm'] == '2') { echo '<span class="adminfont">IP: <a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput=' . $postrow['ip'] . '"><span class="adminfont">' . $postrow['ip'] . '</span></a></span>'; } ?>
        </span>
        <br/>
        <p align="center"><span class="small">
<?php

            $posttime = date ("m/d/Y h:i:s A",$postrow['dateposted'] + $timeadjust);
            echo $posttime;

?>
        </span></p>

      </div>
      <div class="post_main" id="post_main_<?php echo $postrow['msg_id']; ?>">
        <span style="float: right;"><font class="small"><b>Post Options: </b><?php if($locked == '0' && $_SESSION['sr'] == '2' || $_SESSION['perm'] == '1' || $_SESSION['perm'] == '2') { ?><a href="#pagebottom" onClick="gbxQuote('<?php echo $postrow['msg_id']; ?>');">Quote</a><?php if($_SESSION['memberid'] == $postrow['memberid'] && $locked == '0' || $_SESSION['perm'] == '1' || $_SESSION['perm'] == '2') { echo ' | <a href="javascript:gbxEdit(\'message_' . $postrow['msg_id'] . '\');">Edit</a> | <a href="javascript:deletePost(' . $postrow['msg_id'] . ');">Delete</a>'; } }else{ echo 'None'; } ?><a name="<?php echo $postrow['msg_id']; ?>"></a></font></span><br/><br/>

        <div id="message_<?php echo $postrow['msg_id']; ?>">
          <?php echo $message;?>
        </div>
        <div>
	  <br/>
          <hr/>
	  <span>
	
<?php

            $mpi['signature'] = stripslashes($mpi['signature']);
            $mpi['signature'] = censor($mpi['signature']);
            echo $mpi['signature'];

?>

          </span>
        </div>
      </div>
    </div>
    <div class="post_bot">
&nbsp;
    </div>
  </div>

<?php

$fixHeightArr .= "fixHeight('post_info_" . $postrow['msg_id'] . ",post_main_" . $postrow['msg_id'] . "');\n";

        }
    }
}

//Update message logs
//$newloggedposts = implode(",", $nlp);
//mysql_query("UPDATE " . $prefix . "message_logs SET messagesread='$newloggedposts' WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to log your post view: " . mysql_error());

$pcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "posts WHERE thread_id = '{$_GET['thread_id']}'") OR DIE("Gravity Board X was not able to retrieve a post count: " . mysql_error());
list($pcount) = mysql_fetch_row($pcquery);

?>

  <div id="newmessages">
  </div>

  <div id="messagereply" style="display: none;">
    <div>
      <form name="messagereply" action=""><textarea id="gbxReplyContents" name="gbxReplyContents"></textarea><center><button type="button" class="button" onClick="gbxReplySubmit('<?php echo $_GET['thread_id']; ?>', '<?php echo $_GET['board_id']; ?>');">Submit Post</button>&nbsp;<button type="button" class="button" onClick="gbxReplyCancel();">Cancel</button></center></form>
    </div>
  </div>

<script language="javascript">
	var oReply = new FCKeditor('gbxReplyContents');
	oReply.BasePath = "./post/FCKeditor/";
	oReply.Height = 400;
	oReply.ToolbarSet = "GBX";
</script>

<?php

if($_SESSION['sr'] == '2')
{

?>
  <div class="row">
  <div>
    <span class="large"><?php if($_SESSION['sr'] == '2') { ?><b>Options: <?php if($locked == '0' || $_SESSION['perm'] == '1' || $_SESSION['perm'] == '2'){ ?><b><a href="#pagebottom" onClick="gbxReply();">Reply</a></b> | <?php } ?><a href="index.php?action=postnew&amp;board_id=<?php echo $_GET['board_id']; ?>">New Thread</a></b> | <b><a href="index.php?action=bookmark&amp;bm=add&amp;thread_id=<?php echo $_GET['thread_id']; ?>">Bookmark Thread</a></b><?php if($_SESSION['perm'] == '1' || $_SESSION['perm'] == '2'){ ?> | <a href="index.php?action=lock&amp;thread_id=<?php echo $_GET['thread_id']; ?>&amp;board_id=<?php echo $_GET['board_id']; ?>"><span class="adminfont">Lock Thread</span></a> | <a href="index.php?action=float&amp;thread_id=<?php echo $_GET['thread_id']; ?>&amp;board_id=<?php echo $_GET['board_id']; ?>"><span class="adminfont">Float Thread</span></a> | <a href="javascript:confirmthreaddelete()"><span class="adminfont">Delete Thread</span></a><?php } } ?></span>
  </div>
  </div>

<?php

}

?>

  <div>
	<span class="large"><b>Page:</b>
	
<?php

$pages = ceil($pcount / $_SESSION['mperpage']);
for($pagenum = 1; $pagenum <= $pages; $pagenum++)
{
    if($pagenum != 1){ echo ' | '; }
    echo '<a href="index.php?action=viewthread&amp;thread_id=' . $_GET['thread_id'] . '&amp;board_id=' . $_GET['board_id'] . '&amp;page=' . $pagenum . '">';
    echo $pagenum;
    echo '</a>';
}

?>

    </span>
  </div>

<a id="pagebottom"></a>

<script type="text/javascript">
function GBXOnLoad()
{
<?php echo $fixHeightArr; ?>
document.getElementById('digg').style.display = 'block';
}
window.onload = GBXOnLoad();
</script>