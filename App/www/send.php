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
if (isset($_POST['go'])) {
	if (empty($_POST['destinataire']) || empty($_POST['titre']) || empty($_POST['message'])) {
		$message = 'One field is empty.';
	}
	else {
		$sql = 'INSERT INTO pp_messages VALUES("", "'.$_COOKIE['id'].'", "'.$_POST['destinataire'].'", "'.date("Y-m-d H:i:s").'", "'.mysql_escape_string($_POST['titre']).'", "'.mysql_escape_string($_POST['message']).'")';
		mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());

		$message = 'Your message is sent to '.$_POST['destinataire'].'';
		header('Location: message.php');
	}
}

$sql = 'SELECT pp_user.user as user, pp_user.id as id_dest FROM pp_user WHERE id <> "'.$_COOKIE['id'].'" ORDER BY user ASC';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
$nb = mysql_num_rows ($req);

if ($nb == 0) {
	$message = 'You are alone';
}
else {
	while ($data = mysql_fetch_array($req)) {
		   $select[] = $data;

	}
$smarty->assign('select',$select);
}
}


$smarty->assign('message', $message);			
$smarty->display('send.tpl');
	


?>