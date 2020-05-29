<?php
include("qdblog.php");
global $conn;
global $theme;
theme();
if($_POST['username'] and $_POST['wordpass'] and $_POST['wordpass2'] and ($_POST['wordpass'] == $_POST['wordpass2'])) {
	if(mysql_num_rows(mysql_query("SELECT username FROM $prefix"."auth WHERE username = '" . $_POST['username']."';", $conn)) == 0){
		$sql = "INSERT INTO $prefix"."auth(username, password, permissions) VALUES('".$_POST['username']."', MD5('" . $_POST['wordpass'] . "'), 'user');";
		mysql_query($sql, $conn);
		header("Location: index.php");
	} else { header("Location: register.php"); }
} else {
	include("themes/$theme/top.php");
	include("categories.php");
	?>
	<form action="<?php echo $_PHP['SELF']; ?>" method="POST">
	Username <input type="text" name="username" /><br/>
	Password <input type="password" name="wordpass" /><br/>
	Password (Confirm) <input type="password" name="wordpass2" /><br/>
	<input type="submit" value="Register"/>
	</form>
	<?php
	include("themes/$theme/bottom.php");
}
?>
	
	

