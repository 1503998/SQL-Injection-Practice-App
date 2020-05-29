<?php
if($_POST['username'] and $_POST['wordpass']) {
	session_start();
	include("qdblog.php");
	connect_db();
	global $conn;
	$sql = "SELECT permissions, username FROM $prefix"."auth WHERE username = '" . $_POST['username'] . "' AND password = MD5('".$_POST['wordpass']."');";
	$query = mysql_query($sql, $conn);
	if(mysql_num_rows($query) == 1 ) {
		while($row = mysql_fetch_array($query)) {
			$_SESSION['logged_in'] = 'yes';
			$_SESSION['username'] = $row['username'];
			$_SESSION['perm'] = $row['permissions'];
		}
		header("Location: index.php");
	} else { 
		header("Location: login.php");
	}
} else { 
	header("Location: login.php");
}
?>
