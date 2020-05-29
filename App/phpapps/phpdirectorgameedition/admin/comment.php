<?php
/*
+ ----------------------------------------------------------------------------+
|     PHPDirector.
|		$License: GPL General Public License
|		$Website: phpdirector.co.uk
|		$Author: Ben Swanson
|		$Contributors - Dennis Berko and Monte Ohrt (Monte Ohrt)
+----------------------------------------------------------------------------+
*/
ob_start(); 
session_start(); 
include("admin_header.php");
if (checkLoggedin()){
if(isset($_GET['del'])){

mysql_query("DELETE FROM pp_comment WHERE id ='$_GET[del]'");
}


$result = mysql_query("SELECT * FROM pp_comment");

while ($row = mysql_fetch_assoc($result)){
	$com[] = $row;
}

$smarty->assign('com', $com);
$smarty->display('comment.tpl');

	}else{
header("location: login.php");
}
mysql_close($mysql_link);
?>
