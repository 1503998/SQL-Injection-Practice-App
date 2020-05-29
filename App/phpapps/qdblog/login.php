<?php
session_start();
include("qdblog.php");
if(user_allow("index.php", 'anon')) {
theme();
global $theme;
global $conn;

include("themes/$theme/top.php");
include("categories.php");
?>
<form action="authenticate.php" method="POST">
Username<input type="text" name="username" /><br/>
Password<input type="password" name="wordpass" /><br/>
<input type="submit" value="Log In" /></form>
<br/>
<br/>
<a href="register.php">Register Account</a>
<?php
include("themes/$theme/bottom.php");
}
?>
