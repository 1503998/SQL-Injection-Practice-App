<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./config.php');
  
	$tpl = new TemplatePower('./templates/occupy.tpl');
	$tpl->prepare();	

	//Get the Session Data
	$min_army = $_SESSION['min_armies'];
	$transferable = $_SESSION['transferable_armies']; //Have to leave 1 army on host country.
	$from_id = $_SESSION['from_id'];
	$to_id = $_SESSION['to_id'];
	$to_country = $_SESSION['to_country'];

	$tpl -> assign("minimum", $min_army);
	$tpl -> assign("maximum", $transferable);
	$tpl -> assign("from_id", $from_id);
	$tpl -> assign("to_id", $to_id);
	$tpl -> assign("to_country", $to_country);

	for($i=$min_army; $i <= $transferable; $i++){
		$tpl -> newBlock("armies");
		$tpl -> assign("army", $i);
	}

	$tpl->printToScreen();
?>