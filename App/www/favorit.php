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

require('header.php');
if(!isset($_COOKIE["id"]))
{
     header("Location: index.php");
}
else
{
 $result_m= mysql_query("SELECT user FROM pp_user WHERE id=$_COOKIE[id]") or die(mysql_error()); 
      $row_m = mysql_fetch_assoc($result_m);
 $result_us= mysql_query("SELECT * FROM pp_fav WHERE user='$row_m[user]'") or die(mysql_error()); 
      while($row_us = mysql_fetch_assoc($result_us))
	  {
if(!empty($row_us))
{	  
$query_fv = "SELECT * FROM pp_files WHERE id = $row_us[gid]";
$result_fv = mysql_query($query_fv);
while ($row_fv = mysql_fetch_assoc($result_fv))
{
   $videos[] = $row_fv;
}
}
else
{
$smarty->assign('error', 'You have no favorite game');
}
}
}
$smarty->assign('videos', $videos);
$smarty->display('favorit.tpl');	

?>