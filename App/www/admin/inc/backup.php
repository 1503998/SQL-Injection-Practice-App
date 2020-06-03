<?	
	require("../config.php");
	if (isset($_POST['backup'])) {
	$host=$eb_server;
	$base=$eb_db;
	$login=$eb_user;
	$password=$eb_password;
	
	require("inc/sqlbackup.class.php");
	$sav = new sqlbackup;
	$sav->config( $host, $login, $password, $base);					
	$t = date("Y-m-D-w");
	$sav->backup("eb_backup_{$t}.sql","backup/");	
exit();
	}
	else {
		## Define $template
		$template = new template;
		## Change title to "Evilboard Admin Control Panel - Forum Preview :: Powered by EvilBoard"
		$template->top("EvilBoard Admin Control Panel - Backup :: Powered by EvilBoard");
		## Create the header with the dynamic drop down menus
		$template->_header();
		## Create the iFrame with the forum
		return "<span id=\"b\">&nbsp;EvilBoard Forum Backup</span><br>&nbsp;If you feel that you need a backup of all tables in database ({$eb_db}),<br>&nbsp;You can click on the button at the end of this page to download a SQL backup.<br><div align=\"center\"><BR><form action=\"\" method=\"post\"><input name=\"backup\" type=\"submit\" class=\"eb_header\" value=\"Download Backup\"></form></div>";
	}
?>