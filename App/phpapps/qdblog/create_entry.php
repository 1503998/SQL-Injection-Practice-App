<?php
/*
QDBlog (Quick and Dirty Blog) is a simple minimalistic tool for blogging
Copyright © 2004 Ben "SchleyFox" Hughes
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; version 2
of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

//this is pretty ok for now 

session_start();
include("qdblog.php");
if(user_allow("login.php", "admin", "mod")) {
global $conn;
global $theme;
theme();

if($_POST['title'] and $_POST['content']) {
	if($_POST['fp'] == 'on') { $_POST['fp'] = 1; }
	mysql_query("INSERT INTO $prefix"."entries(date, title, content, fp, author, category) VALUES('". date("Y-m-d H:i:s") ."', '" . filter($_POST['title']) . "', '" . filter($_POST['content']) . "', '" . (int)$_POST['fp'] . "', '{$_SESSION['username']}', '{$_POST['category']}');", $conn) or die("Unable to post entry");
	$id = mysql_insert_id($conn);
	mysql_query("INSERT INTO $prefix"."crapflood(id, last) VALUES('$id', '0');", $conn);
	header("Location: index.php");
} else {
include("themes/$theme/top.php");
include("categories.php");
//lets make a form

?>
<form action="<?php echo $_PHP['SELF'] ?>" method="POST">
Title <input type="text" name="title" /><br/>
<textarea rows="20" cols="60" name="content">Type your entry here</textarea><br/>
<?php 
$cats = mysql_query("SELECT name FROM $prefix"."cat;", $conn);
?>
<select name="category">
<?php
while( $row = mysql_fetch_array($cats) ) {
	echo "<option>{$row['name']}</option>\n";
}
?>
</select><br/>
Front Page<input type="checkbox" name="fp" /><br/>
<input type="submit" value="Post"/>
</form>
<?php
include("themes/$theme/bottom.php");
}
}
?>


