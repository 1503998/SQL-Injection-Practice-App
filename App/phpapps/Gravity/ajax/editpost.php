<?php

session_start();

if($_SESSION['sr'] == 2)
{

include("../config.php");

dbconnect();

//ADD NEW BOARD TO THE DATABASE
mysql_query("UPDATE " . $prefix . "posts SET message = '{$_POST['message']}' WHERE msg_id = '{$_POST['msg_id']}'") OR DIE ("Gravity Board X was unable to edit the post: " . mysql_error());

mysql_close();

echo '<span class="errortext">Post saved successfully!</span>';

}else
{
    accessdenied();
}

?>