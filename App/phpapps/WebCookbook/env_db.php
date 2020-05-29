<?php

/*
Web Cookbook

The intention was to make a management system for my own created/modified recipes. Everyone in the internet can see and print them, but in distinction from cooking communities like Recipezaar or Chefkoch in Germany only the manager of the site can add and modify recipes. To interact with users, I implemented 'my little forum' in an iframe and it is also planned, to implement a function to assess the recipes with the options for everybody (which could bring a lot of spam) or for registered users only. To avoid programming an own user management, the userdata table of 'my little forum' should be used.

Copyright (C) 2012  Joachim Gabel

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Initializes the database
function initDB () {

// Different accounts for testing on a local installation or on the server of your provider.
// Feel free to comment out, what you don't need.
	if ($_SERVER['SERVER_NAME'] == "localhost") {
		$server = "localhost";
		$user = "root";
		$passwort = "hacklab2019";
		$datenbank = "web_cook_book";
	}
	else {
		$server = "localhost";
		$user = "root";
		$passwort = "hacklab2019";
		$datenbank = "web_cook_book";
	}

	$conn_id = mysql_connect($server,$user,$passwort);
	mysql_select_db($datenbank);
	$query = "SELECT * FROM das_rezept";
	$result = mysql_query($query);
	$anzahl = mysql_affected_rows();

// This returns the number of records in the recipe table for viewing on the start and recipe page.
	return($anzahl);
}

?>