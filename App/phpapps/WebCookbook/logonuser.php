<?php

include("language.php");
include("env_db.php");
include("env_sc.php");
initDB();

$username = $_POST['name'];
$password = $_POST['pass'];

$errmsg = 0;

if (file_exists('./forum/includes/functions.inc.php')) {
	include('./pwfunctions.inc.php');
	if ($username != "") {
		if ($password != "") {
			$query = "SELECT user_type, user_name, user_pw FROM mlf2_userdata WHERE user_name = '$username'";
			$result = mysql_query($query) or die(mysql_error());
			$stat = mysql_result($result, 0, 'user_type');
			$user = mysql_result($result, 0, 'user_name');
			$pass = mysql_result($result, 0, 'user_pw');
			if ($user == "") {
				die($sys_err_nouser);
			}
			if ($_POST['permlogged']) {
				$timestamp = time()+2*356*12*60*60*1000;
			}
			else {
				$timestamp = 0;
			}
			$wert = "1|$user";
			if (is_pw_correct($password, $pass)) {
				setcookie("logged", $wert, $timestamp); 
			}
		}
		else {
			$errmsg = 3;
		}
	}
	else {
		$errmsg = 2;
	}
}
else {
	$errmsg = 1;
}
header("Location: ./showerr.php?msg=$errmsg");

?>