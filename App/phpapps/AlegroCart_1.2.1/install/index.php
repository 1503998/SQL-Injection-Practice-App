<?php

require('common.php');

// Include Config and Initialisation
//require('../config.php');
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');
define('DIR_BASE', getbasepath());
define('HTTP_BASE', getbaseurl());
define('HTTPS_BASE', '');
require('../common.php');

$step=(isset($_REQUEST['step']))?$_REQUEST['step']:1;
if (filesize('../config.php') > 0) { $step=3; }

$errors = array();

$files=array('config.php','admin'.DIRECTORY_SEPARATOR.'config.php','cache'.DIRECTORY_SEPARATOR,'image'.DIRECTORY_SEPARATOR,'image'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR,'download'.DIRECTORY_SEPARATOR);
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!file_exists($file)) { $errors[]="'$file' was not found! (ensure you have uploaded it)"; }
	elseif (!is_writable($file)) { $errors[]="'$file' is not writable! (chmod a+w)"; }
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Installation</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
		<h1>AlegroCart</h1>
		<div id="container">
<?php 
	if (!empty($errors)) { ?>
		<p>The following errors occured:</p>
		<?php foreach ($errors as $error) {?>
		<div class="warning"><?php echo $error;?></div><br>
		<?php } ?>
		<p>Please fix the above error(s), install halted!</p>
<?php
	} else {
		switch ($step) {
			case '1':
				require('step1.php');
				break;
			case '2':
				require('step2.php');
				break;
			case '3':
				require('step3.php');
				break;
		}
	}
?>
		</div>
		<div class="center"><a href="http://www.alegrocart.com/">AlegroCart</a></div>
	</body>
</html>
