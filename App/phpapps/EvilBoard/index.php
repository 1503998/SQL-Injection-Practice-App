<?
/*
* EvilBoard Forum System
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL License  - 2006
*********** License *************
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
* 
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
* http://www.gnu.org/licenses/lgpl.html
********* End of License **********
*/
session_start(); 
include("config.php");
if($eb_install == "0" || empty($eb_install)) { header("Location: install/install.php"); }
require_once("functions/functions.php");
require_once("functions/tpl.php");

if (!$_GET) {
header("Location: index.php?act=idx");
}
global $db;
$db = new db;
$db->connect();
if($_SESSION['user_level'] == "1") { include("include/header.php"); echo "<br>"; $tpl = new template; $x = $tpl->top("EvilBoard - Index"); $x .= $tpl->smallbox("Banned", "You have been banned from these forums"); echo $x; exit(); }
switch($_GET['act'])
{
	case 'showtopic':		//Load Showtopic
	require("showtopic.php");
	break;
	
	case 'showforum':		//Load Showforum
	require("showforum.php");
	break;
	
	case 'post':		//Post
	require("post.php");
	break;
	
	case 'posttopic':		// Post Topic
	require("posttopic.php");
	break;
	
	case 'logout':		// Logout
	require("logout.php");
	break;
	
	case 'edit':		// Edit
	require("edit.php");
	break;
	
	case 'delete':		// Delete
	require("delete.php");
	break;
	
	case 'usercp':		// UserCP
	require("usercp.php");
	break;
	
	case 'members':		// Members
	require("profile/p-read.php");
	break;
	
	case 'memberslist':		// Memberslist
	require("profile.php");
	break;
	
	case 'group':		// Group
	require("group.php");
	break;
	
	case 'register':		// Register
	require("register.php");
	break;
	
	case 'login':		// Login
	require("login.php");
	break;
	
	default:
	showforum();
	break;
}

function showforum()
{
	include("include/header.php");
	echo '<br><table cellpadding="5" cellspacing="0" border="0" style="width:100%;">';
	// include our much-needed database info.
	if($_GET['c']) { 	$result = mysql_query("select * from eb_category WHERE id = '{$_GET[c]}' order by id asc") or die(mysql_error()); }
	elseif (!$_GET['c']) { 	$result = mysql_query("select * from eb_category order by id asc") or die(mysql_error()); }
	// selects all the rows from the "categories" table, then orders them by id, ascending.
	   // a while loop to repeat code until all our rows have been echo'd out..
	   while($r = mysql_fetch_array($result))
	   {
		  // redefine our category row variables.
		  $category_id = $r['id'];
		  $category_title = $r['category'];
		  $category_description = $r['description'];
		  echo "
	  <tr>
		<td colspan=\"4\" class=\"eb_forum\"><b><a style=\"color: white;\" href=\"index.php?act=idx&c={$category_id}\">{$category_title}</a></b></td>
	  </tr>
	  <tr>
		<td class=\"eb_forum_header\" style=\"border-top-width: 0; height: 0px;\">Forum:</td>
		<td class=\"eb_forum_header\" style=\"border-left-width: 0; border-right-width: 0; border-top-width: 0; height: 0px;\"><div align=\"center\">Topics:</div></td>
		<td class=\"eb_forum_header\" style=\"border-top-width: 0; height: 0px;\"><div align=\"center\">Replays:</div></td>
	</tr>";
		 // selects our forums if the "cid" is the same as our $category_id.
		 $result2 = mysql_query("select * from eb_forums where cid = '$category_id'");
		 
		 // the while loop to echo out our corresponding forums
		 while($r2 = mysql_fetch_array($result2))
		 {
			// redefine our forum row variables.
			$forum_id = $r2['id'];
			$forum_title = $r2['title'];
			$forum_description = $r2['description'];
			$forum_last_post_title = $r2['last_post_title'];
			$forum_last_post_username = $r2['last_post_username'];
					// Get Rows 
			$query = "SELECT * FROM `eb_topic` WHERE `ForumID` = '$forum_id'";
			$connect = mysql_query($query);
			$num_topic_rows = mysql_num_rows($connect);
			// End Get Rows
			// Get Rows 
			$query2 = "SELECT * FROM `eb_post` WHERE `ForumID` = '$forum_id'";
			$connect2 = mysql_query($query2);
			$num_replay_rows = mysql_num_rows($connect2);
			$db = new db;
			$db->connect();
			$forum_mod = $db->query("SELECT * FROM eb_moderator WHERE `forum_id` = '{$forum_id}';");
			$rows = mysql_num_rows($forum_mod);
			if ($rows > 0) {
			$eb_moderator = "<strong>Moderators:</strong> ";
				while ( $mod = mysql_fetch_array($forum_mod)) {
					$mod_name = $mod['member_name'];
					$eb_moderator .= " <a href=\"index.php?act=members&memberid={$mod['member_id']}\">" . $mod_name . "</a>,";
				}
			}
			else {
			/* Do nothing, uncomment these lines if you want it to print out Moderators: None */
				#$eb_moderator = "<strong>Moderators:</strong> ";
				#$eb_moderator .= "<i>None</i>";
			}
			echo "
	  <tr>
		<td width=\"68%\" class=\"forum_footer\">
		<a href=\"index.php?act=showforum&f={$forum_id}\">{$forum_title}</a><br />
		{$forum_description}<br />
		{$eb_moderator}
		</td>
		<td width=\"16%\"  class=\"forum_footer\" style=\"border-left-width: 0; border-right-width: 0;\"><div align=\"center\"> {$num_topic_rows}</div>
	   </td>
		<td width=\"16%\"  class=\"forum_footer\"><div align=\"center\">{$num_replay_rows}</div></td>
	  </tr>";
		  } // end while loop
		  ?>
		  </td>
		<td class="eb_showtopic_extra" colspan="4" style="height: 5px; border-left-width: 0; border-right-width: 0; border-bottom-width: 0;">&nbsp;</td>
	  </tr>
	  <?
	} // end while loop
	echo '</table>';
	?>
	<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td height="22" class="eb_forum"><b>&nbsp;Statistics</b></td>
  </tr>
  <tr>
    <td class="forum_footer"> &nbsp;Our members have made a total of <b><?=$total_post?></b> posts.<br>
&nbsp;We have totaly <b><?PHP sql_stats("totalmem",""); ?></b> registered members. <br>
&nbsp;The newest member is<b><span class="style1">:</span> <?php sql_stats("lastmem",""); ?> :: </b><a href="?act=memberslist"><b>View Full Memberlist</b></a></td>
  </tr>
</table><br>
<?
include("include/footer.php");
}
?>