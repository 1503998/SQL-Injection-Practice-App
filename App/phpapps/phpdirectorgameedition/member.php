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
$member = 'SELECT website,about FROM pp_user WHERE id ='.$_COOKIE['id'].'';
$res= mysql_query($member);
$data = mysql_fetch_assoc($res);
$smarty->assign('website', $data['website']);
$smarty->assign('about', $data['about']);

$website = $_POST['website'];
$about = $_POST['about'];
if(!empty($website) && !empty($about))
{

		$sql = 'UPDATE pp_user SET website = "'.$website.'", about="'.$about.'" WHERE id = '.$_COOKIE['id'].'';
		mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
}
}
			
$smarty->display('member.tpl');
	


?>