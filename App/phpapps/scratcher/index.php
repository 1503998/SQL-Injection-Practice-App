<?php
	if(!file_exists("admin/db.php"))
	{?>
		<h2>Database configuration file admin/db.php does not exists</h2>
		<p style="font-family:'Courier New', Courier, monospace;font-size:22px">
			Click  <a href="admin/installer.php?step=1">&gt;&gt;&gt;here</a> to create it</p>
	<?php 
	exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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

</style>
<title>Scratch Projects</title>
<script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>
<body background="images/brick_bg.gif" onload="MM_preloadImages('images/scratch_cat.jpg')">
<?php 
include 'admin/db.php';
include 'library/functions.php' 
?>
<h2 style="font-family:Arial, Helvetica, sans-serif;color:#FF8000">My Scratch Projects - <?php echo get_name(users()); ?></h2>

<table width="790" border="0">
  <tr>
    <td width="703"><?php


$query = "SELECT id, name FROM `scratch_files`";
$result = mysql_query($query) or die('Error, query failed');
if(mysql_num_rows($result) == 0)
{
echo "<h3>Projects not yet updated .  <br> Visit back later!</h3>" ;
}
else
{
?>
      <table>
        <tr>
          <td><ul style="font-size:20px">
              <?php
while(list($id, $name) = mysql_fetch_array($result))
{
$array=explode('.',$name);
$name=$array[0];
echo "<li style=\"list-style:disc\"><a  href='projects.php?show=$name&id=$id'>$name</a></li>";


}
?>
          </ul></td>
        </tr>
      </table>
      <p>
        <?php
}

 ?>
      </p></td>
    <td width="77"><div align="right"><img src="images/tiger.gif" alt="image" width="65" height="192" /></div></td>
  </tr>
</table>
<p style="color:#CC0000;font-size:24px">To know more about scratch click <a href="http://info.scratch.mit.edu/About_Scratch" target="_blank">here</a>

<table background="images/bg_sidecontainer.png" width="286" border="0" >
  <tr>
    <td width="282" height="73"><div align="center"><a href="http://scratch.mit.edu" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('scratch','','images/scratch_cat.jpg',1)"><img src="images/scratch_logo.jpg" alt="Scratch" name="scratch" width="213" height="71" border="0" id="scratch" /></a></div></td>
  </tr>
  <tr>
    <td height="28"><a align="center" style="color:#3060C1;font-size:24px"><b>imagine &bull; program &bull; share</b></a></td>
  </tr>
</table>
</p>

<p  style="background:url(images/bg_footer.jpg);width:400px" > &nbsp;&nbsp;<a style="font-family:Arial, Helvetica, sans-serif" href="admin">Admin Login </a><br>
    <b style="font-size:18px;color:#9900CC">Copyright&copy; <?php echo get_name(users()); ?></b><br>

<i style="color:#666600;font-family:Georgia, 'Times New Roman', Times, serif">Powered by <a href="https://sourceforge.net/projects/scratcher" target="_blank">Scratcher</a> Developed by <a href="http://satys.co.cc/blog	" target="_blank">Satyadeep</a></i></p>

</body>

</html>
