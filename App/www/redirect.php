<?
/****************************/
/** EvilBoard::Redirection **/
/****************************/

if ( isset($_GET['posttopic']) ) {
	include("config.php");
	mysql_connect($eb_server,$eb_user,$eb_password);
	mysql_select_db($eb_db);
	$topicquery = "SELECT * FROM `eb_topic` ORDER BY `TopicID` DESC LIMIT 0,1";
	$topic = mysql_query( $topicquery );
	while ( $topicid = mysql_fetch_object ( $topic ) ) {
		$topicidx = $topicid -> TopicID;
		header("Refresh: 1; index.php?act=showtopic&t=" . $topicidx . "");
		include("include/header.php");
		echo '<br><table width="355" border="0" align="center" cellspacing="0">
 		 <tr>
 		   <td class="eb_menu1"><div align="center">Redirection</div></td>
		  </tr>
		  <tr>
	    <td class="forum_footer">Redirecting to your topic in 1 secound.</td>
 		 </tr>
		</table>
		';
	}
}
?>
