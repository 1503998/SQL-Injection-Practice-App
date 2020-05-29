<?php
include("env_db.php");
InitDB();
$rank = $_POST['ranking'];
$id = $_POST['id'];
$wert = explode("|", $_COOKIE['logged']);
$query = "INSERT INTO dat_bewertung (recipe, user, ranking) VALUES ($id, '$wert[1]', $rank)";
$result = mysql_query($query) or die(mysql_error());
header("Location: ./rezeptanzeige.php?currid=$id");
?>