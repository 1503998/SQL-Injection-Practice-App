<?
if ( $_GET['accept'] == "false" ) {
	header("Refresh: 1; index.php?act=group");
}
include("include/header.php");
echo "<br>";
######################
## EvilBoard Groups ##
######################
$group = new group;
	if ( isset($_GET['validate'] ) ) {
		$group->validate();
		exit();
	}
if ( !isset($_GET['group']) ) {
		echo "
		  <table width=\100%\" border=\"0\" cellspacing=\"0\" style=\"margin-left: 0px\">
			<tr>
			  <td><td width=\"100%\" class=\"eb_header\"><a href=\"index.php?\">$forum_NAME</a> >> <a href=\"#\">Member Groups</a> </td>
			</tr>
		  </table>";
	echo "<br>";
	$group->top();
echo "<br>";	
}
elseif ( isset($_GET['group']) ) {
	$id = $_GET['group'];
	$group->join_grp();
	$group->info($id);
}
include("include/footer.php");
######################
## Class: group 	##
######################
class group
{
	////////////////////////
	// Function: top();
	// Description: Creating the required table for the groups to be listed
	////////////////////////
	function top()
	{
	echo "
		<table width=\"100%\"  border=\"0\" cellspacing=\"0\">
	  <tr>
		<td colspan=\"3\" class=\"eb_menu1\"><div align=\"center\">Member groups</div></td>
	  </tr>
	  <tr>
		<td width=\"71%\" class=\"eb_showforum_right\">&nbsp;Group Name: </td>
		<td width=\"19%\" class=\"eb_showforum_right\" style=\"border-left-width: 0;\"><div align=\"center\">Group Leader: </div></td>
		<td width=\"10%\" class=\"eb_showforum_right\" style=\"border-left-width: 0;\"><div align=\"center\">Members:</div></td>
	  </tr>";
	  
	 /////////////////
	 // START SQL
	 /////////////////
	 include_once("functions/functions.php");
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
			 echo "
				<tr>
				<td class=\"forum_footer\" height=\"20\">&nbsp;<a href=\"index.php?act=group&group=$id\">$name_new</a></td>
				<td class=\"forum_footer\" style=\"border-left-width: 0;\"><div align=\"center\"><a href=\"index.php?act=members&memberid=$u_id\">$leader</a></div></td>
				<td class=\"forum_footer\" style=\"border-left-width: 0;\"><div align=\"center\">$count_member</div></td>
			  </tr>"; 		}
		echo "</table>";
	}
	//////////////////////////
	// Function: create()
	// Description: Create member groups
	// Access Required: Admin (Normal)
	/////////////////////////
	function create()
	{
	$user_lvl = $_SESSION['user_level'];
		if ( $user_lvl == "3" ) {
			$SQL = "INSERT INTO `eb_group` ( `g_id` , `name` , `clr` , `inf` , `status` )
					VALUES (
					NULL , '$name', '$clr', '$inf', '$sts'
					);`";
			return "Group created";
			}
		else { return "Your user level is not high enouth to create groups."; }
	}
	///////////////////////////////////
	// Function: join()
	// Description: Join group
	// Required: Group must be open
	///////////////////////////////////
	function join_grp()
	{
		if ( isset($_GET['join'] ) || $_GET['join'] == "true" ) {
					session_checker();
			if ( isset($_POST['Submit_N']) ) {
			$name = $this->leader("1");
			$group->reason = $_POST['textfield'];
			$myname = $_SESSION['user_name'];
			$db = new db;
			$group->name = $X;
			$this->jr = $_GET['group'];
			$j = $db->connect();
			$j = $db->query("SELECT * FROM eb_group WHERE g_id = '{$this->jr}'");
			$gp = mysql_fetch_array( $j );
			$this->nr = $_SESSION['userid'];
			$get_token = $this->get_validate_token("{$this->nr}");
			$this->generate_new = $this->generate();
			$db->query("INSERT INTO `eb_pm` ( `id` , `message` , `title` , `from` , `to` , `postdate` , `UserID` )
						VALUES (
						NULL , 'Hello {$name}, <br>I would like to join your group {$gp[name]}.<br> Reason: {$group->reason} <br> If you want to validate me or not go to <a href=\"index.php?act=group&validate=true&token={$get_token}\">index.php?act=group&validate=true&token={$get_token}</a><br><br> $myname', 'Join Group: {$gp[name]}', '$myname', '$name', NOW( ) , '1'
						);");
			$db->query("INSERT INTO `eb_group_val` ( `u_id` , `val_id` , `g_id` )
			VALUES (
			'{$this->nr}' , '{$this->generate_new}' , '{$this->jr}' );");
				echo "<table width=100% border=\"0\" cellspacing=\"0\">
<tr><td width=\"100%\" class=\"eb_menu1\"><div align=\"center\">Personal Message</div></td></tr><tr><td width=\"100%\" class=\"forum_footer\"><div align=\"center\">Personal message has been sendt.</div></td></tr></table><br>&nbsp;";
				include("include/footer.php");
				exit();
			}
			else {
				$this->form = "<form action=\"#\" method=\"post\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\">
				  <tr>
					<td colspan=\"2\" class=\"eb_menu1\"><div align=\"center\">Join Group </div></td>
				  </tr>
				  <tr>
					<td width=\"26%\" valign=\"top\" class=\"forum_footer\"><div align=\"right\">Reason why you want too join this group: </div></td>
					<td width=\"74%\" class=\"forum_footer2\"><textarea name=\"textfield\" rows=\"10\" class=\"eb_header\" style=\"background-image: url(noimage); width: 100%;\"></textarea></td>
				  </tr>
				  <tr>
					<td colspan=\"2\" class=\"forum_footer\"><div align=\"center\">
					  <input name=\"Submit_N\" type=\"submit\" class=\"eb_header\" value=\"Send Personal Message\">
					</div></td>
				  </tr>
				</table></form>";
				echo "{$this->form}";
				include("include/footer.php");
				exit();
			}
		}
	}
	///////////////////////////////////
	// Function: drop($cmd)
	// Description: Drop Group / Drop Member
	// User Level needed: Group Admin / Forum Admin (Normal)
	////////////////////////////////////
	function drop($cmd)
	{
		# GROUP ADM / B ADMIN #
	}
	////////////////////////////////////
	// Function: info($id)
	// Description: Showing group information and members
	////////////////////////////////////
	function info($id)
	{
	
	$db = new db;
	$db->connect();
	$connect = $db->query("SELECT * FROM eb_group WHERE `g_id` = '$id'");
	$group->info = mysql_fetch_array( $connect );
	session_register("eb_grp");
	$_SESSION['eb_grp'] == $group->info[name];
	$this->grpn = $this->colored($group->info['clr'], $group->info['name']);
		echo "<form name=\"grp\" method=\"post\" action=\"index.php?act=group&group={$id}&join=true\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\">
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
";
  	 $this->button($group->info[status]);
	 echo "</table>
<br>
<table width=\"100%\"  border=\"0\" cellspacing=\"0\">
  <tr>
    <td colspan=\"3\" class=\"eb_forum\"><div align=\"center\"><b>Group members:</b> </div></td>
  </tr>";
  $group->query = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '$id' AND admin = 'admin'");
  $group->id = mysql_fetch_array( $group->query );
  $group->leader = $this->leader($group->id[userid]);
  
  $INFO->query = $db->query("SELECT * FROM eb_post WHERE UserID = '{$group->id[userid]}'");
  $INFO->get = mysql_num_rows( $INFO->query );
echo "
  <tr>
    <td colspan=\"3\" class=\"eb_header\" style=\"border-top-width: 0; height: 20px;\">&nbsp;Group leader: </td>
  </tr>
  <tr>
    <td width=\"72%\" class=\"eb_showforum_right\" style=\"border-top-width: 0;\">&nbsp;Username:</td>
    <td width=\"15%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Posts:</div></td>
    <td width=\"13%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Private message: </div></td>
  </tr>
  <tr>
    <td class=\"forum_footer\">&nbsp;<a href=\"index.php?act=members&memberid={$group->id[userid]}\">{$group->leader}</a></td>
    <td class=\"forum_footer2\"><div align=\"center\">{$INFO->get}</div></td>
    <td class=\"forum_footer2\"><div align=\"center\"><a href=\"index.php?act=usercp&pm=new&sendto={$group->id[userid]}\"><img src=\"Themes/Default/Images/pm.gif\" width=\"39\" height=\"14\" border=\"0\"></a></div></td>
  </tr>
  <tr>
    <td colspan=\"3\" class=\"eb_header\" style=\"border-top-width: 0; height: 20px;\">&nbsp;Group members: </td>
  </tr>
  ";
  $members->query = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '$id' AND admin = 'normal';");
 	  $forum = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '$id' AND admin = 'normal';");
	  $forum = mysql_num_rows( $forum );
	  	  if ($forum == "0") { echo "  <tr>
    <td width=\"72%\" class=\"eb_showforum_right\" style=\"border-top-width: 0;\">&nbsp;Username:</td>
    <td width=\"15%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Posts:</div></td>
    <td width=\"13%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Private message: </div></td>
  </tr><tr><td class=\"forum_footer\" colspan=\"3\"><div align=\"center\"><i>There are no members of this group.</i></div></td></tr>"; }
  while ( $get = mysql_fetch_object( $members->query ) ) {
	  $userid = $get->userid;
	  $name = $this->leader($userid);
	  $members->query2 = $db->query("SELECT * FROM eb_post WHERE UserID = '{$userid}'");
	  $members->get = mysql_num_rows( $members->query2 );
	  echo "  <tr>
    <td width=\"72%\" class=\"eb_showforum_right\" style=\"border-top-width: 0;\">&nbsp;Username:</td>
    <td width=\"15%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Posts:</div></td>
    <td width=\"13%\" class=\"eb_showforum_right\" style=\"border-top-width: 0; border-left-width: 0;\"><div align=\"center\">Private message: </div></td>
  </tr>
		  <tr>
			<td class=\"forum_footer\" style=\"border-top-width: 0;\">&nbsp;<a href=\"index.php?act=members&memberid={$userid}\">$name</a></td>
			<td class=\"forum_footer2\" style=\"border-top-width: 0;\"><div align=\"center\">{$members->get}</div></td>
			<td class=\"forum_footer2\" style=\"border-top-width: 0;\"><div align=\"center\"><a href=\"index.php?act=usercp&pm=new&sendto={$userid}\"><img src=\"Themes/Default/Images/pm.gif\" width=\"39\" height=\"14\" border=\"0\"></a></div></td>
		  </tr>";
 	 }
 	 echo "</table></form>";
	 
	}
	////////////////////////////////////
	// Function: colored($color,$text)
	// Description: Changing text to $color
	////////////////////////////////////
	function colored($color,$text)
	{
		if ( $color == "" ) { return "$text"; }
		elseif ( $color !== "") { return "<font color=\"$color\">$text</font>"; }
	}
	/////////////////////////////////////
	// Function: leader($leader_id)
	// Description: Getting ID of $leader_id
	/////////////////////////////////////
	function leader($leader_id) {
	$db = new db;
	$get_leader = $db->query("SELECT * FROM eb_members WHERE userid = '$leader_id'");
	$l_get = mysql_fetch_array( $get_leader );
	
	$leader = $l_get['username'];
	return $leader;
	}
	///////////////////////////////////
	// Function: count_members($group)
	// Description: Counting members of $group
	///////////////////////////////////
	function count_members($group,$id) {
	$db = new db;
	$db->connect();
	$count = $db->query("SELECT * FROM eb_group_mem WHERE grp_id = '{$id}'");
	$count = mysql_num_rows( $count );
	return $count;
	}
	///////////////////////////////
	// Function: button()
	// Desription: Create "Join" Button
	///////////////////////////////
	function button($status)
	{
				 if ( $status == "open" ) { echo "<tr><td width=\"100%\" class=\"forum_footer\" align=\"center\" colspan=\"2\" style=\"border-top-width: 0;\"><input name=\"Submit\" type=\"submit\" class=\"eb_header\" value=\"Join\"></td></tr>"; };
	}
	//////////////////////////////
	// Function: get_validate_token($u_id)
	// Description: Finds the validate token for userID
	//////////////////////////////
	function get_validate_token($u_id)
	{
		$db = new db;
		$token_q = $db->query("SELECT * FROM eb_group_val WHERE u_id = '{$u_id}'");
		$check = mysql_fetch_array( $token_q );
		return $check['val_id'];
	}
	//////////////////////////
	// Function: generate()
	// Generate a 7 character text and crypts it into MD5
	///////////////////////////
	function generate($len = "7") {
		 $salt = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvXxYyZz0123456789";
		 srand((double)microtime()*1000000);
		 $i = 0;
		 while ($i <= $len) {
			  $num = rand() % strlen($salt);
			  $tmp = substr($salt, $num, 1);
			  $pass = $pass . $tmp;
			  $i++;
		 }
		 return md5($pass);
	}
	function validate ()
	{
		session_checker();
		if ( $_SESSION['user_level'] > "2" ) {
					if ( $_GET['accept'] == "false" ) {
						$db = new db;
						$connect = $db->connect();
						$check = $db->query("SELECT * FROM eb_group_val WHERE val_id = '{$token}'");
						$a = mysql_fetch_array( $check );
						$name = $this->leader("{$a[u_id]}");
						$token = $_GET['token'];
						$myname = $_SESSION['user_name'];
						$db->query("INSERT INTO `eb_pm` ( `id` , `message` , `title` , `from` , `to` , `postdate` , `UserID` )
							VALUES (
							NULL , '{$name}, <br>This is a automatic sendt Personal Message to notify you that your application for the group has been denied. <br><br> {$myname}', 'Group Application: Denied', '{$myname}', '{$name}', NOW( ) , '$u_id'
							);");
						$db->query("DELETE FROM `eb_group_val` WHERE `val_id` = '{$token}' LIMIT 1;");
						echo "<div align=\"center\">Deleted.</div>";
						#exit();
			}
					if ( $_GET['accept'] == "true" ) {
						$db = new db;
						$connect = $db->connect();
						$check = $db->query("SELECT * FROM eb_group_val WHERE val_id = '{$token}'");
						$a = mysql_fetch_array( $check );
						$name = $this->leader("{$a[u_id]}");
						$token = $_GET['token'];
						$myname = $_SESSION['user_name'];
						$db->query("INSERT INTO `eb_pm` ( `id` , `message` , `title` , `from` , `to` , `postdate` , `UserID` )
							VALUES (
							NULL , '{$name}, <br>This is a automatic sendt Personal Message to notify you that your application for the group has been accepted. <br><br> {$myname}', 'Group Application: Accepted', '{$myname}', '{$name}', NOW( ) , '$u_id'
							);");
							$db->query("INSERT INTO `eb_group_mem` ( `id` , `grp_id` , `userid` , `admin` )
										VALUES (
										NULL , '{$a[g_id]}', '{$a[u_id]}', 'normal'
										);");
						$db->query("DELETE FROM `eb_group_val` WHERE `val_id` = '{$token}' LIMIT 1;");
						echo "<div align=\"center\">Message send and user added too group.<\div>";
						#exit();
			}
			$token = $_GET['token'];
			$db = new db;
			$validate = $db->connect();
			$check = $db->query("SELECT * FROM eb_group_val WHERE val_id = '{$token}'");
			$a = mysql_fetch_array( $check );
			$name = $this->leader("{$a[u_id]}");
			$id = $a['u_id'];
			echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\"><tr><td class=\"eb_menu1\" width=\"100%\">Validate user.</td></tr><tr><td class=\"forum_footer\" width=\"100%\"><div align=\"center\"><br>Do you want to validate user {$name} (Token: {$token}). <br><br> <span class=\"eb_header\" style=\"padding-left: 10px;padding-right: 10px;\"><a href=\"index.php?act=group&validate=true&token={$token}&accept=true\">Yes</a></span> <span class=\"eb_header\" style=\"padding-left: 10px;padding-right: 10px;\"><a href=\"index.php?act=group&validate=true&token={$token}&accept=false\">No</a></span></div><br></td></tr></table><br>";
			include_once("include/footer.php");
		}
	}
}
?>