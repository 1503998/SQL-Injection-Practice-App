<?php
function dbconnect ()
{
include("config.php");
mysql_connect($eb_server,$eb_user,$eb_password);
mysql_select_db($eb_db);
}
function session_checker() {
	if(!session_is_registered('first_name')){
	echo "<span class='eb_txt'><center>You need to login to access this page</span></center>";
		include 'login.php';
		exit();
	}
} 
function showheader($title) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Final//EN">
<HTML>
<HEAD>
<TITLE> <?php echo $title ?> </TITLE>
</HEAD>

<BODY BGCOLOR="#FFFFFF">
<?php
}
	function id_rank($id)
	{
		$rank = mysql_query("SELECT * FROM eb_ranks WHERE rid = '{$id}'");
		$rank = mysql_fetch_array($rank);
		$rank = $rank['rname'];
		return $rank;
	}
function showfooter() {
$eb_version = sql_get_settings("eb_version");
?>
<table width="100%"  border="0" cellspacing="0" class="eb_header">
  <tr>
    <td style="height: 20px;"><div align="center">Copyright &copy;2006 The EvilBoard Group <b>::</b> EvilBoard
            <?=$eb_version?>
    </div></td>
  </tr>
</table>
</BODY>
</HTML>
<?php
}
function theme_load ($folder) 
{
include ("Themes/" . $folder . "/" . "default.php");
echo '<link rel="stylesheet" type="text/css" href="Themes/' . $folder . '/styles.css">';
}
function sql_forum ()
{
dbconnect();
$query = "SELECT * FROM `eb_forum`";
$connect = mysql_query("$query");
}
function sql_stats ($do,$forumid) {
	if ( $do == "lastmem" ) {
		dbconnect();
		$query = 'SELECT username,userid FROM `eb_members` ORDER BY `userid` DESC LIMIT 0 , 1';
		 $getuser = mysql_query( $query );
		//start results
		if( $getuser )
			{ //start loops
				while( $getinfo = mysql_fetch_object( $getuser ) )
				{
					{
							$useridf = $getinfo -> userid;
							$lastmember = $getinfo -> username;
							echo "$userid";
						   echo '<a href="?act=members&memberid=' . $useridf . '">'. $lastmember .'</a>';
					}
				}
			}
		return "$lastmember";
	}
	if ( $do == "totalpost" ) {
		dbconnect();
		$query = "SELECT * FROM `eb_post`";
		$connect = mysql_query("$query");
		$numrows = mysql_num_rows($connect);
		return "$numrows";
	}
	if ( $do == "totalmem" ) {
		dbconnect();
		$query = 'SELECT userid FROM `eb_members` ORDER BY `userid`';
		$connect = mysql_query("$query") or
		die("Error! : " . mysql_error() );
		$num_rows = mysql_num_rows($connect);
		echo "$num_rows";
	}
}
function load_wysiwyg () {
echo '<script language="JavaScript" type="text/javascript" src="wysiwyg.js"></script>';
}
function sql_get_forums ($do) {
	if ( $do == "catecory" )
	{
		dbconnect();
		$query = "SELECT name FROM eb_forum WHERE ForumID = '0'";
		$get_forum = mysql_query ("$query");
		while( $getinfo = mysql_fetch_object( $get_forum ) )
		{
			$forum_name = $getinfo -> name;
			echo "$forum_name";
		}
	}
	if ( $do == "index" )
	{
		dbconnect();
		global $replay_num;
		$query = "SELECT * FROM eb_forums WHERE catecory = '0'";
		$get_index = mysql_query("$query");
		//$replay_num =  sql_get_topics("get_all_topics", "$forum_id");
		while ( $getinfo = mysql_fetch_object( $get_index ) )
			{
			$forum_id = $getinfo -> ForumID;
		// Get Rows 
		$query = "SELECT * FROM `eb_topic` WHERE `ForumID` = '$forum_id'";
		$connect = mysql_query($query);
		$num_topic_rows = mysql_num_rows($connect);
		// End Get Rows
		// Get Rows 
		$query2 = "SELECT * FROM `eb_post` WHERE `ForumID` = '$forum_id'";
		$connect2 = mysql_query($query2);
		$num_replay_rows = mysql_num_rows($connect2);
		// End Get Rows
			$forum_id = $getinfo -> ForumID;
			$desc = $getinfo -> desc;
			$mods = $getinfo -> mods;
			$name = $getinfo -> name;
				echo '<tr>
  						<td width="80%" class="forum_footer">&nbsp;<a href="?showforum=' . $forum_id . '"><b>' . $name . '</b></a><br>
						&nbsp;' . $desc . '<br>
   						<b>&nbsp;Forum led by:</b><a href="?group=' . $mods . '">&nbsp;' . $mods . '</a></td>
    					<td width="10%" class="forum_footer2"><div align="center"></div>      <div align="center">'. $num_topic_rows .'</div></td>
    					<td width="10%"  class="forum_footer2"><div align="center"></div>      <div align="center">'. $num_replay_rows .'</div></td>
  						</tr>';
			}
	}
	if ( $do == "showforum" )
	{
		$id = $_GET['showforum'];
		//dbconnect();
		$query = "SELECT * FROM `eb_forums` WHERE `id` = '" . $id . "';";
		$get_index = mysql_query("$query");
		while ( $getinfo = mysql_fetch_object( $get_index ) )
			{
			$name = $getinfo -> title;
			echo "$name";
			}
	}
}
function sql_get_settings ($setting) {
	if ( $setting == "get_forum_name" )
	{
		dbconnect();
		$query = "SELECT forum_name FROM eb_settings WHERE 1;";
		$get_name = mysql_query("$query");
		while( $getinfo = mysql_fetch_object( $get_name ) )
		{
			$forum_name = $getinfo -> forum_name;
			return "$forum_name";
		}
	}
	if ( $setting == "get_homepage" )
	{
		dbconnect();
		$query = "SELECT homepage FROM eb_settings WHERE 1;";
		$get_name = mysql_query("$query");
		while( $getinfo = mysql_fetch_object( $get_name ) )
		{
			$homepage = $getinfo -> homepage;
			return "$homepage";
		}
	}
	if ( $setting == "eb_version" )
	{
		dbconnect();
		$query = "SELECT eb_version FROM eb_settings WHERE1;";
		$get_name = mysql_query("$query");
		while( $getinfo = mysql_fetch_object( $get_name ) )
		{
			$eb_version = $getinfo -> eb_version;
			return "$eb_version";
		}
	}
}
function sql_get_all_topics()
{
		$id = $_GET['showforum'];
		$query = "SELECT * FROM eb_topic WHERE ForumID = '2'";
		session_register("forum_id");
		$_SESSION['forum_id'] = $id;
		$get_name = mysql_query("$query");
		while( $getinfo = mysql_fetch_object( $get_name ) )
		{
			$userid = $getinfo -> UserID;
			$title = $getinfo -> title;
			$topicid = $getinfo -> TopicID;
			$desc = $getinfo -> desc;
			$username = $getinfo -> Username;
			if ( empty($title) ) { echo "Forum is empty"; }
									/* SQL GET REPLAYS */
			$replaysql = "SELECT * FROM `eb_post` WHERE `TopicID` = '$topicid'";
			$replay = mysql_query( $replaysql );
			$replays = mysql_num_rows( $replay );
			/* END SQL GET REPLAYS */
			echo '
			 <tr>
  			 <td class="eb_showforum_lower_left">&nbsp;<a href="?showtopic='. $topicid .'">'. $title .'</a><br>
  			 &nbsp;'. $desc .'</td>
    		<td class="eb_showforum_lower_left"><div align="center">'. $replays .'</div></td>
    		<td class="eb_showforum_lower_right"><div align="center"><a href="?action=member&memberid='. $userid .'">'. $username .'</a> </div></td>
  			</tr>
  			';
		}
}
function sql_get_all_posts()
 {
		include("functions/post.parser.php");
		$parse = new post_parser;
				
		echo '<br><table width="100%"  border="0" cellspacing="0">';
		$TopicID = $_GET['showtopic'];
		dbconnect();
		$query = "SELECT * FROM eb_post WHERE TopicID = '$TopicID' LIMIT 0 , 1";
		$get_name = mysql_query("$query");
		while( $get_nub = mysql_fetch_object( 
		$get_name ) )
		{
		$title = $get_nub -> title;
		echo '	<tr>
    				<td height="22" colspan="2" class="eb_menu1"><B>&nbsp;' . $title . '</B></td>
  					</tr>';
		}
		$query2 = "SELECT * FROM eb_post WHERE TopicID = '$TopicID' ORDER BY `PostID`";
		$get_topic = mysql_query("$query2");
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
    <td class="eb_showpost_footer_l"><div align="center">'; 
	
	
				$p_sql = "SELECT * FROM `eb_profile` WHERE `id` = '$UserID';";
				$p_connect = mysql_query( $p_sql );
				while( $p_get = mysql_fetch_object( $p_connect ) ) {
				$logo = $p_get -> logo;
				$rank = $p_get -> rank;
				$website = $p_get -> website;
				$p_id = $p_get -> id;
				}
				$g_posts = "SELECT * FROM `eb_post` WHERE `UserID` = '$UserID';";
				$read_p = mysql_query($g_posts);
				$count = mysql_num_rows($read_p);
		    if ( $logo !== "" ) { echo "<img src='$logo' alt='$logo' width='80' height='80'>"; }
		   elseif ( $logo == "" ) { echo "<img src='Themes/Default/Images/noimage.gif'>"; }
		   $all_r = $count[0];
		   echo "<br>$rank<br><b>Posts:</b> $count";

	 $rep = $parse->parse_post($message);
	 		$u_message = $rep;
	echo '</div></td>
    <td valign="top" class="eb_showpost_footer">'. $u_message .'<br><hr>' . $sig . '</td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="eb_topic_lower"><div align="right"><table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="40">'; if ( $website !== "" ) { echo "<a href=\"$website\"><img src=\"Themes/Default/Images/web.gif\" border=\"0\"></a>"; }
	echo '</td><td width="40"<a href="index.php?act=usercp&pm=new&sendto=' . $p_id . '"><img src="Themes/Default/Images/pm.gif" border="0"></a></td>
    <td width="802"><a href="index.php?act=members&memberid=' . $p_id . '"><img src="Themes/Default/Images/profile.gif" border="0"></a></td>
    <td width="100%"><div align="right"><a href="index.php?act=post&topicid=' . $TopicID . '"><img src="Themes/Default/Images/replay-low.gif" width="38" height="14" border="0"></a></div></td>
';	if ( $_SESSION['user_level'] > "2" ) {
	echo '
    <td width="158"><div align="right"><a href="index.php?act=delete&topicid=' . $TopicID . '&postid=' . $PostID .'"><img src="Themes/Default/Images/del.gif" width="38" height="14" border="0"></a></div></td>';
	}
 if ( $_SESSION['user_name'] == $getinfo->Username || $_SESSION['user_level'] > "2" ) { 
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
	}
	function sql_get_all_topics_2 () 
{	
	dbconnect();
	$query = "SELECT * FROM `eb_topic` WHERE `ForumID` = '$forumid'";
	$connect = mysql_query($query);
	$num_topic_rows = mysql_num_rows($connect);
	echo "$num_topic_rows";
}
function sql_show_all($do) {
if ( $do == "1" ) {
		$TopicID = $_GET['showtopic'];
		$query = "SELECT * FROM eb_post WHERE TopicID = '$TopicID' LIMIT 0 , 1";
		$get_name = mysql_query("$query");
		while( $get_nub = mysql_fetch_object( $get_name ) )
		{
		$title = $get_nub -> title;
		}
		$getforumname = "SELECT * FROM eb_settings LIMIT 0,1;";
		$get_fname = mysql_query("$getforumname");
		while( $getinfo = mysql_fetch_object( $get_fname ) )
		{
		$forumname = $getinfo -> forum_name;
		}
						$f_id = $_SESSION['forum_id'];
		$g_c = "SELECT * FROM eb_forum WHERE `ForumID` = '$f_id' LIMIT 0,1";
		$g_cat = mysql_query("$g_c");
		while( $getinfo = mysql_fetch_object( $g_cat ) )
		{
		$ct = $getinfo -> name;
		}
echo '<br><table width="100%" border="0" cellspacing="0">
  <tr>
    <td class="eb_header"><a href="?">' . $forumname . '</a> &gt;&gt; <a href="?showforum=' . $f_id . '">' . $ct . '</a> &gt;&gt; <a href="?showtopic=' . $TopicID . '">' . $title . '</a></td>
  </tr>
</table>
';
}
elseif ( $do == "2" ) {
		$getforumname = "SELECT * FROM eb_settings LIMIT 0,1;";
		$get_fname = mysql_query("$getforumname");
		while( $getinfo = mysql_fetch_object( $get_fname ) )
		{
		$forumname = $getinfo -> forum_name;
		}
						$f_id = $_SESSION['forum_id'];
		$g_c = "SELECT * FROM eb_forum WHERE `ForumID` = '$f_id' LIMIT 0,1";
		$g_cat = mysql_query("$g_c");
		while( $getinfo = mysql_fetch_object( $g_cat ) )
		{
		$ct = $getinfo -> name;
		}
echo '<br><table width="100%" border="0" cellspacing="0">
  <tr>
    <td class="eb_header"><a href="?">' . $forumname . '</a> &gt;&gt; <a href="?showforum=' . $f_id . '">' . $ct . '</a></td>
  </tr>
</table>
';
}
elseif ( $do == "3" ) {
		$getforumname = "SELECT * FROM eb_settings LIMIT 0,1;";
		$get_fname = mysql_query("$getforumname");
		while( $getinfo = mysql_fetch_object( $get_fname ) )
		{
		$forumname = $getinfo -> forum_name;
		}
		echo '<table width="100%" border="0" cellspacing="0">
  <tr>
    <td class="eb_header"><a href="?">' . $forumname . '</a></td>
  </tr>
</table>
';
}
elseif ( $do == "4" ) {
		$getforumname = "SELECT * FROM eb_settings LIMIT 0,1;";
		$get_fname = mysql_query("$getforumname");
		while( $getinfo = mysql_fetch_object( $get_fname ) )
		{
			$forumname = $getinfo -> forum_name;
		}
echo'
	  <table width="100%"  border="0" cellspacing="0" class="eb_header">
        <tr>
          <td><a href="index.php">' . $forumname . '</a> &gt;&gt; <a href="usercp.php">User Control Panel</a> </td>
        </tr>
      </table>';
	}
elseif ( $do == "5" ) {
	$forum_NAME = $forumname;
	echo "
		  <table width=\100%\"  border=\"0\" cellspacing=\"0\">
			<tr>
			  <td><td width=\"100%\" class=\"eb_header\" height=\"15\">&nbsp;<a href=\"index.php?\">$forum_NAME</a> >> <a href=\"#\">Member Groups</a> </td>
			</tr>
		  </table><br>";
	}
	else { die("ERROR!! :: sql_show_all() :: Missing Option [sql_show_all(??);]"); }
}
function upload_file($input_name, $path)
         {
         global $HTTP_POST_FILES;
		 				 
         if(isset($HTTP_POST_FILES) && is_uploaded_file($HTTP_POST_FILES[$input_name]["tmp_name"]))
           {
           $file_name = $HTTP_POST_FILES[$input_name]["name"];   
           if($HTTP_POST_FILES[$input_name]['type'] != "image/gif" AND $HTTP_POST_FILES[$input_name]['type'] != "image/pjpeg" AND $HTTP_POST_FILES[$input_name]['type'] !="image/jpeg") {
  $error = "This file type is not allowed";
  echo "$error";
  unlink($HTTP_POST_FILES[$input_name]['tmp_name']);
} else {
$maxfilesize=500000;

if ($HTTP_POST_FILES[$input_name]['size'] > $maxfilesize) {
  $error = "file is too large";
  echo $error;
  unlink($HTTP_POST_FILES[$input_name]['tmp_name']);
} else {
  //the file is under the specified number of bytes.

           //For those who have policies about the file types that can be uploaded
           //to their sites, $file_name can be modified to disable unwanted behaviour
           //like the serving of unwanted content.
           move_uploaded_file($HTTP_POST_FILES[$input_name]["tmp_name"], 
                              $path . "/" . $file_name);
							  $xfx = $HTTP_POST_FILES[$input_name]["name"];
							  $uid = $_SESSION['userid'];
			$query = "UPDATE `eb_profile` SET `logo` = 'Upload/$xfx' WHERE `id` = '$uid';";
			mysql_query ( $query );               
           //I do this because some servers will set the permissions on uploaded files
           //to 0600 or 0700.  That makes recently uploaded images unviewable.
           chmod($path . $file_name, 0644);
           }
         }
		}
	}
	##############
	## CLASS DB ##
	##############
	class db
	{
		###############################
		## CONNECT TO MYSQL DATABASE ##
		###############################
		function connect()
			{
				include("config.php");
				mysql_connect($eb_server,$eb_user,$eb_password);
				mysql_select_db($eb_db);
			}
		#################
		## MYSQL QUERY ##
		#################
		function query($query) 
			{
				$db_connected = mysql_query($query);
				return $db_connected;
			}
	}
	class setting
	{
		function num_post()
		{
			$db = new db;
			$db->connect();
			$query = $db->query("SELECT * FROM eb_settings LIMIT 0, 1");
			$num = mysql_fetch_array($query);
			$num = $num['post_per_page'];
			return $num;
		}
		function num_topic()
		{
			$db = new db;
			$db->connect();
			$query = $db->query("SELECT * FROM eb_settings LIMIT 0, 1");
			$num = mysql_fetch_array($query);
			$num = $num['topic_per_page'];
			return $num;
		}
		function num_members()
		{
			$db = new db;
			$db->connect();
			$query = $db->query("SELECT * FROM eb_settings LIMIT 0, 1");
			$num = mysql_fetch_array($query);
			$num = $num['members_per_page'];
			return $num;
		}
		function badword_replace()
		{
			$db = new db;
			$db->connect();
			$query = $db->query("SELECT * FROM eb_settings LIMIT 1;");
			$replace = mysql_fetch_array($query);
			$replace = $replace['badword_replace'];
			return $replace;
		}
	}
	class eboard {
		function uname_id($uname) {
			$db = new db;
			$db->connect();
			$uid = $db->query("SELECT * FROM eb_members WHERE username = '{$uname}'");
			$uid = mysql_fetch_array($uid);
			$uid = $uid['userid'];
			return $uid;
		}
		function user_mod($username,$forum,$opt) {
			$db = new db;
			$db->connect();
			if ($opt !== "None") {
				if ($opt == "CAN_EDIT") {
				$mod = $db->query("SELECT * FROM eb_moderator WHERE member_name = '{$username}' AND forum_id = '{$forum}' AND delete_post = '1'");
				}
				elseif ($opt == "CAN_DELETE") {
				$mod = $db->query("SELECT * FROM eb_moderator WHERE member_name = '{$username}' AND forum_id = '{$forum}' AND delete_post = '1'");
				}
				elseif ( $opt == "VIEW_IP") {
				$mod = $db->query("SELECT * FROM eb_moderator WHERE member_name = '{$username}' AND forum_id = '{$forum}' AND view_ip = '1'");
				}
			}
			$num_rows = @mysql_num_rows($mod);
			if ($num_rows > 0) { return TRUE; }
			elseif ($num_rows < 0) { return FALSE; }
		}
	}
?>