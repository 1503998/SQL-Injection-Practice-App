<?
/* Account activation script */
session_start();
// Get database connection
include 'include/header.php';
dbconnect();
echo '<div align="center">';
// Create variables from URL.

$userid = $_REQUEST['id'];
$code = $_REQUEST['code'];

$sql = mysql_query("UPDATE eb_members SET activated='1' WHERE userid='$userid' AND password='$code'");

$sql_doublecheck = mysql_query("SELECT * FROM eb_members WHERE userid='$userid' AND password='$code' AND activated='1'");
$doublecheck = mysql_num_rows($sql_doublecheck);

if($doublecheck == 0){
    echo "<strong><font color=red>Your account could not be activated!</font></strong>";
} elseif ($doublecheck > 0) {
    echo "<strong>Your account has been activated!</strong> You may login below!<br />";
    include 'login_form.html';
}
echo "</div>";
?>