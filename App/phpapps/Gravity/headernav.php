<div id="nav_top">
  <span class="navheader"><?php echo stripslashes($boardname); ?></span>

  <div id="login">
    <form method="post" action="index.php">

<?php

if($_SESSION['sr'] == '2')
{
    $imnewquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "ims WHERE imto = '{$_SESSION['memberid']}' AND imread = '0'") OR DIE("Gravity Board X was unable to check for new PMs: " . mysql_error());
    list($imnew) = mysql_fetch_row($imnewquery);
    $imquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "ims WHERE imto = '{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to check for PMs: " . mysql_error());
    list($imnum) = mysql_fetch_row($imquery);

?>

  <p align="center"><span><font class="welcomefont"><?php echo stripslashes($_SESSION['displayname']); ?></font></span><br/>
<?php

    if($imnew != '0') { echo '<b>'; }

?>

  <span>
    <a href="#" onClick="PMs=window.open('index.php?action=inbox','PMs','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=616,height=400,left=100,top=100'); return false;"><span class="mailfont"><?php echo $imnum; ?> Message(s) <?php if($imnew != '0') { echo '<img src="images/letter_new.gif" border="0" alt="You have new Messages."/>'; }else{ echo '<img src="images/letter.gif" border="0" alt="You have no new messages."/>'; } ?></span></a><?php if($imnew != '0') { echo '</b>'; } ?>
    <br/>
    <span class="welcomefont"><span class="small"><?php echo date("M j 'y g:iA", time() + $timeadjust); ?></span></span>
  </span></p>

<?php

}else{

?>
          
<div class="row">
  <p align="right"><span class="small">Email: <input type="text" name="login_email" size="10" class="textbox"/><br/>Password: <input type="password" name="login_pw" size="10" class="textbox"/><br/><input type="submit" class="button" value="Login"/></span></p>
</div>

<?php

}

?>


    </form>
  </div>

</div>
<div id="nav_bot">
<span class="navfont"><a href="index.php"><font class="navfont">Home</font></a>  |  <a href="index.php?action=search"><font class="navfont">Search</font></a>  |  <a href="index.php?action=memberlist"><font class="navfont">Member List</font></a> | <a href="index.php?action=whosonline"><font class="navfont">Who's Online</font></a> | <?php if($_SESSION['sr'] == '2') { echo '<a href="index.php?action=profile"><font class="navfont">My Profile</font></a>  |  '; }; ?><?php if($_SESSION['sr'] == '2') { echo '<a href="index.php?action=bookmark"><font class="navfont">My Bookmarks</font></a>  |  '; }; ?><?php if($_SESSION['perm'] == '1') { echo '<a href="index.php?action=admin"><font class="navfont">Control Panel</font></a>  |  '; } ?><?php if($_SESSION['sr'] != '2') { echo '<a href="index.php?action=register"><font class="navfont">Register</font></a>  |  '; } ?><?php if($_SESSION['sr'] != '2') { echo '<a href="index.php?action=resetpw"><font class="navfont">Forgot My Password</font></a> | <a href="index.php?action=resendve"><font class="navfont">Resend Validation Email</font></a>'; } ?><?php if($_SESSION['sr'] == '2') { echo '<a href="index.php?action=logout"><font class="navfont">Logout</font></a>'; } ?></span>
</div>