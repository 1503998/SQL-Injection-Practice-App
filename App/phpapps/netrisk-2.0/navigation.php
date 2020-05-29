<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	  
  	include('./config.php');
  
  	$tpl = new TemplatePower( "./templates/navigation.tpl" );
  	$tpl -> prepare();
  
  	$query = mysql_query("SELECT * FROM ". $mysql_prefix . "users WHERE login = '{$_SESSION['username']}' ") or die(mysql_error()); 
  	$query_row = mysql_fetch_assoc($query);
  	$rank = $query_row['rank'];
  
  	$id = $query_row['id'];
  
  	if($_SESSION['username']){
 		$profile_link = ' <li> | <a href="index.php?p=profile&amp;id='.$id.'">Profile</a></li>';
  		$tpl -> assign("Profile_Link", $profile_link);
  	}
   
  	if($rank == 70){
 		$admin_link = ' <li> | <a href="index.php?p=admin">Admin</a></li>';
  		$tpl -> assign("Admin_Link", $admin_link);
  	}
  	  
  	$tpl -> printToScreen(); 
?>