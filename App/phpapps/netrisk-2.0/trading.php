<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	$tpl = new TemplatePower('./templates/trading.tpl');
	$tpl->prepare();	
  

	//Get the Cards for the Player
	$query2 = mysql_query("SELECT cds.gid, cds.pid, cds.pname, cds.card, plyr.gid, plyr.pid, plyr.pname, plyr.pcolor FROM ". $mysql_prefix . "game_cards cds, ". $mysql_prefix . "game_players plyr WHERE cds.pname = '".$_SESSION['player_name']."' AND cds.gid = {$_SESSION['gid']} AND cds.pname = plyr.pname AND cds.gid = plyr.gid");
	$pcards = mysql_num_rows($query2);
	while($row2 = mysql_fetch_assoc($query2)){ 	
		$cards[] = $row2['card'];
		$pcolor = $row2['pcolor'];
	}

	//Show Message of indicating user can trade
	if ($pcards == 0){
		$tpl -> assign("Message", "You have 0 cards at this time.");
	} else if ($pcards > 1 && $pcards < 5){
		$tpl -> assign("Message", "You may trade cards when you have a set of 3.");
	} else if ($pcards >= 5 ) {
		$tpl -> assign("Message", "You have ". $pcards . " or more cards and must trade at this time.");
	}

	if ($pcards >= 3){
	$tpl -> assign("TradeCards", "<input type='submit' value='Turn In Cards' />");
	$tpl -> assign("StartSelect", "<select name='bonus'><option value='invalid'> +2 Bonus State</option>");		
	$tpl -> assign("EndSelect", "</select>");
	}
	
	//Display the player's Cards
	for($i=0; $i < $pcards; $i++){
		$query3 = mysql_query("SELECT * FROM ". $mysql_prefix . "countries WHERE mtype = '".$_SESSION['maptype']."' AND id = $cards[$i]");
		while ($row3 = mysql_fetch_assoc($query3)) {
			$name = $row3['name'];
			$tpl -> newBlock("cards");
		
			$tpl -> assign("Color", $pcolor);
			$tpl -> assign("Country", $row3['name']);
			$tpl -> assign("ID", $row3['id']);
			$tpl -> assign("Type", $row3['card_type']);
			$type = $row3['card_type'];
			switch($type){
				case '1': $tpl -> assign("Image", "cannon");
		 		   	  	  break;
				case '2': $tpl -> assign("Image", "cavalry");
					 	  break;
				case '3': $tpl -> assign("Image", "infantry1");
					 	  break;
			}
		}
				
		$query4 = mysql_query("SELECT gd.gid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM ". $mysql_prefix . "game_data gd, ". $mysql_prefix . "countries cty WHERE gd.pname = '".$_SESSION['player_name']."' AND gd.gid = {$_SESSION['gid']} AND cty.mtype = '".$_SESSION['maptype']."' AND gd.pterritory = $cards[$i] AND cty.id = $cards[$i]");
		while($row4 = mysql_fetch_assoc($query4)){
			$tpl -> newBlock("bonus"); 	
		
			$tpl -> assign("PTerritory", $row4['pterritory']);
			$tpl -> assign("Name", $row4['name']);
		}
		
	}
	
	

	$tpl->printToScreen();

?>