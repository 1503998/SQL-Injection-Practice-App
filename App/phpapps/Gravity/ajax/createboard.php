<?php

include("../ajaxinclude.php");

if($_SESSION['perm'] == '1')
{

//ADD NEW BOARD TO THE DATABASE
mysql_query("INSERT INTO " . $prefix . "boards (name, cat_id, boardorder, description) VALUES ('{$_POST['name']}','{$_POST['cat_id']}','1','{$_POST['description']}')") OR DIE("Gravity Board X was unable to create a new board: " . mysql_error());

$getid = mysql_query("SELECT board_id FROM " . $prefix . "boards WHERE name = '{$_POST['name']}' AND cat_id = '{$_POST['cat_id']}' LIMIT 1") OR DIE("Gravity Board X was unable to verify the board id for the new board: " . mysql_error());
list($newboardid) = mysql_fetch_row($getid);

//ADD THE BOARD TO THE ANNOUNCEMENTS DATABASE
mysql_query("INSERT INTO " . $prefix . "announcements (board_id, enabled) VALUES ('$newboardid','0')") OR DIE("Gravity Board X was unable to create an announcements profile for the new board: " . mysql_error());

mysql_close();

echo '<span class="errortext">Board \'' . stripslashes($_POST['name']) . '\' created successfully!</span>';

}else
{
    accessdenied();
}

?>