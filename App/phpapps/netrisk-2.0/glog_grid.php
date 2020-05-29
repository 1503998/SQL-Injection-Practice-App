<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
	// require the class
	include('./config.php');
    require('./class.glog.php');    
	
	$query = mysql_query("SELECT * FROM ". $mysql_prefix . "historical_log") or die(mysql_error());  
	
	//Verify Row Count, there is at least 1 game log entry
	if( $query && mysql_num_rows($query) <1 ){
		$row['gid'] = 0;
		$row['gname'] = 'There are no Game Log Entries at this time';
		$row['players'] = 0;
 		$row['card_rules'] = 0;
 		$row['map_type'] = 0;
 		$row['first'] = 0;
 		$row['second'] = 0;
 		$row['third'] = 0;
 		$row['fourth'] = 0;
 		$row['fifth'] = 0;
 		$row['sixth'] = 0;
 		$row['seventh'] = 0;
 		$row['eighth'] = 0;
 		$row['kills'] = 0;
 		$row['points'] = 0;
 		$row['time'] = 0;
		
 		$gdata[] = $row;
	} else {
		while ($row = mysql_fetch_assoc($query)) {
			$gdata[] = $row;
		}
	}
    // instantiate the class and pass the query string to it
    $grid = new dataGrid($gdata);

    // show all the columns
    $grid->showColumn("gid");
    $grid->showColumn("gname");
    $grid->showColumn("players");
 	$grid->showColumn("card_rules");
 	$grid->showColumn("map_type");
 	$grid->showColumn("first");
 	$grid->showColumn("second");
 	$grid->showColumn("third");
 	$grid->showColumn("fourth");
 	$grid->showColumn("fifth");
 	$grid->showColumn("sixth");
 	$grid->showColumn("seventh");
	$grid->showColumn("eighth");
 	$grid->showColumn("kills");
 	$grid->showColumn("points"); 	
 	$grid->showColumn("time");
  

    // make the title column stretch 100%
    $grid->setColumnHTMLProperties("gname", "style='text-align:left;width:100%'");
	
    function convert_time($time, $columns){
	    $time = date("m/d - H:i", $columns['time']);
	    
	    return $time;
    }
    
	$grid->setCallbackFunction(array("time"), "convert_time");
	
    // sort the data by the "gid" column
    $grid->setDefaultSortColumn("gid");

 
    // Create Grid
    $grid->render();
?>