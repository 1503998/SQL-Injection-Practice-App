<html>

<head>
<title>New PM</title>

<link rel="stylesheet" type="text/css" href="skins/<?php echo $currentskin; ?>/skin.css">

<script language="JavaScript" type="text/javascript" src="ajax/un_suggest.js"></script>

</head>
<body style="margin: 0 0 40px 0;">

<?php
if($_SESSION['banned'] == '1'){
?>

<div class="im_mid">
  <div class="im_top">
    <span class="headerfont">Access Denied</span>
  </div>
  <div class="content">
    <span color="#FF0000"><b>We're sorry, but you are currently banned from using this forum.</b></span>
  </div>
  <div class="header_bot">
  </div>
</div>

<?php
}else{

if($_SESSION['sr'] != '2'){
?>

<table class="station" width="600px">
  <tr class="header" height="18">
    <td>
    <p align="center"><font class="headerfont">Send Personal Message</font>
    </td>
  </tr>
  <tr>
    <td>
<font class="small" color="#FF0000"><b>You must be logged in to send private messages. If you do not have an account, <a href="index.php?action=register">Click Here</a> to register.</b></font>
</td>
  </tr>
</form>
</table>

<?php
}else{

if(isset($_POST['submit']) && $_POST['imto'] != '' && $_POST['imsubject'] != '' && $_POST['imbody'] != ''){

if($_SESSION['sr'] == '2') {

$miquery = mysql_query("SELECT memberid FROM " . $prefix . "members WHERE displayname = '{$_POST['imto']}'") OR DIE("Gravity Board X was unable to locate the recipient (2): " . mysql_error());
if(mysql_num_rows($miquery) == '0'){
echo '<font class=small color=#FF0000><b>The user name you supplied does not exist. Please go back and try again.</b></font><br>';
exit;
}
list($rmid) = mysql_fetch_row($miquery);

mysql_query("INSERT INTO " . $prefix . "ims (imbody, imsubject, imdate, imfrom, imto, im_parent) VALUES ('{$_POST['imbody']}','{$_POST['imsubject']}','$savetime','{$_SESSION['memberid']}','$rmid','0')") OR DIE ("Gravity Board X was unable to send your personal message: " . mysql_error());

mysql_query("UPDATE " . $prefix . "members SET pmssent=pmssent+1 WHERE memberid='{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to log your member PM: " . mysql_error());
mysql_query("UPDATE " . $prefix . "stats SET pmssent=pmssent+1") OR DIE("Gravity Board X was unable to log your PM: " . mysql_error());

echo 'Sending Personal Message. Please wait...';
?>
<META HTTP-EQUIV="Refresh" CONTENT="0; URL=inbox.php">
<?php
} else {
accessdenied();
	}
}else{
?>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='imto|required|Please enter a recipient.';
rules[1]='imsubject|required|Please enter a subject.';
rules[2]='imbody|required|Please enter a message.';
rules[3]='imsubject|maxlength|50|Subject must be 50 characters or less.';
</script>

<div class="im_mid">

<div class="im_top">
  <span class="headerfont">Send Personal Message</span>
</div>

<div class="content">

<div id="errorsdiv"></div>

<?php
if(isset($_POST['formstatus'])){
if($_POST['imto'] == ''){
echo '<font class=small color=#FF0000><b>Please enter a recipient.</b></font><br>';
}if($_POST['imsubject'] == ''){
echo '<font class=small color=#FF0000><b>Please enter a subject.</b></font><br>';
}if($_POST['imbody'] == ''){
echo '<font class=small color=#FF0000><b>Please enter a message.</b></font>';
}if(isset($pmerror)){
echo $pmerror;
	}
}
?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=newpm" name="newpm" onsubmit="return performCheck('newpm', rules, 'innerHtml');">
    <p>Recipient:
    <br>
    <div id="un_suggest" style="position: absolute; margin-top: 17px;"></div>
    <input type="textbox" class="textbox" name="imto" id="imto" size="50" onkeyup="unSuggest();" onBlur="document.getElementById('un_suggest').innerHTML=''" autocomplete="off" value="<?php if(isset($imto)){ echo $imto; }?>">
    <br><br>
    <p>Subject:
    <br>
    <input type="textbox" class="textbox" name="imsubject" size="50" value="<?php if(isset($imsubject)){ echo $imsubject; }?>">
    <br><br>
    <p>Message:
    <br>
    <textarea class="textbox" rows="10" cols="40" name="imbody"><?php if(isset($imbody)){ echo $imbody; }?></textarea>
    <br>
    <input type="hidden" name="formstatus"><input type="submit" class="button" name="submit" value="Send PM">


</form>

</div>

<div class="im_bot">
</div>

</div>

<div class="row3" style="position: fixed; bottom: 0px; clear: both; text-align: center; width: 590px;">
  <span class="navfont"><a href="index.php?action=inbox">Inbox</a> | <a href="index.php?action=outbox">Outbox</a> | <a href="index.php?action=newpm">New PM</a></span>
</div>

</body>
</html>
<?php
	}
	}
}
?>
