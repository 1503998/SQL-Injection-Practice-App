<?php

include("ajaxinclude.php");

if($_SESSION['perm'] == '1')
{

$cat_order = 1;
foreach($_POST['editcats'] as $cat_id)
{
	mysql_query("UPDATE " . $prefix . "categories SET catorder = '$cat_order' WHERE cat_id = '$cat_id'");
	$cat_order++;
}

mysql_close();

echo '<span class="errortext">Categories reordered successfully!</span>';

}else
{
    accessdenied();
}

?>