<?php

session_start();

if($_SESSION['perm'] == '1')
{

include("../../config.php");

dbconnect();

//ADD NEW BOARD TO THE DATABASE
mysql_query("INSERT INTO " . $prefix . "categories (catname, catorder) VALUES ('{$_POST['catname']}','1')") OR DIE ("Gravity Board X was unable to create a new category: " . mysql_error());

mysql_close();

echo '<span class="errortext">Category \'' . stripslashes($_POST['catname']) . '\' created successfully!</span>';

}else
{
    accessdenied();
}

?>