<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="installer.css" />
<title>NetRisk Installer: Step 2 / 4</title>
<style type="text/css">
<!--

	.ok {
		color: #000;
	}
	
	.error {
		color: #900;
	}
	
	.alter {
		color: #F90;
	}
	
	.update {
		color: #69C;
	}
	
	.create {
		color: #090;
	}
	
	.none {
		color: #CCC;
	}

-->
</style>
</head>
<body>

<?php

	// fetch version info
	if(file_exists('../includes/version.ini')) {
		$v = parse_ini_file('../includes/version.ini', false);
	}
	else {
		$v['conf_current_version'] = null;
		$v['conf_current_buidl'] = null;
	}
	
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
	$admin_password = sha1($_POST['admin_password']);
	$admin_email = $_POST['admin_email'];
	
	// version variables
	$conf_current_version = $v['conf_current_version'];
	$conf_current_build = $v['conf_current_build'];
	
	//Salt Password
	$salt_password = sha1($_POST['salt_password']);
	
	// server
	$doc_root = $_POST['doc_root'];
	$web_root = $_POST['web_root'];
	$game_path = $_POST['game_path'];
	
	if(strlen($admin_username) == 0) {
		$error = 1;
		$msg[] = '<span class="error"><strong>Administrator:</strong> Invalid username!</span>';
	}
	
	if(strlen($_POST['admin_password']) < 4) {
		$error = 1;
		$msg[] = '<span class="error"><strong>Administrator:</strong> The password is too short!</span>';
	}
	
	if(strlen($admin_email) == 0) {
		$error = 1;
		$msg[] = '<span class="error"><strong>Administrator:</strong> An e-mail address is required!</span>';
	}
	
	if(strlen($doc_root) == 0) {
		$error = 1;
		$msg[] = '<span class="error">Document root: You have to specify the document root!</span>';
	}
	
	if(strlen($_POST['salt_password']) == 0) {
		$error = 1;
		$msg[] = '<span class="error"><strong>Salt PassPhrase:</strong>  You need to enter a Salt PassPhrase!</span>';
	}

	if(strlen($web_root) == 0) {
		$error = 1;
		$msg[] = '<span class="error">Web root: You have to specify the web root!</span>';
	}
	else if(substr($web_root, 0, 7) != 'http://' || substr($web_root, -1, 1) != '/') {
		$error = 1;
		$msg[] = '<span class="error">Web root: The web root must start with "http://" and end width "/" !</span>';
	}
	
	if(strlen($game_path) == 0) {
		$error = 1;
		$msg[] = '<span class="error">Game Patht: You have to specify the game path!</span>';
	}
	else if(substr($game_path, 0, 1) != '/' || substr($game_path, -1, 1) != '/') {
		$error = 1;
		$msg[] = '<span class="error">Game Path: The Game Path must start with and end width "/" !</span>';
	}
	
	
	// connect to host
	if(@mysql_connect($mysql_hostname, $mysql_username, $mysql_password)) {
		$msg[] = '<span class="ok"><strong>MySQL:</strong> Connected to host.</span>';
		
		// select database
		if(@mysql_select_db($mysql_database)) {
			$msg[] = '<span class="ok"><strong>MySQL:</strong> Selecting database "' . $mysql_database . '".</span>';

			// generate list of tables			
			$queryShowTables = 'SHOW TABLES FROM ' . $mysql_database;
			$qShowTables = mysql_query($queryShowTables);
			
			$tables = array();
			while($rShowTables = mysql_fetch_row($qShowTables)) {
				$tables[$rShowTables[0]] = 1;
			}
			
			// create netrisk_active_users
			$queryActiveUsers = 'CREATE TABLE ' . $mysql_prefix . 'active_users (username varchar(30) NOT NULL, timestamp int(11) unsigned NOT NULL, PRIMARY KEY (username)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

			if(!array_key_exists($mysql_prefix . 'active_users', $tables)) {
				mysql_query($queryActiveUsers);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'active_users created.</span>';
				
				// default values
				// No default Values
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'active_users!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}

			// create netrisk_colors			
			$queryColors = 'CREATE TABLE ' . $mysql_prefix . 'colors (id int(10) unsigned NOT NULL default \'0\', type int(10) unsigned NOT NULL default \'0\', color varchar(32) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'colors', $tables)) {
				mysql_query($queryColors);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'colors created.</span>';
				
				// default values
				//mysql_query('INSERT INTO ' . $mysql_prefix . 'blocks (id, block_topic, block_content, block_vis, block_pos, block_style, block_top) VALUES (1,\'{CALENDAR}\',NULL,\'1\',10,\'1\',\'1\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (1, 1, \'black\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (2, 1, \'blue\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (3, 1, \'gold\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (4, 1, \'green\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (5, 1, \'grey\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (6, 1, \'purple\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (7, 1, \'red\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (8, 1, \'teal\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (9, 1, \'yellow\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (10, 2, \'gold\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (11, 2, \'green\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (12, 2, \'orange\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (13, 2, \'pink\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (14, 2, \'red\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (15, 2, \'white\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (16, 2, \'yellow\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (17, 3, \'black\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (18, 3, \'blue\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (19, 3, \'gold\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (20, 3, \'green\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (21, 3, \'grey\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (22, 3, \'red\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (23, 3, \'teal\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (24, 3, \'wood\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'colors (id, type, color) VALUES (25, 3, \'yellow\');');
				
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'colors!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_config
			$queryConfig = 'CREATE TABLE ' . $mysql_prefix . 'config (conf_name varchar(40) NOT NULL default \'\', conf_value text, PRIMARY KEY (conf_name));';
			
			if(mysql_query($queryConfig) || array_key_exists($mysql_prefix . 'config', $tables)) {
				if(array_key_exists($mysql_prefix . 'config', $tables)) {
					$msg[] = '<span class="update"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'config exists. Updating data.</span>';
				}
				else {
					$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'config created.</span>';
				}

				// default values
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_admin_email\',\'' . $admin_email . '\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_admin_password\',\'' . $admin_password . '\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_admin_username\',\'' . $admin_username . '\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_current_build\',\'' . $conf_current_build . '\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_current_version\',\'' . $conf_current_version . '\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_date\',\'%Y-%m-%d %H:%M\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_lang_default\',\'English\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_next_gid\',\'1\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_page_title\',\'NetRisk\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_salt_password\',\'' . $salt_password . '\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_style_default\',\'Blue\');');
				
				mysql_query('INSERT INTO ' . $mysql_prefix . 'config (conf_name, conf_value) VALUES (\'conf_time_offset\',\'0\');');
				
				mysql_query('UPDATE ' . $mysql_prefix . 'config SET conf_value=\'' . $admin_email . '\' WHERE conf_name=\'conf_admin_email\';');
				mysql_query('UPDATE ' . $mysql_prefix . 'config SET conf_value=\'' . $admin_password . '\' WHERE conf_name=\'conf_admin_password\';');
				mysql_query('UPDATE ' . $mysql_prefix . 'config SET conf_value=\'' . $admin_username . '\' WHERE conf_name=\'conf_admin_username\';');
				
				mysql_query('UPDATE ' . $mysql_prefix . 'config SET conf_value=\'' . $conf_current_build . '\' WHERE conf_name=\'conf_current_build\';');
				mysql_query('UPDATE ' . $mysql_prefix . 'config SET conf_value=\'' . $conf_current_version . '\' WHERE conf_name=\'conf_current_version\';');
				mysql_query('UPDATE ' . $mysql_prefix . 'config SET conf_value=\'%Y-%m-%d %H:%M\' WHERE conf_name=\'conf_date\';');
				mysql_query('UPDATE ' . $mysql_prefix . 'config SET conf_value=\'Blue\' WHERE conf_name=\'conf_style_default\';');
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'config!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_continents
			$queryContinents = 'CREATE TABLE ' . $mysql_prefix . 'continents (mtype varchar(32) NOT NULL default \'0\', name varchar(32) NOT NULL default \'0\', states varchar(64) NOT NULL default \'0\', bonus int(3) unsigned NOT NULL default \'0\') ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'continents', $tables)) {
				mysql_query($queryContinents);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'continents created.</span>';
				
				// default values
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r1\', \'North America\', \'1,2,3,4,5,6,7,8,9,10\', 5);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r1\', \'South America\', \'11,12,13,14\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r1\', \'Europe\', \'21,22,23,24,25,26,27\', 5);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r1\', \'Asia\', \'28,29,30,31,32,33,34,35,36,37,38,39\', 7);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r1\', \'Africa\', \'15,16,17,18,19,20\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r1\', \'Australia\', \'40,41,42,43\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r2\', \'North America\', \'1,2,3,4,5,6,7,8,9,10,11\', 5);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r2\', \'South America\', \'12,13,14,15,16\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r2\', \'Europe\', \'23,24,25,26,27,28,29,30\', 5);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r2\', \'Asia\', \'31,32,33,34,35,36,37,38,39,40,41,42\', 7);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r2\', \'Africa\', \'17,18,19,20,21,22\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'continents (mtype, name, states, bonus) VALUES (\'r2\', \'Australia\', \'43,44,45,46,47,48\', 2);');
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'continents!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_countries
			$queryCountries = 'CREATE TABLE ' . $mysql_prefix . 'countries (mtype varchar(32) NOT NULL default \'\',  btype  varchar(32) NOT NULL default \'\', id smallint(4) unsigned NOT NULL default \'0\', name varchar(32) NOT NULL default \'\', adjacencies varchar(32) NOT NULL default \'\', card_type int(1) unsigned NOT NULL default \'0\') ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'countries', $tables)) {
				mysql_query($queryCountries);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'countries created.</span>';
				
				// default values
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 1, \'Alaska\', \'2,3,36\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 2, \'NW Territory\', \'1,3,4,6\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 3, \'Alberta\', \'1,2,4,6,8\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 4, \'Oikiotoluk\', \'2,5,6,7\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 5, \'Greenland\', \'4,7,25\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 6, \'Ontario\', \'2,3,4,7,8,9\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 7, \'Quebec\', \'4,5,6,9\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 8, \'Western US\', \'3,6,9,10\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 9, \'Eastern US\', \'6,7,8,10\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 10, \'Central America\', \'8,9,11\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 11, \'Venezuela\', \'10,12,14\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 12, \'Peru\', \'11,13,14\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 13, \'Argentina\', \'12,14\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 14, \'Brazil\', \'11,12,13,15\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 15, \'North Africa\', \'14,16,19,20,21,22\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 16, \'Congo\', \'15,17,19\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 17, \'South Africa\', \'16,18,19\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 18, \'Madagascar\', \'17,19\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 19, \'East Africa\', \'15,16,17,18,20,28\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 20, \'Egypt\', \'15,19,22,28\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 21, \'Western Europe\', \'15,22,23,24\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 22, \'Southern Europe\', \'15,20,21,24,27,28\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 23, \'Great Britain\', \'21,24,25,26\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 24, \'Northern Europe\', \'21,22,23,26,27\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 25, \'Iceland\', \'5,23,26\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 26, \'Scandinavia\', \'23,24,25,27\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 27, \'Ukraine\', \'22,24,26,28,29,30\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 28, \'Middle East\', \'19,20,22,27,29,38\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 29, \'Afghanistan\', \'27,28,30,37,38\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 30, \'Ural\', \'27,29,31,37\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 31, \'Siberia\', \'30,32,33,34,37\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 32, \'Yakutsk\', \'31,33,36\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 33, \'Irkutsk\', \'31,32,34,36\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 34, \'Mongolia\', \'31,33,35,36,37\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 35, \'Japan\', \'34,36\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 36, \'Kamchatka\', \'1,32,33,34,35\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 37, \'China\', \'29,30,31,34,38,39\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 38, \'India\', \'28,29,37,39\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 39, \'Siam\', \'37,38,40\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 40, \'Indonesia\', \'39,41,42\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 41, \'New Guinea\', \'40,42,43\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 42, \'Western Australia\', \'40,41,43\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R1\', \'org\', 43, \'Eastern Australia\', \'41,42\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 1, \'Alaska\', \'2,3,39\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 2, \'NW Territory\', \'1,3,4,6\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 3, \'Alberta\', \'1,2,4,6,8\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 4, \'Oikiotoluk\', \'2,5,6,7\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 5, \'Greenland\', \'4,6,7,27,28\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 6, \'Ontario\', \'2,3,4,5,7,8,10\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 7, \'Quebec\', \'4,5,6,10\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 8, \'Western US\', \'3,6,9,10,11\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 9, \'Hawaii\', \'8,38\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 10, \'Eastern US\', \'6,7,8,11\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 11, \'Central America\', \'8,10,11,12\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 12, \'Venezuela\', \'11,13,16\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 13, \'Peru\', \'12,14,16\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 14, \'Argentina\', \'13,16,15,48\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 15, \'Falkland Islands\', \'14,19\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 16, \'Brazil\', \'12,13,14,17\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 17, \'North Africa\', \'16,18,21,22,23,24\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 18, \'Congo\', \'17,19,21\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 19, \'South Africa\', \'15,18,20,21\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 20, \'Madagascar\', \'19,21\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 21, \'East Africa\', \'17,18,19,20,22,31\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 22, \'Egypt\', \'17,21,24,31\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 23, \'Western Europe\', \'17,24,25,26\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 24, \'Southern Europe\', \'17,22,23,26,30,31\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 25, \'Great Britain\', \'23,26,27,29\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 26, \'Northern Europe\', \'23,24,25,29,30\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 27, \'Iceland\', \'5,25,29\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 28, \'Svalbard\', \'5,29\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 29, \'Scandinavia\', \'25,26,27,28,30\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 30, \'Ukraine\', \'24,26,29,31,32,33\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 31, \'Middle East\', \'21,22,24,30,32,41\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 32, \'Afghanistan\', \'30,31,33,40,41\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 33, \'Ural\', \'30,32,34,40\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 34, \'Siberia\', \'33,35,36,37,40\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 35, \'Yakutsk\', \'34,36,39\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 36, \'Irkutsk\', \'34,35,37,39\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 37, \'Mongolia\', \'34,36,38,39,40\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 38, \'Japan\', \'9,37,39,44\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 39, \'Kamchatka\', \'1,35,36,37,38\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 40, \'China\', \'32,33,34,37,41,42\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 41, \'India\', \'31,32,40,42\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 42, \'Siam\', \'40,41,43\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 43, \'Indonesia\', \'42,44,45,46\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 44, \'Philippines\', \'38,43\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 45, \'New Guinea\', \'43,46,47\', 3);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 46, \'Western Australia\', \'43,45,47\', 1);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 47, \'Eastern Australia\', \'45,46,48\', 2);');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'countries (mtype, btype, id, name, adjacencies, card_type) VALUES (\'R2\', \'org\', 48, \'New Zealand\', \'14,47\', 3);');
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'countries!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_game_cards
			$queryGameCards = 'CREATE TABLE ' . $mysql_prefix . 'game_cards (gid int(10) unsigned NOT NULL default \'0\', pid int(10) unsigned NOT NULL default \'0\', pname varchar(32) NOT NULL, card int(10) unsigned NOT NULL default \'0\') ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'countries', $tables)) {
				mysql_query($queryGameCards);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'game_cards created.</span>';
				
				// default values
				// No Default Game Cards
				
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'game_cards!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_game_chat
			$queryGameChat = 'CREATE TABLE ' . $mysql_prefix . 'game_chat (id mediumint(7) NOT NULL auto_increment, time bigint(11) NOT NULL default \'0\', gid mediumint(7) NOT NULL, gname text NOT NULL, name tinytext NOT NULL, text text NOT NULL, url text NOT NULL, UNIQUE KEY tid (id)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

			if(!array_key_exists($mysql_prefix . 'game_chat', $tables)) {
				mysql_query($queryGameChat);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'game_chat created.</span>';
				
				// default values
				mysql_query('INSERT INTO ' . $mysql_prefix . 'game_chat (id, time, gid, gname, name, text, url) VALUES (1, 1209049190, 0, \'main_chat\', \'' . $admin_username . '\', \'Welcome to your Netrisk Installation\', \'\');');
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'game_chat!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_game_data
			$queryGameData = 'CREATE TABLE ' . $mysql_prefix . 'game_data (gid smallint(6) NOT NULL default \'0\', gname varchar(64) NOT NULL default \'\', pid smallint(4) NOT NULL default \'0\', pname varchar(32) NOT NULL default \'\', pterritory smallint(4) NOT NULL default \'0\', parmies smallint(4) NOT NULL default \'0\') ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'game_data', $tables)) {
				mysql_query($queryGameData);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'game_data created.</span>';
				
				// default values
				// No Default Game Data
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'game_data!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_game_info
			$queryGameInfo = 'CREATE TABLE ' . $mysql_prefix . 'game_info (gid int(10) unsigned NOT NULL default \'0\', gname varchar(32) NOT NULL, gpass varchar(32) NOT NULL, gstate varchar(32) NOT NULL, gmode varchar(16) NOT NULL default \'individual\', gtype varchar(16) NOT NULL default \'domination\', unit_type smallint(3) NOT NULL default \'1\', blind_man smallint(3) NOT NULL default \'0\', players smallint(3) unsigned NOT NULL default \'0\', capacity smallint(3) NOT NULL default \'0\', kibitz smallint(3) unsigned NOT NULL default \'0\', card_rules varchar(6) NOT NULL default \'US\', trade_value smallint(3) unsigned NOT NULL default \'4\', map_type varchar(32) NOT NULL default \'R1\', css_style varchar(32) NOT NULL default \'org\', last_move int(15) NOT NULL default \'0\', time_limit int(15) NOT NULL default \'0\', custom_rules tinyblob) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'game_info', $tables)) {
				mysql_query($queryGameInfo);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'game_info created.</span>';
				
				// default values
				// No Default Game Info
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'game_info!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_game_log
			$queryGameLog = 'CREATE TABLE ' . $mysql_prefix . 'game_log (tid smallint(7) NOT NULL auto_increment, gid smallint(7) NOT NULL, gname tinytext NOT NULL, time int(15) NOT NULL default \'0\', pid smallint(7) NOT NULL, player tinytext NOT NULL, text tinyblob NOT NULL, UNIQUE KEY tid (tid)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

			if(!array_key_exists($mysql_prefix . 'game_log', $tables)) {
				mysql_query($queryGameLog);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'game_log created.</span>';
				
				// default values
				// No default game logs
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'game_log!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_game_players
			$queryGamePlayers = 'CREATE TABLE ' . $mysql_prefix . 'game_players (gid smallint(6) NOT NULL default \'0\', gname varchar(64) NOT NULL default \'\', pid smallint(4) NOT NULL default \'0\', pname varchar(32) NOT NULL default \'\', phost smallint(4) NOT NULL default \'0\', pcolor varchar(25) NOT NULL default \'\', pstate varchar(25) NOT NULL default \'\', pattackcard smallint(25) NOT NULL default \'0\', pnumarmy smallint(4) NOT NULL default \'0\', pmission smallint(4) NOT NULL default \'0\', pcaporg varchar(25) NOT NULL default \'\', pmail smallint(4) NOT NULL default \'0\', pvote smallint(4) NOT NULL default \'0\', pkick smallint(4) NOT NULL default \'0\', pkills smallint(4) NOT NULL default \'0\', ppoints smallint(4) NOT NULL default \'0\') ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'game_players', $tables)) {
				mysql_query($queryGamePlayers);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'game_players created.</span>';
				
				// default values
				// No Default game players
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'game_players!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_historical_log
			$queryGlog = 'CREATE TABLE ' . $mysql_prefix . 'historical_log (id int(10) NOT NULL auto_increment, gid smallint(6) NOT NULL default \'0\', gname varchar(32) NOT NULL default \'\', players int(3) unsigned NOT NULL default \'0\', card_rules varchar(6) NOT NULL default \'\', map_type varchar(32) NOT NULL default \'\', first varchar(32) NOT NULL default \'\', second varchar(32) NOT NULL default \'\', third varchar(32) NOT NULL default \'----\', fourth varchar(32) NOT NULL default \'----\', fifth varchar(32) NOT NULL default \'----\', sixth varchar(32) NOT NULL default \'----\', seventh varchar(32) NOT NULL default \'----\', eighth varchar(32) NOT NULL default \'----\', mission varchar(10) NOT NULL default \'\', miss_obj varchar(64) NOT NULL default \'\', game_style varchar(10) NOT NULL, points int(11) NOT NULL, kills int(11) NOT NULL, time int(15) NOT NULL, PRIMARY KEY  (id)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

			if(!array_key_exists($mysql_prefix . 'historical_log', $tables)) {
				mysql_query($queryGlog);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'historical_log created.</span>';
				
				// default values
				// No default hisotorical logs
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'historical_log!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			
			
			// create netrisk_map_types
			$queryMapTypes = 'CREATE TABLE ' . $mysql_prefix . 'map_types (id int(3) unsigned NOT NULL default \'0\', game_style varchar(20) default NULL, map_type varchar(20) default NULL, map_style varchar(20) default NULL, css_file varchar(20) default NULL, PRIMARY KEY  (id)) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'map_types', $tables)) {
				mysql_query($queryMapTypes);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'map_types created.</span>';
				
				// default values
				mysql_query('INSERT INTO ' . $mysql_prefix . 'map_types VALUES (1, \'R1\', \'Risk I\', \'Original\', \'org\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'map_types VALUES (2, \'R1\', \'Risk I\', \'GreyScale\', \'grey\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'map_types VALUES (3, \'R1\', \'Risk I\', \'Topographic\', \'top\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'map_types VALUES (4, \'R1\', \'Risk I\', \'Black and White\', \'bw\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'map_types VALUES (5, \'R1\', \'Risk I\', \'Folded\', \'fol\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'map_types VALUES (6, \'R2\', \'Risk II\', \'Original\', \'org\');');
				mysql_query('INSERT INTO ' . $mysql_prefix . 'map_types VALUES (7, \'R2\', \'Risk II\', \'GreyScale\', \'grey\');');				
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'map_types!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_mission
			$queryMissions = 'CREATE TABLE ' . $mysql_prefix . 'missions (id int(3) unsigned NOT NULL default \'0\', game_style varchar(20) default NULL, map_type varchar(20) default NULL, map_style varchar(20) default NULL, css_file varchar(20) default NULL, PRIMARY KEY  (id)) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

			if(!array_key_exists($mysql_prefix . 'missions', $tables)) {
				mysql_query($queryMissions);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'missions created.</span>';
				
				// default values
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'missions!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			// create netrisk_users
			$queryUsers = 'CREATE TABLE ' . $mysql_prefix . 'users (id smallint(6) unsigned NOT NULL auto_increment, rank int(2) NOT NULL default \'0\', login varchar(32) NOT NULL default \'\', password varchar(40) NOT NULL, email varchar(50) NOT NULL default \'\', bio text NOT NULL, avatar mediumblob NOT NULL, image_type varchar(25) NOT NULL default \'\', image_name varchar(60) NOT NULL default \'\', win smallint(4) NOT NULL default \'0\', loss smallint(4) NOT NULL default \'0\', games_played smallint(4) NOT NULL default \'0\', total_players smallint(4) NOT NULL default \'0\', kills smallint(4) NOT NULL default \'0\', points smallint(4) NOT NULL default \'0\', PRIMARY KEY  (id), UNIQUE KEY login (login)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;';

			if(!array_key_exists($mysql_prefix . 'users', $tables)) {
				mysql_query($queryUsers);
				$msg[] = '<span class="create"><strong>MySQL:</strong> Table ' . $mysql_prefix . 'users created.</span>';
				
				// default values
				mysql_query('INSERT INTO ' . $mysql_prefix . 'users (id, rank, login, password, email) VALUES (1, 70, \'' . $admin_username . '\', \'' . sha1($salt_password . $admin_password) . '\', \'' . $admin_email . '\');');
			}
			else {
				$error = 1;
				$msg[] = '<span class="error"><strong>MySQL:</strong> Could not create ' . $mysql_prefix . 'users!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
			}
			
			

		}
		else {
			$error = 1;
			$msg[] = '<span class="error"><strong>MySQL:</strong> Database "' . $mysql_database . '" does not exist!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
		}
		mysql_close();
	}
	else {
		$error = 1;
		$msg[] = '<span class="error"><strong>MySQL:</strong> Could not connect to "' . $mysql_hostname . '"!<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
		$msg[] = '<span class="error"><strong>MySQL:</strong> Make sure you entered correct hostname, username and password.<br />' . mysql_errno() . ' ' . mysql_error() . '</span>';
	}

?>

<div id="netrisk_root">
	<div id="netrisk_head">
		<div id="netrisk_page_title">
			<h1><a href="#" onclick="javascript:return false">NetRisk Installer</a></h1>
		</div>
		<div id="netrisk_page_description">
			<h2>Step: 2 / 4</h2>
		</div>
	</div>
	<div id="netrisk_body">
		<div id="netrisk_block_body">
			<div><img src="images/netrisk.jpg" alt="NetRisk" /></div>
		</div>
		<div id="netrisk_main">
			<form id="install" method="post" action="install_chmod.php">
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
<?php

	if($error == 1) {
		$next = ' disabled="disabled"';
	}
	else {
		$next = null;
	}

	if(isset($msg)) {
		echo "\t\t\t" . '<fieldset>' . "\n";
		echo "\t\t\t\t" . '<legend>Status</legend>' . "\n";
		echo "\t\t\t\t" . '<ul>' . "\n";
		while(list(, $val) = each($msg)) {
			echo "\t\t\t\t\t" . '<li>' . $val . "</li>\n";
		}
		echo "\t\t\t\t" . '</ul>' . "\n";
		echo "\t\t\t" . '</fieldset>' . "\n";
	}

?>
				<fieldset>
					<legend>Options</legend>
					<input type="reset" value="Go back" onclick="javascript:history.go(-1);return false" class="netrisk_button" />
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