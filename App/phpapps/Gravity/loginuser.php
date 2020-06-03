<?php

//Check to see if both fields were filled in
if(isset($_POST['login_email']) && isset($_POST['login_pw']))
{

?>

<div id="dashregister" class="station">

<div id="header_dashregister" class="header">
  <span class="headerfont">User Login</span>
</div><table class=station width="100%">
    <p><font color="#FF0000">
<?php

    //ASSIGN TEMPORARY VALUES
    $tempemail = $_POST['login_email'];
    $temppw = $_POST['login_pw'];

    //CHECK USER'S LOGIN INFORMATION AND LOG THEM IN
    loginuser($tempemail, $temppw);
    if($_SESSION['debug_login_result'] == 0)
    {
        echo 'Logging in.  Please wait...  <a href="index.php">Click here</a> if you do not get transferred.';
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
    }elseif($_SESSION['debug_login_result'] == 1)
    {
        echo 'An error occurred while logging in: Incorrect email or password.';
    }elseif($_SESSION['debug_login_result'] == 2)
    {
        echo 'An error occurred while logging in: Incorrect email or password.';
    }elseif($_SESSION['debug_login_result'] == 4)
    {
        echo 'An error occurred while logging in: You did not enter a valid email address.';
    }elseif($_SESSION['debug_login_result'] == 3)
    {
        echo 'An error occurred while logging in: You have not yet validated your account.  To do so, please read the verification email that was sent to you when you registered.';
    }
    
    echo '</font></p></div>';
    
}

?>
