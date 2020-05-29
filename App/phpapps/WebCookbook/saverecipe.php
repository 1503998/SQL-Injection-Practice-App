<?php

include("language.php");
include("env_db.php");
include("env_sc.php");

InitDB();

$id = $_GET['id'];
$query = "SELECT * FROM das_rezept WHERE id = " . $id;
$result = mysql_query($query);
$title = mysql_result($result, 0, 'title');
$servings = mysql_result($result, 0, 'servings');
$prefix = mysql_result($result, 0, 'prefix');
$preparation = mysql_result($result, 0, 'preparation');
$postfix = mysql_result($result, 0, 'postfix');
$tipp = mysql_result($result, 0, 'tipp');
$arbeitszeit = mysql_result($result, 0, 'arbeitszeit');
$image = mysql_result($result, 0, 'image');

switch (strlen($id)) {
	case 0:
		die("Can't get record without id!");
		break;
	case 1:
		$outfilename = "recipe000" . $id;
		break;
	case 2:
		$outfilename = "recipe00" . $id;
		break;
	case 3:
		$outfilename = "recipe0" . $id;
		break;
	case 4:
		$outfilename = "recipe" . $id;
}

$omode = $_COOKIE['omode'];
if ($omode == "") { $omode = "mmf"; }
switch ($omode) {
	case "wcb":
		$outfilename = $outfilename . ".wcb";
		$aus = fopen("upload/" . $outfilename, "w");
		saveWCBFile($aus, $id, $title, $servings, $prefix, $preparation, $postfix, $tipp, $arbeitszeit, $image);
		echo mysql_error();
		break;
	case "mmf":
		$outfilename = $outfilename . ".mmf";
		$aus = fopen("upload/" . $outfilename, "w");
		saveMMFile($aus, $id, $title, $servings, $prefix, $preparation, $postfix, $tipp, $arbeitszeit, $image);
		break;
	case "rkv":
		$outfilename = $outfilename . ".rk";
		$aus = fopen("upload/" . $outfilename, "w");
		saveRKFile($aus, $id, $title, $servings, $prefix, $preparation, $postfix, $tipp, $arbeitszeit, $image);
		break;
}

fclose($aus);
header("Content-Type: text/plain");
header("Content-length: " . filesize("upload/" . $outfilename));
header("Content-Disposition: attachment; filename=" . $outfilename);
readfile("upload/" . $outfilename);

?>