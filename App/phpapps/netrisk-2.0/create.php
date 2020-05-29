<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
  	//create.php 
  	$tpl = new TemplatePower('./templates/create.tpl');
  	$tpl->prepare();	
  
  	//Page Title
  	$tpl -> assign("PageTitle", "Create Game"); 
  	$tpl -> assign("Player", $_SESSION['username']); 

  	$unit_type = 1;  //Default Images to the standard image	  
	
    
  	//Get colors available
  	$query = mysql_query("SELECT * FROM ". $mysql_prefix . "colors WHERE type = $unit_type ") or die(mysql_error());
  	$row_colors = mysql_num_rows($query);
  	while($colors = mysql_fetch_assoc($query)){
  		$color[] = $colors;
  	}
  
  	//Get the Maps Types
  	$query1 = mysql_query("SELECT DISTINCT game_style, map_type FROM ". $mysql_prefix . "map_types ") or die(mysql_error());
  	$row_maps = mysql_num_rows($query1);
  	while($maps = mysql_fetch_assoc($query1)){
  		$map[] = $maps;
  	}
  
  	for($m=0; $m < $row_maps; $m++){
		$tpl -> newBlock("style");
	  	$tpl -> assign("mapname", $map[$m]['map_type']);
	  	$tpl -> assign("mapvalue", $map[$m]['game_style']);
  	}
  
  	for($c=0; $c < $row_colors; $c++){
		$tpl -> newBlock("colors");
	  	$tpl -> assign("color", $color[$c]['color']);
	  	$tpl -> assign("type", $color[$c]['type']);
  	}

	$tpl->printToScreen();
?>