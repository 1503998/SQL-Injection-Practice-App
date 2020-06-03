<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='unform|required|Please enter a username.';
rules[1]='unform|alnumhyphen|Username must contain only characters a-z, A-Z, 0-9, - or _.';
rules[2]='unform|maxlength|20|Username must be less than 20 characters long.';
rules[3]='pwform|required|Please enter a password.';
rules[4]='pwform|alnumhyphen|Password must contain only characters a-z, A-Z, 0-9, - or _.';
rules[5]='pwconfirmform|required|Please confirm your password.';
rules[6]='pwform|equal|$pwconfirmform|Passwords must match.';
rules[7]='emailform|required|Please enter an email address.';
rules[8]='emailform|email|Please enter a valid email address.';

</script>
<form name="registerform" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=register" onsubmit="return performCheck('registerform', rules, 'innerHtml');">

<?php

//Get user agreement
$uaquery = mysql_query("SELECT useragreement FROM " . $prefix . "settings") OR DIE("Gravity Board X was unable to locate the user agreement.  YOU MAY NOT CONTINUE REGISTRATION!!: " . mysql_error());
list($useragreement) = mysql_fetch_row($uaquery);

?>

<div class="headermid">

<div class="header">
  <span class="headerfont">Register</span>
</div>

<div class="content">

<?php

if(isset($_POST['iagree']) && $_POST['unform'] != '' && $_POST['pwform'] != '' && $_POST['pwconfirmform'] != '' && $_POST['emailform'] != '' && $_POST['pwform'] == $_POST['pwconfirmform'])
{

    //Clean input
    $newun = clean_input($_POST['unform']);
    $newemail = clean_input($_POST['emailform']);

    //Check for existing usernames and email addresses
    $unquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE displayname='$newun'") OR DIE("Gravity Board X experienced an error while trying to check for available usernames: " . mysql_error());
    $emailquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE email='$newemail'") OR DIE("Gravity Board X experienced an error while trying to check for available email addresses: " . mysql_error());

    if(mysql_num_rows($unquery) >= 1)
    {
	     echo '<font color="#FF0000"><b>The username you have selected is already in use. Please use the back button on your browser to go back and try again.</b></font><br>';
    }
    elseif(mysql_num_rows($emailquery) >= 1)
    {
	     echo '<font color="#FF0000"><b>The email address you have selected is already in use. Please use the back button on your browser to go back and try again.</b></font>';
    }else
    {
        //Get default time difference from database
        $tdquery = mysql_query("SELECT timediff FROM " . $prefix . "settings") OR DIE("Gravity Board X was unable to retrieve the default time difference: " . mysql_error());
        list($timediff) = mysql_fetch_row($tdquery);

        //Generate random verification ID
        $verifyid = randstring();

        //Add new member to database
	$regpw = clean_input($_POST['pwform']);
        $regpw = MD5($regpw);
        mysql_query("INSERT INTO " . $prefix . "members (pw, email, displayname, memberGroup, dateregistered, timediff, pmssent, boardviews, threadviews, messageviews, pmsread, totalclicks, tperpage, mperpage, verified, verifyid) VALUES ('$regpw','$newemail','$newun','3','$savetime','$timediff','0','0','0','0','0','0','50','15','0','$verifyid')") OR DIE("Gravity Board X was unable to save your information to the database: " . mysql_error());
        $unquery = mysql_query("SELECT memberid FROM " . $prefix . "members WHERE email='$newemail'") OR DIE("Gravity Board X was unable to verify your member ID: " . mysql_error());
        list($newmid) = mysql_fetch_row($unquery);

        //Create message log account
        mysql_query("INSERT INTO " . $prefix . "message_logs (memberid, messagesread) VALUES ('$newmid', '0')") OR DIE("Gravity Board X was unable to create your message log account: " . mysql_error());

        //Set email headers
        $header = "From: \"$boardname\" <gravityboardx@" . $_SERVER['HTTP_HOST'] . ">\nX-Mailer: Gravity Board X Message Board System";

        //Set email subject
        $subject = "" . $boardname . " Registration";

        //Set email message
        $regquery = mysql_query("SELECT regemail FROM " . $prefix . "settings") OR DIE("Gravity Board X was unable to retrieve the registration information from the database: " . mysql_error());
        list($message) = mysql_fetch_row($regquery);
        $message .= "\n\nForum Name: " . $boardname . " (Powered By Gravity Board X)\nEmail: " . $_POST['emailform'] . "\nUsername: " . $_POST['unform'] . "\n\nTo complete your registration, please visit the following link: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?action=verify&emailverify=" . $_POST['emailform'] . "&verifyid=" . $verifyid . "";

        //Send confirmation email
	if(mail($newemail, $subject, $message, $header))
	{
		echo '<tr><td>Congradulations! Registration was successful.  Please check your email to verify your account before logging in.</td></tr>';
	}else
	{
		echo '<tr><td>An error occurred while sending your registration email.  Please contact the site administrator.</td></tr>';
	}
    }

}else
{

?>

<table>
  <tr>
    <td colspan="3">
      <p align="center"><b>USER AGREEMENT</b></td>
  </tr>
  <tr>
    <td colspan="3">
      <p align="left"><?php echo $useragreement; ?><br><br>
    </td>
  </tr>
  <tr>
  <tr>
    <td colspan="3">
      <div id="errorsDiv"></div>
    </td>
  </tr>
  <tr>
    <td width="30%">
      <p align="right">Username
    </td>
    <td width="10%">
      <input type="text" name="unform" size="20" value="<?php if(isset($_POST['unform'])){ echo $_POST['unform']; } ?>" class="textbox">
    </td>
    <td width="40%"><p>The name other users will know you by</p>
    </td>
  </tr>
  <tr>
    <td width="30%">
      <p align="right">Password
    </td>
    <td width="10%">
      <input type="password" name="pwform" size="20" value="<?php if(isset($_POST['pwform'])){ echo $_POST['pwform']; } ?>" class="textbox">
    </td>
    <td width="40%"><p>The password you will login with</p>
    </td>
  </tr>
  <tr>
    <td width="30%">
      <p align="right">Confirm Password
    </td>
    <td width="10%">
      <input type="password" name="pwconfirmform" size="20" value="<?php if(isset($_POST['pwconfirmform'])){ echo $_POST['pwconfirmform']; } ?>" class="textbox">
    </td>
    <td width="40%"><p>Enter your password again</p>
    </td>
  </tr>
  <tr>
    <td width="30%">
      <p align="right">Email
    </td>
    <td width="10%">
      <input type="text" name="emailform" size="20" value="<?php if(isset($_POST['emailform'])){ echo $_POST['emailform']; } ?>" class="textbox">
    </td>
    <td width="40%"><p>Your contact email (also your login ID)</p>
    </td>
  </tr>
  <tr>
    <td width="30%">
    &nbsp;</td>
    <td width="10%">
      <p align="center">
      <input type="submit" value="I Agree" name="iagree" class="button">
    </td>
    <td width="40%">&nbsp;
    </td>
  </tr>
</table>
</form>

<?php

}

?>

</div>

<div class="headerbot">
</div>

</div>