<?php
include 'authenticate.php';
?>
<?php
if(isset($_GET['id'])){

include 'db.php';
$id = $_GET['id'];
$query = "SELECT * FROM `scratch_files` where id=$id";
$result = MYSQL_QUERY($query);
$count=mysql_num_rows($result);
while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$fname=$row['name'];
$path=$row['path'];
}
if($count==1){
	$query = "DELETE FROM `scratch_files` where id=$id ";
		
	unlink($path);
	$result = mysql_query($query);
	echo "<center><h2>Requested File has been removed.</h2></center>";
	?><center><a href="remove.php">Back</a>
	<p><a href="index.php" target="_top">Admin Home</a></p>
    </center>
<?php
}

	else{
	echo "<center><h2>File not found</h2></center>";
	?>
    <center><a href="remove.php">Back</a>
	<p><a href="index.php" target="_top">Admin Home</a></p>
    </center>
    <?php
	}

}
else
{
?> 

<html>
<head>
<title>Delete Scratch Projects</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso -8859-1"> 
</head>
<body>
<h2 align="center">Remove files</h2>
<div align="center">
  <?php
include 'db.php';

$query = "SELECT id, name FROM `scratch_files`";
$result = mysql_query($query) or die('Error, query failed');
if(mysql_num_rows($result) == 0)
{
echo "Database is empty <br>";
}
else
{
?>
  <table>
    <tr>
      <td>
        <ul>
          <?php
while(list($id, $name) = mysql_fetch_array($result))
{
echo "<li style=\"list-style:disc\"><a onclick=\"return confirm('"."Are you sure?"."');\" href='remove.php?id=$id'>$name</a></li>";


}
?>
        </ul>    </td>
    </tr>
  </table>
  <?php
}
mysql_close($conn);
 ?>
 <p><a href="index.php" target="_top">Admin Home</a></p>
</div>


</body></html>
<?php } ?>