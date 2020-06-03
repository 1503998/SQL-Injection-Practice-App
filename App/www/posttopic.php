<?php
/*
* Post (Topic and original post)
* Arthor: Arne-Christian Blystad
* Copyright 2006 under the LGPL
*/

// Start a session
session_start();
// Print login form if user is not logged in
if ( !$_SESSION['user_name'] ) {
	// Include header file
	include("include/header.php");
echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" class=\"eb_forbit\">
	  <tr>
 	   <td><table width=\"100%\" border=\"0\" cellspacing=\"0\">
 	     <tr>
  	      <td width=\"3%\"><img src=\"Themes/Default/Images/forbid.gif\" width=\"20\"></td>
 	       <td width=\"97%\" class=\"eb_txt\"> You must login to access this page. </td>
 	     </tr>
 	   </table></td>
	  </tr>
	</table>";
	include "login.php";
	exit();
}
// If user is logged in, keep running the script.
elseif($_SESSION['user_name']) {
	// If form has been submited
	if($_POST['Submit']) {
		// Set redirecting header
		#include_once("functions/functions.php");
		#$db = new db;
		sleep('1');
		$gettopic = $db->query("SELECT * FROM `eb_topic` ORDER BY `TopicID` DESC LIMIT 1;");
		$topic = mysql_fetch_array($gettopic);
		$topic = $topic['TopicID'];
		$ntopic = $topic + 1;
		header("Refresh: 5; index.php?act=showtopic&t=" . $ntopic . "");
		// Include header file
		include("include/header.php");
		// Create the WYSIWYG editor
		include("tinymce.php");
		// Connect to database
		$db = new db;
		$db->connect();
		// Get User ID from database
		$usrd = $_SESSION['user_name'];
		$eb = new eboard;
		$userid = $eb->uname_id($usrd);
		// Get information from the form
		$title = $_POST['title'];
		$desc = $_POST['desc'];
		$forumid = $_GET['forumid'];
		$message = $_POST['message'];
		// Create topic to database :)
		$username = $_SESSION['user_name'];	
			$ip = $_SERVER['REMOTE_ADDR'];
			$db->query("INSERT INTO `eb_topic` ( `UserID` , `title` , `TopicID` , `replays` , `desc` , `Username` , `ForumID`)
	VALUES ('$userid', '$title', NULL , '0', '$desc', '$username', '$forumid');"); 
		// Create post and insert it to database
		$gettopic = $db->query("SELECT * FROM `eb_topic` ORDER BY `TopicID` DESC LIMIT 0,1");
		$topic = mysql_fetch_array($gettopic);
		$topic = $topic['TopicID'];
		$ip_addrs = $_SERVER['REMOTE_ADDR'];
		$query = $db->query("INSERT INTO `eb_post` ( `message` , `postdate` , `UserID` , `Username` , `PostID` , `TopicID` , `ForumID` , `title` , `user_ip` )
		VALUES (
		'$message', NOW( ) , '$userid', '$username', '' , '$topic', '$forumid', '$title', '$ip_addrs');");
		include("functions/template.class.php");
		$tpl = new template;
		$x = "<br>";
		$x .= $tpl->smallbox("Information","Post has been posted, Redirecting you to the post in 5 seconds.");
		echo $x;
		exit();
	}
	elseif(!$_POST['Submit']) { 
		include("include/header.php");
		if($_GET['act'] = "posttopic") { $w = "Post Topic"; }
		elseif ($_GET['act'] = "post") { $w = "Post Replay"; }
		include("functions/smiley.class.php");
		$smiley = new smiley;
		?>
		<script language="javascript" type="text/javascript">
function insertEmotion(file_name, title) {
	var html = '<img src="Emoticons/1/' + file_name + '" />';

	tinyMCE.execCommand('mceInsertContent', false, html);
}
</script>
<?
	$smileyz = $smiley->click_smileys();
	$post->form = "<br><form name=\"sub\" method=\"post\" action=\"\">
  <table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"0\">
    <tr>
      <td width=\"100%\" class=\"eb_forum\">
        <div align=\"center\"><strong>{$w}</strong></div></td>
    </tr>
    <tr>
      <td class=\"eb_footer_orgin\">        <table width=\"100%\"  border=\"0\" cellspacing=\"0\" class=\"eb_txt\">
          <tr>
            <td width=\"21%\" rowspan=\"2\" valign=\"top\">{$smileyz}</td>
            <td>Title:<br>
        <input name=\"title\" type=\"text\" class=\"eb_header\" style=\"width: 100%;\">
        Topic Description:<br>
        <input name=\"desc\" type=\"text\" class=\"eb_header\" id=\"desc\"  style=\"width: 100%;\">
        <br>
        Message:<br></td>
          </tr>
          <tr>
            <td width=\"81%\"><textarea  id=\"message\" rows=\"10\" cols=\"200\" name=\"message\" style=\"hieght: 250px; width: 100%; color: #FFFFFF;\"></textarea></td>
          </tr>
          <tr>
            <td colspan=\"2\"><div align=\"center\">
              <input name=\"Submit\" type=\"submit\" class=\"eb_header\" value=\"Post Topic\">
            </div></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<br>";
echo $post->form;
	 }
}

?>