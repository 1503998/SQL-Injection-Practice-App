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

//Installed?
$filename = "installed.php";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$explode_cont = explode(";", $contents);
if ($explode_cont[1] !== "Yes"){
header("Location: install/index.php");
}
//Installed?

require('libs/Smarty.class.php');
require('libs/SmartyPaginate.class.php');
include("db.php");
include("includes/function.inc.php");
$template = config('template');
$smarty = new Smarty();
$smarty->template_dir = './templates/'.$template;
$smarty->compile_dir = './templates_c';
$smarty->cache_dir = './cache';
$smarty->config_dir = './configs';

if(!$_GET["lang"])
 {
  include("lang/".config('lang'));
 }
else
 {
  SetCookie("lang",$_GET["lang"]);
  header('Location: ' . $_SERVER['HTTP_REFERER'] );    
 } 
if (!$_COOKIE["lang"])
 { 
  include("lang/".config('lang'));
 }
else
 {
  $lang = $_COOKIE["lang"];
  include("lang/" . $lang . ".inc.php");
 }

if(isset($_GET['sort']))
$sort1 = $_GET['sort'];
if(isset($_GET['page']))
$page = $_GET['page'];
if(isset($_GET['pt']))
$pagetype = $_GET["pt"];
$smarty->assign('pagetype', $pagetype);
if(isset($_GET['next']))
$smarty->assign('next', $_GET["next"]);
$cnf_name = config('name');
$smarty->assign('config_name', $cnf_name);
//NEWS//
$news = config('news');
$smarty->assign('news', $news);
//NEWS//

//Firefox?
$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
if (!(strpos($HTTP_USER_AGENT,'Mozilla/5') === false)) {
$smarty->assign('firefox', '1');
} else {
$smarty->assign('firefox', '0');
}
//Firefox? END
if(isset($_GET['id']))
$idc=$_GET['id'];
if(isset($idc))
{
      $result = mysql_query("SELECT * FROM pp_files WHERE id=$idc") or die(mysql_error()); 
      $row = mysql_fetch_assoc($result);
   $smarty->assign('title', $row['name']);
   $smarty->assign('desc', $row['description']);
   }
else
   {
   $smarty->assign('desc', 'Play for free with our large database of flash game');
   $smarty->assign('title', 'Play for free');
   }
if(isset($_COOKIE["id"])) 
{ 
   $result_us= mysql_query("SELECT * FROM pp_user WHERE id=$_COOKIE[id]") or die(mysql_error()); 
      $row_us = mysql_fetch_assoc($result_us); 
	  $smarty->assign('user', $row_us['user']); 
	  $smarty->assign('avatar', $row_us['avatar']);
	 $mess= mysql_query("SELECT COUNT(*) as nb FROM pp_messages WHERE id_destinataire = $_COOKIE[id]") or die(mysql_error()); 
      $row_mess = mysql_fetch_assoc($mess); 
	  $smarty->assign('nbmess', $row_mess['nb']);  
}

?>