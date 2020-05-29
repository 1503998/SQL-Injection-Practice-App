<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	$tpl = new TemplatePower('./templates/inactive.tpl');
	$tpl->prepare();	

	//Get the Cards for the Player
	$sql = "SELECT cds.gid, cds.pid, cds.pname, cds.card, plyr.gid, plyr.pid, plyr.pname, plyr.pcolor FROM ". $mysql_prefix . "game_cards cds, ". $mysql_prefix . "game_players plyr WHERE cds.pname = '".$_SESSION['player_name']."' AND cds.gid = {$_SESSION['gid']} AND cds.pname = plyr.pname AND cds.gid = plyr.gid";
	$query = mysql_query($sql);	
	$pcards = mysql_num_rows($query);
	while ($row = mysql_fetch_assoc($query)) {
		$cards[] = $row['card'];
	}

	if($pcards == 0){
		$tpl -> assign("NoCards", "You have 0 Cards at this time");
	} else {	
		
		for($i=0; $i < $pcards; $i++){
			$sql2 = "SELECT * FROM ". $mysql_prefix . "countries WHERE mtype = '".$_SESSION['maptype']."' AND id = $cards[$i] ";
			$query2 = mysql_query($sql2);
			while ($row2 = mysql_fetch_assoc($query2)) {
				$tpl -> newBlock("countries");
		
				$tpl -> assign("Country", $row2['name']);
				$tpl -> assign("Type", $row2['card_type']);
				$type = $row2['card_type'];
				switch($type){
					case '1': $tpl -> assign("Image", "cannon");
			 					break;
					case '2': $tpl -> assign("Image", "cavalry");
								break;
					case '3': $tpl -> assign("Image", "infantry");
								break;
				}
			}
		}
	}
	
	$tpl->printToScreen();  
?>