<?php

include("../ajaxinclude.php");

if($_SESSION['perm'] == '1')
{

//ADD NEW CATEGORY TO THE DATABASE
mysql_query("INSERT INTO " . $prefix . "categories (catname) VALUES ('{$_POST['name']}')") OR DIE("Gravity Board X was unable to create a new category: " . mysql_error());

mysql_close();

echo '<span class="errortext">Category \'' . stripslashes($_POST['name']) . '\' created successfully!</span>';

}else
{
    accessdenied();
}

?>