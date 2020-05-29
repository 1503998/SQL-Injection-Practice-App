<?
session_start(); 

include('./config.php');

/**
 * Delete cookies - the time must be in the past,
 * so just negate what you added when creating the
 * cookie.
 */
if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
   setcookie("cookid", "", time()-60*60*24*100, "/");
   setcookie("cookname", "", time()-60*60*24*100, "/");
   setcookie("cookpass", "", time()-60*60*24*100, "/");
}

if(!$logged_in){
   header("Location: ../index.php");
 
} else {
	removeActiveUser($_SESSION['username']); 
   /* Kill session variables */
   unset($_SESSION['player_name']);
   unset($_SESSION['userid']);
   unset($_SESSION['username']);
   unset($_SESSION['password']);
   $_SESSION = array(); // reset session array
   
   session_destroy();   // destroy session.
   header("Location: ../index.php");
}

?>