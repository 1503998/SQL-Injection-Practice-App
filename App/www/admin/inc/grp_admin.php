<?
/*
* Group Management
* Description: Edit Groups
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
## If not defined : IN_ACP do PHP::DIE()
if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
## Define $template
$template = new template;
## if we $GET group delete run this before header, reason: if not the header dont work.
if (isset($_GET['del_grp'])) {
	$db = new db;
	$g_id = $_GET['g'];
	$db->connect();
	$db->query("DELETE FROM eb_group WHERE `g_id` = {$g_id}");
	$db->query("DELETE FROM eb_group_mem WHERE `g_id` = {$g_id}");
	header("Location: index.php?code=52");
}
## Change title to "Evilboard Admin Control Panel - Forum Management :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Group Management :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();

$grp = new group;
if ($_GET['edit'] == "l") {
	return $grp->e_l();
}
if ($_GET['kick']) {
	$k = $grp->kick();
	$k .= $grp->edit();
	return $k;
}
if ($_GET['a'] == "n") {
	return $grp->a_n();
}
if ( isset($_POST['Add'])) {
	return $grp->a_mem() . $grp->edit();
}
if ( isset($_GET['g']) ) {
	return $grp->edit();
}
return $grp->index();
## Class :: Group
class group
{
	## Index site
	function index()
	{
	$grp = "
		<table width=\"800\"  border=\"0\" cellspacing=\"0\" align=\"center\">
	  <tr>
		<td colspan=\"3\" class=\"eb_forum\"><div align=\"center\"><b>Member groups</b></div></td>
	  </tr>
	  <tr>
		<td width=\"71%\" class=\"eb_header\" style=\"height: 20px; border-top-width: 0;\">&nbsp;Group Name: </td>
		<td width=\"19%\" class=\"eb_header\" style=\"height: 20px; border-top-width: 0;border-left-width: 0;\"><div align=\"center\">Group Leader: </div></td>
		<td width=\"10%\" class=\"eb_header\" style=\"height: 20px; border-top-width: 0;border-left-width: 0;\"><div align=\"center\">Members:</div></td>
	  </tr>";
	  
	 $db = new db;
	 $db->connect();
	 
	 $connect = $db->query("SELECT * FROM eb_group;");
	 
	 while( $group = mysql_fetch_object( $connect ) )
		 {
			 $name = $group->name;
			 $color = $group->clr;
			 $name_new = $this->colored($color,$name);
			 $id = $group->g_id;
			 $u_id = $group->u_id;
			 $leader = $this->leader($u_id);
			 $count_member = $this->count_members($name, $id);
			 $status = $group->status;
			 $grp .= "
				<tr>
				<td class=\"forum_footer\" height=\"20\">&nbsp;<a href=\"index.php?code=52&g=$id\">$name_new</a></td>
				<td class=\"forum_footer\" style=\"border-left-width: 0;\"><div align=\"center\"><a href=\"../index.php?act=members&memberid=$u_id\">$leader</a></div></td>
				<td class=\"forum_footer\" style=\"border-left-width: 0;\"><div align=\"center\">$count_member</div></td>
			  </tr>"; 		}
		$grp .= "</table><br class=\"eb_txt\"><div align=\"center\"><div style=\"width: 800px;\"><div align=\"right\"><a href=\"index.php?code=52&a=n\"><img src=\"Images/new-grp.gif\" border=\"0\"></a></div></div></div>";
		return $grp;
	}
	## Returns username from id
	function leader($leader_id) {
		$db = new db;
		$get_leader = $db->query("SELECT * FROM eb_members WHERE userid = '$leader_id'");
		$l_get = mysql_fetch_array( $get_leader );
		
		$leader = $l_get['username'];
		return $leader;
	}
	## Returns id from username
	function un_id($name) {
		$db = new db;
		$db->connect();
		$INF->query = $db->query("SELECT * FROM eb_members WHERE username = '{$name}'");
		$INF->name = mysql_fetch_array($INF->query);
		
		$INF->id = $INF->name['userid'];
		return $INF->id;
	}
	## Count members in group
	function count_members($group,$id) {
		$db = new db;
		$db->connect();
		$count = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '{$id}'");
		$count = mysql_num_rows( $count );
		return $count;
	}
	## Function colored // Changes $text to $color
	function colored($color,$text)
	{
		if ( $color == "" ) { return "$text"; }
		elseif ( $color !== "") { return "<font color=\"$color\">$text</font>"; }
	}
	function a_mem()
	{
		$u_n = $_POST['textfield'];
		$g = $_GET['g'];
		$INF->userid = $this->un_id($u_n);
		$db = new db;
		$db->connect();
		$lead = $this->leader("$INF->userid");
		$INF->q = $db->query("INSERT INTO `eb_group_mem` ( `id` , `grp_id` , `userid` , `admin` )
								VALUES (
								NULL , '{$g}', '{$INF->userid}', 'normal');");
		return "<div align=\"center\"><span class=\"eb_txt\">User {$lead} (Userid: #{$INF->userid}) has been added to group.</span></div>";
	}
	function k_mem()
	{
	
	}
	function create___grp()
	{
		$grp->NAME = $_POST['grp_n'];
		$grp->LEADER = $_POST['grp_l'];
		$grp->STATUS = $_POST['select'];
		$db = new db;
		$db->connect();
	}
	function create__grp()
	{
	return '<form name="form1" method="post" action="' . $PHP_SELF . '">
			  <table width="800" border="0" align="center" cellspacing="0">
				<tr>
				  <td class="eb_forum"><div align="center"><strong>Create Group </strong></div></td>
				</tr>
				<tr>
				  <td class="forum_footer"><table width="431" border="0" align="center" cellspacing="0">
					<tr>
					  <td width="254" class="eb_txt">Group Name </td>
					  <td width="542"><input name="grp_n" type="text" class="eb_header" style="width: 200px;"></td>
					</tr>
					<tr>
					  <td class="eb_txt">Group Leader </td>
					  <td><input name="grp_l" type="text" class="eb_header" style="width: 200px;"></td>
					</tr>
					<tr>
					  <td class="eb_txt">Group Status </td>
					  <td><select name="select" class="eb_header" style="width: 200px;">
						<option value="open">Open</option>
						<option value="close">Closed</option>
					  </select></td>
					</tr>
					<tr>
					  <td colspan="2"><div align="center">
						<input type="submit" name="Submit" value="Submit">
					  </div></td>
					  </tr>
				  </table>      </td>
				</tr>
			  </table>
			</form>
			';
	}
	function edit()
	{
	$id = $_GET['g'];
	
	$db = new db;
	$db->connect();
	$connect = $db->query("SELECT * FROM eb_group WHERE `g_id` = '$id'");
	$group->info = mysql_fetch_array( $connect );
	session_register("eb_grp");
	$_SESSION['eb_grp'] == $group->info[name];
	$this->grpn = $this->colored($group->info['clr'], $group->info['name']);
		$INF .= '
		<script language="JavaScript" type="text/javascript">function confirm_external(ext)
{
	input_box = confirm("Clicking on this button will delete this group, if you want to continue click OK");
	if ( input_box == true )
	{ 
		location.href = ext; 
	}
	
	else
	{
		// Do nothing
	}
}</script>';
$INF .= "
		<form name=\"grp\" method=\"post\" action=\"javascript: confirm_external('index.php?code=52&g={$group->info[g_id]}&del_grp');\"><table width=\"800\"  border=\"0\" cellspacing=\"0\" align=\"center\">
  <tr>
    <td colspan=\"2\" class=\"eb_forum\"><div align=\"center\"><b>Group Information :</b></div></td>
  </tr>
  <tr>
    <td width=\"22%\" class=\"forum_footer\"><div align=\"right\">Group Name: </div></td>
    <td width=\"78%\" class=\"forum_footer2\">&nbsp;<strong>{$this->grpn}</strong></td>
  </tr>
  <tr>
    <td class=\"forum_footer\"><div align=\"right\">Group Description: </div></td>
    <td class=\"forum_footer2\">&nbsp;{$group->info[inf]}</td>
  </tr>
<tr><td width=\"100%\" class=\"forum_footer\" align=\"center\" colspan=\"2\" style=\"border-top-width: 0;\"><input name=\"Submit\" type=\"submit\" class=\"eb_header\" value=\"Delete Group\"></td></tr>";
	 $INF .= "</table>
<br>
<table width=\"800\"  border=\"0\" cellspacing=\"0\" align=\"center\">
  <tr>
    <td colspan=\"3\" class=\"eb_forum\"><div align=\"center\"><b>Group members:</b> </div></td>
  </tr>";
  $group->query = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '$id' AND admin = 'admin'");
  $group->id = mysql_fetch_array( $group->query );
  $group->leader = $this->leader($group->id[userid]);
  
  $INFO->query = $db->query("SELECT * FROM eb_post WHERE UserID = '{$group->id[userid]}'");
  $INFO->get = mysql_num_rows( $INFO->query );
$INF .= "
  <tr>
    <td colspan=\"3\" class=\"eb_header\" style=\"border-top-width: 0; height: 20px;\">&nbsp;Group leader: </td>
  </tr>
  <tr>
    <td width=\"72%\" class=\"eb_showforum_right\" style=\"border-top-width: 0;\">&nbsp;Username:</td>
    <td width=\"15%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Posts:</div></td>
    <td width=\"13%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Edit Leader: </div></td>
  </tr>
  <tr>
    <td class=\"forum_footer\">&nbsp;<a href=\"../index.php?act=members&memberid={$group->id[userid]}\">{$group->leader}</a></td>
    <td class=\"forum_footer2\"><div align=\"center\">{$INFO->get}</div></td>
    <td class=\"forum_footer2\"><div align=\"center\"><a href=\"index.php?code=52&g={$group->info[g_id]}&edit=l\">Edit</a></div></td>
  </tr>
  <tr>
    <td colspan=\"3\" class=\"eb_header\" style=\"border-top-width: 0; height: 20px;\">&nbsp;Group members: </td>
  </tr>
  ";
  $members->query = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '$id' AND admin = 'normal';");
 	  $forum = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '$id' AND admin = 'normal';");
	  $forum = mysql_num_rows( $forum );
	  	  if ($forum == "0") { $INF .= "  <tr>
    <td width=\"72%\" class=\"eb_showforum_right\" style=\"border-top-width: 0;\">&nbsp;Username:</td>
    <td width=\"15%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Posts:</div></td>
    <td width=\"13%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Kick Member: </div></td>
  </tr><tr><td class=\"forum_footer\" colspan=\"3\"><div align=\"center\"><i>There are no members of this group.</i></div></td></tr>"; }
  else { $INF .= "  <tr>
    <td width=\"72%\" class=\"eb_showforum_right\" style=\"border-top-width: 0;\">&nbsp;Username:</td>
    <td width=\"15%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Posts:</div></td>
    <td width=\"13%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Kick Member: </div></td>
  </tr>"; }
  while ( $get = mysql_fetch_object( $members->query ) ) {
	  $userid = $get->userid;
	  $name = $this->leader($userid);
	  $members->query2 = $db->query("SELECT * FROM eb_post WHERE UserID = '{$userid}'");
	  $members->get = mysql_num_rows( $members->query2 );
	  $g = $_GET['g'];
	  $INF .= "
		  <tr>
			<td class=\"forum_footer\" style=\"border-top-width: 0;\">&nbsp;<a href=\"../index.php?act=members&memberid={$userid}\">$name</a></td>
			<td class=\"forum_footer2\" style=\"border-top-width: 0;\"><div align=\"center\">{$members->get}</div></td>
			<td class=\"forum_footer2\" style=\"border-top-width: 0;\"><div align=\"center\"><a href=\"index.php?code=52&g={$g}&kick={$userid}\">Kick</a></div></td>
		  </tr>";
 	 }
 	 $INF .= "</table></form>";
	 $INF .= '<form name="form1" method="post" action="' . $PHP_SELF . '"><table width="800" border="0" cellspacing="0" align="center">
    <tr>
      <td width="85" class="eb_txt">Add member: </td>
      <td width="144"><input type="text" name="textfield" class="eb_header"></td>
      <td><input name="Add" type="submit" id="Add" value="Add" class="eb_header"></td>
    </tr>
  </table></form>
';
	 return $INF;
	}
	function button()
	{
				 return "<tr><td width=\"100%\" class=\"forum_footer\" align=\"center\" colspan=\"2\" style=\"border-top-width: 0;\"><input name=\"Submit\" type=\"submit\" class=\"eb_header\" value=\"Join\"></td></tr>";
	}
	function a_n()
	{
	if ( isset($_POST['Submit'])) {
	$db = new db;
	$db->connect();
	
	$GRP->name = $_POST['grp_n'];
	$GRP->color = $_POST['grp_c'];
	$GRP->status = $_POST['select'];
	$GRP->ld = $_POST['grp_l'];
	$GRP->info = $_POST['info'];
	$GP->userid = $this->un_id($GRP->ld);
	
	$db->query("INSERT INTO `eb_group` ( `g_id` , `name` , `clr` , `inf` , `status` , `u_id` )
				VALUES (
				NULL , '{$GRP->name}', '{$GRP->color}', '{$GRP->info}', '{$GRP->status}', '{$GP->userid}'
				);") or die("Error: ". mysql_error());
	$GRP->id = $db->query("SELECT * FROM eb_group ORDER by g_id DESC LIMIT 0, 1;");
	$GRP->id = mysql_fetch_array($GRP->id);
	$db->query("INSERT INTO `eb_group_mem` ( `id` , `grp_id` , `userid` , `admin` )
				VALUES (
				NULL , '{$GRP->id[g_id]}', '{$GP->userid}', 'admin'
				);");
	$sh = new template;
	$GRP->ret = $sh->shortbox("Group Added", "Group has been added to database , click <a href=\"index.php?code=52\">here</a> to return to group management");
	return $GRP->ret;
	exit();
	}
	return '<style type="text/css">
			<!--
			.style2 {color: #000000}
			-->
			</style>
			<table width="800" border="0" align="center" cellspacing="0">
			  <tr>
				<td class="a_inf"><div align="center"> <span id="b">Create Group</span></div>
					<form name="form1" method="post" action="">
					
					<table width="298" border="0" align="center" cellspacing="0">
					  <tr>
						<td width="94" class="eb_txt"><div align="right" class="style2">
							<div align="left"><span class="eb_txt ">Group name:</span></div>
						</div></td>
						<td width="200"><input name="grp_n" type="text" id="grp_n" style="width: 200px;"></td>
					  </tr>
					  <tr>
						<td class="eb_txt"><div align="left" class="style2">Group Color: </div></td>
						<td><input name="grp_c" type="text" id="grp_c" style="width: 200px;"></td>
					  </tr>
					  <tr>
						<td class="eb_txt"><div align="left"><span class="style2">Group Status: </span></div></td>
						<td><select name="select" style="width: 200px;">
							<option value="open">Open</option>
							<option value="closed">Closed</option>
						</select></td>
					  </tr>
					  <tr>
						<td class="eb_txt"><div align="left"><span class="style2">Group Leader: 
						</span></div></td>
						<td class="eb_txt"><input type="text" name="grp_l" style="width: 200px;"></td>
					  </tr>
					  <tr>
						<td class="eb_txt" valign="top"><div align="left"><span class="style2">Information:
						</span></div></td>
						<td class="eb_txt" valign="top"><textarea name="info" style="width: 200px;"></textarea></td>
					  </tr>
					  <tr>
						<td colspan="2" class="eb_txt" ><div align="center"><span class="style2">
							<input type="submit" name="Submit" value="Submit">
						</span></div></td>
					  </tr>
				  </table>
					</form></td>
			  </tr>
			</table>';
	}
	function kick()
	{
		$kick->uid = $_GET['kick'];
		$db = new db;
		$db->connect();
		$db->query("DELETE FROM `eb_group_mem` WHERE `userid` = '{$kick->uid}' LIMIT 1;");
	}
	function e_l()
	{
	$db = new db;
	$db->connect();
	$gpn = $_GET['g'];
	$EL->Q = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '{$gpn}' AND admin = 'admin'");
	$E = mysql_fetch_array($EL->Q);
	$LDR = $this->leader($E['userid']);
	$EDITLEADER->ret = '<table width="800" border="0" align="center" cellspacing="0">
						  <tr>
							<td class="a_inf"><div align="center"> <span id="b">Edit Leader </span></div>
								<form name="form1" method="post" action="index.php?code=52">
								  <table width="328" border="0" align="center" cellspacing="0">
									<tr>
									  <td><div align="right"><span class="eb_txt" style="color:black;">Leader:</span></div></td>
									  <td><input type="text" name="textfield" value="' . $LDR . '"></td>
									</tr>
									<tr>
									  <td colspan="2"><div align="center">
										<input type="submit" name="Submit" value="Submit">
									  </div></td>
									</tr>
								  </table>
								</form></td>
						  </tr>
						</table>
						';
		return $EDITLEADER->ret;
	}
}
?>