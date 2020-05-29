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
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"shadowbox/shadowbox.css\">\n";
include("language.php");
include("env_db.php");
include("env_sc.php");
$browser = $_SERVER['HTTP_USER_AGENT'];
if (strstr($browser,"MSIE") != false) {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"msie.css\">\n";
}
else {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"index.css\">\n";
}
echo "</head>\n";

echo "<body bgcolor=\"White\">\n";
echo "<div class=\"pdisplay\">\n";
echo "<div class=\"title\" style=\"left:50px;width:750px;\">Joachims Kochbuch</div>\n";
echo "<div class=\"maske\" style=\"left:50px;width:750px;\">\n";

initDB();

if (isset($_GET['id'])) {
	$id = $_GET['id'];
		
	$query = "SELECT * FROM das_rezept WHERE id = $id";
	$result = mysql_query($query);

	$id = mysql_result($result,0,"id");
	$titel = mysql_result($result,0,"title");
	$servings = mysql_result($result,0,"servings");
	$prefix = mysql_result($result,0,"prefix");
	$preparation = mysql_result($result,0,"preparation");
	$postfix = mysql_result($result,0,"postfix");
	$tipp = mysql_result($result,0,"tipp");
	$image = mysql_result($result,0,"image");
	
	echo "<div class=\"headline\" style=\"left:50px;width:750px;\">$titel</div>\n";

	$x_query = "SELECT c.text FROM dat_category c, dat_rezepttocat r WHERE r.recipe = $id AND r.category = c.id ORDER BY c.id";
	$x_result = mysql_query($x_query);
	$rubrik = "";
	while ($row = mysql_fetch_row($x_result)) {
		if ($rubrik == "") {
			$rubrik = $row[0];
		}
		else {
		$rubrik = $rubrik . ", " . $row[0];
		}
	}
	echo "<div class=\"rubrik\">Rubriken: $rubrik</div>\n";
	echo "<div class=\"servings\" style=\"left:650px;\">$servings Portionen</div>\n";
	echo "<div class=\"rezeptpos\" style=\"width:750px;\">\n";
	echo "<div class=\"zutaten\" style=\"width:750px;\">\n";
	if ($prefix != "") {
		echo "$prefix\n";
	}
	if ($image != "") {
		if ($image == "slideshow") {
			$x_query = "SELECT * FROM dat_slides WHERE recipe = $id";
			$x_result = mysql_query($x_query);
			$image = mysql_result($x_result,0,"image");
		}
		echo "<div class=\"image\" style=\"width:345px;\"><img src=\"images/$image\" width=\"345\"></div>\n";
	}
	echo "<b>Zutaten:</b>\n";
	echo "<table border=\"0\" cellpadding=\"0\">\n";
	$x_query = "SELECT * FROM dat_ingredient WHERE recipe = $id";
	$x_result = mysql_query($x_query);
	while ($row = mysql_fetch_row($x_result)) {
		if ($row[1] < 1) {
			switch ($row[1]) {
				case 0.5:
					$ausgabe = "&frac12;";
					break;
				case 0.25:
					$ausgabe = "&frac14;";
					break;
				case 0:
					$ausgabe = "";
					break;
			}
			echo "<tr><td class=\"zcells\" valign=\"top\">$ausgabe</td><td class=\"icells\"></td><td class=\"zcells\" valign=\"top\">$row[2]</td><td class=\"icells\"></td><td class=\"zcells\">$row[3]</td></tr>\n";
		}
		else {
			echo "<tr><td class=\"zcells\" valign=\"top\">$row[1]</td><td class=\"icells\"></td><td class=\"zcells\" valign=\"top\">$row[2]</td><td class=\"icells\"></td><td class=\"zcells\">$row[3]</td></tr>\n";
		}
	}
	echo "</table>\n";
	echo "<br>\n";
	echo "<br>$preparation\n";
	if ($postfix != "") {
		echo "$postfix\n";
	}
	if ($tipp != "") {
		echo "<b>Tipp:</b> $tipp\n";
	}
	echo "</div> <!-- Zutaten zu -->\n";
	echo "<div style=\"clear:both;\"><a href=\"\" onclick=\"javascript:window.print();\">Drucken</a> \n";
	echo "<a href=\"\" onclick=\"javascript:window.close();\">Schlie&szlig;en</a></div><br>\n";
	echo "</div> <!-- Rezeptpos zu -->\n";

}
echo "</div>\n";
echo "</div>\n";
echo "</body>\n";
echo "</html>\n";

?>