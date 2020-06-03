<?PHP
/************************************************/
/** PHP Personal Message System - By El Diablo **/
/**	 			 	  Inbox		 		 	   **/
/************************************************/

session_start();
session_checker();

?>
<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td colspan="4" class="eb_forum">&nbsp;<strong>Inbox</strong></td>
  </tr>
  <tr>
    <td width="3%" class="eb_forum_header" style="border-top-width:0;">&nbsp;</td>
    <td width="67%"  class="eb_forum_header" style="border-top-width:0; border-left-width: 0;">Title:</td>
    <td width="13%" class="eb_forum_header" style="border-top-width:0; border-left-width: 0;">Send by: </td>
    <td width="17%" class="eb_forum_header" style="border-top-width:0; border-left-width: 0;">Date:</td>
  </tr>
  <tr>
<?
?><?
	$uname = $_SESSION['user_name'];
	$sql = "SELECT * FROM eb_pm where `to` = '$uname' order by `postdate` DESC;";
	$get_inbox = mysql_query("$sql");
	while( $getinfo = mysql_fetch_object( $get_inbox ) ) {
	$message = $getinfo -> message;
	$title = $getinfo -> title;
	$id = $getinfo -> id;
	$to = $getinfo -> to;
	$from = $getinfo -> from;
	$postdate = $getinfo -> postdate;
	$fname = sql_get_settings("get_forum_name");
	echo '
 <link rel="stylesheet" type="text/css" href="Themes/Default/styles.css">';
 	if ( $_COOKIE["EB_RD_" . $id . ""] ) {
    echo '<td class="forum_footer"><img src="Themes/Default/Images/read.gif" width="18" height="12"></td>';
	}
	else {
    echo '<td class="forum_footer"><img src="Themes/Default/Images/unread.gif" width="18" height="12"></td>';
	}
	echo '
    <td class="forum_footer2"><a href="?act=usercp&pm=read&id=' . $id . '">' . $title . '</a></td>
    <td class="forum_footer2">' . $from . '</td>
    <td class="forum_footer2">' . $postdate . '</td>  </tr>';
	}

	?>
<?
?>
</table>
