<?php
if ($_POST['kommentar'] != "") {
	include("env_db.php");
	include("language.php");
	initDB();
	$recipe = $_POST['id'];
	$comment = $_POST['kommentar'];
	$pos = strpos($comment, "<");
	if ($pos === false) {
		$datum = date('Y-m-d');
		$user = $_POST['user'];
		$query = "INSERT INTO dat_comments (recipe, comment, datum, user) VALUES ($recipe, '$comment', '$datum', '$user')";
		$result = mysql_query($query);
		header("Location: ./rezeptanzeige.php?currid=$recipe");
	}
	else {
		echo "<script>alert('$sys_com_err');location.href='./rezeptanzeige.php?currid=$recipe';</script>\n";
	}
}
?>