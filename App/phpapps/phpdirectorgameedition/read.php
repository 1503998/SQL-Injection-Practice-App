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
if (!isset($_GET['id_message']) || empty($_GET['id_message'])) {
	$message = 'No message selected';
}
else {
	$sql = 'SELECT titre, date, message, pp_user.user as expediteur, pp_user.id as expid FROM pp_messages, pp_user WHERE id_destinataire="'.$_COOKIE['id'].'" AND id_expediteur=pp_user.id AND pp_messages.id="'.$_GET['id_message'].'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$nb = mysql_num_rows($req);

	if ($nb == 0) {
		$message= 'No message selected';
	}
	else {
		$data = mysql_fetch_array($req);
		$smarty->assign('messid' , $_GET['id_message']);
		$smarty->assign('date' , $data['date']);
		$smarty->assign('title',stripslashes(htmlentities(trim($data['titre']))) );
		$smarty->assign('from',stripslashes(htmlentities(trim($data['expediteur']))));
		$smarty->assign('idfrom',stripslashes(htmlentities(trim($data['expid']))));
		$smarty->assign('mess', nl2br(stripslashes(htmlentities(trim($data['message'])))));

	}
}
}



$smarty->assign('message', $message);			
$smarty->display('read.tpl');
	


?>