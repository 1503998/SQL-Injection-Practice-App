<?php

include("../config.php");

dbconnect();

if (isset($_GET['search']) && $_GET['search'] != '')
{
	$search = addslashes($_GET['search']);

	$suggest_query = mysql_query("SELECT displayname FROM " . $prefix . "members WHERE displayname like('" . $search . "%') ORDER BY displayname") OR DIE("Error: " . mysql_error());
	while($suggest = mysql_fetch_array($suggest_query))
	{
		//Return each page title seperated by a newline.
		echo $suggest[0] . "\n";
	}
}

mysql_close();

?>