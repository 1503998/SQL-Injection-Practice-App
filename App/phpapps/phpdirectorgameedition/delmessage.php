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
	header ('Location: message.php');
	exit();
}
else {
	$sql = 'DELETE FROM pp_messages WHERE id_destinataire="'.$_COOKIE['id'].'" AND id="'.$_GET['id_message'].'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

	header ('Location: message.php');
	exit();
}

}
?>