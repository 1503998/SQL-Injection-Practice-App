<?php
 
	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	$tpl = new TemplatePower('./templates/cards.tpl');
	$tpl->prepare();	

	//Get the Cards for the Player
	$sql = "SELECT pcards FROM ".$mysql_prefix."game_players WHERE pname = '{$_SESSION['username']}' AND gid = $_SESSION['gid'] ";
	$query = mysql_query($sql);	
	while ($row = mysql_fetch_assoc($query)) {
		$cards = explode(",", $row['pcards']);
		$pcards = count($cards);
		for($i=0; $i < $pcards; $i++){
			$sql2 = "SELECT * FROM ".$mysql_prefix."countries WHERE mtype = '$_SESSION['maptype']' AND id = $cards[$i] ";
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