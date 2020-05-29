<?php
include("language.php");
$errmsg = $_GET['msg'];
switch ($errmsg) {
	case 0:
		header("Location: ./");
		break;
	case 1:
		echo "$sys_err_forum<br><br>\n";
		echo "<a href=\"./\">$sys_savelinkmain</a>\n";
		break;
	case 2:
		echo "$sys_err_user<br><br>\n";
		echo "<a href=\"./\">$sys_savelinkmain</a>\n";
		break;
	case 3:
		echo "$sys_err_pass<br><br>\n";
		echo "<a href=\"./\">$sys_savelinkmain</a>\n";
		break;
	case 4:
		break;
}
?>