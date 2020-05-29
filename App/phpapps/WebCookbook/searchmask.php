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
echo "<h3>Erweiterte Suche</h3>\n";
echo "<form name=\"suchen\" action=\"searchrecipe.php\" method=\"get\">\n";
if (isset($_GET['mobile'])) {
	echo "<input type=\"hidden\" name=\"mobile\" value=\"1\">\n";
}
echo "<input type=\"hidden\" name=\"mode\" value=\"1\">\n";
echo "<table border=\"0\">\n";
echo "<tr><td class=\"zcells\" align=\"right\">Titel:</td><td class=\"zcells\"><input type=\"text\" name=\"title\"></td></tr>\n";
echo "<tr><td class=\"zcells\" align=\"right\">Pr&auml;fix:</td><td class=\"zcells\"><input type=\"text\" name=\"prefix\"></td></tr>\n";
echo "<tr><td class=\"zcells\" align=\"right\">Zubereitung:</td><td class=\"zcells\"><input type=\"text\" name=\"preparation\"></td></tr>\n";
echo "<tr><td class=\"zcells\" align=\"right\">Erg&auml;nzung:</td><td class=\"zcells\"><input type=\"text\" name=\"postfix\"></td></tr>\n";
echo "<tr><td class=\"zcells\" align=\"right\">Tipp:</td><td class=\"zcells\"><input type=\"text\" name=\"tipp\"></td></tr>\n";
echo "<tr><td class=\"zcells\" align=\"right\">Zutat:</td><td class=\"zcells\"><input type=\"text\" name=\"ingredient\"></td></tr>\n";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"zcells\"><input type=\"submit\" value=\"Suchen\"><input type=\"reset\" value=\"Reset\"></td></tr>\n";
echo "</table>\n";
echo "</form>\n";
echo "<div class=\"footer\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
echo "</div>\n";
echo "</div>\n";
echo "</body>\n";
echo "</html>\n";

?>