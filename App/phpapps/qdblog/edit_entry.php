<?php

session_start();
include("qdblog.php");
if(user_allow("login.php", "admin", "mod")) {
global $conn;
global $theme;
theme();
//category themes will come later

if($_POST['title'] and $_POST['content'] and $_POST['id']) {
	//add in the update stuff here
	if($_POST['fp'] == 'on') { $_POST['fp'] = 1; }
	mysql_query("UPDATE $prefix"."entries SET title = '" . filter($_POST['title']) . "', content = '" . filter($_POST['content']) . "', category = '" . filter($_POST['category']) . "', fp = '" . (int)$_POST['fp'] . "' WHERE id = '" . (int)$_POST['id'] . "';", $conn) or die("Editing failed");
	header("Location: index.php");
} else { 
	if($_GET['id']) {
		//now query info and output form
		$old = mysql_query("SELECT id, date, title, content, category, author, fp FROM $prefix"."entries WHERE id = '" . (int)$_GET['id'] . "' LIMIT 1;", $conn);
		while( $row = mysql_fetch_array($old)) {
			include("themes/$theme/top.php");
			include("categories.php");
			?>
			<form action="<?php echo $_PHP['SELF'] ?>" method="POST">
			Title <input type="text" name="title" value="<?php echo $row['title']; ?>" /><br/>
			<textarea rows="20" cols="60" name="content"><?php echo $row['content']; ?></textarea><br/>
			<?php
			$cats = mysql_query("SELECT name FROM $prefix"."cat;", $conn);
			?>
			<select name="category">
			<?php
			while( $row1 = mysql_fetch_array($cats) ) {
				if($row['category'] == $row1['name']) { $selected = "selected"; } else { $selected = NULL; }
				echo "<option $selected >{$row1['name']}</option>";
			}
			?>
			</select><br/>
			Front Page<input type="checkbox"  name="fp" <?php  if($row['fp'] == 1) { echo "checked"; } ?>"/><br/>
			<input type="hidden" name="id" value="<?php echo (int)$_GET['id']; ?>"/>
			<input type="submit" value="Post"/>
			</form>
			<?php
			include("themes/$theme/bottom.php");
		}
	} else { 
		header("Location:index.php");
	}
}
}
?>
