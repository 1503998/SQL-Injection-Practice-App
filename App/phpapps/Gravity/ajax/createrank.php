<?php

include("../ajaxinclude.php");

if($_SESSION['perm'] == 1)
{

//ADD NEW BOARD TO THE DATABASE
mysql_query("INSERT INTO " . $prefix . "ranks (rank, color, postsneeded) VALUES ('{$_POST['name']}','{$_POST['color']}','{$_POST['posts']}')") OR DIE ("Gravity Board X was unable to create a new category: " . mysql_error());

mysql_close();

echo '<span class="errortext">Rank \'' . stripslashes($_POST['name']) . '\' created successfully!</span>';

}else
{
    accessdenied();
}

?>