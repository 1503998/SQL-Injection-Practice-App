<?php
include 'authenticate.php';
include 'db.php';
include '../library/functions.php';
?>
<html>
<head>
<title>Edit Project Data</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso -8859-1">
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

</style>

<!-- TinyMCE -->
<script type="text/javascript" src="<?php echo get_siteurl($_SESSION['username']);?>/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>
<!-- /TinyMCE -->

<script type="text/javascript">
function is_empty(field,alerttxt)
{
with (field){
  if (value==null||value=="")
    {
    alert(alerttxt);
	focus();
	return true;
    }
  else
    {
    return false;
    }
  }
}

function validate_form(thisform)
{
	with (thisform)
	  {
	
		  if (is_empty(name,"File name cannot be empty!"))
		  {
			  return false;
		  }
		  else return true;  
		 
	  }
	
	 
}

</script>
</head>
<body>


<div align="center">
<h2>Edit Project Data
  <?php
if(isset($_GET['id'])){
		
		include 'db.php';
		$id = $_GET['id'];
	if(isset($_POST['submit']))
	{
		$notes=$_POST['notes'];
		$newname=$_POST['name'];
		$newname = str_replace (" ", '', $newname);
		$newname = str_replace (".", '', $newname);
		$newname = $newname . ".sb";
		
		$sql2 = "SELECT name, path FROM `scratch_files` WHERE id=$id";
		$result2 = mysql_query($sql2) or die('Error, query failed' . mysql_error());
		while($row=mysql_fetch_assoc($result2))
		{
			$oldname = $row['name'];
			$oldpath = $row['path'];
			if($oldname != $newname)
			{
			
	//RENAMING THE ACTUAL FILE
				$curDir=getcwd();
				chdir("../projects/");
				if(!rename($oldname,$newname))
				{
					echo "<p style='color:red'>Error - File name should not contain Special Symbols</p>";
				}
				else
				{
	//UPDATING DATABASE
					$newpath=str_replace($oldname,$newname,$oldpath);
					$sql3 = "UPDATE `scratch_files` SET `name` = '$newname' , path = '$newpath'  WHERE `id` = $id LIMIT 1;";
					$result3 = mysql_query($sql3) or die('Error, query failed' . mysql_error());		
					
					echo "<h3>File Name Changed</h3>";
				}
				chdir($curDir);					
			}
			
		}
		
		$sql = "UPDATE `scratch_files` SET `notes` = '$notes' WHERE `id` =$id LIMIT 1";
		$result = mysql_query($sql) or die('Error, query failed' . mysql_error());		
		echo "<h3>Notes Updated</h3>";
	}
	else
	{
		$query = "SELECT * FROM `scratch_files` where id=$id";
		$result = MYSQL_QUERY($query);
		$count=mysql_num_rows($result);
		
			if($count==1){
									while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
									$fname=$row['name'];
									$notes=$row['notes'];
									$path=$row['path'];
									
									$arr=explode('.',$fname);
									$fname=$arr[0];
									}	
							
							?>
 : <em style="color:#0000CC"><?php echo $fname ?></em><br> </h2></p>
<form name="form1" method="post" onSubmit="return validate_form(this)" action="">

<p style="font-size:18px"><strong>Name:</strong>
  
<input type="text" name="name" id="name" size="50" value="<?php echo $fname ?>"> &nbsp;( No dots . and spaces )</p>

<p style="font-size:18px"><strong>Notes:</strong></p>
							 <a style="font-family:Geneva, Arial, Helvetica, sans-serif">(Shift+Enter for Next Line . Enter for Next Paragraph) </a><p>
							  <textarea name="notes" id="notes" cols="45" rows="5"><?php echo $notes ?></textarea>
							</p>
							<p>
							  <input type="submit" name="submit" id="submit" value="Submit">
							  </p>
						  </form>
						<?php
                        }
                        
		
        
	}
	
	?>
    <center><a href="edit.php">Back</a>
	<p><a href="index.php" target="_top">Admin Home</a></p>
    </center>
    <?php
	

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

  <table>
    <tr>
      <td>
        <ul style="font-size:18px">
          <?php
while(list($id, $name) = mysql_fetch_array($result))
{
		$arr=explode('.',$name);
		$name=$arr[0];
		echo "<li style=\"list-style:disc\"><a href='edit.php?id=$id'>$name</a></li>";


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