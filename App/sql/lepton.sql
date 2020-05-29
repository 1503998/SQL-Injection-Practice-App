-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 25, 2020 at 11:48 AM
-- Server version: 5.6.35
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lepton`
--

-- --------------------------------------------------------

--
-- Table structure for table `lep_addons`
--

CREATE TABLE `lep_addons` (
  `addon_id` int(11) NOT NULL,
  `type` varchar(128) NOT NULL DEFAULT '',
  `directory` varchar(128) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `function` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(255) NOT NULL DEFAULT '',
  `guid` varchar(50) DEFAULT NULL,
  `platform` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `license` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_addons`
--

INSERT INTO `lep_addons` (`addon_id`, `type`, `directory`, `name`, `description`, `function`, `version`, `guid`, `platform`, `author`, `license`) VALUES
(1, 'module', 'captcha_control', 'Captcha and Advanced-Spam-Protection (ASP) Control', 'Admin-Tool to control CAPTCHA and ASP', 'tool', '2.0.2', 'c29c5f1a-a72a-4137-b5cd-62982809bd38', '1.x', 'Thomas Hornik (thorn), LEPTON Project', 'GNU General Public License'),
(2, 'module', 'code2', 'Code2', 'This module allows you to execute PHP, HTML, Javascript commands and internal comments (<span style=\"color:#FF0000;\">limit access to users you can trust!</span>).', 'page', '2.3.1', 'e5e36d7f-877a-4233-8dac-e1481c681c8d', '1.3', 'Ryan Djurovich, Chio Maisriml, Thorn, Aldus.', 'GNU General Public License'),
(3, 'module', 'droplets', 'Droplets', 'This tool allows you to manage your local droplets.', 'tool', '2.1.2', '8b5b5074-993e-421a-9aff-2e32ae1601d5', '2.x', 'LEPTON Project', 'GNU General Public License');

-- --------------------------------------------------------

--
-- Table structure for table `lep_groups`
--

