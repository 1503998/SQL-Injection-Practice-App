<?php
include 'authenticate.php';
include 'db.php';
include '../library/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Scratch Administrator</title>
<link rel="stylesheet" type="text/css" href="../library/links.css" />

<link rel="stylesheet" type="text/css" href="../library/flyout_one.css" />

</head>

<body background="../images/brick_bg.gif">
<h2>Welcome <a style="color:#FF3399" > <?php echo get_name($_SESSION['username']);  ?></a></h2>
<p><a style="font-size:20px" href="rightframe.php" target="mainFrame">Dashboard</a></p>
<b>
<ul id="flyout" >
	
      <li>  <a  href=".." target="_blank">Scratch Home</a></li>
   <li> <a class="fly"  href=".." target="_blank">Projects</a>
   	 <ul>   
      <li>  <a  href="upload.php" target="mainFrame"> Upload </a></li>
      <li>  <a href="edit.php" target="mainFrame">Edit Name &amp; Notes </a></li>
      <li>  <a class="last" href="remove.php" target="mainFrame">Delete</a></li>
      </ul>
    </li>    

        <li><a href="comments.php" target="mainFrame">Comments</a></li>
        <li><a class="fly" href="chaccount.php" target="mainFrame">Change Account</a>
        	<ul><li>  <a  href="chaccount.php?password" target="mainFrame"> Change Password </a></li>
      			<li>  <a class="last" href="chaccount.php?account" target="mainFrame">Account</a></li>
      
            </ul>
  </li>
       <li> <a href="logout.php" target="_top">Logout </a></li>
</ul>
</b>
</body>
</html>