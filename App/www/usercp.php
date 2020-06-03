<? 
session_start();
include_once("functions/functions.php");
session_checker();
if ( $_GET['PM'] == "read" ) {
	$id = $_GET['id'];
	setcookie("EB_RD_" . $id . "", "read",time()+30758400);
}
include("include/header.php");
echo "<br>";
sql_show_all("4");
$cp = new usercp;
$cp->no_site = $cp->no_site();
$avatar = $cp->show_avatar();
?>
<br>
    <script type="text/javascript" src="usercp/menu.js"></script>
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="17%" valign="top">
    <div class="sdmenu">
      <span class="title" id="top"><img src="usercp/expanded.gif" class="arrow" alt="-" />Personal Messenger</span>
      <div class="submenu">
        <a href="index.php?act=usercp&PM=new">Compose new message </a><a href="index.php?act=usercp&PM=inbox">Go to Inbox </a><a href="index.php?act=usercp&PM=delete">Delete PMs</a></div>
      <span class="title"><img src="usercp/expanded.gif" class="arrow" alt="-" />Personal Profile </span>
      <div class="submenu">
        <a href="index.php?act=usercp&profile=edit">Edit Profile Information</a>
        <a href="index.php?act=usercp&profile=editsignature">Edit Signature </a>
        <a href="index.php?act=usercp&profile=pphoto">Change Personal Photo</a>
		</div>
      <span class="title"><img src="usercp/expanded.gif" class="arrow" alt="-" />General Settings </span>
      <div class="submenu">
        <a href="index.php?act=usercp&settings=email">Email Settings </a><a href="index.php?act=usercp&settings=board">Board Settings</a><a href="index.php?act=usercp&settings=password">Change Password </a>
      </div>
    </div></td>
    <td width="83%" valign="top"><div align="center">
      <? 
	  if ( $_GET['PM'] == "new" ) { include("PM/pm-create.php"); }
	  elseif ( $_GET['PM'] == "inbox" ) { include("PM/pm-inbox.php"); }
	  elseif ( $_GET['PM'] == "read" ) { include("PM/pm-read.php"); }
	  elseif ( $_GET['PM'] == "delete" ) { include("PM/pm-delete.php"); }
	  elseif ( $_GET['profile'] == "edit" ) { include("profile/p-edit.php"); }
	  elseif ( $_GET['profile'] == "pphoto" ) { include("profile/p-image.php"); }
	  elseif ( $_GET['profile'] == "editsignature" ) { include("profile/p-sig.php"); }
	  elseif ( $_GET['settings'] == "email" ) { include("profile/p-email.php"); }
	  elseif ( $_GET['settings'] == "board" ) { include("profile/p-board.php"); }
	  elseif ( $_GET['settings'] == "password" ) { include("profile/p-pass.php"); }
	  else { echo "$cp->no_site"; } 
	   ?>
    </div></td>
  </tr>
</table><br>
<?
//===================
## UserCP Class
//===================
class usercp
{
//====================
## Function: no_site()
//====================
	function no_site()
	{
	$avatar = $this->show_avatar();
	$last5 = $this->last5();
	return "<table width=\"100%\"  border=\"0\" cellspacing=\"0\">
  <tr>
    <td class=\"eb_forum\">&nbsp;<b>User Control Panel</b></td>
  </tr>
  <tr>
    <td class=\"eb_header\" style=\"border-top-width: 0;\"><div align=\"center\">User Control Panel </div></td>
  </tr>
  <tr>
    <td class=\"forum_footer\" style=\"border-top-width: 0;\"><div align=\"center\">
      <p>Welcome to your UserCP {$_SESSION[user_name]}, From here you can change your profile information,<br>
        Signature, Password and so on, So select an option from the menu on the left side.</p>
      </div></td>
  </tr>
  <tr>
    <td class=\"eb_header\" style=\"border-top-width: 0;\"><div align=\"center\">Current Avitar: </div></td>
  </tr>
  <tr>
    <td class=\"forum_footer\"><div align=\"center\">{$avatar}<br>To change your avatar click on &#8220;Change Personal Photo&#8220;</div></td>
  </tr>
  <tr>
    <td class=\"eb_header\" style=\"border-top-width: 0;\"><div align=\"center\">5 Last Topics: </div>
      <div align=\"center\"></div></td>
  </tr>
  <tr>
    <td class=\"forum_footer\"><div align=\"center\">{$last5}</div></td>
  </tr>
</table>";
	}
	function show_avatar()
	{
		$p_id = $_SESSION['userid'];
		$p_sql = "SELECT logo FROM `eb_profile` WHERE `id` = '$p_id';";
		$p_connect = mysql_query( $p_sql );
		while( $p_get = mysql_fetch_object( $p_connect ) ) {
			$logo = $p_get -> logo;
		}
		if ( $logo !== "" ) { return "<img src='$logo' alt='$logo' width='80' height='80'>"; }
		elseif ( $logo == "" ) { return "<img src='Themes/Default/Images/noimage.gif'>"; }   
	}
	function last5()
	{
		$db = new db;
		$db->connect();
		$connect = $db->query("SELECT * FROM eb_topic WHERE `Username` = '{$_SESSION[user_name]}' ORDER BY `TopicID` DESC LIMIT 0,5;");
		$table = "<br><table width=\"300px\"  border=\"0\" cellspacing=\"0\" align=\"center\"><tr><td class=\"eb_menu1\">Last 5 topics posted by user:</td></tr>";
		while ( $last = mysql_fetch_object( $connect ) ) {
			$table .= "  <tr>
						<td class=\"eb_showpost_footer\" style=\"width: 100px\"><span class=\"eb_txt\"><a href=\"index.php?act=showtopic&t={$last->TopicID}\">{$last->title}</a></span></td>
						 </tr>";
  			}	
		$table .= "</table><br>";
		return $table;
	}
}
?>
<? include("include/footer.php"); ?>