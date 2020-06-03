<?php

if($_SESSION['sr'] == 2)
{
	if($_SESSION['perm'] == 1 || $_SESSION['perm'] == 2)
	{
		mysql_query("DELETE FROM " . $prefix . "threads WHERE thread_id = '{$_GET['thread_id']}'");
		mysql_query("DELETE FROM " . $prefix . "posts WHERE thread_id = '{$_GET['thread_id']}'");

		mysql_close();

		echo '<span class="errortext">Post deleted successfully!</span>';
	}
}else
{
    accessdenied();
}

?>