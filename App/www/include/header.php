<?
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
	 echo "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<title>{$title}</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"Themes/Default/styles.css\">
<script language=\"javascript\" type=\"text/javascript\" src=\"eb_dropdown.js\"></script>";
include("tinymce.php");
	include("config.php");
	include_once("functions/functions.php");
	theme_load("Default");
	$forum_NAME = sql_get_settings("get_forum_name");
	$forum_homepage = sql_get_settings("get_homepage"); 
	$menu = $_SESSION['user_name'];
	$user = $_SESSION['user_name'];
	$lvl = $_SESSION['user_level'];
?>
</head>
<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_logo"><img src="Themes/Default/Images/logo.gif" height="100" /></td>
  </tr>
  <tr>
    <td class="eb_forum"><div align="right">
      <table width="100%" border="0" cellspacing="0">
        <tr>
          <td width="29%" class="eb_txt">&nbsp;<? echo "$forum_NAME"; ?> <b>::</b> <a href="<?=$forum_homepage?>">Go to HomePage</a> </td>
          <td width="71%" class="eb_txt"><div align="right"><b>|</b> <a href="index.php?">Home</a> | 
		  <?
		  if ( !$menu ) {  echo  '<a href="index.php?act=register">Register</a> | <a href="index.php?act=login">Login</a> |'; }
		  echo ' <a href="index.php?act=memberslist">Members</a> | <a href="index.php?act=group">Groups</a> |';
		  if ( $menu ) { echo '&nbsp;<a href="index.php?act=usercp&">UserCP </a>|'; }
		  ?></div></td>
        </tr>
      </table>
      </div></td>
  </tr>
</table><?
if ( $user ) { echo 	'<br><table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_loggedin">&nbsp;<img src="Themes/Default/Images/ok.gif">&nbsp;Welcome '. $user .' (';
	if ( $lvl == "3" ) { echo '<a href="admin/index.php" style="color: white; text-decoration: underline;">Admin CP</a>,&nbsp;'; } 
	echo '<a href="index.php?act=logout" style="color: white; text-decoration: underline;">Logout</a>)
	</td>
  </tr>
</table>';
 }
	elseif ( !$user) { /*Do Nothing */ } ?> 