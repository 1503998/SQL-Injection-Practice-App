<?php

if(defined('LEPTON_PATH')) { die('By security reasons it is not permitted to load \'config.php\' twice!! Forbidden call from \''.$_SERVER['SCRIPT_NAME'].'\'!'); }

// config file created by LEPTON 2.2.0
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'hacklab2019');
define('DB_NAME', 'lepton');
define('TABLE_PREFIX', 'lep_');

define('LEPTON_PATH', dirname(__FILE__));
define('LEPTON_URL', 'http://127.0.0.1');
define('ADMIN_PATH', LEPTON_PATH.'/admins');
define('ADMIN_URL', LEPTON_URL.'/admins');

define('LEPTON_GUID', '803c7313-9449-4d64-8cdc-9b400c62b91b');

define('WB_URL', LEPTON_URL);
define('WB_PATH', LEPTON_PATH);

if (!defined('LEPTON_INSTALL')) require_once(LEPTON_PATH.'/framework/initialize.php');

?>