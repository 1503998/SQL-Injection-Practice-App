<?php
session_start();
if (get_magic_quotes_gpc()) {
	$_REQUEST = array_map('stripslashes', $_REQUEST);
	$_GET = array_map('stripslashes', $_GET);
	$_POST = array_map('stripslashes', $_POST);
	$_COOKIE = array_map('stripslashes', $_COOKIE);
}
include_once('inc/config_inc.php');
include_once('inc/util_inc.php');
include_once('inc/language.php');
if (isset($_SESSION['login_id'])) {
	if (!isLoggedIn($_SESSION['login_id'], $_SESSION['login_uname'], $_SESSION['login_pw'])) {
		displayLoginPage();
		exit();
	}
} elseif (isset($_COOKIE['fcms_login_id'])) {
	if (isLoggedIn($_COOKIE['fcms_login_id'], $_COOKIE['fcms_login_uname'], $_COOKIE['fcms_login_pw'])) {
		$_SESSION['login_id'] = $_COOKIE['fcms_login_id'];
		$_SESSION['login_uname'] = $_COOKIE['fcms_login_uname'];
		$_SESSION['login_pw'] = $_COOKIE['fcms_login_pw'];
	} else {
		displayLoginPage();
		exit();
	}
} else {
	displayLoginPage();
	exit();
}
header("Cache-control: private");
include_once('inc/calendar_class.php');
$calendar = new Calendar($_SESSION['login_id'], 'mysql', $cfg_mysql_host, $cfg_mysql_db, $cfg_mysql_user, $cfg_mysql_pass);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $LANG['lang']; ?>" lang="<?php echo $LANG['lang']; ?>">
<head>
<title><?php echo $cfg_sitename . " - " . $LANG['poweredby'] . " " . $stgs_release; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Ryan Haudenschilt" />
<link rel="stylesheet" type="text/css" href="<?php getTheme($_SESSION['login_id']); ?>" />
<link rel="stylesheet" type="text/css" href="themes/datechooser.css" />
<link rel="shortcut icon" href="themes/images/favicon.ico"/>
<script src="inc/prototype.js" type="text/javascript"></script>
<script type="text/javascript" src="inc/datechooser.js"></script>
<script type="text/javascript">
<!-- //
	window.onload = WindowLoad;
	function WindowLoad()
	{
		var objDatePicker = new DateChooser();
		objDatePicker.setUpdateField({'sday':'j', 'smonth':'n', 'syear':'Y'});
		objDatePicker.setIcon('themes/images/default/datepicker.jpg', 'syear');
		var objDatePicker2 = new DateChooser();
		objDatePicker2.setUpdateField({'eday':'j', 'emonth':'n', 'eyear':'Y'});
		objDatePicker2.setIcon('themes/images/default/datepicker.jpg', 'eyear');
		return true;
	}
