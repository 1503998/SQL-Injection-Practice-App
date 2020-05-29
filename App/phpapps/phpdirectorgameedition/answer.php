<?php
/*
+ ----------------------------------------------------------------------------+
|     PHPDirector.
|		$License: GNU General Public License
|		$Website: phpdirector.co.uk
+----------------------------------------------------------------------------+
*/
require('header.php');
include 'db.php';
if(!isset($_COOKIE["id"]))
{
     header("Location: index.php");
}
else
{
$smarty->assign('to',$_POST['from']);
$smarty->assign('title',$_POST['title']);
$smarty->assign('id',$_POST['idfrom']);

if (isset($_POST['go'])) {
	if (empty($_POST['destinataire']) || empty($_POST['titre']) || empty($_POST['message'])) {
		$message = 'One field is empty.';
	}
	else {
		$sql = 'INSERT INTO pp_messages VALUES("", "'.$_COOKIE['id'].'", "'.$_POST['destinataire'].'", "'.date("Y-m-d H:i:s").'", "'.mysql_escape_string($_POST['titre']).'", "'.mysql_escape_string($_POST['message']).'")';
		mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
		header('Location: message.php');
	}
}
}


$smarty->assign('message', $message);			
$smarty->display('answer.tpl');
	


?>