<?php 
include '../library/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Forgot Password</title>
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

function validate_form(thisform)
{
with (thisform)
  {

	  if (is_empty(check1,"New password field is blank!"))
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
	  else if(check1.value.length<4){
	  	alert("Password is too short!");
		check1.focus();
		return false;
	  }
	  
  }
}
</script>
</head>
<body>
<div align="center">
<h2 style="font-family:Verdana, Arial, Helvetica, sans-serif;color:#CC33CC">Password Reset</h2>
<?php
	if(isset($_SERVER['QUERY_STRING'])&&$_SERVER['QUERY_STRING']!="")
	{
		include 'db.php';
		$key=$_SERVER['QUERY_STRING'];
	//FINDING THE SHA1 HASH OF THE KEY
		 $key=sha1($key);
		 $sql="SELECT * FROM `scratch_users` WHERE `key`='$key' AND `status`='ENABLED' ";
         $result=mysql_query($sql) or die('query failed');
         $count=mysql_num_rows($result);
         if($count==0) 
         {
		 	echo "<h2>Invalid Activation Link</h2>";
			exit;
		 }
		 else
		 {
		 		if(isset($_POST['change']))
				{
						$password=encode($_POST['check1']);		
						$sql1=" UPDATE `scratch_users` SET `password`='$password', `key` = '',`status` = 'DISABLED' WHERE `key`='$key' ";
    			        $result1=mysql_query($sql1) or die('query failed');
						echo "<h3>Your Password changed Successfully</h3>";
						$host=get_siteurl();
						echo " <a href=\"$host/admin/login.php\"> Login here </a>";
						exit;
         
				}
				else
				{
		 		?>
                 
                    <table align="center" border="0">
                    <form name="input"  onSubmit="return validate_form(this)" action=""  method="post" >
                
                <tr><td align="right">Type New Password :</td><td><input name="check1" type="password"></td>
                </tr>
                
                <tr><td align="right">Retype Password : </td><td><input name="check2" type="password"></td>
                <tr><td></td><td><input value="Submit" name="change" type="submit"></td></tr></tr>
            
                </form></table>
				 <?php 
		 		}
			}
    exit;
	}	
	
?>
<a style="font-size:22px;color:#006600">Activation Link to reset your password will be sent to your email</a><br>
	  <?php

    if(isset($_POST['submit']))
    {
    //captcha part
    include 'db.php';
        $username=$_POST['username']; 
            $username=stripslashes($username); 
            $username=mysql_real_escape_string($username);
            $sql="SELECT * FROM scratch_users WHERE username='$username' OR email='$username'";
            $result=mysql_query($sql) or die('query failed');;
            $count=mysql_num_rows($result);
        
        if($count==0) 
        {
            echo "<a style='color:red;font-size:22px'>Invalid username or email</a> Go back";
            exit;		
        }
        
        
                require_once('recaptchalib.php');
                $privatekey = "6LfuugkAAAAAAHKyzlFFqH-Bc2fs-jxLGSlWW_CF";
                $resp = recaptcha_check_answer ($privatekey,
                                                $_SERVER["REMOTE_ADDR"],
                                                $_POST["recaptcha_challenge_field"],
                                                $_POST["recaptcha_response_field"]);
                
                if (!$resp->is_valid) 
                {
                // CODE IS ENTERED WRONG PLEASE TRY AGAIN
                    $code=0;
                    echo "<p style=\"font-size:20px;color:red\">Incorrect Code. Please Enter again</p><p><a href='forgotpasswd.php'>Back</a></p>";
                }
    
    //captcha part ends
                else{
                        while($row=mysql_fetch_assoc($result))
                        {
                            $username=$row['username'];
                            $email=$row['email'];
                            $name=$row['name'];
                            $host=$row['siteurl'];
                        }
                        $key=generate_code();
//echo $key."<br>";///DEBUGGING REMOVE LATER
            // STORING THE KEY AFTER SHA1 ENCRYPTION
                        $key_encrypted=sha1($key);
                        $sql="UPDATE `scratch_users` SET `key` = '$key_encrypted', `status`=\"ENABLED\" WHERE `username`='$username' ";
                        $result=mysql_query($sql) or die('query failed'. mysql_error());
            
    /////MAIL PART STARTS						
                    $to      = $email;
                    $subject = 'Your Scratch Password Recovery on ' . $host;
                    $message = "Scratch Password recovery process for $host \r\n" .
                                "Username: $username \r\n Click on the activation link below to set a new password or ignore this mail\r\n" .
                                "Activation link: $host/admin/forgotpasswd.php?$key  \r\n". 
                                "\r\n You can Login here $host/admin  \r\n". 
                                
                                "Thank You ";
                                
                                
                        
                            $headers = 'From: scratch@'. $_SERVER['HTTP_HOST'] . "\r\n" .
                                'Reply-To: nobody@' . $_SERVER['HTTP_HOST'] ."\r\n" .
                                'X-Mailer: PHP/' . phpversion();
                            
                    if(mail($to, $subject, $message, $headers))
                    {
                        echo "<h2>Activation Link sent to your email address $to</h2>";
                    }
                    else
                    {
                        echo "<h2>Error in sending Activation link to your email</h2>";
                    }
                }
    }
    else
    {
    
    ?>
    </div>
    <form id="form1" name="form1" method="post" action="">
      <div align="center">
        <p>Enter your username or email</p>
        <p>
          <input type="text" name="username" id="username" size="30" />
        </p>
        <p>Enter the code shown:
          <?php 
        require_once('recaptchalib.php');
        $publickey = "6LfuugkAAAAAADre-k0l43Q6rcLRfZUWtX4mqtix"; // you got this from the signup page
        echo recaptcha_get_html($publickey);
        ?>
    
          <input type="submit" name="submit" id="submit" value="Submit" />
          </p>
      </div>
    </form>
    
      <?php
    }
    ?>
<div align="center">
<h3><a href="index.php">Admin</a></h3>
</div>
</body>
</html>