<?
/************************************************/
/** PHP Personal Message System - By El Diablo **/
/**	 			 	PM::READ		 	 	   **/
/************************************************/
session_start();
session_checker();

if ( !$id ) { echo "Error, missing message ID"; exit(); }
else { 
	$sql = "SELECT * FROM eb_pm where `id` = '$id'";
	$read = mysql_query( $sql );
	while( $getinfo = mysql_fetch_object( $read ) ) {
	$message = $getinfo -> message;
	$title = $getinfo -> title;
	$id = $getinfo -> id;
	$to = $getinfo -> to;
	$from = $getinfo -> from;
	$postdate = $getinfo -> postdate;
	$UserID = $getinfo -> UserID;
	}
	$fname = sql_get_settings("get_forum_name");
			echo '<table width="100%"  border="0" cellspacing="0">
<tr>
    				<td height="22" colspan="2" class="eb_forum"><B>&nbsp;' . $title . '</B></td>
  					</tr>
		<td width="21%" class="eb_showforum_left">&nbsp;<a href="index.php?action=members&memberid='. $UserID .'">' . $from .'</a></td>
    <td width="79%" class="eb_showforum_right">'. $postdate .'</td>
  </tr>
  <tr>
    <td class="eb_showpost_footer_l">&nbsp;</td>
    <td class="eb_showpost_footer">'. $message .'</td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="eb_topic_lower"><div align="right"><table width="1" border="0" cellspacing="0">
  <tr>
    <td width="1"><div align="right"><a href="http://localhost/gladzsite/forum/index.php?act=usercp&pm=new&sendto=' . $UserID . '"><img src="Themes/Default/Images/replay-low.gif" width="38" height="14" border="0"></a></div></td>
</table></div></td>
  </tr>
  <tr>
    <td height="5" colspan="2" class="eb_showtopic_extra">&nbsp;</td>
  </tr></table>';
}
?>