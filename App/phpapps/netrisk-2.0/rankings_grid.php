<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	// require the class
	include('./config.php');
	require('./class.rankings.php');

	// this is the query that will be displayed
	// remember: no ORDER BY and no LIMIT!
	$query = mysql_query("SELECT * FROM ". $mysql_prefix . "users") or die(mysql_error());

	while($row = mysql_fetch_assoc($query)) {
		$row['ranking'] = compute_ranking($row['login']);
		
		//Get the Number of Active Games for Each Player
 		$query2 = mysql_query("Select * FROM ". $mysql_prefix . "game_players WHERE pname = '".$row['login']."' AND pstate != 'dead' AND pstate != 'winner' ");
		$row['AGs'] = mysql_num_rows($query2);
					
		$gdata[] = $row;
	}

	// instantiate the class and pass the query string to it
	$grid = new dataGrid($gdata);

	// show all the columns
	$grid->showColumn("id");
	$grid->showColumn("login");
	$grid->showColumn("AGs");
	$grid->showColumn("win");
	$grid->showColumn("loss");
	$grid->showColumn("total_players");
	$grid->showColumn("games_played");
	$grid->showColumn("kills");
	$grid->showColumn("points");
	$grid->showColumn("ranking");

	// make the title column stretch 100%
	//$grid->setColumnHTMLProperties("login", "style='width:30%'");


	// sort the data by the "title" column
	$grid->setDefaultSortColumn("points");

	// create the function that will convert to 3 digit number
	function number_decimal($ranking)
	{
    	return number_format($ranking,3);
	}

	// bound this function to the ranking column
	$grid->setCallbackFunction(array("ranking"), "number_decimal");
	
	//String Replace the Column Names with Custom Names

		
	//Create the goto_profile function
	function goto_profile($login, $columns)
	{
			$id = $columns['id'];
			return ("<a href=\"index.php?p=profile&amp;id={$id}\"><span class=\"gname\"><b>$login</b></span></a>");
	}
    $grid->setCallBackFunction("login", "goto_profile");

	// Create the Grid
	$grid->render();

?>

