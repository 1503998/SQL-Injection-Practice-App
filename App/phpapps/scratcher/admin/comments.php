<?php 
include 'authenticate.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="images/favicon.gif"> 

<style type="text/css">
a:link {
	color: #0000CC;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #0000CC;
}
a:hover {
	text-decoration: underline;
	color: #FF3333;
}
a:active {
	text-decoration: none;

}
a.del:link{
	color:#FF0000;
	text-decoration: none;
}
a.del:hover
{
	background-color:#FF0000;
	color:#99FF33;
	text-decoration: none;
	
}
</style>
</head>

<body background="../images/background.jpg">
<center>
  <p>
    <?php
if(isset($_GET['project']))
{
	$project_id=$_GET['project'];
	include 'db.php';
	$query1="SELECT name FROM scratch_files WHERE id=$project_id";
	$result1=mysql_query($query1) or die('Error, query failed : ' . mysql_error());
	while($row=mysql_fetch_assoc($result1))
	{	$project_name=$row['name'];
		$array=explode('.',$project_name);
		$project_name=$array[0];
		echo "<h2>Comments for the Project: $project_name</h2>
		<title>Comments for $project_name </title>";
	}
	?>
    <a href="../comments.php?project=<?php echo $project_id ?>" target="_blank">Add Comment</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="comments.php">Back</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="del" style="border:groove;border-color:#9900CC"  href="comments.php?project=<?php echo $project_id ?>&del=all">Delete All</a><br />
	<br>
    <?php
		if(isset($_GET['del']))
		{
			$del=$_GET['del'];
			if($del=="all")
			{
					if(isset($_POST['yes']))
					{
						$sql1="DELETE from `scratch_comments` WHERE project_id='$project_id' ";
						$result1=mysql_query($sql1) or die('Query failed'.mysql_error());			
						echo "<h3>All Comments Deleted</h3>";
					}
					elseif(isset($_POST['no']))
					{
					}
					else{
						?>
                        	
						  <form id="form1" name="form1" method="post" action="" >
						<p style="border:dotted; border-color:#FF0000;font-size:18px;background-color:#FFCCFF">  Are you Sure you want to delete all the comments for <?php echo $project_name  ?> ?
						<br>	<input type="submit" name="yes" id="yes" value="Yes" />
						  <input type="submit" name="no" id="no" value="No" /></p>
						  </form>
                          
							
						<?php   
								}
			}
			else
			{					
					$sql="DELETE from `scratch_comments` WHERE id=$del LIMIT 1";
					$result=mysql_query($sql) or die('Query failed'.mysql_error());			
					echo "<h3>Comment Deleted</h3>";
			}
		}
	$query="SELECT * FROM `scratch_comments` WHERE project_id=$project_id ORDER BY date ";
	$result=mysql_query($query) or die('Query failed'.mysql_error());
	if(mysql_num_rows($result)==0)
		echo "<h2>No Comments yet.</h2>";
	else
	{
	
		while($row=mysql_fetch_assoc($result))
		{
		
			$comment=$row['comment'];
			$comment=str_replace("\n","<br>",$comment);
		?>
	              <a class="del" style="border:groove;border-color:#9900CC" onclick="return confirm('Are you sure?');" href="comments.php?project=<?php echo $project_id ?>&del=<?php echo $row['id'] ?>">Delete</a>
<table cellspacing="0" bordercolor="#00FFFF" border="1" >
        <tr><td width="139"><?php echo $row['author'] ?> said:	</td>
			<td width="213"> <?php echo $row['author_email'] ?>  </td>
        </tr>
        <tr><td> <?php echo $row['date'] ?><br>IP <?php echo $row['author_ip'] ?></td>
        	<td><?php echo $comment ?></td>
        </tr></table><br>
            
		<?php }
	}
}
else
{
?> 


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
<title>Comments</title>
<h2>Comments</h2>
  <table>
    <tr>
      <td>
        <ul>
          <?php
while(list($id, $name) = mysql_fetch_array($result))
{
?>
<li style="list-style:disc;font-size:18px"><a href='comments.php?project=<?php echo $id ?>'><?php echo $name ?></a></li>

<?php
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

</center>

</body></html>
<?php } ?>