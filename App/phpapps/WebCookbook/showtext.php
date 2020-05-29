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

if (isset($_GET["mode"])) {
	$mode = $_GET["mode"];
	if ($mode > 800 && $mode < 900) {
		$art = 3;
	}
	else if ($mode > 700 && $mode < 800) {
		$art = 2;
	}
	else if ($mode > 600 && $mode < 700) {
		$art = 4;
	}
	else {
		$art = 1;
	}
	showText($mode, $art);
}

echo "</div>\n";
showMenu(65, 40);
echo "</body>\n";
echo "</html>\n";
?>