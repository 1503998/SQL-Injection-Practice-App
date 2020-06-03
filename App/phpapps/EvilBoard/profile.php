<? 
#######################################
## EVILBOARD MEMBERSLIST START [EBM] ##
#######################################

include("include/header.php");
	  $setting = new setting;
	  $mem_per_page = $setting->num_members();
echo '
<br>
<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td colspan="4" class="eb_forum"><strong>&nbsp;Member List</strong></td>
  </tr>
  <tr>
    <td width="57%" class="eb_showforum_right" style="border-right-width: 1; border-top-width: 1;"> &nbsp;Name:</td>
    <td width="10%" class="eb_showforum_right" style="border-left-width: 0; border-top-width: 1;"><div align="center">Member Group: </div></td>
    <td width="10%" class="eb_showforum_right" style="border-left-width: 0; border-top-width: 1;"><div align="center">Registering Date:</div></td>
    <td width="10%" class="eb_showforum_right" style="border-left-width: 0; border-top-width: 1;"> <div align="center">Posts: </div></td>
  </tr>';
  
  $db = new db;
  $db->connect;
  
  $mlist = new memberlist;

  $mlist->create();
echo "</table><div class=\"eb_txt\">";
	if($_GET['do'] == "search") {
		if($_POST['textfield']) {
			$search = $_POST['textfield'];
			$search = str_replace(' ','%',$search);
			$total_items = $db->query("SELECT * FROM `eb_members` WHERE `username` LIKE '%{$search}%'");
		}
		else {
			$total_items = $db->query("SELECT * FROM `eb_members`;");
		}
	}
	else {
		$total_items = $db->query("SELECT * FROM `eb_members`;");
	}
	$total_items = mysql_num_rows($total_items);
$number_pages = ceil($total_items/$mem_per_page);
	echo "<br style=\"font-size: 3px;\" />Page: ( ";
	for($i=1; $i<=$number_pages; $i++){
	   if($page !== $i){
	      echo "<a href='index.php?act=memberslist&p=".$i."'>".$i."</a> ";
	   } else {
	      echo "<b>$i</b> ";
	   }
	}
	echo ")<br style=\"font-size: 2px;\" /><br style=\"font-size: 3px;\" /> </div>
 <script language=\"javascript\" type=\"text/javascript\">
function query(ext) {
	location.href='index.php?act=memberslist&query=' + ext + '';
}
</script>
 <table width=\"100%\" border=\"0\" cellspacing=\"0\" style=\"border-top: 1px solid #a6a6a6; height:25px;\">
    <!--<tr>
      <td class=\"forum_footer\" style=\"border-right-width: 0;\"><div align=\"left\">&nbsp;<select name=\"search\" class=\"forum_footer\" style=\"border-top: 1px solid #a6a6a6;\"><option onClick=\"javascript:query('username');\">Username</option>
            <option onClick=\"javascript:query('group');\">User Group (Default)</option>
            <option onClick=\"javascript:query('reg_date');\">Registering Date</option>
            <option onClick=\"javascript:query('post');\">Post</option>
            <option onClick=\"javascript:query('rank');\">Rank</option>
            <option selected=\"selected\">Order By:</option>
      </select></div></td>-->
      <td class=\"forum_footer\" style=\"\"><div align=\"right\"><form style=\"font-size: 0px;\" id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?act=memberslist&do=search\"><br style=\"font-size: 2px;\" /><span class=\"eb_txt\">Search for User:&nbsp;</span>
        <input name=\"textfield\" type=\"text\" class=\"forum_footer\" style=\"border-top: 1px solid #a6a6a6;border-top-width: 1;\" /><span class=\"eb_txt\">&nbsp;</span>
        <input name=\"Submit\" type=\"submit\" class=\"forum_footer\" value=\"Submit\" style=\"border-top: 1px solid #a6a6a6;border-top-width: 1;\" /><span class=\"eb_txt\">&nbsp;</span></form></div>
      </form></td>
    </tr>
  </table>
<br style=\"font-size: 10px;\" />";
include("include/footer.php");

/////////////////////////////////////////////////////////
// Class: memberlist                                   //
// Description: Create and fill in info in memberlist. //
/////////////////////////////////////////////////////////

class memberlist
{
	//////////////////////////////////////////
	// Function: Create();                  //
	// Description: Create memberlist table //
	//////////////////////////////////////////
	function create()
	{
	  $setting = new setting;
	  $mem_per_page = $setting->num_members();
	  if($_GET['do'] == "search") {
		if($_POST['textfield']) {
			$search = $_POST['textfield'];
			$search = str_replace(' ','%',$search);
			$query = "SELECT * FROM eb_members WHERE `username` LIKE '%{$search}%' ORDER BY username LIMIT ";
		}
		else {
			$query = "SELECT * FROM eb_members ORDER BY username LIMIT ";
		}
	}
	else {
		$query = "SELECT * FROM eb_members ORDER BY username LIMIT ";
	}
	  $page = $_GET['p'] ? (int)$_GET['p'] : 1; //If no page specified, use 1
	  $offset = ($page-1)*$mem_per_page;
	  $query .= "$offset, $mem_per_page";
	  $connect = mysql_query($query);
	  while( $profile = mysql_fetch_object( $connect ) ) {
	  $name = $profile->username;
	  $sign_date = $profile->signup_date;
	  list($s1,$s2,$s3,$s_a) = split('[/.: /]', $sign_date);
	  $sign_date = $s1;
	  $posts = $this->num_posts($name);
	  $id = $profile->userid;
	  $group = mysql_query("SELECT * FROM eb_group_mem WHERE `userid` = '{$id}'");
	  $group = mysql_fetch_array($group);
	  $group = $group['grp_id'];
	  $grp = mysql_query("SELECT * FROM eb_group WHERE `g_id` = '{$group}'");
	  $grp = mysql_fetch_array($grp);
	  $group = "{$grp[name]}";
	  if ( $grp['name'] == "" ) { $group = "<i>None</i>"; }
	  $name = "<font color=\"$grp[clr]\">{$name}</font>";
	  echo "
	  <tr>
		<td class=\"forum_footer\" height=\"30\">&nbsp;<a href=\"index.php?act=members&memberid=$id\">$name</a></td>
		<td class=\"forum_footer2\" height=\"30\"><div align=\"center\">$group</div></td>
		<td class=\"forum_footer2\" height=\"30\"><div align=\"center\">&nbsp;$sign_date</div></td>
		<td class=\"forum_footer2\" height=\"30\"><div align=\"center\">&nbsp;$posts</div></td>
	  </tr>
	  ";
	  }
	}
	
	//////////////////////////////////////////
	// Function: num_posts($post_user);     //
	// Description: Count members posts.	//
	//////////////////////////////////////////
	function num_posts($post_user)
	{
	$num_posts = mysql_query("SELECT * FROM eb_post WHERE `username` = '$post_user';");
	$num_posts = mysql_num_rows( $num_posts );
	return $num_posts;
	}
}
## EOEBM ##
?>