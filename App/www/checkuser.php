<?
/* Check User Script */
session_start();  // Start Session

include("config.php");
mysql_connect($eb_server,$eb_user,$eb_password);
mysql_select_db($eb_db);

// Conver to simple variables
global $username;
$username = $_POST['username'];
$password = $_POST['password'];

if((!$username) || (!$password)){
    echo "Please enter ALL of the information! <br />";
    include 'login.php';
    exit();
}

// Convert password to md5 hash
$password = md5($password);

// check if the user info validates the db
$sql = mysql_query("SELECT * FROM eb_members WHERE username='$username' AND password='$password' AND activated='1'");
$login_check = mysql_num_rows($sql);

if($login_check > 0){
    while($row = mysql_fetch_array($sql)){
    foreach( $row AS $key => $val ){
        $$key = stripslashes( $val );
    }
        // Register some session variables!
        session_register('first_name');
        $_SESSION['first_name'] = $first_name;
        session_register('last_name');
        $_SESSION['last_name'] = $last_name;
        session_register('email_address');
        $_SESSION['email_address'] = $email_address;
        session_register('special_user');
        $_SESSION['user_level'] = $user_level;
		session_register('user_name');
		$_SESSION['user_name'] = $username;
		session_register('userid');
		$_SESSION['userid'] = $userid;
        mysql_query("UPDATE eb_members SET last_login=now() WHERE userid='$userid'");
        header("Location: index.php");
    }
} else {
    echo "You could not be logged in! Either the username and password do not match or you have not validated your membership!<br />
    Please try again!<br />";
    include 'login.php';
}
?> 