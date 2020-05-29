<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./config.php');
  
	$tpl = new TemplatePower('./templates/attacking.tpl');
	$tpl->prepare();	

	if (isset($_GET['fromstate'])){
		$fromselected = $_GET['fromstate'];
	}
	
	if (isset($_GET['tostate'])){
		$toselected = $_GET['tostate'];
	}


	//Show Message of how many armies they have to place
	$tpl -> assign("Message", "Try and put the dice here, off the map area !!");
 
	//Show the Javascript
	$script = ("function selectme(index,army,tid){
    		document.attacking.fromstate.options[document.attacking.fromstate.selectedIndex].selected = false;
    		document.attacking.armies.options[army].selected = true;
    		document.attacking.fromstate.options[index].selected = true;
    	}
    	function selectthem(index,army,tid){
    		document.attacking.tostate.options[document.attacking.tostate.selectedIndex].selected = false;
    		document.attacking.tostate.options[index].selected = true;
    	}   
	");

	$tpl -> assign("JScriptAttack", $script);

		
	//Display a list of the Players countries
	$qplayer = mysql_query("SELECT gd.gid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM ". $mysql_prefix . "game_data gd, ". $mysql_prefix . "countries cty WHERE gd.pname = '".$_SESSION['username']."' AND gd.gid = {$_SESSION['gid']} AND cty.mtype = '{$_SESSION['maptype']}' AND gd.pterritory = cty.id ORDER BY cty.name") or die(mysql_error());
	$fromnum = 1;
	while($rplayer = mysql_fetch_assoc($qplayer)){
		$tpl -> newBlock("player"); 	
		$tpl -> assign("GID", $rplayer['gid']);
		$tpl -> assign("FromTID", $rplayer['pterritory']);
		$tpl -> assign("FromCountry", $rplayer['name']);
	
		if($rplayer['parmies'] > 3){
			$tpl -> assign("Attackable", 3);
		} else {
			$tpl -> assign("Attackable", ($rplayer['parmies'] - 1));
		}
	
		$tpl -> assign("SelectFrom", $fromnum);

		if($fromselected == $fromnum){
			$tpl -> assign("fromselected", 'selected');
		}
	
		$fromnum++;
	}

	//Display a list of the Opponents countries
	$qopponent = mysql_query("SELECT gd.gid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM ". $mysql_prefix . "game_data gd, ". $mysql_prefix . "countries cty WHERE gd.pname != '".$_SESSION['username']."' AND gd.gid = {$_SESSION['gid']} AND cty.mtype = '{$_SESSION['maptype']}' AND gd.pterritory = cty.id ORDER BY cty.name") or die(mysql_error());
	$tonum = 1;
	while($ropponent = mysql_fetch_assoc($qopponent)){
		$tpl -> newBlock("opponent"); 	
		$tpl -> assign("ToTID", $ropponent['pterritory']);
		$tpl -> assign("ToCountry", $ropponent['name']);
		
		if($ropponent['parmies'] > 3){
			$tpl -> assign("Attackable", 3);
		} else {
			$tpl -> assign("Attackable", ($row['parmies'] - 1));
		}
		
		if($ropponent['parmies'] >= 2){
			$defendable = 2;
		} else {
			$defendable = $ropponent['parmies'];
		}
		$tpl -> assign("Defendable", $defendable);
		$tpl -> assign("SelectTo", $tonum);
	
		if($toselected == $tonum){
			$tpl -> assign("toselected", 'selected');
		}
		
		$tonum++;
	}

	$tpl->printToScreen();

?>