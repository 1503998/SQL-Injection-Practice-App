<?php
if (get_magic_quotes_gpc()) {
	$_REQUEST = array_map('stripslashes', $_REQUEST);
	$_GET = array_map('stripslashes', $_GET);
	$_POST = array_map('stripslashes', $_POST);
	$_COOKIE = array_map('stripslashes', $_COOKIE);
}
include_once('inc/config_inc.php');
include_once('inc/util_inc.php');
include_once('inc/language.php');
?>
<html>
<head>
<title><?php echo $LANG['reg_for']." ".$cfg_sitename; ?></title>
<link rel="stylesheet" type="text/css" href="themes/datechooser.css" />
<script type="text/javascript" src="inc/datechooser.js"></script>
<script type="text/javascript">
<!-- //
	window.onload = WindowLoad;
	function WindowLoad()
	{
		var objDatePicker = new DateChooser();
		objDatePicker.setUpdateField({'day':'j', 'month':'n', 'year':'Y'});
		objDatePicker.setIcon('themes/images/default/datepicker.jpg', 'year');
		document.registerform.username.focus()
		return true;
	}
// -->
</script>
<style type="text/css">
html { font-size: 100%; background: #9ccef0 url(themes/images/default/bg.png) repeat-x; }
body { font-size: 12pt; line-height: 24pt; text-align: center; font-family: Verdana, Sans-Serif; }
p { font-size: 10pt; line-height: 14pt; }
#column { width: 600px; margin: 50px auto; padding: 10px; text-align: left; background-color: #fff; }
h1 { color: #fff; margin-top: 100px; }
h2 { color: #fff; font-weight: bold; background-color: #000; margin: 0; padding: 15px 0 11px 15px; }
#sections-photo, #sections-board, #sections-book, #sections-calendar, #sections-news, #sections-prayers { border: none; }
.field-label { margin: 10px 0 0 0 0; }
.field-widget { margin: 10px 0 0 0; }
.error { font-size: 10pt; line-height: 14pt; color: #f30; }
.req { font-size: 8pt; color: #c00; }
.LV_valid { font-size: 9pt; padding-left: 10px; font-weight: bold; color: #0c0; }
.LV_valid_field, input.LV_valid_field:hover, input.LV_valid_field:active, textarea.LV_valid_field:hover, textarea.LV_valid_field:active { border: 1px solid #0c0; }
.LV_invalid { display: block; font-size: 8pt; font-weight: bold; color : #c00; }
.LV_invalid_field, input.LV_invalid_field:hover, input.LV_invalid_field:active, textarea.LV_invalid_field:hover, textarea.LV_invalid_field:active { border: 1px solid #c00; }
#submit { font-size: 14pt; line-height: 24pt; font-family: Verdana, Sans-Serif; border: none; }
#msg { margin: 50px 0 0 0; width: 60%; }
#msg p { font-size: 14pt; line-height: 24pt; }
</style>
</head>
<body>
<?php
mysql_connect($cfg_mysql_host, $cfg_mysql_user, $cfg_mysql_pass);
mysql_select_db($cfg_mysql_db);
if (isset($_POST['submit'])) {
	$result = mysql_query("SELECT email FROM fcms_users WHERE email='" . $_POST['email'] . "'"); 
	$email_check = mysql_num_rows($result);
	if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['fname']) || !isset($_POST['lname']) || !isset($_POST['email'])) {
		displayForm("<p class=\"error\">".$LANG['err_required']."</p>");
	} elseif ($email_check > 0) {
		displayForm('<p class="error">'.$LANG['err_email_use1'].' <a href="lostpw.php">'.$LANG['err_email_use2'].'</a> '.$LANG['err_email_use3'].'</p>');
	} else {
		$fname = addslashes($_POST['fname']);
		$lname = addslashes($_POST['lname']);
		$email = $_POST['email'];
		$birthday = $_POST['year'] . "-" . str_pad($_POST['month'], 2, "0", STR_PAD_LEFT) . "-" . str_pad($_POST['day'], 2, "0", STR_PAD_LEFT);
		$address = empty($_POST['address']) ? "NULL" : addslashes($_POST['address']);
		$city = empty($_POST['city']) ? "NULL" : addslashes($_POST['city']);
		$state = empty($_POST['state']) ? "" : $_POST['state'];
		$zip = empty($_POST['zip']) ? "" : $_POST['zip'];
		$home = empty($_POST['home']) ? "" : $_POST['home'];
		$work = empty($_POST['work']) ? "" : $_POST['work'];
		$cell = empty($_POST['cell']) ? "" : $_POST['cell'];
		$username = addslashes($_POST['username']);
		$password = $_POST['password'];
		$md5pass = md5($password); 
		mysql_query("INSERT INTO `fcms_users`(`access`, `joindate`, `fname`, `lname`, `email`, `birthday`, `username`, `password`) VALUES (3, NOW(), '$fname', '$lname', '$email', '$birthday', '$username', '$md5pass')") or die("<h1>Error (REG001)</h1>" . mysql_error());
		$lastid = mysql_insert_id();
		mysql_query("INSERT INTO `fcms_address`(`user`, `updated`, `address`, `city`, `state`, `zip`, `home`, `work`, `cell`) VALUES ($lastid, NOW(), '$address', '$city', '$state', '$zip', '$home', '$work', '$cell')") or die("<h1>Error (REG002)</h1>" . mysql_error());
		mysql_query("INSERT INTO `fcms_calendar`(`date`, `title`, `created_by`, `type`) VALUES ('$birthday', '$fname $lname', $lastid, 'Birthday')") or die("<h1>Error (REG003)</h1>" . mysql_error());
		echo '<div id="msg"><h1>'.$LANG['reg_success'].'</h1><p>'.$LANG['reg_msg1'].' ' . $cfg_sitename . '. '.$LANG['reg_msg2'].' ' . $email . '. <br/><b>'.$LANG['reg_msg3'].'</b></p>'
			. '<p>'.$LANG['reg_msg4'].'</div>';
		$subject = "$cfg_sitename ".$LANG['mail_reg1']; 
		$message = $LANG['mail_reg2']." $fname $lname, 

".$LANG['mail_reg3']." $cfg_sitename

".$LANG['mail_reg4']." $cfg_sitename, ".$LANG['mail_reg5']."

".$LANG['mail_reg6']."
".$LANG['username'].": $username 
".$LANG['password'].": $password

".$LANG['mail_reg7']." 
".$LANG['mail_reg8']." $cfg_sitename ".$LANG['mail_reg9']."

".$LANG['mail_reg10'];
		$now = date('F j, Y, g:i a');
		$subject2 = $LANG['mail_reg_adm1']." $cfg_sitename";
		$message2 = $LANG['mail_reg_adm2']." $cfg_sitename:
	
".$LANG['mail_reg_adm3'].": $now

".$LANG['username'].": $username
".$LANG['name'].": $fname $lname
".$LANG['mail_reg_adm4'].": $birthday

".$LANG['mail_reg_adm5'].":
$address
$city, $state $zip

".$LANG['home_phone'].": $home
".$LANG['work_phone'].": $work
".$LANG['mobile_phone'].": $cell

".$LANG['mail_reg_adm5'];
		$subject = addslashes($subject); $message = addslashes($message);
		$subject2 = addslashes($subject2); $message2 = addslashes($message2);
		mail($email, $subject, $message);
		mail($cfg_contact_email, $subject2, $message2);
	}
} else { displayForm(); } ?>
</body>
</html>

<?php
function displayForm ($error = '0') {
	global $LANG; ?>
	<h1><?php echo $LANG['reg']; ?></h1>
	<script type="text/javascript" src="inc/prototype.js"></script>
	<script type="text/javascript" src="inc/livevalidation.js"></script>
	<form id="registerform" name="registerform" action="register.php" method="post">
	<div id="column">
		<?php if ($error !== '0') { echo $error; } ?>
		<h2><?php echo $LANG['mem_account']; ?></h2>
		<div><div class="field-label"><label for="username"><b><?php echo $LANG['username']; ?></b></label>: (<span class="req">*</span>)</div> <div class="field-widget"><input type="text" name="username" id="username" class="required" title="<?php echo $LANG['title_uname']; ?>" size="25" value=""/></div></div>
		<script type="text/javascript">
			var funame = new LiveValidation('username', { validMessage: "<?php echo $LANG['lv_thanks']; ?>", wait: 500});
			funame.add(Validate.Presence, {failureMessage: "<?php echo $LANG['lv_sorry_req']; ?>"});
		</script>
		<div><div class="field-label"><label for="password"><b><?php echo $LANG['password']; ?></b></label>: (<span class="req">*</span>)</div> <div class="field-widget"><input type="password" name="password" id="password" class="required" title="<?php echo $LANG['title_pass']; ?>" size="25" value=""/></div></div>
		<script type="text/javascript">
			var fpass = new LiveValidation('password', { validMessage: "<?php echo $LANG['lv_good_pass']; ?>", wait: 500});
			fpass.add(Validate.Presence, {failureMessage: "<?php echo $LANG['lv_bad_pass']; ?>"});
		</script>
		<div><div class="field-label"><label for="fname"><b><?php echo $LANG['first_name']; ?></b></label>: (<span class="req">*</span>)</div> <div class="field-widget"><input type="text" name="fname" size="50" id="fname" class="required" value="" title="<?php echo $LANG['title_fname']; ?>"/></div></div>
		<script type="text/javascript">
			var ffname = new LiveValidation('fname', { validMessage: "<?php echo $LANG['lv_thanks']; ?>", wait: 500});
			ffname.add(Validate.Presence, {failureMessage: "<?php echo $LANG['lv_sorry_req']; ?>"});
		</script>
		<div><div class="field-label"><label for="lname"><b><?php echo $LANG['last_name']; ?></b></label>: (<span class="req">*</span>)</div> <div class="field-widget"><input type="text" name="lname" size="50" id="lname" class="required" value="" title="<?php echo $LANG['title_lname']; ?>"/></div></div>
		<script type="text/javascript">
			var flname = new LiveValidation('lname', { validMessage: "<?php echo $LANG['lv_thanks']; ?>", wait: 500});
			flname.add(Validate.Presence, {failureMessage: "<?php echo $LANG['lv_sorry_req']; ?>"});
		</script>
		<div><div class="field-label"><label for="email"><b><?php echo $LANG['email_address']; ?></b></label>: (<span class="req">*</span>)</div> <div class="field-widget"><input type="text" name="email" size="50" id="email" class="required validate-email" value="" title="<?php echo $LANG['title_email']; ?>"/></div></div>
		<script type="text/javascript">
			var femail = new LiveValidation('email', { validMessage: "<?php echo $LANG['lv_thanks']; ?>", wait: 500 });
			femail.add( Validate.Presence, { failureMessage: "<?php echo $LANG['lv_sorry_req']; ?>" } );
			femail.add( Validate.Email, { failureMessage: "<?php echo $LANG['lv_bad_email']; ?>" } );
			femail.add( Validate.Length, { minimum: 10 } );
		</script>
		<div><div class="field-label"><label for="day"><b><?php echo $LANG['birthday']; ?></b></label>: (<span class="req">*</span>)</div> <div class="field-widget"><select id="day" name="day">
			<?php
			$d = 1;
			while ($d <= 31) {
				if ($day == $d) { echo "<option value=\"$d\" selected=\"selected\">$d</option>"; }
				else { echo "<option value=\"$d\">$d</option>"; }
				$d++;
			}
			echo '</select><select name="month">';
			$m = 1;
			while ($m <= 12) {
				if ($month == $m) { echo "<option value=\"$m\" selected=\"selected\">" . date('M', mktime(0, 0, 0, $m, 1, 2006)) . "</option>"; }
				else { echo "<option value=\"$m\">" . date('M', mktime(0, 0, 0, $m, 1, 2006)) . "</option>"; }
				$m++;
			}
			echo '</select><select name="year">';
			$y = 1900;
			while ($y - 5 <= date('Y')) {
				if ($year == $y) { echo "<option value=\"$y\" selected=\"selected\">$y</option>"; }
				else { echo "<option value=\"$y\">$y</option>"; }
				$y++;
			} ?></select></div></div>
		<div><div class="field-label"><label for="address"><b><?php echo $LANG['street']; ?></b></label>:</div> <div class="field-widget"><input type="text" name="address" size="50" id="address" class="" value="" title="<?php echo $LANG['title_street']; ?>"/></div></div>
		<div><div class="field-label"><label for="city"><b><?php echo $LANG['city_town']; ?></b></label>:</div> <div class="field-widget"><input type="text" name="city" size="50" id="city" class="" value="" title="<?php echo $LANG['title_city_town']; ?>"/></div></div>
		<div><div class="field-label"><label for="state"><b><?php echo $LANG['state_prov']; ?></b></label>:</div> <div class="field-widget"><input type="text" name="state" id="state" class="" title="<?php echo $LANG['title_state_prov']; ?>" size="50" value=""/></div></div>
		<div><div class="field-label"><label for="zip"><b><?php echo $LANG['zip_pos']; ?></b></label>:</div> <div class="field-widget"><input type="text" name="zip" id="zip" class="" title="<?php echo $LANG['title_zip_pos']; ?>" size="10" value=""/></div></div>
		<div><div class="field-label"><label for="home"><b><?php echo $LANG['home_phone']; ?></b></label>:</div> <div class="field-widget"><input type="text" name="home" id="home" class="validate-phone" title="<?php echo $LANG['title_phone']; ?>" size="20" value=""/></div></div>
		<div><div class="field-label"><label for="work"><b><?php echo $LANG['work_phone']; ?></b></label>:</div> <div class="field-widget"><input type="text" name="work" id="work" class="validate-phone" title="<?php echo $LANG['title_phone']; ?>" size="20" value=""/></div></div>
		<div><div class="field-label"><label for="cell"><b><?php echo $LANG['mobile_phone']; ?></b></label>:</div> <div class="field-widget"><input type="text" name="cell" id="cell" class="validate-phone" title="<?php echo $LANG['title_phone']; ?>" size="20" value=""/></div></div>
		<p><input id="submit" name="submit" type="submit"  value="<?php echo $LANG['submit']; ?>"/></p>
	</div>
	</form><?php
} ?>