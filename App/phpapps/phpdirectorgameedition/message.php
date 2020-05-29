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
$sql = 'SELECT titre, date, pp_user.user as sender, pp_messages.id as id FROM pp_messages, pp_user WHERE id_destinataire="'.$_COOKIE['id'].'" AND id_expediteur=pp_user.id ORDER BY date DESC';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
$nb = mysql_num_rows($req);

if ($nb == 0) {
	$message ='No message';
}
else {
	while ($data = mysql_fetch_array($req)) {
    $read[] = $data;	
	}
$smarty->assign('read', $read);
	}
}


$smarty->assign('message', $message);			
$smarty->display('message.tpl');
	


?>