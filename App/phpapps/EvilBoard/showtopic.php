<?
session_start();
include ("include/header.php");
include("functions/post.parser.php");
$setting = new setting;
$setting->post_per_page = $setting->num_post();
		$parse = new post_parser;
				
		echo '<br><table width="100%"  border="0" cellspacing="0">';
		$TopicID = $_GET['t'];
		dbconnect();
		$query = "SELECT * FROM eb_topic WHERE TopicID = '$TopicID'";
		$get_name = $db->query("$query");
		$title = mysql_fetch_array( $get_name );
		$title2 = $title['title'];
		$desc = $title['desc'];
				echo "	<tr>
    				<td height=\"22\" colspan=\"2\" class=\"eb_forum\"><B>&nbsp;{$title2}, {$desc}</B></td>
  					</tr>";
						/* The Query */
					$items_per_page = $setting->post_per_page;
		$query = "SELECT * FROM `eb_post` WHERE TopicID = '{$TopicID}' ORDER BY `PostID` LIMIT ";
		$page = $_GET['p'] ? (int)$_GET['p'] : 1; //If no page specified, use 1
		$offset = ($page-1)*$items_per_page;
		$query .= "$offset, $items_per_page";
		$get_topic = mysql_query("$query");
		while( $getinfo = mysql_fetch_object( $get_topic ) )
		{
		$message = $getinfo -> message;
		$postdate = $getinfo -> postdate;
		$UserID = $getinfo -> UserID;
		$username = $getinfo -> Username;
		
		$group = mysql_query("SELECT * FROM eb_group_mem WHERE `userid` = '{$UserID}'");
	    $group = mysql_fetch_array($group);
	    $group = $group['grp_id'];
		
		$grp = mysql_query("SELECT * FROM eb_group WHERE `g_id` = '{$group}'");
	    $grp = mysql_fetch_array($grp);
		$username = "<font color=\"$grp[clr]\">{$username}</font>";
		
		$PostID = $getinfo -> PostID;
		$s_g = mysql_query("SELECT sig FROM eb_profile WHERE `id` = '$UserID'");
		while ( $sig_g = mysql_fetch_object( $s_g ) ) {
			$sig = $sig_g -> sig;
		}
		
		$ForumID = $_GET['forumid'];
		echo '
		<td width="15%" class="eb_showforum_left">&nbsp;<a href="index.php?act=members&memberid='. $UserID .'">' . $username .'</a></td>
    <td width="85%" class="eb_showforum_right">'. $postdate .'</td>
  </tr>
  <tr>
    <td class="eb_showpost_footer_l" valign="top"><br class="eb_txt"><div align="center">'; 
	
	
				$p_sql = "SELECT * FROM `eb_profile` WHERE `id` = '$UserID';";
				$p_connect = mysql_query( $p_sql );
				while( $p_get = mysql_fetch_object( $p_connect ) ) {
				$logo = $p_get -> logo;
				$rank = $p_get -> rank;
				$website = $p_get -> website;
				$p_id = $p_get -> id;
				$grp_i = $db->query("SELECT * FROM eb_group_mem WHERE userid = $UserID");
				$grp_i = mysql_fetch_array($grp_i);
				$grp_i = $grp['grp_id'];
				if (!empty($grp_i)) {
					$grpn = $db->query("SELECT * FROM eb_group WHERE `g_id` = '{$grp_i}';");
					$grpn = mysql_fetch_array($grpn);
				}
				}
				$g_posts = "SELECT * FROM `eb_post` WHERE `UserID` = '$UserID';";
				$read_p = mysql_query($g_posts);
				$count = mysql_num_rows($read_p);
		    if (!empty($logo)) { echo "<img src='$logo' alt='$logo' width='80' height='80'>"; }
		   elseif (empty($logo)) { echo "<img src='Themes/Default/Images/noimage.gif'>"; }
		   $all_r = $count[0];
		   $rank = id_rank($rank);
		   if(empty($rank)) { $rank = id_rank('1'); }
		   echo "<br>$rank<br><b>Posts:</b> $count";
			if(!empty($grp) || !empty($grpn)) {
			echo "<br><b>Group:</b> {$grp[name]}";
			}
			 $rep = $parse->parse_post($message);
	 		$u_message = $rep;
			$usr = $_SESSION['user_name'];
				$forumid = $_GET['forumid'];
			$eb = new eboard;
			$user_can_view_ip = $eb->user_mod($usr,$forumid,"VIEW_IP");
			if ( $user_can_view_ip == TRUE || $_SESSION['user_level'] > 2 ) {
				$ip = $getinfo->user_ip;
				$mod = "<div align=\"right\"><strong>User IP:</strong>&nbsp;";
				$mod .= $ip;
			}
			else {
				$mod = '';
			}
	echo '</div></td>
    <td valign="top" class="eb_showpost_footer" style="margin-left: 10px; padding-left: 5px; padding-right: 5px;">'. $u_message .'<br>' . $mod . '<hr><div align="left">' . $sig . '</div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="eb_topic_lower"><div align="right"><table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="40">'; if ( $website !== "" ) { echo "<a href=\"$website\"><img src=\"Themes/Default/Images/web.gif\" border=\"0\"></a>"; }
	echo '</td><td width="40"><a href="index.php?act=usercp&pm=new&sendto=' . $p_id . '"><img src="Themes/Default/Images/pm.gif" border="0"></a></td>
    <td width="802"><a href="index.php?act=members&memberid=' . $p_id . '"><img src="Themes/Default/Images/profile.gif" border="0"></a></td>
    <td width="100%"><div align="right"><a href="index.php?act=post&topicid=' . $TopicID . '"><img src="Themes/Default/Images/replay-low.gif" width="38" height="14" border="0"></a></div></td>
';	
	$eb = new eboard;
	$uname = $_SESSION['user_name'];
	$forumid = $_GET['forumid'];
if ( $_SESSION['user_level'] > "2" || $eb->user_mod($uname,$forumid,"CAN_DELETE") == TRUE ) {
	echo '
    <td width="158"><div align="right"><a href="index.php?act=delete&topicid=' . $TopicID . '&postid=' . $PostID .'"><img src="Themes/Default/Images/del.gif" width="38" height="14" border="0"></a></div></td>';
	}
 if ( $_SESSION['user_name'] == $getinfo->Username || $_SESSION['user_level'] > "2" || $eb->user_mod($uname,$forumid,"CAN_EDIT") == TRUE) { 
 echo '<td width="38"><div align="right"><a href="index.php?act=edit&postid=' . $PostID . '&topicid=' . $TopicID . '"><img src="Themes/Default/Images/edit.gif" width="38" height="14" border="0"></a> </div></td>
 ';
 }
 echo '</tr>
</table></div></td>
  </tr>
  <tr>
    <td height="5" colspan="2" class="eb_showtopic_extra">&nbsp;</td>
  </tr>';
		}
	echo '</table><span class="eb_txt">';
$topicid = $_GET['t'];
$forumid = $_GET['forumid'];
echo '<br>';
	echo '<table width="200" border="0" align="right" cellspacing="0">
  <tr>
    <td><a href="index.php?act=posttopic&forumid=' . $forumid . '"><img src="Themes/Default/Images/new-topic.gif" width="100" height="27" border="0"></a></td>
    <td><a href="index.php?act=post&topicid=' . $topicid . '"><img src="Themes/Default/Images/replay.gif" width="100" height="27" border="0"></a></td>
  </tr>
</table>';
	$total_items = $db->query("SELECT * FROM `eb_post` WHERE `TopicID` = '{$TopicID}';");
	$total_items = mysql_num_rows($total_items);
	$number_pages = ceil($total_items/$items_per_page);
	echo "Page: ( ";
	for($i=1; $i<=$number_pages; $i++){
	   if($page !== $i){
	      echo "<a href='index.php?act=showtopic&t={$TopicID}&p=".$i."'>".$i."</a> ";
	   } else {
	      echo "<b>$i</b> ";
	   }
	}
	echo ") ";
	echo '</span><br><br>';
showfooter();
?>