<?php
include 'authenticate.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../library/links.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dashboard</title>
</head>

<body background="../images/brick1_bg.jpg">
<h1 style="color:#FF33CC"><em>Dashboard</em></h1>
<p style="color:#FF0066;font-family:Georgia, 'Times New Roman', Times, serif;font-size:22px"><a href=".." target="_blank">View Site - Scratch Home</a></p>
<table width="262" border="1" cellspacing="0" cellpadding="1">
 
  <tr>
    <td height="37"><div align="center"><a href="edit.php"><?php echo no_of_projects(); ?> projects</a>&nbsp;</div></td>
    <td><div align="center"><a href="comments.php"><?php echo no_of_comments(); ?> Comments&nbsp;</a></div></td>
  </tr>
</table>
<?php include 'upload.php' ?>
</body>
</html>
<?php 
function no_of_projects()
{
	include 'db.php';
	$query="SELECT * from scratch_files";
	$result=mysql_query($query) or die("Query failed".mysql_error());
	return mysql_num_rows($result);
}
function no_of_comments()
{
	include 'db.php';
	$query="SELECT * from scratch_comments";
	$result=mysql_query($query) or die("Query failed".mysql_error());
	return mysql_num_rows($result);
}

?>