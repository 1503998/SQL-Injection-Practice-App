<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./includes/config.php');

	if(isset($_GET['reset'])) {
		//check to see if any fields were left blank
		if((!$_POST[username]) || (!$_POST[email])) {
			echo "A field was left blank please go back and try again.";
		}else{
			//gets rid of bad stuff from there username and email
			$username = addslashes(htmlspecialchars($_POST['username'])); 
			$email = addslashes(htmlspecialchars($_POST['email'])); 
			
			//Query table for data
			$query = mysql_query("SELECT login,email FROM ". $mysql_prefix ."users WHERE login = '$username'"); 
			$rows= mysql_num_rows($query); 
			while($row = mysql_fetch_assoc($query)){
				$cname = $row['login'];
				$cemail = $row['email'];
			}
			
			//Check if the user and email combination exists
			if($rows < 1) { 
				echo 'That username does not exist';
			} elseif ($email != $cemail) {
				echo 'Incorrect username and Password <br />';
			} else {
				//Reset their Pasword
				
				//Create a New Password
				$defaults = config_defaults();	
   				$SaltPass = $defaults['conf_salt_password']; //SaltPass is already sha1 hashed once in db.
   				$NewPass = genPassword(7,7,2,2,0);
				$password = sha1($NewPass);
				$Sha1Pass = sha1($SaltPass . $password);
				
				//Update $Sha1Pass into database
				$query2 = mysql_query("UPDATE ". $mysql_prefix ."users SET password = '{$Sha1Pass}' WHERE login = '{$username}' AND email = '{$email}' ") or die(mysql_error());	
				
				//Send User an email with their new password
				$EmailTo = "{$email}";
				$EmailFrom = "WebMaster@{$_SERVER['SERVER_NAME']}";
				$EMailFromName = "Webmaster";
				$EMailSubject = "NetRisk: Password Reset";
				$EmailMessage = "A user at this email address requested a password be reset for {$_SERVER['SERVER_NAME']} . <br /><br />";
				$EmailMessage .= "Your new password is: {$NewPass}. <br /><br />";
				$EmailMessage .= "It is recommended that you immediately login and change your password. <br />";
	
				SendEmail($EmailTo,$EmailFrom,$EmailFromName,$EMailSubject,$EmailMessage);  //Common Function
				
				echo 'An email has been sent to you with your new password information.';
			}				
		}
		
	}else{
		// Let the User Rest Their Password
		echo "<form action='forgot_password.php?reset' method='post'>
		<table width='350'>
 		<tr>
    		<th colspan='2' width='350'>NetRisk Reset Password</th>
  		</tr>
  		<tr>
    		<td width='150'>Username:</td>
    		<td width='200'><input type='text' name='username' size='30' maxlength='25'></td>
  		</tr>
  		<tr>
    		<td>Email:</td>
    		<td><input type='text' name='email' size='30' maxlength='55'></td>
  		</tr>
  		<tr>
    		<td colspan='2'><center><input type='submit' value='Submit'></center></td>
  		</tr>
		</table>
		</form>";
	}
?>	