<?php
include 'authenticate.php';
		include 'db.php';
		include '../library/functions.php';
		$username=$_SESSION['username'];
		
//CHANGE PASSWORD
		if(isset($_POST['submit'])){
			$old_pass=$_POST['old'];
			$new_pass=$_POST['check1'];
		$passcode = encode($old_pass);
		$sql="SELECT * FROM scratch_users WHERE username='$username' and password='$passcode'";
		$result=mysql_query($sql) or die('Query failed');
		$count=mysql_num_rows($result);
			if($count!=1){
				echo "<center><h3>Wrong Password!Please enter again.</h3><center><br>";
			}
			else {
			$new_passcode=encode($new_pass);
			$sql="UPDATE scratch_users SET password='$new_passcode' WHERE username='$username'";
			$result=mysql_query($sql) or die('Query failed'.mysql_error());
				echo "<center><h3>Password updated successfully!</h3></center><br>";
			}
		}


//CHANGE ACCOUNT

		if(isset($_POST['change_acc']))
		{
		$new_email = $_POST['email'];
		$new_name  = $_POST['name'];
		$new_siteurl  = $_POST['siteurl'];
		
			
				$sql="UPDATE scratch_users SET email='$new_email', name='$new_name', siteurl='$new_siteurl' WHERE username='$username' LIMIT 1";
				$result=mysql_query($sql) or die('Query failed');
					
				echo "<center><h3>Account updated successfully!</h3></center><br>";
			
		}
?>
<html><head>
<title>Change Password</title>
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
	if(thisform.name=="password")
	{
		with (thisform)
		  {
			  if (is_empty(old,"Current password field is blank!"))
			  {
				return false;}
			  else if (is_empty(check1,"New password field is blank!"))
			  {
				return false;}
			  else if (is_empty(check2,"Retype password filed is blank!"))
			  {
				return false;
			  }
			  else if(check1.value!=check2.value){
				alert("Passwords do not match!");
				check2.focus();
				return false;
			  }
			  else if (old.value==check2.value){
				alert("Type a new password!");
				check1.focus();
				return false;
			  }
			  else if(check1.value.length<4){
				alert("Password is too short!");
				check1.focus();
				return false;
			  }
			  
			}
	}
	else 
	{
		with (thisform)
		  {
			  if (is_empty(name,"Please enter your name!"))
			  {
				return false;}
			  else if (is_empty(email,"Please enter your email!"))
			  {
				return false;}
			  else if (is_empty(siteurl,"Please enter your site Address(URL)"))
			  {
				return false;
			  }
			  else if (echeck(email.value)==false)
			  {
				email.focus()
					return false
			  }
				return true;
		  }
	}  
}
</script>
</head><body>
<center>
<?php 
if($_SERVER['QUERY_STRING']==""||$_SERVER['QUERY_STRING']=="password")
{
?>
    
    <h2 align="center">Change Password</h2>
    <table align="center" border="0">
        <form name="password"  onSubmit="return validate_form(this)" action=""  method="post" >
            
            <tbody><tr><td align="right">Type Current Password : </td><td><input name="old" type="password"></td></tr>
            
            <tr><td align="right">Type New Password :</td><td><input name="check1" type="password"></td>
            </tr>
            
            <tr><td align="right">Retype Password : </td><td><input name="check2" type="password"></td><tr><td></td><td><input value="Submit" name="submit" type="submit"></td></tr></tr>
        
    </tbody>
    </form></table>
    <br>

<?php
	}
?>

<?php 
if($_SERVER['QUERY_STRING']==""||$_SERVER['QUERY_STRING']=="account")
{
?>
<p >
<h2 align="center">Change Account</h2>
<form name="account" onSubmit="return validate_form(this)" method="post" action="">
	<table width="362" height="112" border="0" cellpadding="1" cellspacing="1">
      <tr>
        <td width="175"><div align="right">Your Name</div></td>
        <td width="180"><input name="name" type="text" size="30" value="<?php echo get_name($username) ?>"></td>
      </tr>
      <tr>
        <td><div align="right">Email</div></td>
        <td>
        <input name="email" type="text" size="30" value="<?php echo get_email($username) ?>"></td>
      </tr>
      <tr>
        <td><div align="right">Site Address(URL)</div></td>
        <td><input name="siteurl" type="text" size="30" value="<?php echo get_siteurl() ?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="change_acc" type="submit" value="Submit"></td>
      </tr>
    </table>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
</form>
</p>
<?php
	}
?>
<h3 align="center"><a href="index.php" target="_top">HOME</a></h3>
</center>
</body></html>
