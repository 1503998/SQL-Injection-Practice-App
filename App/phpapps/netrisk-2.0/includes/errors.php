<?php

	/**************************************************

		Project	NetRisk <http://netriks.sourceforge.net>
		Author	PMuldoon <ptmuldoon@gmail@gmail.com>
		License	GPL

	 **************************************************/

$host = $_SERVER['HTTP_HOST'];
//$root = "http://".$host.$game_path;
$root = $web_root;

$cpage = $_GET['p'];
	 
function error_text($text){
	$proper = str_replace(' ','%20',$text); 
    return $proper;
}
	 
function index_error_header($error){
	global $root, $gamepath, $host;
	$nerror = error_text($error);
	header("Location: ".$root."index.php?error=$nerror");
}

function reset_error_header($error){
	global $root, $gamepath, $host;
	$nerror = error_text($error);
	header("Location: ".$root."index.php?p=reset&error=$nerror");
}

function profile_error_header($error){
	global $root, $gamepath, $host, $cpage;
	$nerror = error_text($error);
	header("Location: ".$root."index.php?p=profile&id=$_SESSION[userid]&error=$nerror");
}


function browser_error_header($error){
	global $root, $gamepath, $host;
	$nerror = error_text($error);
	header("Location: ".$root."index.php?p=browser&error=$nerror");
}

function join_error_header($error){
	global $root, $gamepath, $host;
	$nerror = error_text($error);
	header("Location: ".$root."index.php?p=join&error=$nerror");
}

function game_error_header($error){
    global $root, $gamepath, $host, $gid;
    $nerror = error_text($error); 
    header("Location: ".$root."index.php?p=game&id=$_SESSION[gid]&display=status&error=$nerror");
    //originating script should call exit    
}


?>