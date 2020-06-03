<?php

//Pass thread_ids to retrieve as first argument, seperated by commas with no spaces
function getThreads($thread_ids) {

//Assign global variables
global $prefix;
global $admincolor;
global $modcolor;
global $timeadjust;
global $count;
$count = explode(",", $thread_ids);
$count = count($count);

if(substr($thread_ids, -1) == ',')
{
	$thread_ids = substr($thread_ids, 0, -1);
}

//TEMPORARY
$new = '';
$fixHeightArr = '';
if(!isset($_GET['page']))
{
	$curpage = 1;
}else
{
	$curpage = $_GET['page'];
}

$num = ($curpage - 1) * 30;

##################################
##CHECK TO SEE IF BOARD IS EMPTY##
##################################
if(trim($thread_ids) != '')
{

//Get thread information from database
$threadquery = mysql_query("SELECT * FROM " . $prefix . "threads WHERE thread_id IN($thread_ids) ORDER BY last_msg_time DESC LIMIT $num, 30") OR DIE("Gravity Board X was unable to retrieve the thread data from the database: " . mysql_error());

while($threadinfo = mysql_fetch_assoc($threadquery))
{
	//GET FIRST POST
	$firstpostquery = mysql_query("SELECT msg_id FROM " . $prefix . "posts WHERE thread_id = '{$threadinfo['thread_id']}' ORDER BY dateposted ASC LIMIT 1");
	list($firstpost) = mysql_fetch_row($firstpostquery);
	//GET LAST POST
	$lastpostquery = mysql_query("SELECT msg_id FROM " . $prefix . "posts WHERE thread_id = '{$threadinfo['thread_id']}' ORDER BY dateposted DESC LIMIT 1");
	list($lastpost) = mysql_fetch_row($lastpostquery);

	//GET STICKY INFORMATION
	if($threadinfo['sticky'] == 1){ $sticky = 'float'; }else{ $sticky = 'thread'; }

	$threadtemp = mysql_query("SELECT * FROM " . $prefix . "posts WHERE msg_id = '{$threadinfo['first_msg']}'") OR DIE("Gravity Board X was unable to retrieve the data from the database: " . mysql_error());
	while($postinfo = mysql_fetch_assoc($threadtemp))
	{

?>

  <div class="row">
    <div class="<?php echo $sticky; ?>_info" id="thread_info_<?php echo $threadinfo['thread_id']; ?>">

<?php if($threadinfo['locked'] == '1' && $threadinfo['reply_num'] <= '15') { echo '<img src="images/locked_thread.gif" alt="Locked Thread"/>'; } elseif($threadinfo['locked'] == '1' && $threadinfo['reply_num'] >= '15') { echo '<img src="images/hotlocked_thread.gif" alt="Hot/Locked Thread"/>'; } elseif($threadinfo['reply_num'] >= '15'){ echo '<img src="images/hot_thread.gif" alt="Hot Thread"/>'; } else { echo '<img src="images/regular_thread.gif" alt="Regular Thread"/>'; } ?>

    </div>

    <div class="<?php echo $sticky; ?>_subject" id="thread_subject_<?php echo $threadinfo['thread_id']; ?>">

<?php

        //include("messagesread.php");

        //CENSOR THREADS
        $filteredsubject = censor( stripslashes($threadinfo['subject']) );

        if($new >= '1'){ echo '<b>'; }

?>

    <span class="subjectfont"><a href="index.php?action=viewthread&amp;thread_id=<?php echo $threadinfo['thread_id']; ?>&amp;board_id=<?php echo $threadinfo['board_id']; ?>"><?php echo $filteredsubject; ?></a></span>

<?php

        if($new >= '1'){ echo '</b>'; }

if(strstr($postinfo['message'], "<img"))
{
    echo '<img src="images/img.gif" alt="Post contains image"/>';
}
if(strstr($postinfo['message'], "<a href"))
{
    echo '<img src="images/url.gif" alt="Post contains URL"/>';
}
if(trim($postinfo['message']) == '')
{
    echo '<img src="images/emp.gif" alt="Post is empty"/>';
}

		//GET NUMBER OF REPLIES
		$repliesquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "posts WHERE thread_id = '{$threadinfo['thread_id']}'");
		list($replies) = mysql_fetch_row($repliesquery);

		$pages = ceil($replies / $_SESSION['tperpage']);

		if($pages > 1)
		{
			echo '<span class="small">[';

			for($pagenum = 1; $pagenum <= $pages; $pagenum++)
			{
				if($pagenum != 1){ echo ' | '; }
				echo '<a href="index.php?action=viewthread&amp;thread_id=' . $threadinfo['thread_id'] . '&amp;board_id=' . $threadinfo['board_id'] . '&amp;page=' . $pagenum . '">';
				echo $pagenum;
				echo '</a>';
			}

			echo ']</span>';
		}

?>

    </div>

    <div class="<?php echo $sticky; ?>_author"  id="thread_author_<?php echo $threadinfo['thread_id']; ?>">
      <a href="index.php?action=viewprofile&amp;member_id=<?php echo $postinfo['memberid']; ?>">
<?php

        $threadtemp3 = mysql_query("SELECT displayname FROM " . $prefix . "members WHERE memberid = '{$postinfo['memberid']}'") OR DIE("Gravity Board X was unable to retrieve the data from the member database: " . mysql_error());
        while($tsi = mysql_fetch_assoc($threadtemp3))
        {
                //END USER PERMISSION CHECK
		rank($postinfo['memberid']);
		global $userRank;
?>
      <span class="membername" style="color: <?php echo $userRank['color']; ?>; line-height: 100%;"><?php echo $tsi['displayname']; ?></span></a>
    </div>

<?php

        }

?>

    <div class="<?php echo $sticky; ?>_replies" id="thread_replies_<?php echo $threadinfo['thread_id']; ?>">
      <span class="small"><?php echo number_format($replies - 1); ?></span>
    </div>

    <div class="<?php echo $sticky; ?>_views" id="thread_views_<?php echo $threadinfo['thread_id']; ?>">
      <span class="small"><?php echo number_format($threadinfo['view_num']); ?></span>
    </div>

    <div class="<?php echo $sticky; ?>_lastpost" id="thread_lastpost_<?php echo $threadinfo['thread_id']; ?>">

<?php
        $threadtemp = mysql_query("SELECT memberid FROM " . $prefix . "posts WHERE msg_id = '$lastpost'") OR DIE("Gravity Board X was unable to retrieve the data from the database:" . mysql_error());
        list($lastmi) = mysql_fetch_row($threadtemp);
        $threadtemp = mysql_query("SELECT displayname, memberid FROM " . $prefix . "members WHERE memberid = '$lastmi'") OR DIE("Gravity Board X was unable to retrieve the data from the member database:" . mysql_error());
        while($poster = mysql_fetch_assoc($threadtemp))
        {
		rank($poster['memberid']);

?>
      <a href="index.php?action=viewthread&amp;thread_id=<?php echo $threadinfo['thread_id']; ?>&amp;board_id=<?php echo $threadinfo['board_id']; ?>&amp;page=<?php echo $pages; ?>#<?php echo $threadinfo['last_msg']; ?>"><span class="small">

<?php

            echo date ("m/d/Y h:i:s A",$threadinfo['last_msg_time'] + $timeadjust);

?>

      </span></a><br/>

      <span class="small">by <b><a href="index.php?action=viewprofile&amp;member_id=<?php echo $poster['memberid']; ?>"><span class="membername" style="color: <?php echo $userRank['color']; ?>;"><?php echo $poster['displayname']; ?></span></a></b></span>

<?php

        }

?>
    </div>
    <div class="<?php echo $sticky; ?>_float" id="thread_float_<?php echo $threadinfo['thread_id']; ?>">
<?php

        if($_SESSION['perm'] == '1' || $_SESSION['perm'] == '2')
        {
            if($threadinfo['sticky'] == '1') { echo '<a href="index.php?action=float&amp;thread_id=' . $threadinfo['thread_id'] . '&amp;board_id=' . $threadinfo['board_id'] . '"><img src="images/sticky_thread.gif" border="0" alt="Floated Thread - Click to sink"/></a>'; } else { echo '<a href="index.php?action=float&amp;thread_id=' . $threadinfo['thread_id'] . '&amp;board_id=' . $threadinfo['board_id'] . '"><img src="images/nonsticky_thread.gif" border="0" alt="Regular Thread - Click to float"/></a>'; }
        }else
        {
            if($threadinfo['sticky'] == '1') { echo '<img src="images/sticky_thread.gif" border="0" alt="This thread was floted by an admin or moderator"/>'; } else { echo '<img src="images/nonsticky_thread.gif" alt="Regular Thread"/>'; }
        }

	$fixHeightArr .= "fixHeight('thread_info_" . $threadinfo['thread_id'] . ",thread_subject_" . $threadinfo['thread_id'] . ",thread_author_" . $threadinfo['thread_id'] . ",thread_replies_" . $threadinfo['thread_id'] . ",thread_views_" . $threadinfo['thread_id'] . ",thread_lastpost_" . $threadinfo['thread_id'] . ",thread_float_" . $threadinfo['thread_id'] . "');\n";

?>
    </div>
  </div>

<?php

    }
}

?>

<script type="text/javascript">
function GBXOnLoad()
{
<?php echo $fixHeightArr; ?>
}
window.onload = GBXOnLoad();
</script>

<?php

}

//End empty thread_ids check
}
?>