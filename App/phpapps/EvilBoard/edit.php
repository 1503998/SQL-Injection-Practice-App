<? session_start();
if (  isset($_POST['gladz']) && !empty( $_POST['gladz'] ) ) {
$topicid = $_GET['topicid'];
	header("Refresh: 1; index.php?act=showtopic&t=" . $topicid . "");
echo '<div align="center" class="eb_txt">Redirecting to your post in 1 secound.</div>';
	}
	include("config.php");
	mysql_connect($eb_server,$eb_user,$eb_password);
	mysql_select_db($eb_db);
	$getpostid = $_GET['postid'];
	$getsql = "SELECT * From eb_post WHERE PostID = '$getpostid'";
	$connectsql = mysql_query( $getsql );
	while( $getname = mysql_fetch_object( $connectsql ) )
	{
	$username_edit = $getname -> Username;
	}
 ?>
<?php include("include/header.php"); ?><br>
<?
/*if ( !$_SESSION['user_name'] || $username_edit !== $_SESSION['user_name'] || $_SESSION['user_level'] == 2 || $_SESSION['user_level'] == 3 ) {
echo '<table width="100%" border="0" cellspacing="0" class="eb_forbid">
  <tr>
    <td><table width="100%" border="0" cellspacing="0">
      <tr>
        <td width="3%"><img src="Themes/Default/Images/forbid.gif" width="20" height="20"></td>
        <td width="97%" class="eb_txt"> You must login to access this page. </td>
      </tr>
    </table></td>
  </tr>
</table>';
			include "login.php";
}
else {*/
include("source/lib/tinymce.php");
if (  isset($_POST['gladz']) && !empty( $_POST['gladz'] ) ) {
	$message = $_POST['gladz'];
	$PostID = $_GET['postid'];
	dbconnect();
	$sql = "UPDATE `eb_post` SET `message` = '$message' WHERE `PostID` = '$PostID' LIMIT 1 ;";
	mysql_query("$sql");
}

if ( !isset($_POST['gladz']) ) {
	dbconnect();
	$PostID = $_GET['postid'];
	$query = "SELECT * FROM `eb_post` WHERE PostID = '$PostID';";
	$editpost = mysql_query( $query );
	while( $getinfo = mysql_fetch_object( $editpost ) )
	{
	$message = $getinfo -> message;
	include("functions/smiley.class.php");
$smiley = new smiley;			

?>
		<script language="javascript" type="text/javascript">
function insertEmotion(file_name, title) {
	var html = '<img src="Emoticons/1/' + file_name + '" />';

	tinyMCE.execCommand('mceInsertContent', false, html);
}
</script>
<form name="form1" method="post" action="<?=$PHP_SELF?>">
  <table width="100%" border="0" align="center" cellspacing="0">
    <tr>
      <td width="100%" class="eb_menu1"><div align="center" >Edit Post </div></td>
    </tr>
    <tr>
      <td class="eb_footer_orgin"><table width="100%"  border="0" cellspacing="0" class="eb_txt">
          <tr>
            <td width="21%" rowspan="2" valign="top"><? echo $smiley->click_smileys(); ?></td>
            <td>Message: </td>
          </tr>
          <tr>
            <td width="81%"><textarea name="gladz"  class="eb_header" id="gladz" style="height: 170px; width: 100%; color: #FFFFFF;"><?=$message?></textarea></td>
          </tr>
          <tr>
            <td colspan="2"><div align="center">
              <input name="Submit" type="submit" class="eb_header" value="Submit">
            </div></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form><?php } } showfooter();  #}	?>
<script language="javascript1.2">
  generate_wysiwyg('gladz');
</script>
