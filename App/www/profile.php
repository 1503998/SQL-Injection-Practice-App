<?php
session_start();
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
include_once('inc/profile_class.php');
$profile = new Profile($_SESSION['login_id'], 'mysql', $cfg_mysql_host, $cfg_mysql_db, $cfg_mysql_user, $cfg_mysql_pass);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $LANG['lang']; ?>" lang="<?php echo $LANG['lang']; ?>">
<head>
<title><?php echo $cfg_sitename . " - " . $LANG['poweredby'] . " " . $stgs_release; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Ryan Haudenschilt" />
<link rel="stylesheet" type="text/css" href="<?php getTheme($_SESSION['login_id']); ?>" />
<link rel="shortcut icon" href="themes/images/favicon.ico"/>
</head>
<body id="body-profile">
	<div><a name="top"></a></div>
	<div id="header"><?php echo "<h1 id=\"logo\">$cfg_sitename</h1><p>".$LANG['welcome']." <a href=\"profile.php?member=".$_SESSION['login_id']."\">"; echo getUserDisplayName($_SESSION['login_id']); echo "</a> | <a href=\"settings.php\">".$LANG['link_settings']."</a> | <a href=\"logout.php\" title=\"".$LANG['link_logout']."\">".$LANG['link_logout']."</a></p>"; ?></div>
	<?php displayTopNav(); ?>
	<div id="pagetitle"><?php echo $LANG['link_profiles']; ?></div>
	<div id="leftcolumn">
		<h2><?php echo $LANG['navigation']; ?></h2>
		<?php
		displaySideNav();
		if(checkAccess($_SESSION['login_id']) < 3) { 
			echo "\t<h2>".$LANG['admin']."</h2>\n\t"; 
			displayAdminNav("fix");
		} ?></div>
	<div id="content">
		<div id="profile" class="centercontent">
			<p><a href="profile.php"><?php echo $LANG['profiles']; ?></a> | <a href="profile.php?awards=yes"><?php echo $LANG['link_admin_awards']; ?></a></p>
			<?php
			if (isset($_GET['member'])) {
				$profile->displayProfile($_GET['member']);
			} elseif (isset($_GET['awards'])) {
				$profile->displayAwards();
			} else {
				$profile->displayAll();
			} ?>
		</div><!-- #profile .centercontent -->
	</div><!-- #content -->
	<div id="footer">
		<p>
			<a href="http://www.haudenschilt.com/fcms/" class="ft"><?php echo $LANG['link_home']; ?></a> | <a href="http://www.haudenschilt.com/forum/index.php" class="ft"><?php echo $LANG['link_support']; ?></a> | <a href="help.php" class="ft"><?php echo $LANG['link_help']; ?></a><br />
			<a href="http://www.haudenschilt.com/fcms/"><?php echo $stgs_release; ?></a> - Copyright &copy; 2006/07 Ryan Haudenschilt.  
		</p>
	</div>
</body>
</html>