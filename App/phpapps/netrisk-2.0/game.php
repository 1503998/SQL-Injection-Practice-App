<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./includes/config.php');

  	$tpl = new TemplatePower( "./templates/game.tpl" );
  	$tpl -> prepare();
  
	$gid = $_GET['id'];  // $_SESSION['gid'] is not working?
    
	//Get Game Information, Game ID, etc. 
	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix . "game_info WHERE gid = $gid ") or die(mysql_error()); 
	$query1_row = mysql_fetch_assoc($query1);

	$timelimit = $query1_row['time_limit'];
	$time = seconds_to_HMS($timelimit);
	$lastmove = $query1_row['last_move'];
	$gstate = $query1_row['gstate'];
	$last = date("m/d - g:i a", $lastmove);
	$mtype = $query1_row['map_type'];

	$maptype = strtolower($mtype);
      
	//Display a list of ALL the countries 
	$query2 = mysql_query("SELECT gd.gid, gd.pid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM ". $mysql_prefix . "countries cty, ". $mysql_prefix . "game_data gd WHERE gd.gid = $gid AND cty.mtype = '$mtype' AND gd.pterritory = cty.id ORDER BY cty.name") or die(mysql_error());

	$me = 1; $them = 1; 
	while($state = mysql_fetch_assoc($query2)){
		//Strip the Spaces for the CSS
		$state['name'] = str_replace(' ', '', $state['name']);
		
		//Assign the CSS for each army type
		if ($state['parmies'] < 1) { 
			$state['CSS'] = 'blank.gif" alt="';
    	} else if ($state['parmies'] < 5) {
	  		$state['CSS'] = 'infantry'.$state['parmies'];
    	} else if ($state['parmies'] < 10) {
	  		$state['CSS'] = 'cavalry'.$state['parmies'];
    	} else if ($state['parmies'] >= 10) {
	   		$state['CSS'] = 'cannon';
    	} else {
	   		$state['CSS'] = 'ERROR NO IMAGE';
      	} 	
	
		if($state['parmies'] > 3){
			$state['attackable'] = 3;
		} else {
			$state['attackable'] = $state['parmies'] - 1;
		}
		
		//for each state, get the name, etc 
    	$query3 = mysql_query("SELECT * FROM ". $mysql_prefix . "game_players where gid = $gid AND pid = {$state['pid']}") or die(mysql_error()); 
    	$pname = mysql_result($query3, 0, 'pname');
    	$state['color'] =   mysql_result($query3, 0, 'pcolor');
    	$state['pname'] =   mysql_result($query3, 0, 'pname');
     
    	if($pname == $_SESSION['player_name']){ 
        	$state['jselect'] = 'me'; 
        	$state['selectid'] = $me++; 
    	} else { 
        	$state['jselect'] = 'them'; 
        	$state['selectid'] = $them++; 
    	} 
         
    	$states[] = $state; 
    	$count = count($states); 
    	$rowcount = $count - 1; //Since Starts at 0? 
	}

	//Assign the Game Info
	for($i=0; $i < $query1; $i++){
		$tpl -> assign("MapType", $maptype); 
		$tpl -> assign("CSS", $_SESSION['css_style']); 
		$tpl -> assign("GNumber", $_SESSION['gid']); 
		$tpl -> assign("GName", $_SESSION['gname']); 
		$tpl -> assign("CardRules", $_SESSION['cardrules']); 
		$tpl -> assign("BlindType", $_SESSION['blindman']);
		$tpl -> assign("TimeLimit", $time);
		$tpl -> assign("LastMove", $last);
	}
		
   //Assign the Map Info	
	for($i=0; $i <= $rowcount; $i++){	
		$tpl -> newBlock("states");
		$tpl -> assign("TID", $states[$i]['pterritory']);
		$tpl -> assign("Country", $states[$i]['name']);
		$tpl -> assign("Color", $states[$i]['color']);
		$tpl -> assign("UnitCss", $states[$i]['CSS']);
		$tpl -> assign("Army", $states[$i]['parmies']);  
		$tpl -> assign("Attackable", $states[$i]['attackable']);  
		$tpl -> assign("JSelect", $states[$i]['jselect']);  
		$tpl -> assign("SelectID", $states[$i]['selectid']);
		
		if($gstate == 'Initial' && ($states[$i]['pname'] != $_SESSION['player_name'])){
			$tpl -> assign("UnitCss", 'infantry1');
			$tpl -> assign("Army", '?');  
		} else {
			$tpl -> assign("UnitCss", $states[$i]['CSS']);
			$tpl -> assign("Army", $states[$i]['parmies']);  
		}
	}
	
  	$tpl -> printToScreen(); 
?>