// -->
</script>
</head>
<body id="body-calendar">
	<div><a name="top"></a></div>
	<div id="header"><?php echo "<h1 id=\"logo\">$cfg_sitename</h1><p>".$LANG['welcome']." <a href=\"profile.php?member=".$_SESSION['login_id']."\">"; echo getUserDisplayName($_SESSION['login_id']); echo "</a> | <a href=\"settings.php\">".$LANG['link_settings']."</a> | <a href=\"logout.php\" title=\"".$LANG['link_logout']."\">".$LANG['link_logout']."</a></p>"; ?></div>
	<?php displayTopNav(); ?>
	<div id="pagetitle"><?php echo $LANG['link_calendar']; ?></div>
	<div id="leftcolumn">
		<h2><?php echo $LANG['navigation']; ?></h2>
		<?php
		displaySideNav();
		if(checkAccess($_SESSION['login_id']) < 3) { 
			echo "\t<h2>".$LANG['admin']."</h2>\n\t"; 
			displayAdminNav("fix");
		} ?></div>
	<div id="content">
		<div id="messageboard" class="centercontent">
			<?php
			$showcal = true;
			if ($_GET['edit']) {
				if (checkAccess($_SESSION['login_id']) <= 5) { $showcal = $calendar->displayForm('edit', $_GET['edit']); }
			} else if ($_GET['add']) {
				if (checkAccess($_SESSION['login_id']) <= 5) { $showcal = $calendar->displayForm($_GET['add']); }
			}
			if ($_POST['edit']) {
				$date = $_POST['syear'] . "-" . str_pad($_POST['smonth'], 2, "0", STR_PAD_LEFT) . "-" . str_pad($_POST['sday'], 2, "0", STR_PAD_LEFT);
				if (isset($_POST['private'])) { $private = 1; } else { $private = 0; }
				mysql_query("UPDATE `fcms_calendar` SET `date`='$date', `title`='".addslashes($_POST['title'])."', `desc`='".addslashes($_POST['desc'])."', `type`='".addslashes($_POST['type'])."', `private`=$private WHERE id = " . $_POST["id"]) or die("<h1>Edit Calendar Error (calendar.php 81)</h1>" . mysql_error());
				echo "<p class=\"ok-alert\" id=\"msg\"><b>".$LANG['ok_cal_update']."</b><br/>$date - ".$_POST['type']."<br/>".$_POST['title']."<br/>".$_POST['desc']."</p>";
				echo "<script type=\"text/javascript\">window.onload=function(){ var t=setTimeout(\"$('msg').toggle()\",3000); }</script>";
			} else if ($_POST['add']) {
				$date = $_POST['syear'] . "-" . str_pad($_POST['smonth'], 2, "0", STR_PAD_LEFT) . "-" . str_pad($_POST['sday'], 2, "0", STR_PAD_LEFT);
				if (isset($_POST['private'])) { $private = 1; } else { $private = 0; }
				mysql_query("INSERT INTO `fcms_calendar`(`date`, `title`, `desc`, `created_by`, `type`, `private`) VALUES ('$date', '".addslashes($_POST['title'])."', '".addslashes($_POST['desc'])."', " . $_SESSION['login_id'] . ", '".addslashes($_POST['type'])."', $private)") or die("<h1>Add Calendar Error (calendar.php 86)</h1>" . mysql_error());
				echo "<p class=\"ok-alert\" id=\"msg\"><b>".$LANG['ok_cal_add']."</b><br/>$date - ".$_POST['type']."<br/>".$_POST['title']."<br/>".$_POST['desc']."</p>";
				echo "<script type=\"text/javascript\">window.onload=function(){ var t=setTimeout(\"$('msg').toggle()\",3000); }</script>";
			} else if ($_POST['delete']) {
				mysql_query("DELETE FROM `fcms_calendar` WHERE id = " . $_POST["id"]) or die("<h1>Delete Calendar Error (calendar.php 90)</h1>" . mysql_error());
				echo "<p class=\"ok-alert\" id=\"msg\">".$LANG['ok_cal_delete']."</p>";
				echo "<script type=\"text/javascript\">window.onload=function(){ var t=setTimeout(\"$('msg').toggle()\",3000); }</script>";
			}
			if ($showcal) {
				$year  = isset($_GET['year']) ? $_GET['year'] : date('Y');
				$month = isset($_GET['month']) ? str_pad($_GET['month'], 2, 0, STR_PAD_LEFT) : date('m');
				$day = isset($_GET['day']) ? str_pad($_GET['day'], 2, 0, STR_PAD_LEFT) : date('d');
				$calendar->displayCalendar($month, $year, $day, 'big');
				echo "<div class=\"caltoolbar\">";
				//<div class=\"views\"><b>Calendar View:</b> [ <a class=\"monthview\" href=\"?view=month\">Month</a> | <a class=\"weekview\" href=\"?view=week\">Week</a> | <a class=\"dayview\" href=\"?view=day\">Day</a> ] </div>";
				echo "<div class=\"prints\"><a class=\"print\" href=\"#\" onclick=\"window.open('inc/calendar_print.php?year=$year&amp;month=$month&amp;day=$day','name','width=700,height=400,scrollbars=yes,resizable=yes,location=no,menubar=no,status=no'); return false;\">".$LANG['print']."</a></div></div>\n";
			}
			?>
		</div><!-- #messageboard .centercontent -->
	</div><!-- #content -->
	<div id="footer">
		<p>
			<a href="http://www.haudenschilt.com/fcms/" class="ft"><?php echo $LANG['link_home']; ?></a> | <a href="http://www.haudenschilt.com/forum/index.php" class="ft"><?php echo $LANG['link_support']; ?></a> | <a href="help.php" class="ft"><?php echo $LANG['link_help']; ?></a><br />
			<a href="http://www.haudenschilt.com/fcms/"><?php echo $stgs_release; ?></a> - Copyright &copy; 2006/07 Ryan Haudenschilt.  
		</p>
	</div>
</body>
</html>