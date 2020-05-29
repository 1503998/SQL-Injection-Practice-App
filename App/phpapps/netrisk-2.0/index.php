<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
	session_start();
	if(!file_exists($doc_root . 'config.php')) {
		//No Config File, so redirect to Installer
			header('Location: ./install/install.php');
	}

	include('./includes/config.php');
	$time = time();
   	addActiveUser($_SESSION['username'], $time);
	removeInactiveUsers();

	$tpl = new TemplatePower("./templates/index.tpl");
	$tpl->prepare();

	$GridCSS = '<link rel="stylesheet" type="text/css" href="./templates/grid/style.css" />'; 

	//Add to Header
	$ChatCSS = jal_add_to_head();

	if (isset($_GET['p'])){
		$Title = 'NetRisk: ' . $_GET['p']; 
	} else {
		$Title = 'NetRisk 2.0 '; 
	}

	$tpl->assign(array(
   		"Title" => $Title,
   		"GridCSS" => $GridCSS,
   		"ChatCSS" => $ChatCSS
   		));
   
	$tpl->printToScreen();
?> 