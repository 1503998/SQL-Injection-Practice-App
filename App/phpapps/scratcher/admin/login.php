<?php
include '../library/functions.php';
if(isset($_POST['submit']))
{
	include 'db.php';
	$username=$_POST['myusername'];
	$password=$_POST['mypassword'];
	$username = stripslashes($username);
	$password = stripslashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	$passcode = encode($password);
	$sql="SELECT * FROM scratch_users WHERE username='$username' and password='$passcode'";
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);
	
	if($count==1) 
	{
		session_start();
		$_SESSION['flag']=1;
		$_SESSION['username']=$username;
		$token="hahahathisisencrypted".$_SERVER['REMOTE_ADDR'];
		$_SESSION['token']=sha1(md5($token)); 
		header("Location:index.php");
		exit;
	}
	else
	{
	$message="Invalid username or password";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
<meta name="robots" content="noindex,nofollow">

<title>Login</title>
<style type="text/css">
<!--
.style6 {font-family: "Courier New", Courier, monospace}
-->
</style>
</head>

<body text="#000000" bgcolor="#ffffff" link="#0000cc" vlink="#0000cc" alink="#0000cc" onLoad="squirrelmail_loginpage_onload();">
<form  action="login.php" method="post">
<table bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td align="center"><center>
<br />
  <h1 style="color:blue;font-size:350%"><small><span class="style6">Scratch Administrator Login</span><br />
  </small></h1>
<table bgcolor="#ffffff" border="0" width="350"><tr><td>
</tr>
<tr><td bgcolor="#ffffff" align="left">
<table bgcolor="#ffffff" align="center" border="0" width="100%"><tr>
  <td align="right" width="30%">Username:</td>
<td align="left" width="*"><input type="text" name="myusername" value="" /></td>
</tr>

<tr><td align="right" width="30%">Password:</td>
<td align="left" width="*"><input type="password" name="mypassword" /></td>
</tr>
</table>
</td>
</tr>
<tr><td align="left"><center><input name="submit" type="submit" value="Login" />
</center></td>
</tr>
</table>
<h2><?php echo $message ?>&nbsp;</h2>
<p><a href="forgotpasswd.php" title="Sends password to your email">Forgot Password ?</a></p>
<p><a style="color:#FF0000;font-size:24px" href="..">Scratch Home</a></p>
<p>&copy <?php include 'db.php';
			echo  get_name(users()); ?></p>
</center></td>
</tr>
</table>
<tr><td colspan="2"><br /><center>
</center>
</td></tr></form>

</body></html>
