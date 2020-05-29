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

$member = 'SELECT * FROM pp_user WHERE user ="'.$_GET['u'].'"';
$res= mysql_query($member);
$data = mysql_fetch_assoc($res);
$smarty->assign('website', $data['website']);
$smarty->assign('register', $data['register']);
$smarty->assign('about', $data['about']);
$smarty->assign('avatar', $data['avatar']);
$smarty->assign('plays', $data['plays']);
$smarty->assign('id', $data['id']);
$smarty->assign('user', $_GET['u']);

			
$smarty->display('user.tpl');
	


?>