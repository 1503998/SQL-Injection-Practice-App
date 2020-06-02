<?php 
include '../library/functions.php';
if(!isset($_GET['step']))
	header("Location:?step=1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Scratch Installer &gt;Step <?php echo $_GET['step'] ?></title>
<script type="text/javascript">
function is_empty(field,alerttxt)
{
with (field)
  {
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

function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID")
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

 		 return true					
	}
function validate_form(thisform)
{
with (thisform)
  {
	  if (is_empty(username,"Username field is blank!"))
	  {
	  	return false;}
		
	  else if (is_empty(password,"Password field is blank!"))
	  {
	  	return false;}
	  else if (is_empty(repassword,"Retype password field is blank!"))
	  {
	  	return false;
	  }
	  else if (is_empty(name,"Name field is blank!"))
	  {
	  	return false;}
		else if (is_empty(email,"Email field is blank!"))
	  {
	  	return false;}
		else if(password.value!=repassword.value){
	  	alert("Passwords do not match!");
	  	return false;
	  }
	  else if(password.value.length<4){
	  	alert("Password is too short!");
	
		return false;
	  }
	  else if (echeck(email.value)==false)
	  {
				email.focus()
					return false;
	  }
	  
  }
}
</script>
</head>

<body>
<center>
<h1 style="font-family:Georgia, 'Times New Roman', Times, serif;color:#00CCFF">Scratcher Installer</h1>

<?php 


//FINDING THE URL OF THE SCRATCH FOLDER
		$url="http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		$curdir=basename(dirname($_SERVER['PHP_SELF']));
		$url=str_replace("/$curdir","",$url);

?>
<p style="font-size:20px" >Site:<a href=" <?php echo $url; ?>"> <?php echo $url; ?></a> 
</p>
<?php
if($_GET['step']=="1")
{	
	$dbfile="db.php";

	if(isset($_POST['dbsubmit']))
	{
		$dbhost=$_POST['dbhost'];
		$dbuser=$_POST['dbuser'];
		$dbpass=$_POST['dbpass'];
		$dbname=$_POST['dbname'];

		if(!mysql_connect($dbhost, $dbuser, $dbpass))
		{?>
			<h2>Error connecting to the MySql Database</h2><br>
			<p style="font-family:'Courier New', Courier, monospace;font-size:22px">
			1) Check the username and password<br>
			2) Check the host/server of your MySql<br></p>
		<?php	
			exit;
		}
		if(!mysql_select_db($dbname))
		{?>
			<h2>Error connecting to the MySql Database</h2><br>
			<p style="font-family:'Courier New', Courier, monospace;font-size:22px">
            1) Does the Database exists?<br>
			2) Check the username and password?<br>
			3) Check the host<br></p>
		<?php	
			exit;
		}

		$fp1=fopen($dbfile,"w");
		$string= "<?php\n
				\$dbhost = \"$dbhost\";\n
				\$dbuser = \"$dbuser\";\n
				\$dbpass = \"$dbpass\";\n
				\$dbname = \"$dbname\";\n
				\$conn = mysql_connect(\$dbhost, \$dbuser, \$dbpass) or die ('Error connecting to mysql');\n
				mysql_select_db(\$dbname) or die ('Could not Select the database:' . \$dbname);\n
				?>";
		fputs($fp1,$string);
		fclose($fp1);
		
		

include 'db.php';
	if(is_table_exists("scratch_files")||is_table_exists("scratch_users")||is_table_exists("scratch_comments"))
	{
			echo "<h2>Looks like you have already installed Scratcher</h2>";
			echo "<p style='font-size:22px'>Clear the database tables `scratch_files`, `scratch_comments`, `scratch_users`<br> ";
		    echo "And then <a href='javascript:history.go(-1)'>Go back</a></p>";
			exit;
	}
		
// CREATING TABLE scratch_files
$sql = "CREATE TABLE IF NOT EXISTS `scratch_files` (\n"
    . " `id` int(11) NOT NULL AUTO_INCREMENT,\n"
    . " `name` varchar(80) NOT NULL,\n"
    . " `notes` text NOT NULL,\n"
    . " `size` int(11) NOT NULL,\n"
    . " `path` varchar(100) NOT NULL,\n"
    . " PRIMARY KEY (`id`)\n"
    . ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$result=mysql_query($sql) or die("Error:query failed" . mysql_error());
//CREATING scratch_comments
$sql2 = "\n"
    . "CREATE TABLE IF NOT EXISTS `scratch_comments` (\n"
    . " `id` int(20) NOT NULL AUTO_INCREMENT,\n"
    . " `project_id` int(20) NOT NULL,\n"
    . " `author` varchar(30) NOT NULL,\n"
    . " `author_email` varchar(50) NOT NULL,\n"
    . " `comment` text NOT NULL,\n"
    . " `author_ip` varchar(100) NOT NULL,\n"
    . " `date` varchar(20) NOT NULL,\n"
    . " PRIMARY KEY (`id`)\n"
    . ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$result2=mysql_query($sql2) or die("Error:query failed" . mysql_error());
