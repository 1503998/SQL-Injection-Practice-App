<?php
include("themes/$theme/cat_top.php");
$file1 = fopen("themes/$theme/cat_mid.html", "r");
$cat_theme = fread($file1, filesize("themes/$theme/cat_mid.html"));
fclose($file1);
$query1 = mysql_query("SELECT name FROM $prefix"."cat;", $conn);
while( $value1 = mysql_fetch_array($query1)) {
	global $value1;
	echo cat_theme($cat_theme);
}
include("themes/$theme/cat_bottom.php");
?>
