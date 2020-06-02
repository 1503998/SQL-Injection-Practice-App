<?php
include 'library/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="images/favicon.gif"> 
<title><?php echo $_GET['show']?></title>
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
</head>
<body background="images/background.jpg">
<center>
<h2>Click green flag to start - Red to stop</h2>
<table width="580" cellspacing="0" border="1" bgcolor="#FFFFCC">
  <tr>
    <td width="175" style="font-family:Geneva, Arial, Helvetica, sans-serif;color:#9900FF"><b><?php echo $_GET['show']?> &nbsp;</b></td>
    <td width="154"><div align="center"><a href="projects/<?php echo $_GET['show']?>.sb">Download</a> as .sb file</div></td>
    <td width="117"  ><div align="center"><a style="color:#FF0000;font-size:18px" href="comments.php?project=<?php echo $id = $_GET['id'] ?>" target="_blank"><?php echo no_of_comments($_GET['id']) ?> Comments</a></div></td>
    <td width="116"><div align="right"><a href="index.php">back</a></div></td>
  </tr>
</table>
<div align="center">

  <table  width="90%" >
    <tr >
      <td width="550"><center><applet id="ProjectApplet" style="display:block" code="ScratchApplet" codebase="./library/" archive="ScratchApplet.jar" height="387" width="482">
        <param name="project" value="../projects/<?php echo $_GET['show']?>.sb">
        </applet></center></td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif;"  ><h3>Notes About the Project:</h3>
        <?php 
if(isset($_GET['id'])){

	include 'admin/db.php';
	$id = $_GET['id'];
	$query = "SELECT * FROM `scratch_files` where id=$id";
	$result = MYSQL_QUERY($query);
	$count=mysql_num_rows($result);
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
		$notes=$row['notes'];
		}
		if($count==1){
		//$notes=str_replace("\n","<br>",$notes);
		echo wordwrap($notes,70,"<br>");
		}
	
		else{
		echo "File not found";
		}

}
?></td>
      </tr>
  </table>
</div>
<p><b>Note :</b> The above scratch project needs <a href="http://java.com/en/download/" target="_blank">Java Runtime</a> to play online</p>
<a style="font-size:18px;color:#9900CC" href="<?php echo get_siteurl(); ?>">Copyright&copy; <?php echo get_name(users()); ?></a><br>

</center>
</body>
</html>
<?php
function no_of_comments($project_id)
{
	include 'admin/db.php';
	$query="SELECT * from scratch_comments WHERE project_id=$project_id";
	$result=mysql_query($query) or die("Query failed".mysql_error());
	return mysql_num_rows($result);
}
?>