CREATE TABLE `lep_groups` (
  `group_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `system_permissions` text NOT NULL,
  `module_permissions` text NOT NULL,
  `template_permissions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_groups`
--

INSERT INTO `lep_groups` (`group_id`, `name`, `system_permissions`, `module_permissions`, `template_permissions`) VALUES
(1, 'Administrators', 'pages,pages_view,pages_add,pages_add_l0,pages_settings,pages_modify,pages_delete,media,media_view,media_upload,media_rename,media_delete,media_create,addons,modules,modules_view,modules_install,modules_uninstall,templates,templates_view,templates_install,templates_uninstall,languages,languages_view,languages_install,languages_uninstall,settings,settings_basic,settings_advanced,access,users,users_view,users_add,users_modify,users_delete,groups,groups_view,groups_add,groups_modify,groups_delete,admintools,service', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_captcha_control`
--

CREATE TABLE `lep_mod_captcha_control` (
  `enabled_captcha` varchar(1) NOT NULL DEFAULT '1',
  `enabled_asp` varchar(1) NOT NULL DEFAULT '0',
  `captcha_type` varchar(255) NOT NULL DEFAULT 'calc_text',
  `asp_session_min_age` int(11) NOT NULL DEFAULT '20',
  `asp_view_min_age` int(11) NOT NULL DEFAULT '10',
  `asp_input_min_age` int(11) NOT NULL DEFAULT '5',
  `ct_text` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_captcha_control`
--

INSERT INTO `lep_mod_captcha_control` (`enabled_captcha`, `enabled_asp`, `captcha_type`, `asp_session_min_age`, `asp_view_min_age`, `asp_input_min_age`, `ct_text`) VALUES
('1', '1', 'calc_text', 20, 10, 5, ''),
('1', '1', 'calc_text', 20, 10, 5, '');

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_code2`
--

CREATE TABLE `lep_mod_code2` (
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `whatis` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_droplets`
--

CREATE TABLE `lep_mod_droplets` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `code` longtext NOT NULL,
  `description` text NOT NULL,
  `modified_when` int(11) NOT NULL DEFAULT '0',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `admin_edit` int(11) NOT NULL DEFAULT '0',
  `admin_view` int(11) NOT NULL DEFAULT '0',
  `show_wysiwyg` int(11) NOT NULL DEFAULT '0',
  `comments` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_droplets`
--

INSERT INTO `lep_mod_droplets` (`id`, `name`, `code`, `description`, `modified_when`, `modified_by`, `active`, `admin_edit`, `admin_view`, `show_wysiwyg`, `comments`) VALUES
(1, 'check-css', '// ---- begin droplet\r\n$ch = curl_init();\r\ncurl_setopt($ch, CURLOPT_URL, \"http://www.lepton-cms.org/_packinstall/check-css.txt\" );\r\ncurl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);\r\n$source = curl_exec($ch);\r\ncurl_close($ch);\r\n       \r\nreturn $source;\r\n// end droplet', 'this droplets displays standard css of your template file', 1582116037, 1, 1, 0, 0, 0, 'Usage:[[check-css]]'),
(2, 'EditThisPage', '// This Droplet will show a link to the backend page editor. This is only shown when the user is logged in and has the correct permissions to edit the page in question.\r\nglobal $wb, $page_id, $HEADING, $database, $admin;\r\n$str = \" \";		\r\nif (FRONTEND_LOGIN == \'enabled\' AND is_numeric($wb->get_session(\'USER_ID\'))) {\r\n	if ($page_id) \r\n		$this_page = $page_id;\r\n	else\r\n		$this_page = $wb->default_page_id;\r\n	$results = $database->query(\"SELECT * FROM \".TABLE_PREFIX.\"pages WHERE page_id = \'$this_page\'\");\r\n	$results_array = $results->fetchRow();\r\n	$old_admin_groups = explode(\',\', $results_array[\'admin_groups\']);\r\n	$old_admin_users = explode(\',\', $results_array[\'admin_users\']);\r\n	$this_user = $wb->get_session(\'GROUP_ID\');\r\n	$query = \"SELECT * FROM \".TABLE_PREFIX.\"pages WHERE page_id = \'\".$page_id.\"\'\";\r\n	$get_pages = $database->query($query);\r\n	$page = $get_pages->fetchRow();\r\n	$admin_groups = explode(\',\', str_replace(\'_\', \'\', $page[\'admin_groups\']));\r\n	$admin_users = explode(\',\', str_replace(\'_\', \'\', $page[\'admin_users\']));\r\n	$in_group = FALSE;\r\n	foreach($admin->get_groups_id() as $cur_gid)\r\n		if (in_array($cur_gid, $admin_groups)) $in_group = TRUE;\r\n	if (($in_group) OR is_numeric(array_search($this_user, $old_admin_groups)) ) {\r\n		$str  = \'<a href=\"\' . ADMIN_URL . \'/pages/modify.php?page_id=\'.$this_page;\r\n		$str .= \'\" target=\"_blank\"><img align=\"left\" border=\"0\" src=\"\';\r\n		$str .= THEME_URL . \'/images/modify_16.png\" alt=\"\' . $HEADING[\'MODIFY_PAGE\'] . \'\" />Edit Page</a>\';\r\n	}      \r\n}\r\nreturn $str;', 'Shows an \"Edit page\" link in the frontend', 1582116037, 1, 1, 0, 0, 0, 'Usage: [[editthispage]]'),
(3, 'EmailFilter', ' \r\n// You can configure the output filtering with the options below.\r\n// Tip: Mailto links can be encrypted by a Javascript function. \r\n// To make use of this option, one needs to add the PHP code \r\n//       register_frontend_modfiles(\'js\');\r\n// into the <head> section of the index.php of your template. \r\n// Without this modification, only the @ character in the mailto part will be replaced.\r\n\r\n// Basic Email Configuration: \r\n// Filter Email addresses in text 0 = no, 1 = yes - default 1\r\n$filter_settings[\'email_filter\'] = \'1\';\r\n\r\n// Filter Email addresses in mailto links 0 = no, 1 = yes - default 1\r\n$filter_settings[\'mailto_filter\'] = \'1\';\r\n\r\n// Email Replacements, replace the \'@\' and the \'.\' by default (at) and (dot)\r\n$filter_settings[\'at_replacement\']  = \'(at)\';\r\n$filter_settings[\'dot_replacement\'] = \'(dot)\';\r\n\r\n// No need to change stuff underneatch unless you know what you are doing.\r\n\r\n// work out the defined output filter mode: possible output filter modes: [0], 1, 2, 3, 6, 7\r\n// 2^0 * (0.. disable, 1.. enable) filtering of mail addresses in text\r\n// 2^1 * (0.. disable, 1.. enable) filtering of mail addresses in mailto links\r\n// 2^2 * (0.. disable, 1.. enable) Javascript mailto encryption (only if mailto filtering enabled)\r\n\r\n// only filter output if we are supposed to\r\nif($filter_settings[\'email_filter\'] != \'1\' && $filter_settings[\'mailto_filter\'] != \'1\'){\r\n	// nothing to do ...\r\n	return true;\r\n}\r\n\r\n// check if non mailto mail addresses needs to be filtered\r\n$output_filter_mode = ($filter_settings[\'email_filter\'] == \'1\') ? 1 : 0;		// 0|1\r\n	\r\n// check if mailto mail addresses needs to be filtered\r\nif($filter_settings[\'mailto_filter\'] == \'1\')\r\n{\r\n	$output_filter_mode = $output_filter_mode + 2;								// 0|2\r\n					\r\n        // check if Javascript mailto encryption is enabled (call register_frontend_functions in the template)\r\n        $search_pattern = \'/<.*src=\\\".*\\/mdcr.js.*>/iU\';\r\n        if(preg_match($search_pattern, $wb_page_data))\r\n        {\r\n          $output_filter_mode = $output_filter_mode + 4;       // 0|4\r\n        } else {\r\n        	$mdcr_script_url = LEPTON_URL.\"/modules/droplets/js/mdcr.js\";\r\n        	$mdcr_script_tag = \"\\n<script type=\'text/javascript\' src=\'\".$mdcr_script_url.\"\'></script>\\n\";\r\n        	$wb_page_data = str_replace(\"</head>\", $mdcr_script_tag.\"</head>\", $wb_page_data);\r\n        	$output_filter_mode = $output_filter_mode + 4;       // 0|4\r\n        }\r\n}\r\n		\r\n// define some constants so we do not call the database in the callback function again\r\ndefine(\'OUTPUT_FILTER_MODE\', (int) $output_filter_mode);\r\ndefine(\'OUTPUT_FILTER_AT_REPLACEMENT\', $filter_settings[\'at_replacement\']);\r\ndefine(\'OUTPUT_FILTER_DOT_REPLACEMENT\', $filter_settings[\'dot_replacement\']);\r\n	\r\n// function to filter mail addresses embedded in text or mailto links before outputing them on the frontend\r\nif (!function_exists(\'filter_mail_addresses\')) {\r\n	function filter_mail_addresses($match) { \r\n		\r\n	// check if required output filter mode is defined\r\n		if(!(defined(\'OUTPUT_FILTER_MODE\') && defined(\'OUTPUT_FILTER_MODE\') && defined(\'OUTPUT_FILTER_MODE\'))) {\r\n			return $match[0];\r\n		}\r\n		\r\n		$search = array(\'@\', \'.\');\r\n		$replace = array(OUTPUT_FILTER_AT_REPLACEMENT ,OUTPUT_FILTER_DOT_REPLACEMENT);\r\n		\r\n		// check if the match contains the expected number of subpatterns (6|8)\r\n		if(count($match) == 8) {\r\n			/**\r\n				OUTPUT FILTER FOR EMAIL ADDRESSES EMBEDDED IN TEXT\r\n			**/\r\n			\r\n			// 1.. text mails only, 3.. text mails + mailto (no JS), 7 text mails + mailto (JS)\r\n			if(!in_array(OUTPUT_FILTER_MODE, array(1,3,7))) return $match[0];\r\n\r\n			// do not filter mail addresses included in input tags (<input ... value = \"test@mail)\r\n			if (strpos($match[6], \'value\') !== false) return $match[0];\r\n			\r\n			// filtering of non mailto email addresses enabled\r\n			return str_replace($search, $replace, $match[0]);\r\n				\r\n		} elseif(count($match) == 6) {\r\n			/**\r\n				OUTPUT FILTER FOR EMAIL ADDRESSES EMBEDDED IN MAILTO LINKS\r\n			**/\r\n\r\n			// 2.. mailto only (no JS), 3.. text mails + mailto (no JS), 6.. mailto only (JS), 7.. all filters active\r\n			if(!in_array(OUTPUT_FILTER_MODE, array(2,3,6,7))) return $match[0];\r\n			\r\n			// check if last part of the a href link: >xxxx</a> contains a email address we need to filter\r\n			$pattern = \'#[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\\.)+[A-Z]{2,4}#i\';\r\n			if(preg_match_all($pattern, $match[5], $matches)) {\r\n				foreach($matches as $submatch) {\r\n					foreach($submatch as $value) {\r\n						// replace all . and all @ in email address parts by (dot) and (at) strings\r\n						$match[5] = str_replace($value, str_replace($search, $replace, $value), $match[5]);\r\n					}\r\n				}\r\n			}\r\n\r\n			// check if Javascript encryption routine is enabled\r\n			if(in_array(OUTPUT_FILTER_MODE, array(6,7))) {\r\n				/** USE JAVASCRIPT ENCRYPTION FOR MAILTO LINKS **/\r\n				\r\n				// extract possible class and id attribute from ahref link\r\n				preg_match(\'/class\\s*?=\\s*?(\"|\\\')(.*?)\\1/ix\', $match[0], $class_attr);\r\n				$class_attr = empty($class_attr) ? \'\' : \'class=\"\' . $class_attr[2] . \'\" \';\r\n				preg_match(\'/id\\s*?=\\s*?(\"|\\\')(.*?)\\1/ix\', $match[0], $id_attr);\r\n				$id_attr = empty($id_attr) ? \'\' : \'id=\"\' . $id_attr[2] . \'\" \';\r\n				\r\n				// preprocess mailto link parts for further usage\r\n				$search = array(\'@\', \'.\', \'_\', \'-\'); $replace = array(\'F\', \'Z\', \'X\', \'K\');\r\n				$email_address = str_replace($search, $replace, strtolower($match[2]));\r\n				$email_subject = rawurlencode(html_entity_decode($match[3]));\r\n				\r\n				// create a random encryption key for the Caesar cipher\r\n				mt_srand((double)microtime()*1000000);	// (PHP < 4.2.0)\r\n				$shift = mt_rand(1, 25);\r\n				\r\n				// encrypt the email using an adapted Caesar cipher\r\n		  		$encrypted_email = \"\";\r\n				for($i = strlen($email_address) -1; $i > -1; $i--) {\r\n					if(preg_match(\'#[FZXK0-9]#\', $email_address[$i], $characters)) {\r\n						$encrypted_email .= $email_address[$i];\r\n					} else {	\r\n						$encrypted_email .= chr((ord($email_address[$i]) -97 + $shift) % 26 + 97);\r\n					}\r\n				}\r\n				$encrypted_email .= chr($shift + 97);\r\n\r\n				// build the encrypted Javascript mailto link\r\n				$mailto_link  = \"<a {$class_attr}{$id_attr}href=\\\"javascript:mdcr(\'$encrypted_email\',\'$email_subject\')\\\">\" .$match[5] .\"</a>\";\r\n				\r\n				return $mailto_link;	\r\n\r\n			} else {\r\n				/** DO NOT USE JAVASCRIPT ENCRYPTION FOR MAILTO LINKS **/\r\n\r\n				// as minimum protection, replace replace @ in the mailto part by (at)\r\n				// dots are not transformed as this would transform my.name@domain.com into: my(dot)name(at)domain(dot)com\r\n				\r\n				// rebuild the mailto link from the subpatterns (at the missing characters \" and </a>\")\r\n				return $match[1] .str_replace(\'@\', OUTPUT_FILTER_AT_REPLACEMENT, $match[2]) .$match[3] .\'\"\' .$match[4] .$match[5] .\'</a>\';\r\n				// if you want to protect both, @ and dots, comment out the line above and remove the comment from the line below\r\n				// return $match[1] .str_replace($search, $replace, $match[2]) .$match[3] .\'\"\' .$match[4] .$match[5] .\'</a>\';\r\n			}\r\n		\r\n		}\r\n		\r\n		// number of subpatterns do not match the requirements ... do nothing\r\n		return $match[0];\r\n	}		\r\n}\r\n	\r\n// first search part to find all mailto email addresses\r\n$pattern = \'#(<a[^<]*href\\s*?=\\s*?\"\\s*?mailto\\s*?:\\s*?)([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\\.)+[A-Z]{2,4})([^\"]*?)\"([^>]*>)(.*?)</a>\';\r\n// second part to find all non mailto email addresses\r\n$pattern .= \'|(value\\s*=\\s*\"|\\\')??\\b([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\\.)+[A-Z]{2,4})\\b#i\';\r\n\r\n// Sub 1:\\b(<a.[^<]*href\\s*?=\\s*?\"\\s*?mailto\\s*?:\\s*?)		-->	\"<a id=\"yyy\" class=\"xxx\" href = \" mailto :\" ignoring white spaces\r\n// Sub 2:([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\\.)+[A-Z]{2,4})		-->	the email address in the mailto: part of the mail link\r\n// Sub 3:([^\"]*?)\"							--> possible ?Subject&cc... stuff attached to the mail address\r\n// Sub 4:([^>]*>)							--> all class or id statements after the mailto but before closing ..>\r\n// Sub 5:(.*?)</a>\\b						--> the mailto text; all characters between >xxxxx</a>\r\n// Sub 6:|\\b([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\\.)+[A-Z]{2,4})\\b		--> email addresses which may appear in the text (require word boundaries)\r\n$content = $wb_page_data;			\r\n// find all email addresses embedded in the content and filter them using a callback function\r\n$content = preg_replace_callback($pattern, \'filter_mail_addresses\', $content);\r\n$wb_page_data = $content;\r\nreturn true;\r\n		\r\n', 'Emailfiltering on your output - output filtering with the options below - Mailto links can be encrypted by a Javascript', 1582116037, 1, 1, 0, 0, 0, 'usage:  [[EmailFilter]]'),
(4, 'LoginBox', 'global $wb, $TEXT, $MENU;\r\n$return_value = \" \";\r\nif(FRONTEND_LOGIN == \'enabled\' && VISIBILITY != \'private\' && $wb->get_session(\'USER_ID\') == \'\') {\r\n	$return_value  = \'<form name=\"login\" action=\"\'.LOGIN_URL.\'\" method=\"post\" class=\"login_table\">\';\r\n	$return_value .= \'<h2>\'.$TEXT[\'LOGIN\'].\'</h2>\';\r\n	$return_value .= $TEXT[\'USERNAME\'].\':<input type=\"text\" name=\"username\" style=\"text-transform: lowercase;\" /><br />\';\r\n	$return_value .= $TEXT[\'PASSWORD\'].\':<input type=\"password\" name=\"password\" /><br />\';\r\n	$return_value .= \'<input type=\"submit\" name=\"submit\" value=\"\'.$TEXT[\'LOGIN\'].\'\" class=\"dbutton\" /><br />\';\r\n	$return_value .= \'<a href=\"\'.FORGOT_URL.\'\">\'.$TEXT[\'FORGOT_DETAILS\'].\'</a><br />\';\r\n	if(is_numeric(FRONTEND_SIGNUP))  \r\n		$return_value .= \'<a href=\"\'.SIGNUP_URL.\'\">\'.$TEXT[\'SIGNUP\'].\'</a>\';\r\n	$return_value .= \'</form>\';\r\n} elseif(FRONTEND_LOGIN == \'enabled\' && is_numeric($wb->get_session(\'USER_ID\'))) {\r\n	$return_value = \'<form name=\"logout\" action=\"\'.LOGOUT_URL.\'\" method=\"post\" class=\"login_table\">\';\r\n	$return_value .= \'<h2>\'.$TEXT[\'LOGGED_IN\'].\'</h2>\';\r\n	$return_value .= $TEXT[\'WELCOME_BACK\'].\', \'.$wb->get_display_name().\'<br />\';\r\n	$return_value .= \'<input type=\"submit\" name=\"submit\" value=\"\'.$MENU[\'LOGOUT\'].\'\" class=\"dbutton\" /><br />\';\r\n	$return_value .= \'<a href=\"\'.PREFERENCES_URL.\'\">\'.$MENU[\'PREFERENCES\'].\'</a><br />\';\r\n	$return_value .= \'<a href=\"\'.ADMIN_URL.\'/index.php\" target=\"_blank\">\'.$TEXT[\'ADMINISTRATION\'].\'</a>\';\r\n	$return_value .= \'</form>\';\r\n}\r\nreturn $return_value;', 'Puts a Login / Logout box on your page.', 1582116038, 1, 1, 0, 0, 0, 'Use: [[LoginBox]]. Remember to enable frontend login in your website settings.'),
(5, 'Lorem', '$lorem = array();\n$lorem[] = \"Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Ut odio. Nam sed est. Nam a risus et est iaculis adipiscing. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Integer ut justo. In tincidunt viverra nisl. Donec dictum malesuada magna. Curabitur id nibh auctor tellus adipiscing pharetra. Fusce vel justo non orci semper feugiat. Cras eu leo at purus ultrices tristique.<br /><br />\";\n$lorem[] = \"Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.<br /><br />\";\n$lorem[] = \"Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.<br /><br />\";\n$lorem[] = \"Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.<br /><br />\";\n$lorem[] = \"Cras consequat magna ac tellus. Duis sed metus sit amet nunc faucibus blandit. Fusce tempus cursus urna. Sed bibendum, dolor et volutpat nonummy, wisi justo convallis neque, eu feugiat leo ligula nec quam. Nulla in mi. Integer ac mauris vel ligula laoreet tristique. Nunc eget tortor in diam rhoncus vehicula. Nulla quis mi. Fusce porta fringilla mauris. Vestibulum sed dolor. Aliquam tincidunt interdum arcu. Vestibulum eget lacus. Curabitur pellentesque egestas lectus. Duis dolor. Aliquam erat volutpat. Aliquam erat volutpat. Duis egestas rhoncus dui. Sed iaculis, metus et mollis tincidunt, mauris dolor ornare odio, in cursus justo felis sit amet arcu. Aenean sollicitudin. Duis lectus leo, eleifend mollis, consequat ut, venenatis at, ante.<br /><br />\";\n$lorem[] = \"Consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<br /><br />\"; \nif (!isset($blocks)) $blocks = 1;\n$blocks = (int)$blocks - 1;\nif ($blocks <= 0) $blocks = 0;\nif ($blocks > 5) $blocks = 5;\n$returnvalue = \"\";\nfor ( $i=0 ; $i<=$blocks ; $i++) {\n    $returnvalue .= $lorem[$i];\n}\nreturn $returnvalue;', 'Create Lorum Ipsum text', 1582116038, 1, 1, 0, 0, 0, 'Use: [[Lorem?blocks=6]] (max 6 paragraphs)'),
(6, 'year', '$datum = date(\"Y\");\r\nreturn $datum;', 'displays the current year', 1582116038, 1, 1, 0, 0, 0, 'Usage:[[year]]'),
(7, 'LEPTON_SearchBox', 'if (SHOW_SEARCH) {\r\n	global $wb;\r\n	global $parser;\r\n	global $loader;\r\n\r\nif (!isset($parser))\r\n{\r\n require_once( LEPTON_PATH.\"/modules/lib_twig/library.php\" );\r\n}\r\n/**\r\n * Load Language file, load files frontend.js and frontend.css file via get_page_headers\r\n */\r\n$lang = LEPTON_PATH.\"/modules/lib_search/languages/\".LANGUAGE.\".php\";\r\ninclude( file_exists($lang) ? $lang : LEPTON_PATH.\"/modules/lib_search/languages/EN.php\" );\r\n \r\n $template = ($wb->page[\'template\'] != \"\")\r\n ? $wb->page[\'template\'] // current frontend-template for this page\r\n : DEFAULT_TEMPLATE  // default-frontend-template\r\n ;\r\n\r\n // set the template path and enable custom templates\r\nif (file_exists( LEPTON_PATH.\'/templates/\'.$template.\'/frontend/lib_search/templates/search.box.lte\')) \r\n	{\r\n	// custom template\r\n	$loader->prependPath(LEPTON_PATH.\'/templates/\'.$template.\'/frontend/lib_search/templates/\');\r\n	}\r\nelse 	{\r\n	// standard module template\r\n	$loader->prependPath(LEPTON_PATH.\'/modules/lib_search/templates/\');\r\n	}	\r\n  \r\n// parse the search.box template\r\nreturn $parser->render(\'search.box.lte\', array(\'action\' => LEPTON_URL.\'/search/index.php\', \'MOD_SEARCH\'=> $MOD_SEARCH));\r\n}\r\nelse {\r\n    // the LEPTON search function is not enabled!\r\n    return false;\r\n}', 'Shows Search Box in the frontend', 1582116038, 1, 1, 0, 0, 0, 'Usage: [[LEPTON_SearchBox]]\r  This Droplet will show a search box. You can use it within your template or at any WYSIWYG page section');

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_droplets_load`
--

CREATE TABLE `lep_mod_droplets_load` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `register_name` varchar(255) NOT NULL DEFAULT '',
  `register_type` varchar(64) NOT NULL DEFAULT 'droplet',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `module_directory` varchar(255) NOT NULL DEFAULT '',
  `file_type` varchar(128) NOT NULL DEFAULT '',
  `file_name` varchar(255) NOT NULL DEFAULT '',
  `file_path` text,
  `options` text,
  `timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_droplets_permissions`
--

CREATE TABLE `lep_mod_droplets_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `edit_perm` varchar(50) NOT NULL,
  `view_perm` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_droplets_settings`
--

CREATE TABLE `lep_mod_droplets_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `attribute` varchar(50) NOT NULL DEFAULT '0',
  `value` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_droplets_settings`
--

INSERT INTO `lep_mod_droplets_settings` (`id`, `attribute`, `value`) VALUES
(1, 'Manage_backups', '1'),
(2, 'Import_droplets', '1'),
(3, 'Delete_droplets', '1'),
(4, 'Add_droplets', '1'),
(5, 'Export_droplets', '1'),
(6, 'Modify_droplets', '1'),
(7, 'Manage_perms', '1');

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_initial_page`
--

CREATE TABLE `lep_mod_initial_page` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '1',
  `init_page` text NOT NULL,
  `page_param` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_initial_page`
--

INSERT INTO `lep_mod_initial_page` (`id`, `user_id`, `init_page`, `page_param`) VALUES
(1, 1, 'start/index.php', '');

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_jsadmin`
--

CREATE TABLE `lep_mod_jsadmin` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '0',
  `value` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_jsadmin`
--

INSERT INTO `lep_mod_jsadmin` (`id`, `name`, `value`) VALUES
(1, 'mod_jsadmin_persist_order', 1),
(2, 'mod_jsadmin_ajax_order_pages', 1),
(3, 'mod_jsadmin_ajax_order_sections', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_menu_link`
--

CREATE TABLE `lep_mod_menu_link` (
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `target_page_id` int(11) NOT NULL DEFAULT '0',
  `redirect_type` int(11) NOT NULL DEFAULT '302',
  `anchor` varchar(255) NOT NULL DEFAULT '0',
  `extern` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_news_comments`
--

CREATE TABLE `lep_mod_news_comments` (
  `comment_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `commented_when` int(11) NOT NULL DEFAULT '0',
  `commented_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_news_comments`
--

INSERT INTO `lep_mod_news_comments` (`comment_id`, `section_id`, `page_id`, `post_id`, `title`, `comment`, `commented_when`, `commented_by`) VALUES
(1, 0, 0, 0, '', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_news_groups`
--

CREATE TABLE `lep_mod_news_groups` (
  `group_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_news_groups`
--

INSERT INTO `lep_mod_news_groups` (`group_id`, `section_id`, `page_id`, `active`, `position`, `title`) VALUES
(1, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_news_posts`
--

CREATE TABLE `lep_mod_news_posts` (
  `post_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `link` text NOT NULL,
  `content_short` text NOT NULL,
  `content_long` text NOT NULL,
  `commenting` varchar(7) NOT NULL DEFAULT '',
  `published_when` int(11) NOT NULL DEFAULT '0',
  `published_until` int(11) NOT NULL DEFAULT '0',
  `posted_when` int(11) NOT NULL DEFAULT '0',
  `posted_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_news_posts`
--

INSERT INTO `lep_mod_news_posts` (`post_id`, `section_id`, `page_id`, `group_id`, `active`, `position`, `title`, `link`, `content_short`, `content_long`, `commenting`, `published_when`, `published_until`, `posted_when`, `posted_by`) VALUES
(1, 0, 0, 0, 0, 0, '', '', '', '', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_news_settings`
--

CREATE TABLE `lep_mod_news_settings` (
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `posts_per_page` int(11) NOT NULL DEFAULT '0',
  `commenting` varchar(7) NOT NULL DEFAULT '',
  `resize` int(11) NOT NULL DEFAULT '0',
  `use_captcha` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_quickform`
--

CREATE TABLE `lep_mod_quickform` (
  `section_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(128) NOT NULL DEFAULT '',
  `subject` varchar(128) NOT NULL DEFAULT '',
  `template` varchar(64) NOT NULL DEFAULT 'form',
  `successpage` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_quickform_data`
--

CREATE TABLE `lep_mod_quickform_data` (
  `message_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `submitted_when` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_wrapper`
--

CREATE TABLE `lep_mod_wrapper` (
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `url` text NOT NULL,
  `height` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_wrapper`
--

INSERT INTO `lep_mod_wrapper` (`section_id`, `page_id`, `url`, `height`) VALUES
(1, 1, 'https://doc.lepton-cms.org/_packinstall/start-package2.html', 800);

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_wysiwyg`
--

CREATE TABLE `lep_mod_wysiwyg` (
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  `text` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_wysiwyg`
--

INSERT INTO `lep_mod_wysiwyg` (`section_id`, `page_id`, `content`, `text`) VALUES
(-1, -120, '<p><b>Berthold\'s</b> quick brown fox jumps over the lazy dog and feels as if he were in the seventh heaven of typography.</p>', 'Berthold\'s quick brown fox jumps over the lazy dog and feels as if he were in the seventh heaven of typography.');

-- --------------------------------------------------------

--
-- Table structure for table `lep_mod_wysiwyg_admin`
--

CREATE TABLE `lep_mod_wysiwyg_admin` (
  `id` int(11) NOT NULL,
  `skin` varchar(255) NOT NULL DEFAULT 'cirkuit',
  `menu` varchar(255) NOT NULL DEFAULT 'Smart',
  `width` varchar(64) NOT NULL DEFAULT '100%',
  `height` varchar(64) NOT NULL DEFAULT '250px',
  `editor` varchar(255) NOT NULL DEFAULT 'tiny_mce_jq'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_mod_wysiwyg_admin`
--

INSERT INTO `lep_mod_wysiwyg_admin` (`id`, `skin`, `menu`, `width`, `height`, `editor`) VALUES
(1, 'none', 'none', '100%', '250px', 'none'),
(2, 'kama', 'Smart', '100%', '250px', 'ckeditor'),
(3, 'cirkuit', 'Smart', '100%', '250px', 'tiny_mce_jq'),
(4, 'default', 'default', '100%', '250px', 'edit_area'),
(5, 'lightgray', 'Full', '100%', '250px', 'tiny_mce_4');

-- --------------------------------------------------------

--
-- Table structure for table `lep_pages`
--

CREATE TABLE `lep_pages` (
  `page_id` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `root_parent` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `link` text NOT NULL,
  `target` varchar(7) NOT NULL DEFAULT '',
  `page_title` varchar(255) NOT NULL DEFAULT '',
  `menu_title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `page_trail` text NOT NULL,
  `template` varchar(255) NOT NULL DEFAULT '',
  `visibility` varchar(255) NOT NULL DEFAULT '',
  `position` int(11) NOT NULL DEFAULT '0',
  `menu` int(11) NOT NULL DEFAULT '0',
  `language` varchar(5) NOT NULL DEFAULT '',
  `page_code` varchar(100) NOT NULL DEFAULT '',
  `searching` int(11) NOT NULL DEFAULT '0',
  `admin_groups` text NOT NULL,
  `admin_users` text NOT NULL,
  `viewing_groups` text NOT NULL,
  `viewing_users` text NOT NULL,
  `modified_when` int(11) NOT NULL DEFAULT '0',
  `modified_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_search`
--

CREATE TABLE `lep_search` (
  `search_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `extra` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_search`
--

INSERT INTO `lep_search` (`search_id`, `name`, `value`, `extra`) VALUES
(1, 'header', '\n<h1>[TEXT_SEARCH]</h1>\n\n<form name=\"searchpage\" action=\"[LEPTON_URL]/search/index.php\" method=\"get\">\n<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\" width=\"500\">\n<tr>\n<td>\n<input type=\"hidden\" name=\"search_path\" value=\"[SEARCH_PATH]\" />\n<input type=\"text\" name=\"string\" value=\"[SEARCH_STRING]\" style=\"width: 100%;\" />\n</td>\n<td width=\"150\">\n<input type=\"submit\" value=\"[TEXT_SEARCH]\" style=\"width: 100%;\" />\n</td>\n</tr>\n<tr>\n<td colspan=\"2\">\n<input type=\"radio\" name=\"match\" id=\"match_all\" value=\"all\"[ALL_CHECKED] />\n<label for=\"match_all\">[TEXT_ALL_WORDS]</label>\n<input type=\"radio\" name=\"match\" id=\"match_any\" value=\"any\"[ANY_CHECKED] />\n<label for=\"match_any\">[TEXT_ANY_WORDS]</label>\n<input type=\"radio\" name=\"match\" id=\"match_exact\" value=\"exact\"[EXACT_CHECKED] />\n<label for=\"match_exact\">[TEXT_EXACT_MATCH]</label>\n</td>\n</tr>\n</table>\n\n</form>\n\n<hr />\n	', ''),
(2, 'footer', '', ''),
(3, 'results_header', '[TEXT_RESULTS_FOR] \'<b>[SEARCH_STRING]</b>\':\n<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"padding-top: 10px;\">', ''),
(4, 'results_loop', '<tr style=\"background-color: #F0F0F0;\">\n<td>[LOCK]<a href=\"[LINK]\">[TITLE]</a></td>\n<td align=\"right\">[TEXT_LAST_UPDATED_BY] [DISPLAY_NAME] ([USERNAME]) [TEXT_ON] [DATE]</td>\n</tr>\n<tr><td colspan=\"2\" style=\"text-align: justify; padding-bottom: 5px;\">[DESCRIPTION]</td></tr>\n<tr><td colspan=\"2\" style=\"text-align: justify; padding-bottom: 10px;\">[EXCERPT]</td></tr>', ''),
(5, 'results_footer', '</table>', ''),
(6, 'no_results', '<tr><td><p>[TEXT_NO_RESULTS]</p></td></tr>', ''),
(7, 'module_order', 'wysiwyg', ''),
(8, 'max_excerpt', '15', ''),
(9, 'time_limit', '0', ''),
(10, 'cfg_enable_old_search', 'false', ''),
(11, 'cfg_search_keywords', 'true', ''),
(12, 'cfg_search_description', 'true', ''),
(13, 'cfg_search_non_public_content', 'false', ''),
(14, 'cfg_link_non_public_content', '', ''),
(15, 'cfg_show_description', 'true', ''),
(16, 'cfg_enable_flush', 'false', ''),
(17, 'template', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `lep_sections`
--

CREATE TABLE `lep_sections` (
  `section_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `module` varchar(255) NOT NULL DEFAULT '',
  `block` varchar(255) NOT NULL DEFAULT '',
  `publ_start` varchar(255) NOT NULL DEFAULT '0',
  `publ_end` varchar(255) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT 'no name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lep_settings`
--

CREATE TABLE `lep_settings` (
  `setting_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_settings`
--

INSERT INTO `lep_settings` (`setting_id`, `name`, `value`) VALUES
(1, 'lepton_version', '2.2.0'),
(2, 'website_title', 'LEPTON CMS 2series'),
(3, 'website_description', ''),
(4, 'website_keywords', ''),
(5, 'website_header', 'LEPTON CMS 2series'),
(6, 'website_footer', 'settings/website footer'),
(7, 'backend_title', 'LEPTON CMS 2series'),
(8, 'rename_files_on_upload', 'jpg,jpeg,gif,gz,png,pdf,tif,zip'),
(9, 'er_level', '-1'),
(10, 'prompt_mysql_errors', 'false'),
(11, 'default_language', 'EN'),
(12, 'app_name', 'lep7171'),
(13, 'sec_anchor', 'lep_'),
(14, 'default_timezone_string', 'Europe/Berlin'),
(15, 'default_date_format', 'M d Y'),
(16, 'default_time_format', 'g:i A'),
(17, 'redirect_timer', '1500'),
(18, 'leptoken_lifetime', '1800'),
(19, 'max_attempts', '9'),
(20, 'home_folders', 'true'),
(21, 'default_template', 'lepton2'),
(22, 'default_theme', 'algos'),
(23, 'default_charset', 'utf-8'),
(24, 'link_charset', 'utf-8'),
(25, 'multiple_menus', 'true'),
(26, 'page_level_limit', '4'),
(27, 'page_trash', 'inline'),
(28, 'homepage_redirection', 'false'),
(29, 'page_languages', 'false'),
(30, 'wysiwyg_editor', 'tiny_mce_4'),
(31, 'manage_sections', 'true'),
(32, 'section_blocks', 'true'),
(33, 'frontend_login', 'false'),
(34, 'frontend_signup', 'false'),
(35, 'search', 'public'),
(36, 'page_extension', '.php'),
(37, 'page_spacer', '-'),
(38, 'pages_directory', '/page'),
(39, 'media_directory', '/media'),
(40, 'operating_system', 'windows'),
(41, 'string_file_mode', '0644'),
(42, 'string_dir_mode', '0755'),
(43, 'wbmailer_routine', 'phpmail'),
(44, 'server_email', 'tristan@email.com'),
(45, 'wbmailer_default_sendername', 'LEPTON Mailer'),
(46, 'wbmailer_smtp_host', ''),
(47, 'wbmailer_smtp_auth', ''),
(48, 'wbmailer_smtp_username', ''),
(49, 'wbmailer_smtp_password', ''),
(50, 'mediasettings', ''),
(51, 'enable_old_language_definitions', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `lep_users`
--

CREATE TABLE `lep_users` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` varchar(255) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `statusflags` int(11) NOT NULL DEFAULT '6',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `last_reset` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL,
  `timezone_string` varchar(50) NOT NULL DEFAULT 'Europe/Berlin',
  `date_format` varchar(255) NOT NULL DEFAULT '',
  `time_format` varchar(255) NOT NULL DEFAULT '',
  `language` varchar(5) NOT NULL DEFAULT 'EN',
  `home_folder` text NOT NULL,
  `login_when` int(11) NOT NULL DEFAULT '0',
  `login_ip` varchar(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lep_users`
--

INSERT INTO `lep_users` (`user_id`, `group_id`, `groups_id`, `active`, `statusflags`, `username`, `password`, `last_reset`, `display_name`, `email`, `timezone_string`, `date_format`, `time_format`, `language`, `home_folder`, `login_when`, `login_ip`) VALUES
(1, 1, '1', 1, 6, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 0, 'Administrator', 'tristan@email.com', 'Europe/Berlin', '', '', 'EN', '', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lep_addons`
--
ALTER TABLE `lep_addons`
  ADD PRIMARY KEY (`addon_id`);

--
-- Indexes for table `lep_groups`
--
ALTER TABLE `lep_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `lep_mod_code2`
--
ALTER TABLE `lep_mod_code2`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `lep_mod_droplets`
--
ALTER TABLE `lep_mod_droplets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lep_mod_droplets_load`
--
ALTER TABLE `lep_mod_droplets_load`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `lep_mod_droplets_permissions`
--
ALTER TABLE `lep_mod_droplets_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lep_mod_droplets_settings`
--
ALTER TABLE `lep_mod_droplets_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lep_mod_initial_page`
--
ALTER TABLE `lep_mod_initial_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lep_mod_jsadmin`
--
ALTER TABLE `lep_mod_jsadmin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lep_mod_menu_link`
--
ALTER TABLE `lep_mod_menu_link`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `lep_mod_news_comments`
--
ALTER TABLE `lep_mod_news_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `lep_mod_news_groups`
--
ALTER TABLE `lep_mod_news_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `lep_mod_news_posts`
--
ALTER TABLE `lep_mod_news_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `lep_mod_news_settings`
--
ALTER TABLE `lep_mod_news_settings`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `lep_mod_quickform`
--
ALTER TABLE `lep_mod_quickform`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `lep_mod_quickform_data`
--
ALTER TABLE `lep_mod_quickform_data`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `lep_mod_wrapper`
--
ALTER TABLE `lep_mod_wrapper`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `lep_mod_wysiwyg`
--
ALTER TABLE `lep_mod_wysiwyg`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `lep_mod_wysiwyg_admin`
--
ALTER TABLE `lep_mod_wysiwyg_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lep_pages`
--
ALTER TABLE `lep_pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `lep_search`
--
ALTER TABLE `lep_search`
  ADD PRIMARY KEY (`search_id`);

--
-- Indexes for table `lep_sections`
--
ALTER TABLE `lep_sections`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `lep_settings`
--
ALTER TABLE `lep_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `lep_users`
--
ALTER TABLE `lep_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lep_addons`
--
ALTER TABLE `lep_addons`
  MODIFY `addon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `lep_groups`
--
ALTER TABLE `lep_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lep_mod_droplets`
--
ALTER TABLE `lep_mod_droplets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `lep_mod_droplets_load`
--
ALTER TABLE `lep_mod_droplets_load`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lep_mod_droplets_settings`
--
ALTER TABLE `lep_mod_droplets_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `lep_mod_initial_page`
--
ALTER TABLE `lep_mod_initial_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lep_mod_news_comments`
--
ALTER TABLE `lep_mod_news_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lep_mod_news_groups`
--
ALTER TABLE `lep_mod_news_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lep_mod_news_posts`
--
ALTER TABLE `lep_mod_news_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lep_mod_quickform_data`
--
ALTER TABLE `lep_mod_quickform_data`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lep_mod_wysiwyg_admin`
--
ALTER TABLE `lep_mod_wysiwyg_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `lep_pages`
--
ALTER TABLE `lep_pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lep_search`
--
ALTER TABLE `lep_search`
  MODIFY `search_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `lep_sections`
--
ALTER TABLE `lep_sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lep_settings`
--
ALTER TABLE `lep_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `lep_users`
--
ALTER TABLE `lep_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
