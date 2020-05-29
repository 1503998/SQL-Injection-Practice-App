<?php
session_start();
include("qdblog.php");
if(user_allow("login.php", "admin")){
//Yes this is damned hackish and not so pretty, but its easily extendable
//Ok for the if statement just add in configuration values that might be good to record.
if($_POST['cat_add'] or $_POST['cat_del'] or $_POST['theme_default']) {
	//just check to see which ones are defined and then update accordingly
	connect_db(); //Kind of important
	if($_POST['cat_add']) {
		mysql_query("INSERT INTO $prefix"."cat(name) VALUES('". $_POST['cat_add'] . "');", $conn);
	}
	if($_POST['cat_del']) {
		mysql_query("DELETE FROM $prefix"."cat WHERE name='". $_POST['cat_del'] ."';", $conn);
	}
	if($_POST['theme_default']) {
		mysql_query("UPDATE $prefix"."config SET theme_default = '". $_POST['theme_default'] . "';", $conn);
	}
	header("Location: index2.php");
} else {
	theme();
	include("themes/$theme/top.php");
	?>
	<p>
	<?php include("categories.php"); ?>
	<h1>Administration</h1>
	<form action="<?php echo $_PHP['SELF'] ?>" method="POST">
	<h3>Categories</h3><br/>
	Add Category <input type="text" name="cat_add"/><br/>
	Delete Category 
	<?php
	$cats = mysql_query("SELECT name FROM $prefix"."cat;", $conn);
	?>
	<select name="cat_del">
	<option selected></option>
	<?php
	while( $row = mysql_fetch_array($cats) ) {
		echo "<option>{$row['name']}</option>\n";
	}
	?>
	</select><br/>
		
	<hr/>
	<h3>Themes</h3><br/>
	Default Theme
	<?php
	$themes = mysql_query("SELECT name FROM $prefix"."themes;", $conn);
	$default_theme = mysql_query("SELECT theme_default FROM $prefix"."config LIMIT 1;", $conn);
	?>
	<select name="theme_default">
        <?php
	while( $row = mysql_fetch_array($default_theme) ) {
	while( $row1 = mysql_fetch_array($themes) ) {
		if($row['theme_default'] == $row1['name']) { $selected = "selected"; } else { $selected = NULL; }
		echo "<option $selected >{$row1['name']}</option>";
	}
	}
	?>
	</select><br/>
	<br/>
	<hr/>
	<input type="submit" value="Apply"/>
	</form>
	</p>
	<?php
	include("themes/$theme/bottom.php");
}
}
?>
