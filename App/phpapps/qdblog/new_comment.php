<?php
include("qdblog.php");
//TODO:set up special permission checking, ignore it now

session_start();
global $theme;
global $conn;
connect_db();
//time to prevent crapfloods 
//TODO: Make time user configurable
if($_POST['entry_id']) {
	$last = mysql_query("SELECT last FROM $prefix"."crapflood WHERE id = '" . $_POST['entry_id'] . "' LIMIT 1;", $conn);
	while($row = mysql_fetch_array($last) ) {
		if($row['last'] > (date("U") - 15)) {
			$lock = "yes";
		} else { $lock = "no"; }
	}
}

if($_POST['entry_id'] and $_POST['title'] and $_POST['content'] and $_POST['author'] and $_POST['email']  and $lock == "no") {
	mysql_query("INSERT INTO $prefix"."comments(entry_id, title, content, author, email, date) VALUES('" . (int)$_POST['entry_id'] . "', '" . filter($_POST['title']) ."', '" . filter($_POST['content']) . "', '" . filter($_POST['author']) . "', '" . filter($_POST['email']) . "', '" . date("Y-m-d H:i:s") . "');", $conn);
	mysql_query("UPDATE $prefix"."crapflood SET last = '" . date("U") . "' WHERE id = '" . (int)$_POST['entry_id'] ."';", $conn);
	header("Location: index.php");
} else {
	//Check to see if the error is due to craplock
	theme();
	include("themes/$theme/top.php");
	include("categories.php");
	if($lock == "yes") {
		echo "Sorry, Flood prevention has prevented you from posting wait a few seconds<br/>\n";
	}
	?>
	<form action="<?php echo $_PHP['self']; ?>" method="POST">
	<input type="hidden" name="entry_id" value="<?php echo $_GET['id']; ?>" />
	Name <input type="text" name="author" value="<?php echo $_SESSION['username']; ?>"/><br/>
	E-mail <input type="text" name="email" /><br/>
	Title: <input type="text" name="title" /><br/>
	<textarea name="content" rows="10" cols="30"></textarea><br/>
	<input type="submit" value="Post" />
	</form>
	<?php
}
?>


