<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
  	$tpl = new TemplatePower( "./templates/players.tpl" );
  	$tpl -> prepare();
  	
  	if($_SESSION['cardrules'] == 'US'){
  		$tpl -> assign("NextTrade", "
  			<tr style='background-color:red; color:black; font-weight:bold;'>
  				<td colspan='4'>Next Trade Value: " . $_SESSION['trade_value'] . "
  				</td>
  			</tr>"); 
	}
  
  	//Get All the Players in the current Game
  	//Assumes their must be at least 1 player (the Host Player) in Query
  	$sql = "SELECT usr.id, usr.login, usr.avatar, usr.image_type, plyr.gid, plyr.pid, plyr.pname, plyr.pcolor, plyr.pstate FROM ". $mysql_prefix . "users usr, ". $mysql_prefix . "game_players plyr WHERE plyr.gid = {$_SESSION['gid']} AND plyr.pname = usr.login ORDER by plyr.pid";
  	$query = mysql_query($sql);	
  	while ($plyrs = mysql_fetch_assoc($query)) {
		//Get Each Players Info
	  	$tpl -> newBlock("players");
	  
	  	$tpl -> assign("PlyrID", $plyrs['id']);
	  	//Show Kill, Arrow or Dead Image for Players
	  	$pstate = $plyrs['pstate'];
	  	if($pstate != 'inactive' && $pstate != 'dead'){
		  	$tpl -> assign("Image", "arrow"); 
	  	} else if ($pstate == 'dead'){
	  		$tpl -> assign("Image", "dead"); 
  		} else if ($pstate == 'initial'){
  			$tpl -> assign("Image", "kill"); // The Kill needs to link to a page?
		} else {
			$tpl -> assign("Image", "blank"); // The Kill needs to link to a page?
		}	
	   
		if($plyrs['image_type'] == "") {
            $image = "<img src=\"./images/misc/blank.gif\" alt=\"avatar\" />";
            $tpl -> assign("Avatar", $image); 
      	} else {
       		$tpl -> assign("Avatar", "<img src=\"avatar.php?image_id={$plyrs['id']}\" alt=\"avatar\" />"); 
      	}
      	 	
		$pname  = $plyrs['pname'];
	  	//Get each players Cards
	  	foreach($plyrs as $value){
			$sql2 = mysql_query("SELECT * FROM ". $mysql_prefix . "game_cards WHERE pname = '$pname' AND gid = {$_SESSION['gid']} ") or die(mysql_error()); 
		  	$cards = mysql_num_rows($sql2);		  
	  	}	 
	  
	  	$tpl -> assign("Cards", $cards); 
		$tpl -> assign("Player", $plyrs['pname']); 
		$tpl -> assign("Color", $plyrs['pcolor']); 
		$tpl -> assign("State", $plyrs['pstate']);  
	}
  
  	$tpl -> printToScreen(); 
?>