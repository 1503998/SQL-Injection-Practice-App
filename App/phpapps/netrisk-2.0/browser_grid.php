<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
	// require the class
	include('./config.php');
    require("./class.browsergrid.php");    
	
    //Run Skip Player And Auto Delete Game Functions First
    skip_player();
    auto_game_delete();
    // End Auto Run Functions
    
	$query = mysql_query("SELECT * FROM ". $mysql_prefix . "game_info") or die(mysql_error());

	//Verify Row Count, there is at least 1 game log entry
	if( $query && mysql_num_rows($query) <1 ){
		$row['gid'] = 0;
		$row['gname'] = 'There are active games at this time';
		$row['Capacity'] = 0;
		$row['players'] = 0;
		$row['capacity'] = 0;
		$row['Info'] = "N/A";
		$row['gameinfo'] = "N/A";
		$row['Status'] = "waiting";
		$row['Current_Player'] = "N/A";
		$row['Next_Player'] = "N/A";
		$row['Last_Player'] = "N/A";
		$row['Last_Move'] = "N/A";
		$row['time_limit'] = "N/A";
		$row['last_move'] = 0;
		$gdata[] = $row;
	} else {
		while ($row = mysql_fetch_assoc($query)) {
			$row['Capacity'] = $row['players'] . "/" . $row['capacity'];
			if($row['custom_rules'] == Null){
				$crules = 'No';
			} else {
				$crules = 'Yes';
			} 
		
			$player = get_last_current_next_player($row['gid']);
			$row['Last_Player'] = $player[0];
			$row['Current_Player'] = $player[1];
			$row['Status'] = $player[2];
			$row['Next_Player'] = $player[3];
	
			$row['Info'] = "???";
			$row['Last_Move'] = $row['last_move'];
		
			$row['gameinfo'] =  ("Players: " . $row['players'] . "<br /> Card Rules: " . $row['card_rules'] . "<br /> Map Type: " . $row['map_type'] . "<br /> Mission Game: " . $row['gmode'] .  "<br /> Custom Rules: " . $crules );
			
			switch($row['Status']){
				case 'waiting': 	$row['Status_Order'] = 1;
									break;
				case 'initial': 	$row['Status_Order'] = 2;
									break;
				default:		 	$row['Status_Order'] = 3;
									break;
			}
			
		
			$gdata[] = $row;
		}
	}
	

    // instantiate the class and pass the query string to it
    $grid = new dataGrid($gdata);

    // show all the columns
    $grid->showColumn("gid");
    $grid->showColumn("gname");
    $grid->showColumn("Capacity");
    $grid->showColumn("Info");
    $grid->showColumn("Status");
    $grid->showColumn("Current_Player");
    $grid->showColumn("Next_Player");
    $grid->showColumn("Last_Player");
    $grid->showColumn("Last_Move");
    //$grid->showColumn("time_limit");

    // make the title column stretch 100%
    //$grid->setColumnHTMLProperties("gname", "style='text-align:left;width:50%'");
    
    // Set the Players color to their status
    function css_current_player($cplayer, $columns)
    {
    	if(isset($_SESSION['username']) && $cplayer == $_SESSION['username']){
	    	$status = $columns['Status'];
    		return ("<span class=\"state-{$status}\">$cplayer</span>");
	    }
	    else {
		    return $cplayer;
	    }
	}
	// bound css_current_player to the player
	$grid->setCallbackFunction(array("Current_Player"), "css_current_player");
	
	
	// Set the Players color to their status
    function css_status($status, $columns)
    {
		    return ("<span class=\"state-{$status}\">$status</span>");
	}
	// bound css_current_player to the player
	$grid->setCallbackFunction(array("Status"), "css_status");
	
	
	
	// Set the Color if their is a time limit
    function css_timelimit($lastmove, $columns)
    {
	    $tlimit = $columns['time_limit'];
	    $lastmove = date("m/d - H:i", $columns['last_move']);
	    $now = time();
	    $timeout = $now - $columns['last_move'];
		$remaining = $tlimit - $timeout;
		$timelimit = seconds_to_HMS($tlimit);
		$timeleft = seconds_to_HMS($remaining);		
    	if($tlimit > 0){
	    	return ("<a href=\"#" . $columns['gid'] . "\" class=\"tt\">" . $lastmove . "<span class=\"tooltip\"><span class=\"top\"><span class=\"middle\"> " . $timelimit . " timelimit <br />" . $timeleft ." remaining. <span class=\"bottom\"></span></span></span></span> </a>");
	    }
	    else {
		    return $lastmove;
	    }
	}
	// bound css_timelimit to the time limit.
	$grid->setCallbackFunction(array("Last_Move"), "css_timelimit");
	
	
	function css_gameinfo($ginfo, $columns)
	{
		$ginfo = $columns['gameinfo'];
		return ("<a href=\"#" . $columns['gid'] . "\" class=\"tt\">???<span class=\"tooltip\"><span class=\"top\"><span class=\"middle\"> " . $ginfo ."<span class=\"bottom\"></span></span></span></span></a>");	
	}
	// bound css_gameinfo to the game info.
	$grid->setCallbackFunction(array("Info"), "css_gameinfo");

	function css_players($players, $columns)
	{
		$gid = $columns['gid'];
		$players = get_players($gid);
		return ("<a href=\"#" . $gid . "\" class=\"tt\">" . $columns['players'].'/'.$columns['capacity'] . "<span class=\"tooltip\"><span class=\"top\"><span class=\"middle\"> " . $players . "<span class=\"bottom\"></span></span></span></span></a>");
		
	}
	// bound css_gameinfo to the game info.
	$grid->setCallbackFunction(array("Capacity"), "css_players");
	
	
	
    // create the function that will change a unix timestamp to an user-readable date
	function convert_unixtimestamp($timestamp)
	{
    	return date("m/d - H:i", $timestamp);
	}
	
	//seconds_to_HMS function is now located in the functions_common file
	// bound this function to the "timelimit" column's fields
	//$grid->setCallbackFunction(array("time_limit"), "seconds_to_HMS");

	// bound this function to the "created" and "modified" column's fields
	$grid->setCallbackFunction(array("Last Move"), "convert_unixtimestamp");

    // sort the data by the "title" column
    $grid->setDefaultSortColumn("Status");
    $grid->setSecondarySortColumns("Last_Move");

 
    function goto_game($gname, $columns)
	{
		$gid = $columns['gid'];
		if($gid > 0){
			return ("<a href=\"login.php?&amp;id={$gid}\"><span class=\"gname\"><b>$gname</b></span></a>");
		} else {
			return ("<span class=\"gname\">$gname</span>");
		}
	}
    $grid->setCallBackFunction("gname", "goto_game");

   
	
    // Create Grid
    $grid->render();
    

?>