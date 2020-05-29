<?php
session_start();
include("qdblog.php");
if(user_allow("login.php", "mod", "admin")) {
	global $conn;
	connect_db();
	mysql_query("DELETE FROM $prefix"."comments WHERE id = '" . (int)$_GET['id'] . "';", $conn);
	header("Location: index.php");
}
?>
