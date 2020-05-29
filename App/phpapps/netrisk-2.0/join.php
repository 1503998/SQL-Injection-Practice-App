<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
		
  	$tpl = new TemplatePower( "./templates/join.tpl" );
  	$tpl->prepare();
  
  	//Assign the CSS to the Header info
  	$tpl -> assign("BaseCss", "base"); 
  
  	//Page Title
  	$tpl -> assign("PageTitle", "Join Game"); 
  	$tpl -> assign("Player", $_SESSION['username']);
  	$tpl -> assign("GameName", $_SESSION['gname']);
  
  	//Get all Game Data
  	$query = mysql_query("SELECT * FROM ". $mysql_prefix . "game_info WHERE gid = {$_SESSION['gid']} ") or die(mysql_error());  
  	while($ginfo = mysql_fetch_assoc($query)){
	  
		//Set Custom Yes/No
		if($ginfo['blind_man'] == 2){
			$tpl -> assign("BlindMan", "Total");
		}elseif($ginfo['blind_man'] == 1){
	  		$tpl -> assign("BlindMan", "Yes");
		}else{
			$tpl -> assign("BlindMan", "No");
		}
	  
		if($ginfo['custom_rules'] == 'Null'){
	  		$tpl -> assign("CustomRules", "None");
  		} else {
			$tpl -> assign("CustomRules", $ginfo['custom_rules']);
  		}
  	  
  		if($ginfo['gpass'] != ''){
			$tpl -> assign("Password", "
	  	  			<div id=\"join_title\">					
						<th> Game Password</th>
					</div>
	  	  			<div>
	  	  			<span id=\"joinpassword\">Game is locked. Please type password:  
							<img src=\"images/lock.gif\">
							<input type=\"text\" name=\"password\" size=\"18\" />
						</span>
					</div>");
		}
	  	  
  	  
  	  
		$tpl->assign( Array( GName  => $ginfo['gname'],
	  				 MapType => $ginfo['map_type'],
                     CardRules => $ginfo['card_rules'],
                     TimeLimit => seconds_to_HMS($ginfo['time_limit'])
                      ));
		                    
  	}
  
  	//Get Current Players and Colors in the Game
  	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix . "game_players WHERE gid = {$_SESSION['gid']} ") or die(mysql_error());
  	$num_players = mysql_num_rows($query1);
  	while($players = mysql_fetch_assoc($query1)){
	  	$player[] = $players;
  		$pcolors[] = $players['pcolor'];
  	}
  
  	//Get All Existing Colors
  	$query2 = mysql_query("SELECT * FROM ". $mysql_prefix . "colors WHERE type = {$_SESSION['unit_type']} ") or die(mysql_error());
  	$row_colors = mysql_num_rows($query2);
  	while($colors = mysql_fetch_assoc($query2)){
  		$color[] = $colors['color'];
  	}
  
  	//Get the Color Difference or Remaining Colors
  	$ColorDiffs = array_diff($color,$pcolors);
  	$avail = count($ColorDiffs);
	
  	//Player and Next PID
  	$tpl -> assign("Player", $_SESSION['username']); 
  	$npid = $num_players + 1;
  	$tpl -> assign("npid", $npid);
  	  
  	foreach($player as $value){
		$tpl -> newBlock("players");
	  	$tpl -> assign("pname", $value['pname']);
	  	$tpl -> assign("pcolor", $value['pcolor']);
	  	$tpl -> assign("type", $_SESSION['unit_type']);
  	}
  
  	foreach($ColorDiffs as $colors){
		$tpl -> newBlock("colors");
	  	$tpl -> assign("color", $colors);
	  	$tpl -> assign("type", $_SESSION['unit_type']);
  	}
  
  	$tpl->printToScreen(); 
?>