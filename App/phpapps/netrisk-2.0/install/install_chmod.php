<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="installer.css" />
<title>NetRisk Installer: Step 3 / 4</title>
</head>
<body>

<?php

	// define variables
	$msg = null;
	$error = null;

	// mysql
	$mysql_hostname = $_POST['mysql_hostname'];
	$mysql_username = $_POST['mysql_username'];
	$mysql_password = $_POST['mysql_password'];
	$mysql_database = $_POST['mysql_database'];
	$mysql_prefix = $_POST['mysql_prefix'];
	
	// administrator
	$admin_username = $_POST['admin_username'];
	$admin_password = $_POST['admin_password'];
	$admin_email = $_POST['admin_email'];

	// server
	$doc_root = $_POST['doc_root'];
	$web_root = $_POST['web_root'];
	$game_path = $_POST['game_path'];

	/*
	$dir	=	array(
					'upload/',
					'upload/tn/'
				);

	while(list(, $val) = each($dir)) {
		if($fp = @fopen('../' . $val . 'test', 'w')) {
			fclose($fp);
			unlink('../' . $val . 'test');
		}
		else {
			$error[] = '<strong>ERROR:</strong> Write permission is needed on the directory /' . $val;
		}
	}
	*/

?>

<div id="netrisk_root">
	<div id="netrisk_head">
		<div id="netrisk_page_title">
			<h1><a href="#" onclick="javascript:return false">NetRisk Installer</a></h1>
		</div>
		<div id="netrisk_page_description">
			<h2>Step: 3 / 4</h2>
		</div>
	</div>
	<div id="netrisk_body">
		<div id="netrisk_block_body">
			<div><img src="images/netrisk.jpg" alt="NetRisk" /></div>
		</div>
		<div id="netrisk_main">
			<form name="install" id="install" method="post" action="install_finish.php">
				<input type="hidden" name="mysql_hostname" id="mysql_hostname" value="<?php echo $mysql_hostname; ?>" />
				<input type="hidden" name="mysql_username" id="mysql_username" value="<?php echo $mysql_username; ?>" />
				<input type="hidden" name="mysql_password" id="mysql_password" value="<?php echo $mysql_password; ?>" />
				<input type="hidden" name="mysql_database" id="mysql_database" value="<?php echo $mysql_database; ?>" />
				<input type="hidden" name="mysql_prefix" id="mysql_prefix" value="<?php echo $mysql_prefix; ?>" />
				<input type="hidden" name="admin_username" id="admin_username" value="<?php echo $admin_username; ?>" />
				<input type="hidden" name="admin_password" id="admin_password" value="<?php echo $admin_password; ?>" />
				<input type="hidden" name="admin_email" id="admin_email" value="<?php echo $admin_email; ?>" />
				<input type="hidden" name="doc_root" id="doc_root" value="<?php echo $doc_root; ?>" />
				<input type="hidden" name="web_root" id="web_root" value="<?php echo $web_root; ?>" />
				<input type="hidden" name="game_path" id="game_path" value="<?php echo $game_path; ?>" />
				<fieldset>
					<legend>Messages</legend>
<?php

	if(isset($error)) {
		echo "\t\t\t\t\t" . '<ul>' . "\n";
		
		while(list(, $val) = each($error)) {
			echo "\t\t\t\t\t\t" . '<li>' . $val . '</li>' . "\n";
		}
		
		echo "\t\t\t\t\t" . '</ul>' . "\n";
		
		$next = 'disabled';
	}
	else {
		echo "\t\t\t\t\t" . '<ul>' . "\n";
		echo "\t\t\t\t\t\t" . '<li>The script has write permissions to all required directories.</li>' . "\n";
		echo "\t\t\t\t\t\t" . '<li>Click on "Next step" to continue installation.</li>' . "\n";
		echo "\t\t\t\t\t" . '</ul>' . "\n";
		
		$next = null;
	}

?>
				</fieldset>
				<fieldset>
					<legend>Options</legend>
					<input type="reset" value="Go back" onClick="javascript:history.go(-1);return false" class="netrisk_button" />
					<input type="reset" value="Try again" onClick="javascript:document.getElementById('install').action='install_chmod.php';submit();" class="netrisk_button" />
					<input type="submit" value="Next step"<?php echo $next; ?> class="netrisk_button" />
				</fieldset>
			</form>
		</div>
	</div>
	<div id="netrisk_copy"></div>
	<div id="netrisk_foot"></div>
</div>

</body>
</html>