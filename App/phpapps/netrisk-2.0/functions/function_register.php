<?

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	session_start(); 
	include("./../includes/config.php");

	$_G = array(
		'mysql_prefix' => $mysql_prefix,
    	'empty' => 'future_use');

	/**
 	* Returns true if the username has been taken
 	* by another user, false otherwise.
 	*/
	function usernameTaken($username){
   		global $_G;
   		if(!get_magic_quotes_gpc()){
      		$username = addslashes($username);
   		}
   		
   		$q = "SELECT login FROM ". $_G['mysql_prefix'] ."users WHERE login = '$username'";
   		$result = mysql_query($q);
   		return (mysql_numrows($result) > 0);
	}

	/**
 	* Inserts the given (username, password) pair
 	* into the database. Returns true on success,
 	* false otherwise.
 	*/
	function addNewUser($username, $password, $email){
   		global $_G;
   		
   		$q = "INSERT INTO ". $_G['mysql_prefix'] ."users (login,password,email) VALUES ('$username', '$password','$email')";
   		return mysql_query($q);
	}

	/**
 	* Displays the appropriate message to the user
 	* after the registration attempt. It displays a 
 	* success or failure status depending on a
 	* session variable set during registration.
 	*/

   	if($_SESSION['regresult']){
		$_SESSION['regcode'] = "success";
      	header("Location: ../index.php?p=register");
   	} else {
      	header("Location: ../index.php?p=register");
   	}
  
	/**
 	* Determines whether or not to show to sign-up form
 	* based on whether the form has been submitted, if it
 	* has, check the database for consistency and create
 	* the new account.
 	*/
	if(isset($_POST['subjoin'])){
   		/* Make sure all fields were entered */
   		if(!$_POST['user'] || !$_POST['pass']){
      		//die('You didn\'t fill in a required field.');
      		$_SESSION['regcode'] = "error_field";
      		header("Location: ../index.php?p=register");
   		}

   		/* Spruce up username, check length */
   		$_POST['user'] = trim($_POST['user']);
   		if(strlen($_POST['user']) > 30){
      		//die("Sorry, the username is longer than 30 characters, please shorten it.");
      		$_SESSION['regcode'] = "error_length";
      		header("Location: ../index.php?p=register");
   		}

   		/* Check if username is already in use */
   		if(usernameTaken($_POST['user'])){
      		$use = $_POST['user'];
      		//die("Sorry, the username: <strong>$use</strong> is already taken, please pick another one.");
      		$_SESSION['regcode'] = "error_user";
      		header("Location: ../index.php?p=register");
   		}

   		/* Add the new account to the database */
   		$md5pass = md5($_POST['pass']);
   		$_SESSION['reguname'] = $_POST['user'];
   		$_SESSION['regresult'] = addNewUser($_POST['user'], $md5pass, $_POST['email']);
   		$_SESSION['registered'] = true;
   		//echo "<meta http-equiv=\"Refresh\" content=\"0;url=$HTTP_SERVER_VARS[PHP_SELF]\">";
   		return;
	}
?>