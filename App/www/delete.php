<?
session_start();
include("config.php");
mysql_connect($eb_server,$eb_user,$eb_password);
mysql_select_db($eb_db);
	$postid = $_GET['postid'];
	$topicid = $_GET['topicid'];
	
	$usersql = "SELECT Username FROM eb_post WHERE postid = '$postid'";
	$username = mysql_query ( $usersql );
	$getname = mysql_fetch_array ( $username );
	$getusername = $username['Username'];
	echo "$getusername";
	if ($_GET['delete'] == "true" && $getusername = $_SESSION['user_name'] ) {
		$del = "DELETE FROM `eb_post` WHERE `PostID` = '$postid' LIMIT 1;";
		$delete = mysql_query($del);
		if ( $delete ) {
			header("Refresh: 1; index.php?act=showtopic&t=" . $topicid . "");
				include("include/header.php");
		echo '<br>
				<table width="415" border="0" align="center" cellspacing="0">
			  <tr>
 			   <td width="381" class="eb_menu1"><div align="center">delete post #' . $postid . '</div></td>
 			 </tr>
			  <tr>
			    <td class="forum_footer"><div align="center">You have deleted topic #'. $postid .'</div></td>
			  </tr>
			</table><br>
					';
					include("include/footer.php");
					exit();
			}
	}
	include_once("include/header.php");
			echo '<br>
					<table width="415" border="0" align="center" cellspacing="0">
				  <tr>
 				   <td width="381" class="eb_menu1"><div align="center">Are you sure you want to delete post #' . $postid . '</div></td>
 				 </tr>
				  <tr>
				    <td class="forum_footer"><div align="center"><a href="?act=delete&postid=' . $postid . '&delete=true&topicid=' . $topicid . '">Yes</a> | <a href="?showtopic=' . $topicid . '">No </a></div></td>
				  </tr>
				</table><br>
					';
					include_once("include/footer.php");
					?>