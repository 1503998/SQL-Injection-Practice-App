<?

/**
 * Checks whether or not the given username is in the
 * database, if so it checks if the given password is
 * the same password in the database for that user.
 * If the user doesn't exist or if the passwords don't
 * match up, it returns an error code (1 or 2). 
 * On success it returns 0.
 */

     
function confirmUser($username, $password){
   global $_G;
   /* Add slashes if necessary (for query) */
   if(!get_magic_quotes_gpc()) {
	$username = addslashes($username);
   }

   /* Verify that user is in database */
   $q = "select * from ". $_G['mysql_prefix'] ."users where login = '{$username}'";
   $result = mysql_query($q);
   if(!$result || (mysql_num_rows($result) < 1)){
      return 1; //Indicates username failure
   }

   /* Retrieve userid and password from result, strip slashes */
   $dbarray = mysql_fetch_array($result);
   $dbarray['password'] = stripslashes($dbarray['password']);
   $password = stripslashes($password);
   
   /* Validate that password is correct */
   if($password == $dbarray['password']){
      return 0; //Success! Username and password confirmed
   }
   else{
      return 2; //Indicates password failure
   }
}

/**
 * checkLogin - Checks if the user has already previously
 * logged in, and a session with the user has already been
 * established. Also checks to see if user has been remembered.
 * If so, the database is queried to make sure of the user's 
 * authenticity. Returns true if the user has logged in.
 */
function checkLogin(){
   /* Check if user has been remembered */
   if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
      $_SESSION['userid'] = $_COOKIE['cookid'];
	  $_SESSION['username'] = $_COOKIE['cookname'];
      $_SESSION['password'] = $_COOKIE['cookpass'];
   }

   /* Username and password have been set */
   if(isset($_SESSION['username']) && isset($_SESSION['password'])){
      /* Confirm that username and password are valid */
      if(confirmUser($_SESSION['username'], $_SESSION['password']) != 0){
         /* Variables are incorrect, user not logged in */
         unset($_SESSION['userid']);
         unset($_SESSION['username']);
         unset($_SESSION['password']);
         return false;
      }
      return true;
   }
   /* User not logged in */
   else{
      return false;
   }
}

/**
 * Determines whether or not to display the login
 * form or to show the user that he is logged in
 * based on if the session variables are set.
 */
function displayLogin(){
   global $_G, $logged_in;
   if($logged_in){
      echo "<br /><span class=\"welcome\">Welcome <b>$_SESSION[username]</b>, you are logged in. <a href=\"./includes/logout.php\">Logout</a></span>";
   }
   else{
?>
<form action="" method="post">
<table>
<tr>
	<td class="register">Username:</td>
	<td><input type="text" name="user" maxlength="30" /></td>
	<td class="register">Password:</td>
	<td><input type="password" name="pass" maxlength="30" /></td>
</tr>
<tr>
	<td colspan="3" align="left"><span class="remember_me"><input type="checkbox" name="remember" />Remember me next time</span><input type="submit" name="sublogin" value="Login" /></td>
	<td align="left"><span class="register"><a href="./register.php" onclick="NewWindow(this.href,'register','400','200','no','center');return false" onfocus="this.blur()" class="register">
            Register</a><br /><a href="forgot_password.php" onclick="NewWindow(this.href,'forgot_password','400','200','no','center');return false" onfocus="this.blur()" class="register">Lost Password </a></span></td>
</tr>
</table>
</form>
<?
   }
}


/**
 * Checks to see if the user has submitted his
 * username and password through the login form,
 * if so, checks authenticity in database and
 * creates session.
 */
if(isset($_POST['sublogin'])){
   /* Check that all fields were typed in */
   if(!$_POST['user'] || !$_POST['pass']){
      //die('You didn\'t fill in a required field.');
      index_error_header("You didn\'t fill in the required fields.");
	  exit;
   }
   /* Spruce up username, check length */
   $_POST['user'] = trim($_POST['user']);
   if(strlen($_POST['user']) > 30){
      //die("Sorry, the username is longer than 30 characters, please shorten it.");
      index_error_header("Sorry, the username is longer than 30 characters, please shorten it.");
	  exit;
   }

   /* Checks that username is in database and password is correct */
   $defaults = config_defaults();	
   $SaltPass = $defaults['conf_salt_password']; //SaltPass is already sha1 hashed once in db.	
   $UserPass = sha1(mysql_real_escape_string($_POST['pass']));
   $sha1pass = sha1(mysql_real_escape_string($SaltPass . $UserPass));
   $result = confirmUser($_POST['user'], $sha1pass);

   /* Check error codes */
   if($result == 1){
      //die('That username doesn\'t exist in our database.');
      index_error_header("That username does not exist.");
	  exit;
   }
   else if($result == 2){
      //die('Incorrect password, please try again.');
      index_error_header("Incorrect password. Please try again.");
	  exit;
   }
   
   $query = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."users WHERE login = '{$_POST['user']}'"); 
   $query_id = mysql_fetch_assoc($query);
   $userid = $query_id['id'];	
   
   /* Username and password correct, register session variables */
   $_POST['user'] = stripslashes($_POST['user']);
   $_SESSION['userid'] = $userid;
   $_SESSION['username'] = $_POST['user'];
   $_SESSION['password'] = $sha1pass;

   //Add User to Active Users table
   $time = time();
   addActiveUser($_SESSION['username'], $time);
   
   
   
   /**
    * This is the cool part: the user has requested that we remember that
    * he's logged in, so we set two cookies. One to hold his username,
    * and one to hold his sha1 encrypted password. We set them both to
    * expire in 100 days. Now, next time he comes to our site, we will
    * log him in automatically.
    */
   if(isset($_POST['remember'])){
      setcookie("cookid", $_SESSION['userid'], time()+60*60*24*100, "/");
	  setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
      setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
   }

   /* Quick self-redirect to avoid resending data on refresh */
   $location = $_SERVER['REQUEST_URI'];
   header("Location: $location");
   //echo "<meta http-equiv=\"Refresh\" content=\"0;url=$HTTP_SERVER_VARS[PHP_SELF]\">";
   return;
}

/* Sets the value of the logged_in variable, which can be used in your code */
$logged_in = checkLogin();

?>