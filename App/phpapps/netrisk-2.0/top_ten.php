<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/  
  
	include('./config.php');	
  	$tpl = new TemplatePower( "./templates/top_ten.tpl" );
  	$tpl -> prepare();

  	// Get the Ranking Info from the Users Table
  	$query = mysql_query("SELECT id, login, win, loss FROM ". $mysql_prefix . "users ") or die(mysql_error());;
  
	$rowCheck = mysql_num_rows($query);
	if($rowCheck > 0){
		while($row = mysql_fetch_array($query)){
      		//Compute Player Ranking
      		$row['ranking'] = compute_ranking($row['login']);
 		
 			//Get the Number of Active Games for Each Player
 			$query2 = mysql_query("Select * FROM ". $mysql_prefix . "game_players WHERE pname = '".$row['login']."' AND pstate != 'dead' AND pstate != 'winner' ");
			$row['active'] = mysql_num_rows($query2);
	 
			$pdata[] = $row;
		}
	}

	// Obtain a list of columns
	foreach ($pdata as $key => $row) {
		$ranking[$key]  = $row['ranking'];
		$player[$key] = $row['login'];
	}
		
	// Sort the data by Ranking and then player
	array_multisort($ranking, SORT_DESC, $player, SORT_ASC, $pdata);

	for($i=0; $i <10; $i++){
		//create a new TopTen block
		$tpl->newBlock("TopTen");
		//assign values
		$tpl -> assign("AGs", $pdata[$i]['active']);
		$tpl -> assign("ID", $pdata[$i]['id']);		
		$tpl -> assign("Player", $pdata[$i]['login']);
		$tpl -> assign("Ranking", $pdata[$i]['ranking']);
	}
    
  	$tpl -> printToScreen(); 
  
?>