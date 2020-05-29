<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./includes/config.php');

	//checks if there trying to veriy there account
	if(isset($_GET['verify'])) {
		//gets the code and makes it safe
		$code = addslashes($_GET['code']);
		//gets the code from the database
		$getcode=mysql_query("SELECT * FROM `verification` WHERE `code` = '$code'");
		//counts the number of rows
		$getcode = mysql_num_rows($getcode);
		//if the ammount of rows is 0 the code does not exist
		if($getcode == 0) { 
			echo "Invalid verification code!"; 
		} else {
			//or if the code does exist we will activiate there account
			//get the data from the database
			$getcode=mysql_query("SELECT * FROM `verification` WHERE `code` = '$code'");
			//fetchs the data from the db
			$dat = mysql_fetch_array($getcode);
			//sets the users user level to 2 which means they can now use there account
			$update = mysql_query("UPDATE `members` SET `userlevel` = '2' WHERE `username` = '".$dat['username']."'") or die(mysql_error());
			//deletes the code as there is no use of it now
			$delete = mysql_query("DELETE FROM `verification` WHERE code = '$code'");
			//says thanks and your account is ready for use
			echo "Thank you, Your account has been verified.";
		}
	} else 
		//if we have posted the register for we will register this user
			if(isset($_GET['register'])) {
			//check to see if any fields were left blank
				if((!$_POST[username]) || (!$_POST[password]) || (!$_POST[cpassword]) || (!$_POST[email])) {
					echo "A field was left blank please go back and try again.";
				}else{
					//posts all the data from the register form
					$username = $_POST[username]; 
					$password = $_POST[password]; 
					$cpassword = $_POST[cpassword]; 
					$email = $_POST[email];
					//check see if the 2 passwords are the same
					if($password == $cpassword){
						//encrypts the password
						$defaults = config_defaults();	
						$SaltPass = $defaults['conf_salt_password']; //SaltPass is already sha1 hashed once in db.	
						$password = sha1($cpassword);
						$password = sha1($SaltPass . $password);
				 
						//$cname = mysql_query("SELECT `username` FROM `members` WHERE `username` = '$username'"); 
						$cname = mysql_query("SELECT `login` FROM `netrisk_users` WHERE `login` = '$username'"); 
						$cname= mysql_num_rows($cname); 
						//checks to see if the username or email allready exist
						if($cname>=1) { 
							echo "The username is already in use"; 
						}else{
							//gets rid of bad stuff from there username and email
							$username = addslashes(htmlspecialchars($username)); 
							$email = addslashes(htmlspecialchars($email));

							if($semail == "1") { // $email set as 1 means email activation is active
								//adds them to the db
								$adduser = mysql_query("INSERT INTO `netrisk_users` (`login`, `password`, `email`) VALUES('$username','$password','$email')");
								//posible letters for the verification code
								$alphanum  = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
								//shuffles the letters around to create a 16 long code
								$code = substr(str_shuffle($alphanum), 0, 16); 
								//adds there code along with there user name to the db
								$addcode = mysql_query("INSERT INTO `verification` (`username`, `code`) VALUES('$username','$code')");
								//don't edit this, this is the link for there activication
								$link = "http://$host$self?verify&code=$code";
								//sends the email to the person
								mail("$email", "Member-Ship Validation", "Thank you for registering on $sitename.
								Please copy the below link into you address bar,
	
								$link", "From: Site Verification");
								//message sent now lets tell them to check there email
								echo "You are now registered,<br><br>Please check your email to activate your account.";
							}else{ //no need for email activation
								$adduser = mysql_query("INSERT INTO `netrisk_users` (`login`, `password`, `email`, `rank`) VALUES('$username','$password','$email','2')");
								//$adduser = mysql_query("INSERT INTO `members` (`username`, `password`, `email`, `userlevel`) VALUES('$username','$password','$email','2')");
								echo "You are now registered,<br><br>You can now loggin to your account";
							}
						}
				}else{
					echo "Your password and conformation password do not match!";
			}
		}
	}else{
		//none of the above so lets show the register form
		echo "<form action='register.php?register' method='post'>
		<table width='350'>
		 	<tr>
    			<th colspan='2' width='350'>NetRisk Registration</th>
  			</tr>
  			<tr>
    			<td width='150'>Username:</td>
    			<td width='200'><input type='text' name='username' size='30' maxlength='25'></td>
  			</tr>
  			<tr>
	    		<td>Password:</td>
    			<td><input type='password' name='password' size='30' maxlength='25'></td>
  			</tr>
  			<tr>
    			<td>Confirm Password:</td>
    			<td><input type='password' name='cpassword' size='30' maxlength='25'></td>
  			</tr>
  			<tr>
    			<td>Email:</td>
    			<td><input type='text' name='email' size='30' maxlength='55'></td>
  			</tr>
  			<tr>
    			<td colspan='2'><center><input type='submit' value='Register'></center></td>
  			</tr>
		</table>
		</form>";
	}
?> 