<?
/************************************************/
/** PHP Personal Message System - By El Diablo **/
/**	 			 	 PM::DELETE	 		 	   **/
/************************************************/
if ( $_GET['id'] ) {
$dd_id = $_GET['id'];
$d_id = "DELETE FROM `eb_pm` WHERE `id` = $dd_id LIMIT 1;";
$query = mysql_query ( $d_id );
if ( $query ) {
echo '<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_header"><div align="center">DELETED</div></td>
  </tr>
  <tr>
    <td class="forum_footer"><div align="center">PM [$dd_id] has been deleted. </div></td>
  </tr>
</table>';
}
else {
echo '<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_header"><div align="center">DELETED</div></td>
  </tr>
  <tr>
    <td class="forum_footer"><div align="center">PM [$dd_id] has <b>NOT</b> been deleted. ERROR: [' . mysql_error() . '] Please report this error to system admin.</div></td>
  </tr>
</table><br>';
}
}
?>
<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td colspan="4" class="eb_forum" style="border-bottom-width: 0;">&nbsp;<strong>Delete personal messages</strong></td>
  </tr>
  <tr>
    <td width="3%" class="eb_menu1">&nbsp;</td>
    <td width="67%" class="eb_menu3">Title:</td>
    <td width="13%" class="eb_menu3">Send by: </td>
    <td width="17%" class="eb_menu3">Date:</td>
  </tr>
  <tr>
<?
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
    echo '<td class="forum_footer"><a href="?act=usercp&pm=delete&id=' . $id . '" class="eb_delete"><img src="Themes/Default/Images/del.gif" border="0"></a></td>';
	echo '
    <td class="forum_footer2"><a href="?act=usercp&pm=read&id=' . $id . '">' . $title . '</a></td>
    <td class="forum_footer2">' . $from . '</td>
    <td class="forum_footer2">' . $postdate . '</td>  </tr>';
	}

	?>
<?
?>
</table><br>