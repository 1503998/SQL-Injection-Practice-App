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
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>Rezeptverwaltung</title>\n";
echo "<meta name=\"Keywords\" content=\"\">\n";
echo "<script src=\"index.js\" type=\"text/javascript\"></script>\n";
// Shadowbox Start
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"shadowbox/shadowbox.css\">\n";
echo "<script type=\"text/javascript\" src=\"shadowbox/shadowbox.js\"></script>\n";
echo "<script type=\"text/javascript\">\n";
echo "Shadowbox.init({\n";
echo "handleOversize: \"drag\",\n";
echo "modal: true\n";
echo "});\n";
echo "</script>\n";
// Shadowbox End

include("language.php");
include("env_db.php");
include("env_sc.php");

$browser = $_SERVER['HTTP_USER_AGENT'];
if (isset($_GET['mobile'])) {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"mindex.css\">\n";
}
else {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"index.css\">\n";
}
echo "</head>\n";

echo "<body bgcolor=\"White\">\n";
echo "<div class=\"display\">\n";
echo "<div class=\"title\">Joachims Kochbuch</div>\n";
echo "<div class=\"rdisplay\">\n";

if (isset($_GET['mode'])) {
	$mode = 1;
	$title = $_GET['title'];
	$prefix = $_GET['prefix'];
	$preparation = $_GET['preparation'];
	$postfix = $_GET['postfix'];
	$tipp = $_GET['tipp'];
	$ingredient = $_GET['ingredient'];
	$sstring = "";
	if ($title != "") {
		$sstring = "a.title LIKE '%$title%' ";
	}
	if ($prefix != "") {
		if ($sstring == "") {
			$sstring = "a.prefix LIKE '%$prefix%' ";
		}
		else {
			$sstring = $sstring . "AND a.prefix LIKE '%$prefix%' ";
		}
	}
	if ($preparation != "") {
		if ($sstring == "") {
			$sstring = "a.preparation LIKE '%$preparation%' ";
		}
		else {
			$sstring = $sstring . "AND a.preparation LIKE '%$preparation%' ";
		}
	}
	if ($postfix != "") {
		if ($sstring == "") {
			$sstring = "a.postfix LIKE '%$postfix%' ";
		}
		else {
			$sstring = $sstring . "AND a.postfix LIKE '%$postfix%' ";
		}
	}
	if ($tipp != "") {
		if ($sstring == "") {
			$sstring = "a.tipp LIKE '%$tipp%' ";
		}
		else {
			$sstring = $sstring . "AND a.tipp LIKE '%$tipp%' ";
		}
	}
	if ($ingredient != "") {
		$mode = 2;
		if ($sstring == "") {
			$sstring = "b.description LIKE '%$ingredient%' ";
		}
		else {
			$sstring = $sstring . "AND b.description LIKE '%$ingredient%' ";
		}
	}
	if ($sstring != "") {
		searchRecipe($mode, $sstring);
	}
	else {
		echo "Suchstring ist leer!\n";
	}
}
else {
	if (isset($_GET['sstring'])) {
		$sstring = $_GET['sstring'];
		if ($sstring != "") {
			searchRecipe(0, $sstring);
		}
		else {
			echo "Suchstring ist leer!\n";
		}
	}
	else {
		$sstring = "Kein Suchstring definiert!";
	}
}

echo "<br><div class=\"footer\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
showMenu(65, 0);
echo "</div>\n";
echo "</div>\n";
echo "</body>\n";
echo "</html>\n";

?>