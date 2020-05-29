	<?php
	ob_start(); 
	session_start();
	
	include("admin_header.php");
	$smarty->display('admin_header.tpl');
	
	if (checkLoggedin()){
	if ((isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {
	$id = $_GET['id'];
	} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id = $_POST['id'];
	} else {
	echo 'Please choose a video to edit.';
	exit();
	}
	 
	if (isset($_POST['submitted'])) {
	$errors = array();
	 
	if (empty($_POST['name'])) {
	$errors[] = 'You forgot to enter a name.';
	} else {
	$name = $_POST['name'];
	 
	}
	 
	if (empty($_POST['creator'])) {
	$errors[] = 'You forgot to enter an creator.';
	} else {
	$creator = $_POST['creator'];
	}
	 
	if (empty($_POST['description'])) {
	$errors[] = 'You forgot to enter a description';
	} else {
	$description = $_POST['description'];
	}
	 
	if (empty($errors)) {
	$query = "UPDATE pp_files SET name='$name', creator='$creator', description='$description' WHERE id=$id";
	$result = mysql_query($query);
	 
	if ($result) {
	echo "The Video Has Been Updated!";
	} else {
	echo "The Video could not be updated.";
	}
	} else {
	echo 'The Video could not be updated for the following reasons -<br />';
	foreach ($errors as $msg) {
	echo " - $msg<br />\n";
	}
	}
	} else {
	$query = "SELECT name, creator, description, id FROM pp_files WHERE id=$id";
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	$row = mysql_fetch_array ($result, MYSQL_NUM);
	 
	$name = $row['0'];
    $creator = $row['1'];
	$description = $row['2'];
	 
	if ($num == 1) {
	echo '<div align="center"> <h3>Edit Video '.$name.' </h3>
	<form action="?id=video_edit&num='.$id.'" method="post">
	<p>Video Title : <input type="text" name="name" size="50" maxlength="255" value="'.$name.'" /></p>
<p>Creator : <input type="text" name="creator" size="50" maxlength="255" value="'.$creator.'" /></p>
	<p>Description : <br /><textarea rows="5" cols="70" name="description">'.$description.'</textarea></p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" /></p>
	<input type="hidden" name="id" value="'.$id.'" /> </div>';
} else {
echo 'The Video could not be edited, please try again.';
}
	}
	}
	?> 
