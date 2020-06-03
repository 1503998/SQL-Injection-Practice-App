<?
/*
* Manage Forum
* Description: Manage forums and categorys
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
## If not defined : IN_ACP do PHP::DIE()
if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
## Define $template
$template = new template;
## Change title to "Evilboard Admin Control Panel - Forum Management :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Forum Management :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
$mng = new manage;
switch($_GET['do']) {
	case 'permission':
	return $mng->edit();
	break;
	
	default:
	return $mng->idx();
	break;
} 
class manage {
function idx() {
?>
		  <script type="text/javascript">
						function open_search() {
							var pop=window.open("inc/usr_src.php","","width=415,height=200")
						}
						function ins_usr(usr) {
							document.ins.usrn.value = '' + usr + '';
							//document.write("EL D");
						}
		</script>
						<?
		$this->frm .= "<form action=\"index.php?code=81&do=permission\" method=\"POST\" name=\"ins\"><br class=\"eb_txt\">
<table width=\"600\" border=\"0\" cellspacing=\"0\" align=\"center\">
								<tr>
									  <td colspan=\"4\" class=\"eb_forum\"><div align=\"center\"><b>Edit Member:</b></div></td>
								</tr>
								<tr>
								  <td class=\"a_inf\" style=\"width: 100px; border-top-width: 0;border-right-width:0;\">Username:</td>
								  <td class=\"a_inf\" style=\"width: 300px; border-top-width: 0; border-right-width:0;\"><input type=\"text\" id=\"usrn\" name=\"usrs\" style=\"width: 300px; border: 1px solid #a6a6a6;\"></td>
								  <td class=\"a_inf\" style=\"width: 200px; border-top-width: 0;border-right-width:0; border-left-width: 0;\"><input type=\"submit\" name=\"Submit\" value=\"Set user permissions\"></td>
								  <td class=\"a_inf\" style=\"width: 200px; border-top-width: 0;border-left-width: 0;\"><input type=\"button\" name=\"is\" value=\"Search after username\" onClick=\"javascript: open_search();\"></td>
								</tr>
							  </table>
							   </form><br>";
		return $this->frm;
	}
	function edit() {
	switch($_GET['act']) {
	case 'add':
	$this->add_moderator();
	break;
	
	case 'delete':
	$this->delete_moderator();
	break;
	
	default:
	/* Show nothing */
	break;
	}
	$usr = $_POST['usrs'];
	$db = new db;
	$db->connect();
	$eboard = new admin;
	$uid = $eboard->uname_id("$usr");
	$rank = $db->query("SELECT * FROM eb_profile WHERE `id` = '{$uid}'");
	$rank = mysql_fetch_array($rank);
	$rank = $rank['rank'];
	if(empty($rank)) {
		$rank = $eboard->rank('1');
	}
	elseif(!empty($rank)) {
		$rank = $eboard->rank($rank);
	}
	$forums = $this->view_forums();
	$this->permission = "<span id=\"b\">&nbsp;Permissions</span><br /><br />
    &nbsp;<strong>Username:</strong> {$usr} <br />
      <strong>&nbsp;Rank:</strong> {$rank} <br /><br />
	<form action=\"index.php?code=81&do=permission_update\" method=\"post\">
    &nbsp;<span id=\"b\">Forum Permissions</span><br><br>{$forums} ";
	return $this->permission;
	
	}
	function view_forums() {
	$f = '
<table width="700"  border="0" cellspacing="0" align="center">
  <tr>
    <td colspan="4" class="eb_forum">&nbsp;<strong>Forum</strong></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="eb_forum_header" style="border-top-width: 0; border-right-width: 0;">&nbsp;Forum // Category:</td>
    <td width="13%" class="eb_forum_header" style="border-left-width: 0; border-top-width: 0;"><div align="center" height="20">&nbsp;</div></td>
  </tr>	
';
$db = new db;
$db->connect();
	// include our much-needed database info.
	
	// selects all the rows from the "categories" table, then orders them by id, ascending.
	$result = mysql_query("select * from eb_category order by id asc") or die(mysql_error());
	   // a while loop to repeat code until all our rows have been echo'd out..
	   while($r = mysql_fetch_array($result))
	   {
		  // redefine our category row variables.
		  $category_id = $r['id'];
		  $category_title = $r['category'];
		  $category_description = $r['description'];
	$f .= '
 <tr>
		<td height="30" colspan="2" class="a_item" style="color: #0099CC; height: 5px; border-right-width: 0;">&nbsp; ' . $category_title . '</td>
		<td class="a_item" style=" height: 5px; border-left-width: 0;"><div align="center">&nbsp;</div></td>
	  </tr>
	';
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
			?>
			<script language="javascript" type="text/javascript">
			function change(ext)
			{
			location.href=ext;
			}
			</script>
			<?
			$query = $db->query("SELECT * FROM eb_moderator WHERE forum_id = '{$forum_id}' AND member_name = '{$usr}'");
			$ismod = mysql_fetch_array($query);
			if(!empty($ismod['forum_id'])) {
				$ismod = "true";
			}
			elseif(empty($ismod['forum_id'])) {
				$ismod = "false";
			}
			$usr = $_POST['usrs'];
	$f .= "  <tr>
		<td width=\"1\" class=\"forum_footer\" style=\"border-right-width:0;\"><img src=\"Images/mod-{$ismod}.gif\" /></td>
		<td width=\"100%\" class=\"forum_footer\" style=\"border-left-width:0; border-right-width: 0;\">
		<a href=\"../index.php?act=showforum&f={$forum_id}\">&nbsp;{$forum_title}</a><br />
		&nbsp;{$forum_description}
		</td>
		<td width=\"11%\"  class=\"forum_footer\" style=\"border-left-width: 0;\"><div align=\"center\"><select name=\"check_{$forum_id}\">
		<option>&nbsp;</option>
  <option value=\"0\" onClick=\"javascript:change('index.php?code=81&do=permission&act=delete&forum={$forum_id}&name={$usr}');\">Not Moderator</option>
  <option value=\"1\" onClick=\"javascript:change('index.php?code=81&do=permission&act=add&forum={$forum_id}&name={$usr}');\">Moderator</option>
</select></div>
	   </td>
	 ";
		  } // end while loop
	} // end while loop
	$f .= "</table></form><br /><span id=\"b\">&nbsp;Legend</span><br /><div align=\"center\"><div style=\"width: 700px; height: 20px;\" class=\"eb_header\"><b>Legend</b></div><div style=\"width: 700px;\" class=\"forum_footer\">User is moderator on forum (<img src=\"Images/mod-true.gif\" />)&nbsp; if user is not moderator on the forum (<img src=\"Images/mod-false.gif\" />)</div></div><br />";
	
	return $f;
	}
	function uname_id($uname) {
			$db = new db;
			$db->connect();
			$uid = $db->query("SELECT * FROM eb_members WHERE username = '{$uname}'");
			$uid = mysql_fetch_array($uid);
			$uid = $uid['userid'];
			return $uid;
	}
	function add_moderator()
	{
		if(!$_GET['forum'] || !$_GET['name'])
		{
		/* Not show anything */
		}
		else {
			$forumid = $_GET['forum'];
			$name = $_GET['name'];
			# Check if user exist in DB
			$db = new db;
			$db->connect();
		    $exsit = $db->query("SELECT * FROM eb_moderator WHERE `member_name` = '{$name}' AND `forum_id` = '{$forumid}'");
			$num_rows = mysql_num_rows($exsit);
			$id = $this->uname_id($name);
			if($num_rows == 0) {
				$db->query("INSERT INTO `eb_moderator` ( `mid` , `forum_id` , `member_name` , `member_id` , `edit_post` , `delete_post` , `view_ip` )
				VALUES (
							NULL , '{$forumid}', '{$name}', '{$id}', '1', '1', '1'
							);");
			}
			else {
			/* do noting */
			}
		}
	}
	function delete_moderator()
	{
		if(!$_GET['forum'] || !$_GET['name'])
		{
		/* Not show anything */
		}
		else {
			$forumid = $_GET['forum'];
			$name = $_GET['name'];
			# Check if user exist in DB
			$db = new db;
			$db->connect();
		    $exsit = $db->query("SELECT * FROM eb_moderator WHERE `member_name` = '{$name}' AND `forum_id` = '{$forumid}'");
			$num_rows = mysql_num_rows($exsit);
			$id = $this->uname_id($name);
			$db->query("DELETE FROM eb_moderator WHERE `forum_id` = '{$forumid}' AND member_name = '{$name}'");
		}
	}
}
?>