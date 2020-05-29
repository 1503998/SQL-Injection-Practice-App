<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	require_once('../includes/config.php');
	require_once('./function_avatar_upload.php');

	$error_in_up = 0;
	$errors_array = array();

	$userid = $_SESSION['userid'];

	//Check and make sure orignal password matches
	$defaults = config_defaults();	
   	$SaltPass = $defaults['conf_salt_password']; //SaltPass is already sha1 hashed once in db.
	$oldpass = sha1($_POST['oldpass']);
	$oldpassword = sha1($SaltPass . $oldpass);

	$pass1 = sha1(mysql_real_escape_string($_POST['password1']));
	$pass2 = sha1(mysql_real_escape_string($_POST['password2']));

	//Check and make sure password is consistant for both fields
	if($_POST['password1'] != "" || $_POST['password2'] != ""){
		//User Submitted a new password, so check for old and new matches
		if($oldpassword != $_SESSION['password']) {
  			profile_error_header("Incorrect old Password.");
  			exit;
		}elseif($pass1 != $pass2) {
  			profile_error_header("New passwords do not match.");
  			exit;
		} else {
			$NewPass = sha1($SaltPass . $pass2);
  			$details .= " password='$NewPass',";
			}
	}


	//Check if URL was submitted or if image was uploaded
	switch($_POST['avatar_type']) {
  		case 'url':    //check if a url was submitted
                 if(isset($_POST['reg_avatar']) && is_valid_url($_POST['reg_avatar'])) {
                    avatar_url($_POST['reg_avatar']);
                 }
        	break;
  		case 'upload': //Get image and resize
                 // check if a file was submitted
                 if(isset($_FILES['userfile'])) {
                     avatar_upload();
                 }
            break;
	}


	//Also check for correct email syntax
	if ($_POST['email']) { 
		$email = mysql_real_escape_string($_POST['email']);
        //check e-mail
        /*
        if(check_email($email)) {
          $details .= " email='$email',";
        }
        else {
          $error_in_up = 1;
          $errors_array['email'] = 1;
          $errors_array['email_value'] = $email;
        }*/
    	$details .= " email='$email',";
	}

	//Update Bio
	if ($_POST['bio']) { 
		$bio = mysql_real_escape_string($_POST['bio']);
		$details.= " bio='$bio',";
	}

	if($error_in_up) {
  	//$errors_array['bio_up'] = $_POST['up_bio'];
  		foreach($errors_array as $value => $key) {
    		$errors_output .= "&$value=$key";
  		}
  		header("Location: ../index.php?p=profile?$errors_output");
	} else {
  		$details = substr($details, 0, strlen($details)-1);
  		$sql = "UPDATE ". $mysql_prefix ."users SET ".$details." WHERE login='".$_SESSION['username']."'";
  		$result = mysql_query($sql);

  		//If Password was changed	
  		if($_POST['password1'] != "") {
  			profile_error_header("Password was changed.  You need to re-login.");
  			exit;
  		}
  		header("Location: ../index.php?p=profile&id=$userid");
	}
	
?>