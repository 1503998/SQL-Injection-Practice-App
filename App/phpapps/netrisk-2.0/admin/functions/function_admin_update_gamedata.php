<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Admin Update Game Function
	include(dirname(__FILE__) . '/../../includes/config.php');

	// GET FORM INPUT AND SANITIZE DATA
	$gid = $_POST['gid'];  //Hidden Field.  Can not be changed by user
	$gname = mysql_real_escape_string($_POST['gname']);
	$gstate = mysql_real_escape_string($_POST['gstate']);
	$gmode = mysql_real_escape_string($_POST['gmode']);
	$gtype = mysql_real_escape_string($_POST['gtype']);
	$utype = $_POST['utype'];
	$bman = $_POST['bman'];
	$players = $_POST['players'];
	$capacity = $_POST['capacity'];
	$kibitz = $_POST['kibitz'];
	$cardrules = $_POST['cardrules'];
	$tradevalue = $_POST['tradevalue'];
	$timelimit = $_POST['timelimit'];
	$customrules = mysql_real_escape_string($_POST['customrules']);

	//PREPARE DATA FOR SQL INPUT
	$details .= " gname='$gname',";
	$details .= " gstate='$gstate',";
	$details .= " gtype='$gtype',";
	$details .= " unit_type='$utype',";
	$details .= " blind_man='$bman',";
	$details .= " players='$players',";
	$details .= " capacity='$capacity',";
	$details .= " kibitz='$kibitz',";
	$details .= " card_rules='$cardrules',";
	$details .= " trade_value='$tradevalue',";
	$details .= " time_limit='$timelimit',";
	$details .= " custom_rules='$customrules'";

	//UPDATE GAME DATA
	$query = mysql_query("UPDATE ". $mysql_prefix ."game_info SET {$details} WHERE gid = {$gid} ");

	header("Location: ../../index.php?p=edit&mode=gamedata&gid={$gid}");
?>
