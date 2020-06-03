<?php

include("ajaxinclude.php");

if($_SESSION['perm'] == '1')
{

$board_order = 1;
foreach($_POST['editboards'] as $board_id)
{
	mysql_query("UPDATE " . $prefix . "boards SET boardorder = '$board_order' WHERE board_id = '$board_id'");
	$board_order++;
}

mysql_close();

echo '<span class="errortext">Boards reordered successfully!</span>';

}else
{
    accessdenied();
}

?>