<?php
session_start();

if (isset($_SESSION["flag"])) {
$_SESSION = array();
session_destroy();
}
else{
?>
<center>
<h2 style="color:#CC3300">Not Logged in</h2>
<h3><a href="login.php"> Login </a></h3>
</center>
<?php
exit;
}

?>
<html><center>
<p><h2>Successfully logged out</p></h2>
<h3><a href="login.php"> Login </a></h3>
<center>
</html>