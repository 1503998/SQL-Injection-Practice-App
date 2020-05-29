<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="./installer.css" />
<title>NetRisk Installer: Step 1 / 4</title>
</head>
<body>
<?php

	$doc_root = substr(dirname($_SERVER['SCRIPT_FILENAME']), 0, -7);
	$web_root = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('install', '', dirname($_SERVER['SCRIPT_NAME']));
	
	if(file_exists($doc_root . 'config.php')) {
		echo "\t" . '<p>Attention: <strong>config.php already exists. Please delete it before running the installer!</strong></p>' . "\n";
		echo '</body>' . "\n";
		echo '</html>' . "\n";
		exit;
	}

?>

<div id="netrisk_root">
	<div id="netrisk_head">
		<div id="netrisk_page_title">
			<h1><a href="#" onclick="javascript:return false">NetRisk Installer</a></h1>
		</div>
		<div id="netrisk_page_description">
			<h2>Step: 1 / 4</h2>
		</div>
	</div>
	<div id="netrisk_body">
		<div id="netrisk_block_body">
			<div><img src="images/netrisk.jpg" alt="NetRisk" /></div>
		</div>
		<div id="netrisk_main">
			<form id="install" method="post" action="install_mysql.php">
			<fieldset>
				<legend>MySQL Details</legend>
				<div class="netrisk_var">MySQL hostname</div>
				<div class="netrisk_val"><input type="text" name="mysql_hostname" id="mysql_hostname" size="40" value="localhost" class="netrisk_input" /></div>

				<div class="netrisk_var">MySQL username</div>
				<div class="netrisk_val"><input type="text" name="mysql_username" id="mysql_username" size="40" value="" class="netrisk_input" /></div>

				<div class="netrisk_var">MySQL password</div>
				<div class="netrisk_val"><input type="text" name="mysql_password" id="mysql_password" size="40" value="" class="netrisk_input" /></div>

				<div class="netrisk_var">MySQL database</div>
				<div class="netrisk_val"><input type="text" name="mysql_database" id="mysql_database" size="40" value="" class="netrisk_input" /></div>

				<div class="netrisk_var">MySQL prefix</div>
				<div class="netrisk_val"><input type="text" name="mysql_prefix" id="mysql_prefix" size="40" value="netrisk_" class="netrisk_input" /></div>
			</fieldset>
			<fieldset>
				<legend>Administrator</legend>
				<div class="netrisk_var">Username</div>
				<div class="netrisk_val"><input type="text" name="admin_username" id="admin_username" size="40" value="" class="netrisk_input" /></div>
				<div class="netrisk_var">Password</div>
				<div class="netrisk_val"><input type="text" name="admin_password" id="admin_password" size="40" value="" class="netrisk_input" /></div>
				<div class="netrisk_var">E-mail</div>
				<div class="netrisk_val"><input type="text" name="admin_email" id="admin_email" size="40" value="" class="netrisk_input" /></div><br />
				<div class="netrisk_quote">The below Salt PassPhrase is required to provide addtional password protection.  Example: "Mary Had a Little Lamb" (without quotes)</div>
				<div class="netrisk_var">Salt PassPhrase</div>
				<div class="netrisk_val"><input type="text" name="salt_password" id="salt_password" size="40" value="" class="netrisk_input" /></div><br />
			</fieldset>
			<fieldset>
				<legend>Server</legend>
				<div class="netrisk_quote">These values are auto-detected and usually works fine,<br/>but in some cases they may need some editing.</div><br />
				<div class="netrisk_var">Document root</div>
				<div class="netrisk_val"><input type="text" name="doc_root" id="doc_root" size="40" value="<?php echo $doc_root; ?>" class="netrisk_input" /></div>
				<div class="netrisk_var">Web root</div>
				<div class="netrisk_val"><input type="text" name="web_root" id="web_root" size="40" value="<?php echo $web_root; ?>" class="netrisk_input" /></div>
				<div class="netrisk_var">Game Path</div>
				<div class="netrisk_val"><input type="text" name="game_path" id="game_path" size="40" value="/netrisk/" class="netrisk_input" /></div>
			</fieldset>
			<fieldset>
				<legend>Options</legend>
				<input type="submit" value="Next step" class="netrisk_button" />
			</fieldset>
			</form>
		</div>
	</div>
	<div id="netrisk_copy"></div>
	<div id="netrisk_foot"></div>
</div>

</body>
</html>