//CREATING scratch_users
$sql3 = "CREATE TABLE IF NOT EXISTS `scratch_users` (\n"
    . " `id` int(20) NOT NULL AUTO_INCREMENT,\n"
	. "`siteurl` varchar(80) NOT NULL COMMENT 'the host url',\n"
    . " `username` varchar(60) NOT NULL,\n"
    . " `password` text NOT NULL,\n"
    . " `name` varchar(50) NOT NULL,\n"
    . " `email` varchar(100) NOT NULL,\n"
    . " `key` varchar(60) NOT NULL,\n"
	. " `status` varchar(30) NOT NULL DEFAULT 'DISABLED' COMMENT 'Password Changing', \n"
    . " PRIMARY KEY (`id`),\n"
    . " UNIQUE KEY `username` (`username`), \n"	
	. " UNIQUE KEY `email` (`email`) \n"
    . ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$result3=mysql_query($sql3) or die("Error:query failed" . mysql_error());

		?>
       <h2> Database Updated </h2>
       <p style="font-family:Arial, Helvetica, sans-serif;color:#00CC00;font-size:22px">
       Proceed forward to create administrator account <a href="installer.php?step=2">&gt;&gt;&gt;Next</a></p>
        <?php
		
		
	}
	else
	{
		////CHECKING WHETHER ALREADY INSTALLED
	
		if(file_exists("db.php"))
		{
			echo "<h2>Looks like you have already installed Scratcher</h2>";
			echo "<p style='font-size:22px'>1) Please delete configuration file admin/db.php<br> ";
			echo "   2) Clear the database tables </p><br> ";
			exit;
		}
	

	
?>
<script>alert("Warning:Do not install over a existing installation!");</script>
<form id="form1" name="form1" method="post" action="">
  <h2>MySql Database Details </h2>
  <p style="font-size:18px">Configuration file /admin/db.php</p>
  <table width="518" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td width="101" height="36"><div align="right">Host/Server</div></td>
      <td width="150"><input type="text" name="dbhost" id="dbhost" /></td>
      <td width="257">The Host of your MySql</td>
    </tr>
    <tr>
      <td height="38"><div align="right">Username</div></td>
      <td><input type="text" name="dbuser" id="dbuser" /></td>
      <td>(Your MySql Username)</td>
    </tr>
    <tr>
      <td height="41"><div align="right">Password</div></td>
      <td><input type="password" name="dbpass" id="dbpass" /></td>
      <td>(Your MySql Password) </td>
    </tr>
    <tr>
      <td height="48"><div align="right">Database name </div></td>
      <td><input type="text" name="dbname" id="dbname" /></td>
      <td>(The Database where you want to store )</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="dbsubmit" id="dbsubmit" value="Submit" /></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  </form>
<?php
	}
}
?>

<?php 
	if($_GET['step']=="2")
	{
		if(!file_exists("db.php"))
		{?>
			<h2>Error Database configuration file doesnt exist</h2><br>
			<p style="font-family:'Courier New', Courier, monospace;font-size:22px">
			Go back to step 1 <a href="installer.php?step=1">&lt;&lt;&lt;Back</a></p>
		<?php 
		exit;
		}
		else
		{
			include 'db.php';
			$sql="SELECT * FROM `scratch_users` ";
			$result=mysql_query($sql) or die('Error: query failed');
			$count=mysql_num_rows($result);
			if($count!=0)
			{
			echo "<h2>Administrator Account already exists</h2>";
			echo "<p style='font-size:22px'>Clear the database tables first<br> ";
			
			exit;
			}
		}
		
		
		if(isset($_POST['submit2']))
		{
				include 'db.php';
				
				if($_POST['password']!=$_POST['repassword'])
				{
					echo "<h2>Error:Passwords do not match Go back</h2>";exit;
				}
				$username=$_POST['username'];
				$pass=$_POST['password'];
				$password=encode($_POST['password']);
				$name=$_POST['name'];
				$email=$_POST['email'];
			$sql = "INSERT INTO `scratch_users` (`id`, `siteurl`, `username`, `password`, `name`, `email`, `key`, `status`) VALUES (NULL, '$url', '$username', '$password', '$name', '$email', '', 'DISABLED');";
			$result=mysql_query($sql) or die("Error:query failed" . mysql_error());

//SENDING EMAIL USERNAME AND PASSWORD
		$host=$_SERVER['HTTP_HOST'];
				$to      = $email;
				$subject = $host.':Your Scratch Account Created';
				$message = "Your Scratch Password on $host \r\nUsername: $username \r\n Password: $pass \r\n" .
							" Login here $url/admin  \r\n". 
							"Thank You ";
							
							
					
						$headers = 'From: scratch@'. $host . "\r\n" .
							'Reply-To: noreply@'. $host . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
						
				if(mail($to, $subject, $message, $headers))
				{
					echo "<h2>Password sent to your email address </h2>";
				}
				else
				{
					echo "<h2>Error in sending password to your email</h2>";
				}
	//END OF MAILING
			?>
            <h2>Install Complete</h2>
            <p style="font-family:'Courier New', Courier, monospace;font-size:22px">
            Your Account Username and Password have been sent to your email<br>
            Login here  <a href="login.php">&gt;&gt;&gt;Admin Login</a>
            </p>
            <?php
			
		}		
		else
		{

	?>
  <h2>Administrator Account</h2>    
		  
	<form id="form2" onSubmit="return validate_form(this)" name="form2" method="post" action="" >
	  <p>&nbsp;</p>
	  <table width="315" height="157" border="0" cellpadding="1" cellspacing="1">
        <tr>
          <td>Username </td>
          <td><input type="text" name="username" id="username" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Password </td>
          <td><input type="password" name="password" id="password" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Retype password</td>
          <td><input type="password" name="repassword" id="repassword" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Full Name</td>
          <td><input type="text" name="name" id="name" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type="text" name="email" id="email" /></td>
          <td>&nbsp;</td>
        </tr>
      </table>
	  <p>
	    <input type="submit" name="submit2" id="submit2" value="Submit" />
	  </p>
	  <p>&nbsp;    </p>
	</form>
	<?php	
		}
	}
?>
</center>
</body>
</html>