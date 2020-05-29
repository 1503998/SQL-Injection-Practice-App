<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	 
	include('./config.php');
  
	$tpl = new TemplatePower('./templates/placing.tpl');
	$tpl->prepare();	

	if (isset($_GET['state'])){
		$state = $_GET['state'];
	}

	//Show the Javascript
	$script = ("function selectme(index){
    			document.addarmy.state.options[document.addarmy.state.selectedIndex].selected = false;
    			document.addarmy.state.options[index].selected = true;
    		}
    		function selectthem(index){}		
	");

	$tpl -> assign("JScriptPlace", $script);
	  
	//Get the Player Information which contains how many armies they have available to place.
	$sql2 = "SELECT * FROM " . $mysql_prefix . "game_players WHERE pname = '".$_SESSION['player_name']."' AND gid = {$_SESSION['gid']} ";
	$query2 = mysql_query($sql2);
	while($row2 = mysql_fetch_assoc($query2)){ 	
		$cards = explode(",", $row2['pcards']);
		$pcards = count($cards);
		$armies = $row2['pnumarmy'];
		$color = $row2['pcolor'];
		$tpl -> assign("GID", $row2['gid']);
	}
	
	//Show Message of how many armies they have to place
	if ($armies >= 0){
		$tpl -> assign("Message", "You have ". $armies . " remaining armies to place.");
		for( $i=1; $i <= $armies; $i++ ){
     		//create a dropdown list of avaialable armies
      		$tpl->newBlock("armies");
     		//assign value
      		$tpl->assign("army", $i );
  		}
	} else {
		//When Player hits 0 armies left, do something......
		//If Game State Playing, Move to Attacking, else if Game State is waiting, move to Waiting State
		$sql3 = "UPDATE ". $mysql_prefix . "game_players SET pstate = 'attacking' WHERE pname = '".$_SESSION['player_name']."' AND gid = {$_SESSION['gid']} ";
		$query3 = mysql_query($sql3);
		header("Location: ./index.php?page=game&id=$_SESSION[gid]");  //Refresh the Game to Force UserControls to user Attacking	
	}

	//Display a list of their countries
	$sql = "SELECT gd.gid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM " . $mysql_prefix . "game_data gd, " . $mysql_prefix . "countries cty WHERE gd.pname = '".$_SESSION['player_name']."' AND gd.gid = '{$_SESSION['gid']}' AND cty.mtype = '{$_SESSION['maptype']}' AND gd.pterritory = cty.id ORDER BY cty.name ASC";
	$query = mysql_query($sql);
	$statenum = 1;
	while($row = mysql_fetch_assoc($query)){
		$tpl -> newBlock("states"); 	
	
		$tpl -> assign("GID", $row['gid']);
		$tpl -> assign("PTID", $row['pterritory']);
		$tpl -> assign("Country", $row['name']);
	
		if($state == $statenum){
			$tpl -> assign("Selected", 'selected');
		}
	
		$statenum ++;
	}

	$tpl->printToScreen();
?>