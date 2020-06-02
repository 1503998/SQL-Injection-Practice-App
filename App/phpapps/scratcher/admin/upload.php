<?php
include 'authenticate.php';
include 'db.php';
include '../library/functions.php';
?><head>
<title>Upload Scratch Projects</title>
<!-- TinyMCE -->
<script type="text/javascript" src="<?php echo get_siteurl($_SESSION['username']);?>/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme: "simple"
	});
</script>
<!-- /TinyMCE -->
</head>

<form method="post" enctype="multipart/form-data">
  <div align="center">
<h2>  Upload files</h2>
          <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="50000000">
            <input name="userfile" type="file" id="userfile">
    </p>
          <p>
          <h3>Notes About the Project:</h3>
            <a style="font-family:Geneva, Arial, Helvetica, sans-serif">(Shift+Enter for Next Line . Enter for Next Paragraph) </a>
            <p>
          <textarea name="notes" id="notes"   cols="45" rows="5" >Enter notes about the project here...
          </textarea>
          </p>
   
          </p>
<p>        
            
            <input name="upload" type="submit" class="box" id="upload" value=" Upload " />
          </p>
  </div>
</form>
<div align="center">
  <h2>
    <?php
$uploadDir = '../projects/';
if(!is_dir($uploadDir))
	mkdir($uploadDir); 
$allowed_ext = "sb"; 

if(isset($_POST['upload']))
{
$fileName = $_FILES['userfile']['name'];
$fileName = str_replace (" ", '', $fileName);
$tmpName = $_FILES['userfile']['tmp_name'];
$fileSize = $_FILES['userfile']['size'];
$fileType = $_FILES['userfile']['type'];
$filePath = $uploadDir . $fileName;
$notes=$_POST['notes'];
// Check Entension
$ok = "0";
$extension = pathinfo($_FILES['userfile']['name']);

$extension = $extension['extension'];

$allowed_paths = explode(", ", $allowed_ext);

for($i = 0; $i < count($allowed_paths); $i++) {

			if ($allowed_paths[$i] == "$extension") {
			
			$ok = "1";
			
			}

		}//end of for

			if($ok == "1")
			{
				include 'db.php';
				
				if(!get_magic_quotes_gpc())
				{
				$fileName = addslashes($fileName);
				$filePath = addslashes($filePath);
				}
					$sql = "SELECT name from `scratch_files` WHERE name='$fileName'";
					$result=mysql_query($sql) or die('Error, query failed : ' . mysql_error());
					if(mysql_num_rows($result)!=0)
					{
						echo "Error - File with same name already exists";
					}
					else
					{
							$query = "INSERT INTO `scratch_files` (name, size, path, notes ) ".
							"VALUES ( '$fileName', '$fileSize', '$filePath', '$notes')";
			
							mysql_query($query) or die('Error, query failed : ' . mysql_error());
						
							mysql_close($conn);
						
			
							$result = move_uploaded_file($tmpName, $filePath);
							if (!$result)
      						 {
							echo "Error uploading file - check the file size";
							exit;
							}
			
				
							echo "<br>File uploaded<br>";
					}
			}
	else
	{
	echo "Incorrect extension - or exceeding file size";
	}
}
?>
  </h2>
  <p><a href="index.php" target="_top">Admin Home</a></p>
</div>
<h3 align="center">&nbsp;</h3>
