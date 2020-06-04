<?php

session_start();

if($_SESSION['sr'] == 2)
{

include("../config.php");

dbconnect();

//Get post information from database
$postquery = mysql_query("SELECT * FROM " . $prefix . "posts WHERE msg_id = '{$_POST['msg_id']}'");
while($post = mysql_fetch_assoc($postquery))
{
	if($post['locked'] == 0 && $_SESSION['memberid'] == $post['memberid'] || $_SESSION['perm'] == 1 || $_SESSION['perm'] == 2)
	{
		//User is authorized, delete post
		mysql_query("DELETE FROM " . $prefix . "posts WHERE msg_id = '{$_POST['msg_id']}'");
	}
}

mysql_close();

echo '<span class="errortext">Post deleted successfully!</span>';

}else
{
    accessdenied();
}

?>