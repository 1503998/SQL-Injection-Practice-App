<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
	include('./config.php');

	$tpl = new TemplatePower('./templates/fortifying.tpl');
	$tpl->prepare();	

	//Show Message of how many armies they have to place
	$tpl -> assign("Message", "Fortify Mode !!");
 
	//Show the Javascript
	$script = ("var i=0;
	function selectme(index){
		if(i == 0){
  			document.fortify.fromstate.options[document.fortify.fromstate.selectedIndex].selected = false;
    		document.fortify.fromstate.options[index].selected = true;
    		i = 1;
    	} else {
    		document.fortify.tostate.options[document.fortify.tostate.selectedIndex].selected = false;
    		document.fortify.tostate.options[index].selected = true;
    		i=0;
    	}
	}
	function selectthem(index){ }
	");

	$tpl -> assign("JScriptFortify", $script);


	//Display a list of their countries
	$query = mysql_query("SELECT gd.gid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM " . $mysql_prefix . "game_data gd, " . $mysql_prefix . "countries cty WHERE gd.pname = '".$_SESSION['player_name']."' AND gd.gid = '{$_SESSION['gid']}' AND cty.mtype = '{$_SESSION['maptype']}' AND gd.pterritory = cty.id ORDER BY cty.name") or die(mysql_error());
	while($row = mysql_fetch_assoc($query)){
		$tpl -> newBlock("fromstate");
		$tpl -> assign("TID", $row['pterritory']);
		$tpl -> assign("Country", $row['name']); 
	
		$tpl -> newBlock("tostate"); 		
		$tpl -> assign("TID", $row['pterritory']);
		$tpl -> assign("Country", $row['name']);
	}
$tpl->printToScreen();





?>