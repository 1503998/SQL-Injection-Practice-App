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

mysql_query("DELETE FROM pp_partner WHERE id ='$_GET[del]'");
}
else
if(isset($_GET['edit']))
{
mysql_query("UPDATE pp_partner SET status= 1 WHERE id ='$_GET[edit]'");
}

$result = mysql_query("SELECT * FROM pp_partner");

while ($row = mysql_fetch_assoc($result)){
	$partner[] = $row;
}

$smarty->assign('partner', $partner);
$smarty->display('partner.tpl');

	}else{
header("location: login.php");
}
mysql_close($mysql_link);
?>
