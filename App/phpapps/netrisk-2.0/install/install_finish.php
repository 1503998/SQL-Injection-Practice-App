<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="installer.css" />
<title>NetRisk Installer: Step 4 / 4</title>
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

	if($fp = @fopen($doc_root . '/config.php', 'w')) {
		fputs($fp, '<?php' . "\n\n");
		fputs($fp, "\t" . '$mysql_hostname' . "\t" . '= \'' . $mysql_hostname . '\';' . "\n");
		fputs($fp, "\t" . '$mysql_username' . "\t" . '= \'' . $mysql_username . '\';' . "\n");
		fputs($fp, "\t" . '$mysql_password' . "\t" . '= \'' . $mysql_password . '\';' . "\n");
		fputs($fp, "\t" . '$mysql_database' . "\t" . '= \'' . $mysql_database . '\';' . "\n");
		fputs($fp, "\t" . '$mysql_prefix' . "\t" . '= \'' . $mysql_prefix . '\';' . "\n");
		fputs($fp, "\t" . '$doc_root' . "\t\t\t" . '= \'' . $doc_root . '\';' . "\n");
		fputs($fp, "\t" . '$web_root' . "\t\t\t" . '= \'' . $web_root . '\';' . "\n");
		fputs($fp, "\t" . '$game_path' . "\t\t\t" . '= \'' . $game_path . '\';' . "\n\n");
		fputs($fp, '?>');
		fclose($fp);
		
		$config_php = 1;
	}
	else {
		$config_php = 0;
	}

?>

<div id="netrisk_root">
	<div id="netrisk_head">
		<div id="netrisk_page_title">
			<h1 id="netrisk_page_title_text">NetRisk Installer</h1>
		</div>
		<div id="netrisk_page_description">
			<h2 id="netrisk_page_description_text">Step: 4 / 4</h2>
		</div>
	</div>
	<div id="netrisk_body">
		<div id="netrisk_block_body">
			<div><img src="images/netrisk.jpg" alt="NetRisk" /></div>
		</div>
		<div id="netrisk_main">
			<form>
				<fieldset>
					<legend>Messages</legend>
					<ul>
						<li>NetRisk has been successfully installed!</li>
						<li><strong>! IMPORTANT !</strong></li>
<?php

	if($config_php == 1) {
		echo "\t\t\t\t\t\t" . '<li>The file <strong>config.php</strong> has been automatically created for you in your document root. Please make sure that it exists. If not, copy and paste the content below into an empty file and name it config.php and upload it to your document root.</li>' . "\n";
	}
	else {
		echo "\t\t\t\t\t\t" . '<li>Please copy and paste this code into <strong>config.php</strong> and upload it to the root of your NetRisk Site:</li>' . "\n";
	}

?>
					</ul><br />
	
					<pre class="netrisk_code">&lt;?php

$mysql_hostname = <strong>'<?php echo $mysql_hostname; ?>'</strong>;
$mysql_username = <strong>'<?php echo $mysql_username; ?>'</strong>;
$mysql_password = <strong>'<?php echo $mysql_password; ?>'</strong>;
$mysql_database = <strong>'<?php echo $mysql_database; ?>';</strong>
$mysql_prefix   = <strong>'<?php echo $mysql_prefix; ?>'</strong>;

$doc_root       = <strong>'<?php echo $doc_root; ?>'</strong>;
$web_root       = <strong>'<?php echo $web_root; ?>'</strong>;
$game_path      = <strong>'<?php echo $game_path; ?>'</strong>;

?&gt;</pre><br />
			
				<strong>! IMPORTANT !</strong><br />
				Also, make sure to delete the directory <strong>/install</strong> and all of it's content.
				</fieldset>
				<fieldset>
					<legend>Options</legend>
					You may now login on your NetRisk Game with the username '<strong><?php echo $admin_username; ?></strong>' and password '<strong><?php echo $admin_password; ?></strong>'.<br /><br />
					<div class="netrisk_quote">
						<strong>To go directly to your NetRisk Site, click on the below link:</strong><br /><br />
						<a href="<?php echo $web_root; ?>index.php"><?php echo $web_root; ?>index</a>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<div id="netrisk_copy"></div>
	<div id="netrisk_foot"></div>
</div>

</body>
</html>