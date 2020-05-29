<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
  	include('./config.php');
  
	$tpl = new TemplatePower( "./templates/gamelog.tpl" );
  	$tpl->prepare();
  
  	//Verify Game Has Started
  	$gstate = $_SESSION['gstate'];
  
  	if($gstate != 'Playing' && $gstate != 'Finished'){
		$tpl -> assign("Message", "Game Log Does not show until game starts");    
  	} else {
  		$query = mysql_query("SELECT * FROM ". $mysql_prefix . "game_log WHERE gid = {$_SESSION['gid']} ORDER by time DESC") or die(mysql_error());
  		while($row = mysql_fetch_assoc($query)){
			$row['date'] = date('M-d : h:i a', $row['time']);
	  		$log[] = $row;
  		}  
 
  		$numrows = mysql_num_rows($query);
  
	  	for($i=0; $i < $numrows; $i++){
			$tpl -> newBlock("log");
			$tpl -> assign("Time", $log[$i]['date']);
			$tpl -> assign("Text", $log[$i]['text']);
  		}
  	}
  
  $tpl->printToScreen();
?>