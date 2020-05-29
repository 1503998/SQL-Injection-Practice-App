<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./config.php');
  
	$tpl = new TemplatePower('./templates/dice.tpl');
	$tpl->prepare();	

	if (isset($_SESSION['attack_rolls'])) {
		foreach ($_SESSION['attack_rolls'] as $key => $val) {
			$tpl -> newBlock("attack_rolls"); 	
			$tpl -> assign("attack_die", $val);
		}

		foreach ($_SESSION['defend_rolls'] as $key => $val) {
			$tpl -> newBlock("defend_rolls"); 	
			$tpl -> assign("defend_die", $val);
		}
	} 	

	$tpl->printToScreen();
?>