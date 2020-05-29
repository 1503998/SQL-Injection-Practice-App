<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	if (isset($_GET['p'])){
		
   		switch($_GET['p']){
      		// Browser
      		case 'browser':	include('./browser.php'); break;   
      		// Game
      		case 'game':		include('./game.php'); break;
      		// Rankings
      		// Game
      		case 'test':		include('./game_test.php'); break;
      		// Rankings
      		case 'rankings':	include('./rankings.php'); break;
      		// Register User
      		case 'register':	include('./register.php'); break;
      		// Register User
      		case 'reset':		include('./reset_pass.php'); break;
      		// Game Log
      		case 'glog':		include('./glog.php'); break;
      		// Mission Game Log
      		case 'mlog':		include('./mlog.php'); break;
      		// Create Game
      		case 'create':	include('./create.php'); break;
      		// Join Game
      		case 'join':		include('./join.php'); break;
      		// Player Profile
      		case 'profile':	include('./profile.php'); break;
      		// Admin Index
      		case 'admin':		include('./admin/index.php'); break;
      		// Admin Edit Game
      		case 'edit':		include('./admin/edit.php'); break;
      		// Admin Delete Game
      		case 'delete':	include('./admin/functions/function_admin_delete.php'); break;
      
      		default:		include('./browser.php'); break;
   		}
	} else {
		include('./browser.php');
	}
?> 