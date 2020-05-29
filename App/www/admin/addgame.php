	<?php
	ob_start(); 
	session_start();
	include("admin_header.php");
	
	
	if (checkLoggedin()){
if(isset($_POST['gameurl']))
{
	$name = $_POST['name'];
	$author_link = $_POST['authorlink'];
	$author = $_POST['author'];
	$description = $_POST['description'];
	$game_url = $_POST['gameurl'];
	$slug = $_POST['slug'];
	$thumb_url = $_POST['thumburl'];
	$leader = $_POST['leaderboard'];
	$width = $_POST['width'];
	$height = $_POST['height'];
	
	$query=mysql_query("INSERT INTO pp_files (name,url_creator,creator,description,date,file,file2,approved,feature,ip,picture,category,views,width,height)
	VALUES ('$name','$author_link','$author','$description',CURRENT_DATE(),'$game_url','$slug',1,$leader,0,'$thumb_url',0,0,$width,$height)")or die(mysql_error());
	
	
$smarty->assign('message', 'Game added<br /><a href="addgame.php">Add a new game</a>');
}
    $smarty->display('addgame.tpl');
	
	}
	else{
header("location: login.php");
}
	?> 
