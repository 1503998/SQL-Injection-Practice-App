<?php
//********** AUTHENTICATION ********************
session_start();
$host  = $_SERVER['HTTP_HOST'];
$code="hahahathisisencrypted".$_SERVER['REMOTE_ADDR'];
if(isset($_SESSION["flag"])&&$_SESSION["token"]==sha1(md5($code)))
{
}
else
{
	header("Location:login.php");  //HOME
	exit;
}

//**************END OF AUTHENTICATION************
?>