<?
session_start();
include ("include/header.php");
dbconnect();

// Define post fields into simple variables
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email_address = $_POST['email_address'];
$username = $_POST['username'];
$info = $_POST['info'];
$password  = $_POST['password'];

/* Let's strip some slashes in case the user entered
any escaped characters. */

$first_name = stripslashes($first_name);
$last_name = stripslashes($last_name);
$email_address = stripslashes($email_address);
$username = stripslashes($username);
$info = stripslashes($info);
$password = stripslashes($password);


/* Do some error checking on the form posted fields */

if((!$first_name) || (!$last_name) || (!$email_address) || (!$username) || (!$password)){
    echo 'You did not submit the following required information! <br />';
    if(!$first_name){
        echo "First Name is a required field. Please enter it below.<br />";
    }
    if(!$last_name){
        echo "Last Name is a required field. Please enter it below.<br />";
    }
    if(!$email_address){
        echo "Email Address is a required field. Please enter it below.<br />";
    }
    if(!$username){
        echo "Username is a required field. Please enter it below.<br />";
    }
	if(!$password){
		echo "Password is a required field. Please enter it below.<br />";
	}
    include 'register.php'; // Show the form again!
    /* End the error checking and if everything is ok, we'll move on to
     creating the user account */
    exit(); // if the error checking has failed, we'll exit the script!
}
    
/* Let's do some checking and ensure that the user's email address or username
 does not exist in the database */
 
 $sql_email_check = mysql_query("SELECT email_address FROM eb_members 
             WHERE email_address='$email_address'");
 $sql_username_check = mysql_query("SELECT username FROM eb_members 
             WHERE username='$username'");
 
 $email_check = mysql_num_rows($sql_email_check);
 $username_check = mysql_num_rows($sql_username_check);
 
 if(($email_check > 0) || ($username_check > 0)){
     echo "Please fix the following errors: <br />";
     if($email_check > 0){
         echo "<strong>Your email address has already been used by another member 
         in our database. Please submit a different Email address!<br />";
         unset($email_address);
     }
     if($username_check > 0){
         echo "The username you have selected has already been used by another member 
          in our database. Please choose a different Username!<br />";
         unset($username);
     }
     include 'register.php'; // Show the form again!
     exit();  // exit the script so that we do not create this account!
 }

$random_password = $password;

$db_password = md5($random_password);

// Enter info into the Database.
$info2 = htmlspecialchars($info);
$sql = mysql_query("INSERT INTO eb_members (first_name, last_name, 
        email_address, username, password, info, signup_date)
        VALUES('$first_name', '$last_name', '$email_address', 
        '$username', '$db_password', '$info2', now())") 
        or die (mysql_error());
$sql2 = mysql_query("INSERT INTO `eb_profile` ( `name` , `logo` , `rank` , `email` , `msn` , `yahoo` , `icq` , `aim` , `location` , `website` , `intr` , `alias` , `age` , `mpad` , `hps` , `mouse` , `cpu` , `mboard` , `ram` , `monit` , `gpcard` , `id` )
VALUES (
'X', '', '1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
);
") or die(mysql_error());
$sql3 = mysql_query("INSERT INTO `eb_psettings` ( `UserID` , `h_mail` , `s_pm` , `s_update` , `p_sig` , `p_avy` )
VALUES (
'', '1', '1', '1', '1', '1'
)");
 // Forum Config Query //
 		$db_query = "SELECT * FROM `eb_settings`";
$connect_db = mysql_query($db_query);
$db_get = mysql_fetch_array($connect_db);
$server_reg = $db_get['register'];

if ( $server_reg == "0" ) {
	if(!$sql){
   		echo 'There has been an error creating your account. Please contact the webmaster.';
	} else {
    	$userid = mysql_insert_id();
    	// Let's mail the user!
    	$subject = "Registration on $forum";
   		 $message = "Dear $first_name $last_name,
   	 Thank you for registering at our website, ". $board_address ."
    
    You are two steps away from logging in and accessing our exclusive members area.
    
    To activate your membership, 
    please click here:" .  $board_address . "activate.php?id=". $userid ."&code=" . $db_password ."
    
    Once you activate your memebership, you will be able to login
    with the following information:
    Username: ". $username ."
    Password: ". $random_password ."
    
    Thanks!
    The Webmaster
    
    This is an automated response, please do not reply!";
    
    mail($email_address, $subject, $message, 
        "From: EvilBoard Admin<admin@". $domain .">\n
        X-Mailer: PHP/" . phpversion());
    echo 'Your membership information has been mailed to your email address! 
    Please check it and follow the directions!';
	}
}
elseif ( $server_reg = "1" ) {
echo '<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_menu1"><div align="center">Registration Complete. </div></td>
  </tr>
  <tr>
    <td class="forum_footer"><p align="center">You may login now. <br></p></td>
  </tr>
</table><br>';
mysql_query("UPDATE eb_members SET activated='1' WHERE username='$username'");
include "login.php";
};
showfooter();
?> 