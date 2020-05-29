<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Admin Update Players Function
	include(dirname(__FILE__) . '/../../includes/config.php');

	$gid = $_POST['gid'];  //Hidden Field.  Can not be changed by user

	// GET FORM DATA  All Fields are arrays 0 - XX
	$pid = $_POST['pid'];
	$pname = $_POST['pname'];
	$pcolor = $_POST['pcolor'];
	$pstate = $_POST['pstate'];
	$pattackcard = $_POST['pattackcard'];
	$pnumarmy = $_POST['pnumarmy'];
	$pmission = $_POST['pmission'];
	$pcaporg = $_POST['pcaporg'];
	$pmail = $_POST['pmail'];
	$pvote = $_POST['pvote'];
	$pkick = $_POST['pkick'];
	$pkills = $_POST['pkills'];
	$ppoints = $_POST['ppoints'];

	//Count the Number of Rows/Players
	$num_rows = count($pid);

	for($i=0; $i < $num_rows; $i++){
		$details = null;
		$pid = $i + 1;  //Since Array starts with 0, but first pid is 1
	
		$player = mysql_real_escape_string($pname[$i]); 
	
		$details .= " pname='$player',";	
		$details .= " pcolor='$pcolor[$i]',";	
		$details .= " pstate='$pstate[$i]',";
		$details .= " pattackcard='$pattackcard[$i]',";
		$details .= " pnumarmy='$pnumarmy[$i]',";
		$details .= " pmission='$pmission[$i]',";
		$details .= " pcaporg='$pcaporg[$i]',";
		$details .= " pmail='$pmail[$i]',";
		$details .= " pvote='$pvote[$i]',";
		$details .= " pkick='$pkick[$i]',";
		$details .= " pkills='$pkills[$i]',";
		$details .= " ppoints='$ppoints[$i]'";
	
		//UPDATE Players
		$query = mysql_query("UPDATE ". $mysql_prefix ."game_players SET {$details} WHERE gid = {$gid} AND pid = $pid ");
	}

	header("Location: ../../index.php?p=edit&mode=players&gid={$gid}");
?>
