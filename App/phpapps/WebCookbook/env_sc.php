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

// Displays one record of the recipe table with all belonging data.
function showRezept($rezept, $mode) {

	include("language.php");
	initDB();
		
	$query = "SELECT * FROM das_rezept WHERE id = $rezept";
	$result = mysql_query($query);

	$id = mysql_result($result,0,"id");
	$titel = mysql_result($result,0,"title");
	$servings = mysql_result($result,0,"servings");
	$prefix = mysql_result($result,0,"prefix");
	$preparation = mysql_result($result,0,"preparation");
	$postfix = mysql_result($result,0,"postfix");
	$tipp = mysql_result($result,0,"tipp");
	$worktime = mysql_result($result,0,"arbeitszeit");
	$kalorie = mysql_result($result,0,"brennwert");
	$image = mysql_result($result,0,"image");
	
// Recipe counter (Due to errors on the server version of PHP, this function does not work.
/*
	$z_query = "SELECT a.zaehler, b.id, b.IP FROM sys_zaehler a, sys_ip b WHERE b.id = $id AND b.IP = '" . getenv('REMOTE_ADDR') . "'";
	$z_result = mysql_query($z_query);
	$zaehler = intval(mysql_result($z_result,0,"zaehler"));
	$z_id = mysql_result($z_result,0,"id");
	$ip = mysql_result($z_result,0,"IP");
	echo "Zaehler: $zaehler id: $z_id ip: $ip\n";
	if ($zaehler == "" || $zaehler == 0) {
		$z_query = "SELECT * FROM sys_zaehler WHERE id = $id";
		$z_result = mysql_query($z_query);
		$zaehler = intval(mysql_result($z_result,0,"zaehler"));
		$zaehler = $zaehler + 1;
		$z_query = "UPDATE sys_zaehler SET zaehler = $zaehler WHERE id = $id";
		$z_result = mysql_query($z_query);
		$z_query = "INSERT INTO sys_ip (id, IP) VALUES ($id, '" . getenv('REMOTE_ADDR') . "')";
		$z_result = mysql_query($z_query);
	}
*/
//	if 
	
	echo "<div class=\"headline\">$titel</div>\n";
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
	echo "<div class=\"rubrik\">$sys_sections $rubrik</div>\n";
	echo "<div class=\"servings\">$servings $sys_portions</div>\n";
	echo "<div class=\"rezeptpos\">\n";
	echo "<form action=\"saverecipe.php\" method=\"get\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<input type=\"button\" value=\"$sys_print\" onclick=\"showPrintForm($id);\">\n";
	echo "<input type=\"submit\" value=\"$sys_save\">\n";
	echo "</form>\n";
	echo "<div class=\"zutaten\">\n";
	if ($prefix != "") {
		echo "$prefix\n";
	}
// Display the image of the recipe, otherwise all related images counted in the dat_slides table.
	if ($image != "") {
		if ($image == "slideshow") {
			$x_query = "SELECT * FROM dat_slides WHERE recipe = $id ORDER BY id";
			$x_result = mysql_query($x_query);
			$i = 0;
			while ($row = mysql_fetch_row($x_result)) {
				if ($i == 0) {
					echo "<div class=\"image\"><a href=\"images/$row[1]\" rel=\"shadowbox[$titel]\" title=\"$row[2]\"><img src=\"images/$row[1]\" width=\"445\"><br>Slideshow</a></div>\n";
					$i++;
				}
				else {
					echo "<div class=\"image\"><a href=\"images/$row[1]\" rel=\"shadowbox[$titel]\" title=\"$row[2]\"><img src=\"images/$row[1]\" width=\"0\"></a></div>\n";
				}
			}
		}
		else {
			echo "<div class=\"image\"><a href=\"images/$image\" rel=\"shadowbox\" title=\"$titel\"><img src=\"images/$image\" width=\"445\"></a></div>\n";
		}
	}
// Displays all belonging ingredients.
	echo "<b>$sys_ingred:</b>\n";
	echo "<table border=\"0\" cellpadding=\"0\">\n";
	$x_query = "SELECT * FROM dat_ingredient WHERE recipe = $id ORDER BY id";
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
			echo "<tr><td class=\"zcells\" valign=\"top\" align=\"right\">$ausgabe</td><td class=\"icells\"></td><td class=\"zcells\" valign=\"top\" align=\"right\">$row[2]</td><td class=\"icells\"></td><td class=\"zcells\">$row[3]</td></tr>\n";
		}
		else {
			echo "<tr><td class=\"zcells\" valign=\"top\" align=\"right\">$row[1]</td><td class=\"icells\"></td><td class=\"zcells\" valign=\"top\" align=\"right\">$row[2]</td><td class=\"icells\"></td><td class=\"zcells\">$row[3]</td></tr>\n";
		}
	}
	echo "</table>\n";
	echo "<br>\n";
	echo "<br>$preparation\n";
	if ($postfix != "") {
		echo "$postfix\n";
	}
	if ($tipp != "") {
		echo "<b>$sys_tip</b> $tipp\n";
	}
	$z_query = "SELECT datum FROM sys_zaehler WHERE id = $id";
	$z_result = mysql_query($z_query);
	$datum = mysql_result($z_result,0,"datum");
	echo "<table border=\"0\" width=\"100%\">\n";
	echo "<tr>\n";
	echo "<td class=\"zcells\" align=\"right\" width=\"200\"><b>$sys_mod</b<</td>\n";
// This is only for the german date format. In English speaking countries comment this out.
// For all other countries: Customize the setDate() function to your need.
	$datum = setDate($datum);
	echo "<td class=\"zcells\" width=\"300\">$datum</td>\n";
	echo "<td class=\"zcells\" align=\"right\" width=\"200\"><b>$sys_wt</b></td>\n";
	if ($worktime > 0) {
		echo "<td class=\zcells\">$worktime</td>\n";
	}
	else {
		echo "<td></td>\n";
	}
	echo "</tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td class=\"zcells\" valign=\"bottom\"><b>$sys_com_comment</b></td><td class=\"zcells\" align=\"right\"><b>$sys_rnk_label</b></td>\n";
	echo "<td class=\"zcells\" colspan=\"2\">\n";
	$wert = explode("|", $_COOKIE['logged']);
	showRanking($id);
	echo "</td></tr>\n";
	echo "<tr><td class=\"zcells\" colspan=\"4\">\n";
	showComments($id);
	echo "</td></tr>\n";
	if ($wert[0] == 1) {
		echo "<tr><td class=\"zcells\" colspan=\"4\">\n";
		showCominput($id, $wert[1]);
		echo "</td></tr>\n";
	}
	echo "</table>\n";
	echo "<div style=\"clear:both;\"></div><br>\n";
//	echo "<div class=\"zaehler\">$sys_prerecipe $zaehler $sys_postrecipe</div><br>";
	echo "</div> <!-- Zutaten zu -->\n";
	echo "<div class=\"footer\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
	echo "</div> <!-- Rezeptpos zu -->\n";
}

// This shows either the recipe page with the sections or the sections self as links to the recipes.
function showRubric($mode) {
	include("language.php");
	echo "<div class=\"rdisplay\">\n";
	echo "<div class=\"idisplay\">\n";
	echo "<table>\n";
	switch ($mode) {
		case 0: // This is the start page of the application.
			$anzahl = InitDB();
			if (isset($_GET['mobile'])) {
				echo "<div class=\"servings\" style=\"left:600px;top:0px;width:160px;\">$sys_rezanz $anzahl</div>\n";
				showSearch(440, 15);
			}
			else {
				echo "<div class=\"servings\" style=\"left:750px;top:0px;width:160px;\">$sys_rezanz $anzahl</div>\n";
				showSearch(610, 15);
			}
			echo "<br><img src=\"images/system/empty.gif\" height=\"500\" align=\"center\">\n";
			break;
		case 1: // The recipes main page. (Not used anymore)
			$anzahl = InitDB();
			if (isset($_GET['mobile'])) {
				echo "<div class=\"servings\" style=\"left:600px;top:0px;width:160px;\">$sys_rezanz $anzahl</div>\n";
			}
			else {
				echo "<div class=\"servings\" style=\"left:750px;top:0px;width:160px;\">$sys_rezanz $anzahl</div>\n";
			}
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_recipe</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=101\">$sys_appetizer</a></td>\n";
			echo "<td></td><td class=\"icells\"></td><td class=\"zcells\"><a href=\"./?mode=200\">$sys_region</td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=102\">$sys_soup</a></td>\n";
			echo "<td></td><td class=\"icells\"></td><td class=\"zcells\"><a href=\"./?mode=300\">$sys_international</td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=103\">$sys_salad</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=104\">$sys_deli</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=105\">$sys_fish</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><b>$sys_meat</b></td><td class=\"zcells\"><a href=\"./?mode=106\">$sys_poultry</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"></td><td class=\"zcells\"><a href=\"./?mode=107\">$sys_minced</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"></td><td class=\"zcells\"><a href=\"./?mode=108\">$sys_innereien</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"></td><td class=\"zcells\"><a href=\"./?mode=109\">$sys_calf</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"></td><td class=\"zcells\"><a href=\"./?mode=110\">$sys_lamb</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"></td><td class=\"zcells\"><a href=\"./?mode=111\">$sys_beef</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"></td><td class=\"zcells\"><a href=\"./?mode=112\">$sys_pork</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"></td><td class=\"zcells\"><a href=\"./?mode=113\">$sys_game</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"></td><td class=\"zcells\"><a href=\"./?mode=114\">$sys_wurst</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=115\">$sys_egg</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=116\">$sys_potato</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=117\">$sys_noodle</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=118\">$sys_rice</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=119\">$sys_conserve</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=120\">$sys_casserole</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=121\">$sys_sauces</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=122\">$sys_dessert</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=123\">$sys_baking</a></td></tr>\n";
			echo "<tr><td class=\"zcells\"><a href=\"./?mode=124\">$sys_others</a></td></tr>\n";
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 2: // Menus
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_menu</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(500);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 6: // Forums
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_forum</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				echo "<tr><td class=\"zcells\"><iframe src=\"./forum/\" width=\"700\" height=\"400\"></iframe></td></tr>\n";
			}
			else {
				echo "<tr><td class=\"zcells\"><iframe src=\"./forum/\" width=\"900\" height=\"500\"></iframe></td></tr>\n";
			}
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			break;
		case 7: // Options
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_options\n";
			echo "<br><img src=\"images/system/empty.gif\" width=\"300\" align=\"center\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">\n";
			echo "<div class=\"option\">\n";
			echo "<div class=\"options1\">\n";
			echo "<table border=\"0\" width=\"100%\">\n";
			echo "<form>\n";
			echo "<tr><td class=\"zcells\"><b>$sys_opt_outformat</b></td></tr>\n";
			echo "<tr><td class=\"zcells\">\n";
			echo "<input type=\"radio\" id=\"omode1\" name=\"omode\" value=\"wcb\">Webcookbook\n";
			if ($sys_opt_outformat == "Ausgabeformat:") {
				echo "<input type=\"radio\" name=\"omode\" id=\"omode2\" value=\"mmf\">Mealmaster\n";
				echo "<input type=\"radio\" name=\"omode\" id=\"omode3\" value=\"rkv\" checked>Rezkonv\n";
			}
			else {
				echo "<input type=\"radio\" name=\"omode\" id=\"omode2\" value=\"mmf\" checked>Mealmaster\n";
				echo "<input type=\"radio\" name=\"omode\" id=\"omode3\" value=\"rkv\">Rezkonv\n";
			}
			echo "</td></tr>\n";
			echo "<tr><td class=\"zcells\" align=\"center\">\n";
			echo "<input type=\"button\" value=\"$sys_save\" onclick=\"moveTostart(1)\">\n";
			echo "<input type=\"button\" value=\"$sys_cancel\" onclick=\"moveTostart(0)\">\n";
			echo "</td></tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"2\">$sys_opt_warning</td></tr>\n";
			echo "</form>\n";
			echo "</table>\n";
			echo "</div>\n";
			echo "<div class=\"options2\">\n";
			echo "<form name=\"login\" action=\"logonuser.php\" method=\"post\">\n";
			echo "<table border=\"0\" width=\"100%\">\n";
			echo "<tr><td class=\"zcells\" colspan=\"3\"><b>$sys_login:</b></td></tr>\n";
			echo "<tr><td class=\"zcells\">Username:</td><td><input type=\"text\" name=\"name\"></td>\n";
			echo "<td class=\"zcells\"><input type=\"checkbox\" name=\"permlogged\">$sys_permlog</td></tr>\n";
			echo "<tr><td class=\"zcells\">Password:</td><td><input type=\"password\" name=\"pass\"></td></tr>\n";
			echo "<tr><td class=\"zcells\" align=\"center\" colspan=\"3\">\n";
			echo "<input type=\"submit\" value=\"$sys_login\">\n";
			echo "<input type=\"button\" value=\"$sys_cancel\" onclick=\"moveTostart(0)\">\n";
			echo "</td></tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"3\">$sys_opt_login</td></tr>\n";
			echo "</table>\n";
			echo "</form>\n";
			echo "</div>\n";
			echo "</div>\n";
			break;
		case 8:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_help</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(10000);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 9:
			initDB();
			$id = $_GET["id"];
			$query = "SELECT * FROM das_rezept WHERE id = $id";
			$result = mysql_query($query) or die(mysql_error());
			$titel = mysql_result($result, 0, "title") or die(mysql_error());
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_rnk_msg \"$titel\"</td></tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
			echo "<tr><td colspan=\"2\">\n";
			$wert = explode("|", $_COOKIE['logged']);
			$query = "SELECT * FROM dat_bewertung WHERE recipe = $id";
			$result = mysql_query($query);
			$anzahl = mysql_num_rows($result);
			$wert = explode("|", $_COOKIE['logged']);
			if ($anzahl == 0) {
				$rank = 0;
			}
			else {
				$rank = 0;
				while ($row = mysql_fetch_row($result)) {
					if ($row[2] == $wert[1]) {
						echo "<script>alert('$sys_rnk_erruser');\n";
						echo "location.href = './rezeptanzeige.php?currid=$id'";
						echo "</script>\n";
//						header("Location: ./rezeptanzeige?currid=$id");
					}
					$rank = $rank + $row[3];
				}
				$rank = $rank + 100;
				$anzahl = $anzahl + 20;
				$rank = $rank / $anzahl;
			}
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n";
			echo "<form name=\"rankform\" method=\"post\" action=\"saveranking.php\">\n";
			echo "<tr>\n";
			if ($_COOKIE['logged'] != "") {
				if ($rank > 0) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank01\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(1)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank01\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(1)\"></td>\n";
				}
				if ($rank > 1) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank02\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(2)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank02\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(2)\"></td>\n";
				}
				if ($rank > 2) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank03\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(3)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank03\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(3)\"></td>\n";
				}
				if ($rank > 3) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank04\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(4)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank04\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(4)\"></td>\n";
				}
				if ($rank > 4) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank05\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(5)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank05\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(5)\"></td>\n";
				}
				if ($rank > 5) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank06\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(6)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank06\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(6)\"></td>\n";
				}
				if ($rank > 6) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank07\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(7)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank07\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(7)\"></td>\n";
				}
				if ($rank > 7) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank08\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(8)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank08\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(8)\"></td>\n";
				}
				if ($rank > 8) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank09\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(9)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank09\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(9)\"></td>\n";
				}
				if ($rank > 9) {
					echo "<td><img src=\"images/system/goldknopf.jpg\" id=\"rank10\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(10)\"></td>\n";
				}
				else {
					echo "<td><img src=\"images/system/stahlknopf.jpg\" id=\"rank10\" border=\"0\" onmouseover=\"document.body.style.cursor='pointer'\" onmouseout=\"document.body.style.cursor='auto'\" onclick=\"changeRank(10)\"></td>\n";
				}
			}
			echo "</td></tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
			echo "<tr><td align=\"center\" colspan=\"10\">\n";
			echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
			echo "<input type=\"hidden\" name=\"ranking\" id=\"ranking\" value=\"0\">\n";
			echo "<input type=\"submit\" name=\"submitrank\" value=\"$sys_save\">\n";
			echo "<input type=\"button\" name=\"abbrechen\" value=\"$sys_cancel\" onclick=\"location.href='./rezeptanzeige.php?currid=$id'\">\n";
			echo "</td></tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
			echo "</form>\n";
			echo "</table>\n";
			break;
		case 101: // Appetizer
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_appetizer</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(101);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 102: // Soups and stews
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_soup</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(102);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 103: // Vegetables and salads
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_salad</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(103);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 104: // Delis
			echo "<tr><td class=\"hcells\" colspan=\"2\"><br>$sys_deli</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"2\">$sys_delitext</td></tr>\n";
			showKategorie(104);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 105: // Seafood
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_fish</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(105);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 106: // Poultry
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_poultry</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(106);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 107: // Minced meat
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_minced</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(107);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 108: // Innereien (I use the German word, because the English offal means the same as dirt or trash ... not acceptable for me ;-))
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_innereien</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(108);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 109: // Calf
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_calf</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(109);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 110: // Lamb
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_lamb</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(110);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 111: // Beef
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_beef</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(111);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 112: // Pork
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_pork</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(112);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 113: // Game, venison
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_game</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(113);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 114: // Cooking with sausages
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_wurst</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(114);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 115: // Egg dishes
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_egg</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(115);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 116: // Potatoes
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_potato</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(116);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 117: // Noodles
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_noodle</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(117);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 118: // Rice
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_rice</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(118);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 119: // Conserve and the making of sausages
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_conserve</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(119);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 120: // Casseroles
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_casserole</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(120);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 121: // Sauces
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_sauces</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(121);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 122: // Dessert
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_dessert</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(122);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 123: // Baking
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_baking</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(123);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 124: // Others
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_others</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(124);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			showSearch(610, 15);
			break;
		case 200: // Regionals
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_region</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(200);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
// 201 to 299 are German regionals (not Bundesländer!), change them to your own country needs.
// In GB there could be England, Wales or the Midlands etc., in France departements or Provence, Bretagne
// etc. and in the US the states with their own recipes. Don't forget to change the dat_category table!
		case 201: 
			echo "<tr><td class=\"hcells\" colspan=\"2\">Baden-W&uuml;rtemberg</td></tr>\n";
			echo "<tr><td colspan=\"2\"><img src=\"images/system/Bw.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(201);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 202:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Bayern</td></tr>\n";
			echo "<tr><td colspan=\"2\"><img src=\"images/system/By.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(202);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 203:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Berlin und Brandenburg</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Be.gif\" width=\"200\"><img src=\"images/system/Br.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(203);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 204:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Franken</td></tr>\n";
			echo "<tr><td colspan=\"2\"><img src=\"images/system/Fr.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(204);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 205:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Hessen</td></tr>\n";
			echo "<tr><td colspan=\"2\"><img src=\"images/system/He.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(205);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 206:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Niedersachsen/Hamburg/Bremen</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Ns.gif\" width=\"200\"><img src=\"images/system/Hm.gif\" width=\"200\" height=\"133\"><img src=\"images/system/Hb.gif\" width=\"200\" height=\"133\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(206);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 207:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Pfalz</td></tr>\n";
			echo "<tr><td colspan=\"2\"><img src=\"images/system/Rp.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(207);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 208:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Rheinland</td></tr>\n";
			echo "<tr><td colspan=\"2\"><img src=\"images/system/Koeln.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(208);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 209:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Schleswig-Holstein</td></tr>\n";
			echo "<tr><td colspan=\"2\"><img src=\"images/system/Sh.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(209);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 210:
			echo "<tr><td class=\"hcells\" colspan=\"2\">Westfalen</td></tr>\n";
			echo "<tr><td colspan=\"2\"><img src=\"images/system/Nrw.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(210);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 300:  // International kitchen
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_international</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(300);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
// 301 to 399 are for countries or regions over the world. Most of these kitchen have a common origin like
// the Arabian peninsula, the north African Maghreb or central Asia. Change these countries for your own need.
// Flags of the countries you can find all over the internet. Choose them from a side with a Creative Commons license.
		case 301:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_arab</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Kuwait.gif\" width=\"200\"><img src=\"images/system/Saudi_arabien.png\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(301);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 302:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_belgien</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Belgien.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(302);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 303:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_china</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/China.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(303);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 304:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_denmark</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Daenemark.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(304);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 305:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_france</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Frankreich.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(305);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 306:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_greece</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Griechenland.jpg\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(306);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 307:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_indonesia</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Indonesien.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(307);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 308:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_italia</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Italien.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(308);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 309:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_jamaica</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Jamaika.jpg\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(309);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 310:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_caucasus</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Georgiena.gif\" width=\"200\"><img src=\"images/system/aserbaidschan.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(310);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 311:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_cs</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Kroatien.gif\" width=\"200\"><img src=\"images/system/Serbien.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(311);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 312:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_mexico</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Mexiko.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(312);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 313:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_nmeast</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Afghanistan.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(313);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 314:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_holland</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Niederlande.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(314);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 315:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_austria</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Oesterreich.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(315);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 316:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_russia</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Russland.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(316);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 317:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_sweden</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Schweden.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(317);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 318:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_swizz</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Schweiz.gif\" width=\"133\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(318);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 319:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_spain</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Spanien.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(319);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 320:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_thai</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Thailand.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(320);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 321:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_tschechia</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Tschechien.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(321);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 322:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_turkey</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Tr.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(322);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 323:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_hungary</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Ungarn.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(323);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 324:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_uk</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Grossbritannien.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(324);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 325:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_usa</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Usa.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(325);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 326:
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_maghreb</td></tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"images/system/Algerien.gif\" width=\"200\"><img src=\"images/system/Marokko.gif\" width=\"200\"></td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(326);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 600: // Beverages
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_bev</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(600);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 700: // Ingredients with special pages
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_ingred</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(700);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 800: // Howto's
			echo "<tr><td class=\"hcells\" colspan=\"2\">$sys_prod</td></tr>\n";
			echo "<tr><td class=\"hcells\" colspan=\"2\">&nbsp;</td></tr>\n";
			showKategorie(800);
			echo "<tr><td class=\"zcells\">&nbsp;</td></tr>\n";
			if (isset($_GET['mobile'])) {
				showSearch(440, 15);
			}
			else {
				showSearch(610, 15);
			}
			break;
		case 10000: // Help
			break;
	}
	echo "</table><br>\n";
	echo "<div class=\"footer\" style=\"left:-50;\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
	echo "</div>\n";
	echo "</div>\n";
	showMenu(65, 40);
}

// Shows the pages
function showKategorie($kategorie) {
	
	initDB();

	if ($kategorie < 200) {
		$query = "SELECT r.id, r.title FROM das_rezept r, dat_rezepttocat c WHERE c.category = $kategorie AND r.id = c.recipe ORDER BY r.title";
		$result = mysql_query($query);
		$anzahl = mysql_affected_rows();
		$peng = 0;
		$equalrow = false;
		while ($row = mysql_fetch_row($result)) {
			$z_query = "SELECT datum FROM sys_zaehler WHERE id = $row[0]";
			$z_result = mysql_query($z_query);
			$zeit = mysql_result($z_result,0,"datum");
			$vergleich = datDifference($zeit);
			if (isset($_GET['mobile'])) {
				if ($vergleich <= 14) {
					if ($anzahl <= 25) {
						echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
					}
					else {
						if ($peng < 3) {
							echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
						}
						else {
							if (!$equalrow) {
								$equalrow = true;
								echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a> <span class=\"neuspan\">Neu</span></td>\n";
								echo "<td class=\"icells\"></td>\n";
							}
							else {
								$equalrow = false;
								echo "<td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
							}
						}
						$peng = $peng + 1;
						echo "$peng<br>\n";
					}
				}
				else {
					if ($anzahl <= 25) {
						echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a></td></tr>\n";
					}
					else {
						if ($peng < 3) {
							echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a></td></tr>\n";
						}
						else {
							if (!$equalrow) {
								$equalrow = true;
								echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a></td>\n";
								echo "<td class=\"icells\"></td>\n";
							}
							else {
								$equalrow = false;
								echo "<td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a></td></tr>\n";
							}
						}
					}
					$peng = $peng + 1;
				}
			}
			else {
				if ($vergleich <= 14) {
					if ($anzahl <= 25) {
						echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
					}
					else {
						if ($peng <= 3) {
							echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
						}
						else {
							if (!$equalrow) {
								$equalrow = true;
								echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a> <span class=\"neuspan\">Neu</span></td>\n";
								echo "<td class=\"icells\"></td>\n";
							}
							else {
								$equalrow = false;
								echo "<td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
							}
						$peng = $peng + 1;
						}
					}
				}
				else {
					if ($anzahl <= 25) {
						echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a></td></tr>\n";
					}
					else {
						if ($peng <= 1) {
							echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a></td></tr>\n";
						}
						else {
							if (!$equalrow) {
								$equalrow = true;
								echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a></td>\n";
								echo "<td class=\"icells\"></td>\n";
							}
							else {
								$equalrow = false;
								echo "<td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a></td></tr>\n";
							}
						}
						$peng = $peng + 1;
					}
				}
			}
		}
	}
	else if ($kategorie == 200) {
		$query = "SELECT * FROM dat_category WHERE id > 200 AND id < 300 ORDER BY text";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			if (isset($_GET['mobile'])) {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./?mode=$row[0]&mobile=1\">$row[1]</a></td></tr>\n";
			}
			else {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./?mode=$row[0]\">$row[1]</a></td></tr>\n";
			}
		}
	}
	else if ($kategorie > 200 && $kategorie < 300) {
		$query = "SELECT r.id, r.title FROM das_rezept r, dat_rezepttocat c WHERE c.category = $kategorie AND r.id = c.recipe ORDER BY r.title";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			$z_query = "SELECT datum FROM sys_zaehler WHERE id = $row[0]";
			$z_result = mysql_query($z_query);
			$zeit = mysql_result($z_result,0,"datum");
			$vergleich = datDifference($zeit);
			if (isset($_GET['mobile'])) {
				if ($vergleich <= 14) {
					echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
				}
				else {
					echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a></td></tr>\n";
				}
			}
			else {
				if ($vergleich <= 14) {
					echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
				}
				else {
					echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a></td></tr>\n";
				}
			}
		}
	}
	else if ($kategorie == 300) {
		$query = "SELECT * FROM dat_category WHERE id > 300 AND id < 400 ORDER BY text";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			if (isset($_GET['mobile'])) {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./?mode=$row[0]&mobile=1\">$row[1]</a></td></tr>\n";
			}
			else {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./?mode=$row[0]\">$row[1]</a></td></tr>\n";
			}
		}
	}
	else if ($kategorie > 300 && $kategorie < 400) {
		$query = "SELECT r.id, r.title FROM das_rezept r, dat_rezepttocat c WHERE c.category = $kategorie AND r.id = c.recipe ORDER BY r.title";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			$z_query = "SELECT datum FROM sys_zaehler WHERE id = $row[0]";
			$z_result = mysql_query($z_query);
			$zeit = mysql_result($z_result,0,"datum");
			$vergleich = datDifference($zeit);
			if (isset($_GET['mobile'])) {
				if ($vergleich <= 14) {
					echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
				}
				else {
					echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie&mobile=1\">$row[1]</a></td></tr>\n";
				}
			}
			else {
				if ($vergleich <= 14) {
					echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a> <span class=\"neuspan\">Neu</span></td></tr>\n";
				}
				else {
					echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"rezeptanzeige.php?currid=$row[0]&category=$kategorie\">$row[1]</a></td></tr>\n";
				}
			}
		}
	}
	else if ($kategorie == 500) {
		$query = "SELECT * FROM dat_category WHERE id > 500 AND id < 600 ORDER BY text";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			if (isset($_GET['mobile'])) {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]&mobile=1\">$row[1]</a></td></tr>\n";
			}
			else {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]\">$row[1]</a></td></tr>\n";
			}
		}
	}
	else if ($kategorie == 600) {
		$query = "SELECT * FROM dat_category WHERE id > 600 AND id < 700 ORDER BY text";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			if (isset($_GET['mobile'])) {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]&mobile=1\">$row[1]</a></td></tr>\n";
			}
			else {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]\">$row[1]</a></td></tr>\n";
			}
		}
	}
	else if ($kategorie == 700) {
		$query = "SELECT * FROM dat_category WHERE id > 700 AND id < 800 ORDER BY text";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			if (isset($_GET['mobile'])) {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]&mobile=1\">$row[1]</a></td></tr>\n";
			}
			else {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]\">$row[1]</a></td></tr>\n";
			}
		}
	}
	else if ($kategorie == 800) {
		$query = "SELECT * FROM dat_category WHERE id > 800 AND id < 900 ORDER BY text";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			if (isset($_GET['mobile'])) {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]&mobile=1\">$row[1]</a></td></tr>\n";
			}
			else {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]\">$row[1]</a></td></tr>\n";
			}
		}
	}
	else if ($kategorie == 10000) {
//		include("language.php");
//		switch ($sys_lang) {
//			case "de":
				$query = "SELECT * FROM dat_category WHERE id > 10000 AND id < 20000 ORDER BY text";
//				break;
//			case "en":
//				$query = "SELECT * FROM dat_category WHERE id > 20000 AND id < 30000 ORDER BY text";
//				break;
//		}
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			if (isset($_GET['mobile'])) {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]&mobile=1\">$row[1]</a></td></tr>\n";
			}
			else {
				echo "<tr><td class=\"zcells\" colspan=\"2\"><a href=\"./showtext.php?mode=$row[0]\">$row[1]</a></td></tr>\n";
			}
		}
	}
}

// Displays text entries to beverages, menus and howto's
function showText($kategorie, $art) {
	initDB();
	echo "<div class=\"rdisplay\">\n";
	$query = "SELECT * FROM dat_texte WHERE id = $kategorie";
	$result = mysql_query($query);
	$chapter = intval(mysql_result($result,0,"chapter"));
	$text = mysql_result($result,0,"text");
	$image = mysql_result($result,0,"image");
	$position = mysql_result($result,0,"position");
	echo interpretText($text, $image, $position, $kategorie);
	echo "<div style=\"clear:both;text-align:center;\"></div><br>\n";
	echo "<div class=\"footer\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
	echo "</div\n";
}

// HTML should not be allowed in database entries for security reasons, so this would translate pseudo tags
// and filter out any real HTML tags
function interpretText($text, $image, $position, $id) {
	$vergleich = "";
	$lfflag = true;
	for ($i; $i <= strlen($text); $i++) {
		switch (substr($text, $i, 1)) {
			case "<":
				$vergleich = "<h1>$sys_htmerr</h1>";
				break;
			case "[":
				if (substr($text, $i, 3) == "[B]") {
					$vergleich = $vergleich . "<b>";
					$i = $i + 2;
				}
				if (substr($text, $i, 4) == "[/B]") {
					$vergleich = $vergleich . "</b>";
					$i = $i + 3;
				}
				if (substr($text, $i, 3) == "[H]") {
					$vergleich = $vergleich . "<h3>";
					$i = $i + 2;
				}
				if (substr($text, $i, 4) == "[/H]") {
					$vergleich = $vergleich . "</h3>";
					$lfflag = false;
					$i = $i + 3;
				}
				if (substr($text, $i, 3) == "[I]") {
					if ($image == "slideshow") {
						$x_query = "SELECT * FROM dat_slides WHERE recipe = $id ORDER BY id";
						$x_result = mysql_query($x_query);
						$j = 0;
						while ($row = mysql_fetch_row($x_result)) {
							if ($j == 0) {
								if ($position == "l") {
									$vergleich = $vergleich . "<div class=\"image\" style=\"float:left;margin-left:0px;margin-right:10px;\"><a href=\"images/$row[1]\" rel=\"shadowbox[peng]\" title=\"$row[2]\"><img src=\"images/$row[1]\" width=\"445\"><br>Slideshow</a></div>\n";
								}
								else {
									$vergleich = $vergleich . "<div class=\"image\"><a href=\"images/$row[1]\" rel=\"shadowbox[peng]\" title=\"$row[2]\"><img src=\"images/$row[1]\" width=\"445\"><br>Slideshow</a></div>\n";
								}
								$j++;
							}
							else {
								$vergleich = $vergleich . "<div class=\"image\"><a href=\"images/$row[1]\" rel=\"shadowbox[peng]\" title=\"$row[2]\"><img src=\"images/$row[1]\" width=\"0\"></a></div>\n";
							}
						}
					}
					else {
						if ($position == "l") {
							$vergleich = $vergleich . "<div class=\"image\" style=\"float:left;margin-left:0px;margin-right:10px;\"><a href=\"images/$image\" rel=\"shadowbox\" title=\"$titel\"><img src=\"images/$image\" width=\"445\"></a></div>";
						}
						else {
							$vergleich = $vergleich . "<div class=\"image\"><a href=\"images/$image\" rel=\"shadowbox\" title=\"$titel\"><img src=\"images/$image\" width=\"445\"></a></div>";
						}
					}
					$i = $i + 2;
				}
				if (substr($text, $i, 3) == "[L]") {
					$link = "";
					$i = $i + 3;
					while (substr($text, $i, 4) != "[/L]") {
						$link = $link . substr($text, $i, 1);
						$i++;
					}
					$murks = explode(chr(34), $link);
					if (isset($_GET['mobile'])) {
						$link = "<a href=\"$murks[1]&mobile=1\">$murks[2]</a>";
					}
					else {
						$link = "<a href=\"$murks[1]\">$murks[2]</a>";
					}
					$vergleich = $vergleich . $link;
					$i = $i + 4;
				}
				if (substr($text, $i, 4) == "[UL]") {
					$vergleich = $vergleich . "<ul>";
					$i = $i + 3;
				}
				if (substr($text, $i, 5) == "[/UL]") {
					$lfflag = false;
					$vergleich = $vergleich . "</ul>";
					$i = $i + 4;
				}
				if (substr($text, $i, 4) == "[LI]") {
					$vergleich = $vergleich . "<li>";
					$i = $i + 3;
				}
				if (substr($text, $i, 5) == "[/LI]") {
					$vergleich = $vergleich . "</li>";
					$lfflag = false;
					$i = $i + 4;
				}
				break;
			case chr(10):
				$vergleich = $vergleich . "<br>";
				$lfflag = true;
				break;
			default:
				$vergleich = $vergleich . substr($text, $i, 1);
				break;
		}
	}
	$text = $vergleich;
	return ($text);
}

// This function controls the date difference between now and the last modify date.
// In case it is less than 14 days, the red new flag will appear.
function datDifference($zeit) {
	$zeitx = explode("-", $zeit);
	$zeity = mktime(0, 0, 0, $zeitx[1], $zeitx[2], $zeitx[0]);
	$datum = getDay($zeity);
	$refda = getDay(time());
	$vergleich = $refda - $datum;
	return ($vergleich);
}

// Gets the day of a date
function getDay($datetime) {
	$seconds = $datetime % 60;
	$minutes = (($seconds - $seconds) / 60) % 60;
	$datum = floor( ((((($datetime - $seconds) /60) - $minutes) / 60) / 24) );
	return $datum; 
}

// Settings for local time scemes. Feel free to change this for your country.
function setDate($zeit) {
	$datum = substr($zeit,8,2).".".substr($zeit,5,2).".".substr($zeit,0,4);
	return $datum;
}

// Displays the main menu. Parameters are the left and top position of the menu.
function showMenu($x, $y) {
	include("language.php");
	$styletext = "position:absolute;top:".$y."px;left:".$x."px;";
	echo "<table cellPadding=\"0\" cellSpacing=\"0\" style=\"$styletext\">\n";
	echo "<tr>\n";
	echo "<td valign=\"top\">\n";
	echo "<div class=\"bereich\" onMouseover=\"anzeigen('menue#1')\" onMouseout=\"anzeigen('menue#1')\">\n";
	if (isset($_GET['mobile'])) {
		echo "$sys_recipe\n";
		echo "<span id=\"menue#1\" style=\"display:none;\">\n";
		echo "<a href=\"./?mode=101&mobile=1\" class=\"link\">$sys_appetizer</a>\n";
		echo "<a href=\"./?mode=102&mobile=1\" class=\"link\">$sys_soup</a>\n";
		echo "<a href=\"./?mode=103&mobile=1\" class=\"link\">$sys_salad</a>\n";
		echo "<a href=\"./?mode=104&mobile=1\" class=\"link\">$sys_deli</a>\n";
		echo "<a href=\"./?mode=105&mobile=1\" class=\"link\">$sys_fish</a>\n";
		echo "<a href=\"./?mode=106&mobile=1\" class=\"link\">$sys_meat->$sys_poultry</a>\n";
		echo "<a href=\"./?mode=107&mobile=1\" class=\"link\">$sys_meat->$sys_minced</a>\n";
		echo "<a href=\"./?mode=108&mobile=1\" class=\"link\">$sys_meat->$sys_innereien</a>\n";
		echo "<a href=\"./?mode=109&mobile=1\" class=\"link\">$sys_meat->$sys_calf</a>\n";
		echo "<a href=\"./?mode=110&mobile=1\" class=\"link\">$sys_meat->$sys_lamb</a>\n";
		echo "<a href=\"./?mode=111&mobile=1\" class=\"link\">$sys_meat->$sys_beef</a>\n";
		echo "<a href=\"./?mode=112&mobile=1\" class=\"link\">$sys_meat->$sys_pork</a>\n";
		echo "<a href=\"./?mode=113&mobile=1\" class=\"link\">$sys_meat->$sys_game</a>\n";
		echo "<a href=\"./?mode=114&mobile=1\" class=\"link\">$sys_meat->$sys_wurst</a>\n";
		echo "<a href=\"./?mode=115&mobile=1\" class=\"link\">$sys_egg</a>\n";
		echo "<a href=\"./?mode=116&mobile=1\" class=\"link\">$sys_potato</a>\n";
		echo "<a href=\"./?mode=117&mobile=1\" class=\"link\">$sys_noodle</a>\n";
		echo "<a href=\"./?mode=118&mobile=1\" class=\"link\">$sys_rice</a>\n";
		echo "<a href=\"./?mode=119&mobile=1\" class=\"link\">$sys_conserve</a>\n";
		echo "<a href=\"./?mode=120&mobile=1\" class=\"link\">$sys_casserole</a>\n";
		echo "<a href=\"./?mode=121&mobile=1\" class=\"link\">$sys_sauces</a>\n";
		echo "<a href=\"./?mode=122&mobile=1\" class=\"link\">$sys_dessert</a>\n";
		echo "<a href=\"./?mode=123&mobile=1\" class=\"link\">$sys_baking</a>\n";
		echo "<a href=\"./?mode=124&mobile=1\" class=\"link\">$sys_others</a>\n";
		echo "<a href=\"\" class=\"link\">&nbsp;</a>\n";
		echo "<a href=\"./?mode=200&mobile=1\" class=\"link\">$sys_region</a>\n";
		echo "<a href=\"./?mode=300&mobile=1\" class=\"link\">$sys_international</a>\n";
	}
	else {
		echo "$sys_recipe\n";
		echo "<span id=\"menue#1\" style=\"display:none;\">\n";
		echo "<a href=\"./?mode=101\" class=\"link\">$sys_appetizer</a>\n";
		echo "<a href=\"./?mode=102\" class=\"link\">$sys_soup</a>\n";
		echo "<a href=\"./?mode=103\" class=\"link\">$sys_salad</a>\n";
		echo "<a href=\"./?mode=104\" class=\"link\">$sys_deli</a>\n";
		echo "<a href=\"./?mode=105\" class=\"link\">$sys_fish</a>\n";
		echo "<a href=\"./?mode=106\" class=\"link\">$sys_meat->$sys_poultry</a>\n";
		echo "<a href=\"./?mode=107\" class=\"link\">$sys_meat->$sys_minced</a>\n";
		echo "<a href=\"./?mode=108\" class=\"link\">$sys_meat->$sys_innereien</a>\n";
		echo "<a href=\"./?mode=109\" class=\"link\">$sys_meat->$sys_calf</a>\n";
		echo "<a href=\"./?mode=110\" class=\"link\">$sys_meat->$sys_lamb</a>\n";
		echo "<a href=\"./?mode=111\" class=\"link\">$sys_meat->$sys_beef</a>\n";
		echo "<a href=\"./?mode=112\" class=\"link\">$sys_meat->$sys_pork</a>\n";
		echo "<a href=\"./?mode=113\" class=\"link\">$sys_meat->$sys_game</a>\n";
		echo "<a href=\"./?mode=114\" class=\"link\">$sys_meat->$sys_wurst</a>\n";
		echo "<a href=\"./?mode=115\" class=\"link\">$sys_egg</a>\n";
		echo "<a href=\"./?mode=116\" class=\"link\">$sys_potato</a>\n";
		echo "<a href=\"./?mode=117\" class=\"link\">$sys_noodle</a>\n";
		echo "<a href=\"./?mode=118\" class=\"link\">$sys_rice</a>\n";
		echo "<a href=\"./?mode=119\" class=\"link\">$sys_conserve</a>\n";
		echo "<a href=\"./?mode=120\" class=\"link\">$sys_casserole</a>\n";
		echo "<a href=\"./?mode=121\" class=\"link\">$sys_sauces</a>\n";
		echo "<a href=\"./?mode=122\" class=\"link\">$sys_dessert</a>\n";
		echo "<a href=\"./?mode=123\" class=\"link\">$sys_baking</a>\n";
		echo "<a href=\"./?mode=124\" class=\"link\">$sys_others</a>\n";
		echo "<a href=\"\" class=\"link\">&nbsp;</a>\n";
		echo "<a href=\"./?mode=200\" class=\"link\">$sys_region</a>\n";
		echo "<a href=\"./?mode=300\" class=\"link\">$sys_international</a>\n";
	}
	echo "</span>\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "<td valign=\"top\">\n";
	echo "<div class=\"bereich\">\n";
	if (isset($_GET['mobile'])) {
		echo "<a href=\"./?mode=2&mobile=1\" class=\"link\">$sys_menu</a>\n";
	}
	else {
		echo "<a href=\"./?mode=2\" class=\"link\">$sys_menu</a>\n";
	}
	echo "</div>\n";
	echo "</td>\n";
	echo "<td valign=top>\n";
	echo "<div class=\"bereich\">\n";
	if (isset($_GET['mobile'])) {
		echo "<a href=\"./?mode=600&mobile=1\" class=\"link\">$sys_bev</a>\n";
	}
	else {
		echo "<a href=\"./?mode=600\" class=\"link\">$sys_bev</a>\n";
	}
	echo "</div>\n";
	echo "</td>\n";
	echo "<td valign=\"top\">\n";
	echo "<div class=\"bereich\">\n";
	if (isset($_GET['mobile'])) {
		echo "<a href=\"./?mode=700&mobile=1\" class=\"link\">$sys_ingred</a>\n";
	}
	else {
		echo "<a href=\"./?mode=700\" class=\"link\">$sys_ingred</a>\n";
	}
	echo "</div>\n";
	echo "</td>\n";
	echo "<td valign=\"top\">\n";
	echo "<div class=\"bereich\">\n";
	if (isset($_GET['mobile'])) {
		echo "<a href=\"./?mode=800&mobile=1\" class=\"link\">$sys_prod</a>\n";
	}
	else {
		echo "<a href=\"./?mode=800\" class=\"link\">$sys_prod</a>\n";
	}
	echo "</div>\n";
	echo "</td>\n";
	echo "<td valign=\"top\">\n";
	echo "<div class=\"bereich\" onMouseover=\"anzeigen('menue#2')\" onMouseout=\"anzeigen('menue#2')\">\n";
	echo "$sys_system\n";
	echo "<span id=\"menue#2\" style=\"display:none;\">\n";
	if (isset($_GET['mobile'])) {
		echo "<a href=\"./?mode=7&mobile=1\" class=\"link\">$sys_options</a>\n";
		echo "<a href=\"./?mode=6&mobile=1\" class=\"link\">$sys_forum</a>\n";
		echo "<a href=\"http://www.gabel-it.com/index.php/impressum\" target=\"_blank\" class=\"link\">$sys_imprint</a>\n";
		echo "<a href=\"https://sourceforge.net/projects/webcookbook/\" target=\"_blank\" class=\"link\">$sys_download</a>\n";
		echo "<a href=\"./?mobile=1&mobile=1\" class=\"link\">$sys_mobile</a>\n";
	}
	else {
		echo "<a href=\"./?mode=7\" class=\"link\">$sys_options</a>\n";
		echo "<a href=\"./?mode=6\" class=\"link\">$sys_forum</a>\n";
		echo "<a href=\"http://www.gabel-it.com/index.php/impressum\" target=\"_blank\" class=\"link\">$sys_imprint</a>\n";
		echo "<a href=\"https://sourceforge.net/projects/webcookbook/\" target=\"_blank\" class=\"link\">$sys_download</a>\n";
		echo "<a href=\"./?mobile=1\" class=\"link\">$sys_mobile</a>\n";
	}
	echo "<a href=\"./\" class=\"link\">$sys_start</a>\n";
	if (file_exists('./admin/index.php')) {
		echo "<a href=\"./admin/peng.php\" class=\"link\">Administration</a>\n";
	}
	echo "</span>\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "<td valign=\"top\">\n";
	echo "<div class=\"bereich\">\n";
	if (isset($_GET['mobile'])) {
		echo "<a href=\"./?mode=8&mobile=1\" class=\"link\">$sys_help</a>\n";
	}
	else {
		echo "<a href=\"./?mode=8\" class=\"link\">$sys_help</a>\n";
	}
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

// Displays the search entry. Use this like a module in a CSS like Joomla. Parameters are
// the left and top position of the module.
function showSearch($x, $y) {
	include("language.php");
	$stylestring = "left:".$x."px;top:".$y."px;";
	echo "<div class=\"suchen\" style=\"$stylestring\">\n";
	echo "<form name=\"Suchen\" action=\"searchrecipe.php\" method=\"get\">\n";
	if (isset($_GET['mobile'])) {
		echo "<input type=\"hidden\" name=\"mobile\" value=\"1\">\n";
	}
	echo "<input type=\"text\" name=\"sstring\">\n";
	echo "<input type=\"submit\" value=\"$sys_search\">\n";
	echo "</form>\n";
	if (isset($_GET['mobile'])) {
		echo "<br><span style=\"font-family:sans-serif;font-size:10px;\"><a href=\"searchmask.php?mobile=1\">$sys_searchenhanced</a></span>\n";
	}
	else {
		echo "<br><span style=\"font-family:sans-serif;font-size:10px;\"><a href=\"searchmask.php\">$sys_searchenhanced</a></span>\n";
	}
	echo "</div>\n";
}

// Implements search functionality of the application.
function searchRecipe($mode, $sstring) {
	initDB();
	switch ($mode) {
		case 0: // Not special searches inside the title and the ingredients.
			$query = "SELECT DISTINCT a.id, a.title FROM das_rezept a, dat_ingredient b WHERE a.title LIKE '%$sstring%' OR b.description LIKE '%$sstring%' AND a.id = b.recipe ORDER BY a.title";
			echo "<br><br>Suchergebnisse nach <b>$sstring</b> in Titel und Zutaten<br><br>\n";
			break;
		case 1: // Search inside the recipe table.
			$query = "SELECT DISTINCT a.id, a.title FROM das_rezept a, dat_ingredient b WHERE " . $sstring . "ORDER BY a.title";
			echo "<br><br>Suchergebnisse nach <b>$sstring</b><br>\n";
			break;
		case 2: // Search the recipe table and the incredient table.
			$query = "SELECT DISTINCT a.id, a.title FROM das_rezept a, dat_ingredient b WHERE " . $sstring . "AND a.id = b.recipe ORDER BY a.title";
			echo "<br><br>Suchergebnisse nach <b>$sstring</b><br>\n";
			break;
	}
	$result = mysql_query($query);
	while ($row = mysql_fetch_row($result)) {
		if (isset($_GET['mobile'])) {
			echo "<a href=\".//rezeptanzeige.php?currid=$row[0]&category=0&mobile=1\">$row[1]</a><br>\n";
		}
		else {
			echo "<a href=\".//rezeptanzeige.php?currid=$row[0]&category=0\">$row[1]</a><br>\n";
		}
	}
}

// Administration of the application
function showAdministration($mode) {
	include("language.php");
	InitDB();
	echo "<div class=\"rdisplay\">\n";
	echo "<div class=\"admin\">\n";
	switch ($mode) {
		case 0: // Shows the admin menu
			echo "<h3 align=\"center\">$sys_settings</h3>\n";
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\" align=\"center\" width=\"80%\">\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" align=\"center\" width=\"33%\"><a href=\"./peng2.php\">$sys_insert</a></td>\n";
			echo "<td class=\"zcells\" align=\"center\" width=\"33%\"><a href=\"./peng5.php\">$sys_edit</a></td>\n";
			echo "<td class=\"zcells\" align=\"center\" width=\"33%\"><a href=\"./peng6.php\">$sys_adm_delete</a></td>\n";
			echo "</tr><tr>\n";
			echo "<td class=\"zcells\" align=\"center\" width=\"33%\"><a href=\"./peng1.php\">$sys_rubric</a></td>\n";
			echo "<td class=\"zcells\" align=\"center\" width=\"33%\"><a href=\"./peng3.php\">$sys_adminrubric</a></td>\n";
			echo "<td class=\"zcells\" align=\"center\" width=\"33%\"><a href=\"./peng4.php\">$sys_import</a></td>\n";
			echo "</tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
			echo "<tr><td class=\"zcells\" align=\"center\" width=\"33%\"><a href=\"../\">$sys_start</a></td></tr>\n";
			echo "</table>\n";
			break;
		case 1: // Manage recipe sections
			echo "<h3 align=\"center\">$sys_rubric</h3>\n";
			echo "<form name=\"managesection\" method=\"post\" action=\"./\">\n";
			echo "<input type=\"hidden\" name=\"peng\" value=\"peng\">\n";
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n";
			$result = mysql_query("SELECT MAX(id) FROM dat_category WHERE id >= 100 AND id < 200");
			$row = mysql_fetch_row($result);
			$idcat = $row[0];
			$idcat = $idcat + 1;
			$result = mysql_query("SELECT MAX(id) FROM dat_category WHERE id >= 200 AND id < 300");
			$row = mysql_fetch_row($result);
			$idregio = $row[0];
			$idregio = $idregio + 1;
			$result = mysql_query("SELECT MAX(id) FROM dat_category WHERE id >= 300 AND id < 400");
			$row = mysql_fetch_row($result);
			$idint = $row[0];
			$idint = $idint + 1;
			echo "<input type=\"hidden\" name=\"idcat\" value=\"$idcat\">\n";
			echo "<input type=\"hidden\" name=\"idregio\" value=\"$idregio\">\n";
			echo "<input type=\"hidden\" name=\"idint\" value=\"$idint\">\n";
			echo "<input type=\"hidden\" name=\"mode\" value=\"11\">\n";
			echo "<tr><td class=\"zcells\"><select name=\"sectiontype\">\n";
			echo "<option>$sys_menutype</option>\n";
			echo "<option>$sys_region</option>\n";
			echo "<option>$sys_international2</option>\n";
			echo "</select></td>\n";
			echo "<td class=\"zcells\">&nbsp;&nbsp;$sys_rubriktext</td>\n";
			echo "<td class=\"zcells\"><input type=\"text\" name=\"rubriktext\"></td></tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"3\" align=\"center\">&nbsp;</td></tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"3\" align=\"center\">\n";
			echo "<input type=\"submit\" value=\"$sys_save\">&nbsp;\n";
			echo "<input type=\"reset\" value=\"$sys_reset\">\n";
			echo "</td></tr>\n";
			echo "</table>\n";
			break;
		case 2: // Insert single recipes
			echo "<h3 align=\"center\">$sys_insert</h3>\n";
			echo "<form name=\"Neueingabe\" method=\"post\" action=\"./\">\n";
			echo "<input type=\"hidden\" name=\"peng\" value=\"peng\">\n";
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\">\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" align=\"right\">$sys_mtitle</td><td class=\"zcells\" colspan=\"3\"><input type=\"text\" name=\"title\" size=\"80\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" align=\"right\">$sys_mrubrik</td><td class=\"zcells\"><select name=\"section\">\n";
			$result = mysql_query("SELECT * FROM dat_category WHERE id < 200 ORDER BY id");
			while ($row = mysql_fetch_row($result)) {
				echo "<option>$row[1]</option>\n";
			}
			echo "</select></td>\n";
			echo "<td class=\"zcells\" align=\"right\">$sys_portions:</td><td class=\"zcells\"><input type = \"text\" name=\"portionen\">\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_ingred:</td><td class=\"zcells\" valign=\"top\"><textarea name=\"zutaten\" cols=\"50\" rows=\"6\"></textarea>\n";
			echo "<td class=\"zcells\" valign=\"top\" colspan=\"2\">$sys_ingedformat</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_prefix</td><td class=\"zcells\"><textarea name=\"prefix\" cols=\"50\" rows=\"6\"></textarea>\n";
			echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_preparation</td><td class=\"zcells\"><textarea name=\"preparation\" cols=\"50\" rows=\"6\"></textarea>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_postfix</td><td class=\"zcells\"><textarea name=\"postfix\" cols=\"50\" rows=\"6\"></textarea>\n";
			echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_tip</td><td class=\"zcells\"><textarea name=\"tipp\" cols=\"50\" rows=\"6\"></textarea>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			$zeit = date('Y-m-d');
			echo "<input type=\"hidden\" name=\"datum\" value=\"$zeit\">\n";
			echo "<input type=\"hidden\" name=\"mode\" value=\"12\">\n";
			echo "<td class=\"zcells\" align=\"right\">$sys_wt</td><td class=\"zcells\"><input type=\"text\" name=\"arbeitszeit\">\n";
			echo "<td class=\"zcells\" align=\"right\">$sys_calories</td><td class=\"zcells\"><input type=\"text\" name=\"calories\">\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" align=\"right\">$sys_image</td><td class=\"zcells\" colspan=\"3\"><input type=\"text\" name=\"image\" size=\"80\"></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" align=\"center\" colspan=\"4\">\n";
			echo "<input type=\"submit\" value=\"$sys_save\">&nbsp;\n";
			echo "<input type=\"reset\" value=\"$sys_reset\">\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</form>\n";
			break;
		case 3: // Sort recipes in additional sections
			echo "<h3 align=\"center\">$sys_adminrubric</h3>\n";
			echo "<form name=\"sortsections\" method=\"post\" action=\"./\">\n";
			echo "<input type=\"hidden\" name=\"peng\" value=\"peng\">\n";
			echo "<input type=\"hidden\" name=\"mode\" value=\"13\">\n";
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\" align=\"center\">\n";
			echo "<tr><td class=\"zcells\" colspan=\"4\">$sys_sortrecipe</td></tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\">$sys_mtitle</td>\n";
			echo "<td class=\"zcells\"><input type=\"text\" name=\"titel\" size=\50\"></td>\n";
			echo "<td class=\"zcells\" align=\"right\">$sys_mrubrik</td><td class=\"zcells\"><select name=\"section\">\n";
			$result = mysql_query("SELECT * FROM dat_category WHERE id < 400 ORDER BY id");
			while ($row = mysql_fetch_row($result)) {
				echo "<option>$row[1]</option>\n";
			}
			echo "</select></td>\n";
			echo "</tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"4\" align=\"center\">&nbsp;</td></tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"4\" align=\"center\">\n";
			echo "<input type=\"submit\" value=\"$sys_save\">&nbsp;\n";
			echo "<input type=\"reset\" value=\"$sys_reset\">\n";
			echo "</td></tr>\n";
			echo "</table>\n";
			echo "</form>\n";
			break;
// Import/export several recipes. Format is my own first. A description is in the format file description "format.txt".
// Additional import formats like Mealmaster or Rezkonv will be implemented later.
		case 4:
			echo "<h3 align=\"center\">$sys_import</h3>\n";
// For file uploads only the post method works
			echo "<form name=\"importfile\" method=\"post\" action=\"./\" enctype=\"multipart/form-data\">\n";
			echo "<input type=\"hidden\" name=\"mode\" value=\"14\">\n";
			echo "<input type=\"hidden\" name=\"peng\" value=\"peng\">\n";
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\" align=\"center\"></td>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\">$sys_filetype</td>\n";
			echo "<td class=\"zcells\"><select name=\"fileformat\">\n";
			echo "<option>$sys_myown</option>\n";
			echo "<option>$sys_mealmaster</option>\n";
			echo "<option>$sys_rezkonv</option>\n";
			echo "</select></td>\n";
			echo "<td class=\"zcells\"><input type=\"file\" name=\"filename\" size=\"30\"></td>\n";
			echo "</tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"3\" align=\"center\">&nbsp;</td></tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" colspan=\"3\" align=\"center\"><input type=\"radio\" name=\"ieflag\" value=\"import\" checked>Import&nbsp;&nbsp;&nbsp;\n";
			echo "<input type=\"radio\" name=\"ieflag\" value=\"export\">Export</td>\n";
			echo "</tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"3\" align=\"center\">&nbsp;</td></tr>\n";
			echo "<tr><td class=\"zcells\" colspan=\"3\" align=\"center\">\n";
			echo "<input type=\"submit\" value=\"$sys_save\">&nbsp;\n";
			echo "<input type=\"reset\" value=\"$sys_reset\">\n";
			echo "</td></tr>\n";
			echo "</table>\n";
			echo "</form>\n";
			break;
		case 5: // Edit recipes
			echo "<h3 align=\"center\">$sys_edit</h3>\n";
			echo "<form name=\"Neueingabe\" method=\"post\" action=\"./\">\n";
			echo "<input type=\"hidden\" name=\"peng\" value=\"peng\">\n";
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\" align=\"center\">\n";
			echo "<tr><td class=\"zcells\" colspan=\"2\">$sys_edit_recipe</td></tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" align=\"center\">$sys_mtitle</td><td class=\"zcells\"><input type=\"text\" name=\"title\" size=\"75\">\n";
			echo "<input type=\"submit\" name=\"msearch\" value=\"$sys_search\"></td>\n";
			echo "</tr>\n";
			echo "<input type=\"hidden\" name=\"mode\" value=\"15\">\n";
			echo "</table>\n";
			echo "</form>\n";
			break;
		case 6: //Delete recipes
			echo "<h3 align=\"center\">$sys_adm_delete</h3>\n";
			echo "<form name=\"Loeschen\" method=\"post\" action=\"./\">\n";
			echo "<input type=\"hidden\" name=\"peng\" value=\"peng\">\n";
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\" align=\"center\">\n";
			echo "<tr><td class=\"zcells\" colspan=\"2\">$sys_edit_recipe</td></tr>\n";
			echo "<tr>\n";
			echo "<td class=\"zcells\" align=\"center\">$sys_mtitle</td><td class=\"zcells\"><input type=\"text\" name=\"title\" size=\"75\">\n";
			echo "<input type=\"submit\" name=\"msearch\" value=\"$sys_search\"></td>\n";
			echo "</tr>\n";
			echo "<input type=\"hidden\" name=\"mode\" value=\"16\">\n";
			echo "</table>\n";
			echo "</form>\n";
			break;
	}
	echo "</div>\n";
	echo "</div>\n";
}

// Accepts denominator and divisor of a fraction and returns a floating-point value
function getAmount($nenner, $teiler) {
	switch ($teiler) {
		case 2:
			switch ($nenner) {
				case 1:
					$ing_amount = 0.5;
					break;
				default:
					$ing_amount = 0;
					break;
			}
			break;
		case 3:
			switch ($nenner) {
				case 1:
					$ing_amount = 0.33;
					break;
				case 2:
					$ing_amount = 0.66;
					break;
			}
			break;
		case 4:
			switch ($nenner) {
				case 1:
					$ing_amount = 0.25;
					break;
				case 3:
					$ing_amount = 0.75;
					break;
				default:
					$ing_amount = 0;
					break;
			}
			break;
		case 8:
			switch ($nenner) {
				case 1:
					$ing_amount = 0.125;
					break;
				case 3:
					$ing_amount = 0.375;
					break;
				default:
					$ing_amount = 0;
					break;
			}
			break;
	}
	return($ing_amount);

}

function saveIngredients($zutaten, $id) {
// Split the ingredients into their single values
	$ingredientlines = explode(chr(10), $zutaten);
	$i = 0;
	while ($ingredientlines[$i] != "") {
		$ingredients = explode("|", $ingredientlines[$i]);
		if ($ingredients[0] != "") {
			$query = "INSERT INTO dat_ingredient (amount, value, description, recipe) VALUES ($ingredients[0]";
		}
		else {
			$query = "INSERT INTO dat_ingredient (amount, value, description, recipe) VALUES (NULL";
		}
		if ($ingredients[1] != "") {
			$query = $query . ", '$ingredients[1]'";
		}
		else {
			$query = $query . ", NULL";
		}
		if ($ingredients[2] != "") {
			$query = $query . ", '$ingredients[2]'";
		}
		else {
			$query = $query . ", NULL";
		}
		$query = $query . ", $id)";
		$result = mysql_query($query) or die('Error in saveIngredients: ' . mysql_error() . ' ' . $query);
		$i++;
	}
}

function saveWCBFile($aus, $id, $title, $serving, $prefix, $preparation, $postfix, $tipp, $arbeitszeit, $image) {
	fputs($aus, "========== Gabel Rezept ==========\n");
	fputs($aus, "\n");
	fputs($aus, "Titel:$title\n");
	$c_query = "SELECT category FROM dat_rezepttocat WHERE recipe = " . $id . " ORDER BY id";
	$c_result = mysql_query($c_query) or die(mysql_error());
	$category = mysql_result($c_result, 0, "category");
	fputs($aus, "Kategorie:$category\n");
	fputs($aus, "Portion:$serving\n");
	fputs($aus, "\n");
	if ($prefix != "") {
		fputs($aus, "Prefix:\n");
		fputs($aus, "$prefix\n");
		fputs($aus, "Prefix!\n");
		fputs($aus, "\n");
	}
	fputs($aus, "Zutaten:\n");
	$i_query = "SELECT * FROM dat_ingredient WHERE recipe = " .$id . " ORDER BY id";
	$i_result = mysql_query($i_query) or die (mysql_error());
	while ($irow = mysql_fetch_row($i_result)) {
		fputs($aus, "$irow[1]|$irow[2]|$irow[3]\n");
	}
	fputs($aus, "Zutaten!\n");
	fputs($aus, "\n");
	fputs($aus, "Zubereitung:\n");
	fputs($aus, "$preparation\n");
	fputs($aus, "Zubereitung!\n");
	fputs($aus, "\n");
	if ($postfix != "") {
		fputs($aus, "Postfix:\n");
		fputs($aus, "$postfix\n");
		fputs($aus, "Postfix!\n");
		fputs($aus, "\n");
	}
	if ($tipp != "") {
		fputs($aus, "Tipp:\n");
		fputs($aus, "$tipp\n");
		fputs($aus, "Tipp!\n");
		fputs($aus, "\n");
	}
	if ($arbeitszeit != "" && $row[7] > 0) {
		fputs($aus, "Arbeitszeit: $arbeitszeit\n");
	}
	if ($image != "") {
		fputs($aus, "Image: $image\n");
	}
	fputs($aus, "\n");
	fputs($aus, "=====\n");
}

function saveMMFile($aus, $id, $title, $serving, $prefix, $preparation, $postfix, $tipp, $arbeitszeit, $image) {
	fputs($aus, "---------- Recipe via Meal-Master - Gabel Webcookbook\n");
	fputs($aus, "\n");
	fputs($aus, "      Title: $title\n");
	fputs($aus, " Categories: None\n");
	fputs($aus, "      Yield: $serving Servings\n");
	fputs($aus, "\n");
	$i_query = "SELECT * FROM dat_ingredient WHERE recipe = " .$id . " ORDER BY id";
	$i_result = mysql_query($i_query) or die (mysql_error());
	while ($irow = mysql_fetch_row($i_result)) {
		$ing_amount = $irow[1];
		switch (strlen($ing_amount)) {
			case 0:
				$ing_amount = "       ";
				break;
			case 1:
				$ing_amount = "      " . $ing_amount;
				break;
			case 2:
				$ing_amount = "     " . $ing_amount;
				break;
			case 3:
				$ing_amount = "    " . $ing_amount;
				break;
			case 4:
				$ing_amount = "   " . $ing_amount;
				break;
			case 5:
				$ing_amount = "  " . $ing_amount;
				break;
			case 6:
				$ing_amount = " " . $ing_amount;
				break;
		}
		$ing_value = substr($irow[2],0,2);
		switch (strlen($ing_value)) {
			case 0:
				$ing_value = "  ";
				break;
			case 1:
				$ing_value = " " . $ing_value;
				break;
		}
		fputs($aus, "$ing_amount $ing_value $irow[3]\n");
	}
	fputs($aus, "\n");
	fputs($aus, "$preparation\n");
	fputs($aus, "\n");
	if ($prefix != "") {
		fputs($aus, "$prefix\n");
		fputs($aus, "\n");
	}
	if ($postfix != "") {
		fputs($aus, "$postfix\n");
		fputs($aus, "\n");
	}
	if ($tipp != "") {
		fputs($aus, "$tipp\n");
		fputs($aus, "\n");
	}
	if ($arbeitszeit != "" && $arbeitszeit > 0) {
		fputs($aus, "Working time: $arbeitszeit\n");
	}
	if ($image != "") {
		fputs($aus, "Image: $image\n");
	}
	fputs($aus, "\n");
	fputs($aus, "-----\n");
}

function saveRKFile($aus, $id, $title, $serving, $prefix, $preparation, $postfix, $tipp, $arbeitszeit, $image) {
	fputs($aus, "========== RezkonvSuite Rezept = Webcookbook ==\n");
	fputs($aus, "\n");
	fputs($aus, "     Titel: $title\n");
	fputs($aus, "Kategorien: None\n");
	fputs($aus, "     Menge: $serving Portionen\n");
	fputs($aus, "\n");
	fputs($aus, "Zutaten:\n");
	$i_query = "SELECT * FROM dat_ingredient WHERE recipe = " .$id . " ORDER BY id";
	$i_result = mysql_query($i_query) or die (mysql_error());
	while ($irow = mysql_fetch_row($i_result)) {
		$ing_amount = $irow[1];
		switch (strlen($ing_amount)) {
			case 0:
				$ing_amount = "       ";
				break;
			case 1:
				$ing_amount = "      " . $ing_amount;
				break;
			case 2:
				$ing_amount = "     " . $ing_amount;
				break;
			case 3:
				$ing_amount = "    " . $ing_amount;
				break;
			case 4:
				$ing_amount = "   " . $ing_amount;
				break;
			case 5:
				$ing_amount = "  " . $ing_amount;
				break;
			case 6:
				$ing_amount = " " . $ing_amount;
				break;
		}
		$ing_value = substr($irow[2], 0, 9);
		switch (strlen($ing_value)) {
			case 0:
				$ing_value = "         ";
				break;
			case 1:
				$ing_value = "        " . $ing_value;
				break;
			case 2:
				$ing_value = "       " . $ing_value;
				break;
			case 3:
				$ing_value = "      " . $ing_value;
				break;
			case 4:
				$ing_value = "     " . $ing_value;
				break;
			case 5:
				$ing_value = "    " . $ing_value;
				break;
			case 6:
				$ing_value = "   " . $ing_value;
				break;
			case 7:
				$ing_value = "  " . $ing_value;
				break;
			case 8:
				$ing_value = " " . $ing_value;
				break;
		}
		fputs($aus, "$ing_amount " . $ing_value . "  $irow[3]\n");
	}
	fputs($aus, "\n");
	fputs($aus, "Zubereitung:\n");
	fputs($aus, "$preparation\n");
	fputs($aus, "\n");
	if ($prefix != "") {
		fputs($aus, "$prefix\n");
		fputs($aus, "\n");
	}
	if ($postfix != "") {
		fputs($aus, "$postfix\n");
		fputs($aus, "\n");
	}
	if ($tipp != "") {
		fputs($aus, "$tipp\n");
		fputs($aus, "\n");
	}
	if ($arbeitszeit != "" && $arbeitszeit > 0) {
		fputs($aus, "Arbeitszeit: $arbeitszeit\n");
	}
	if ($image != "") {
		fputs($aus, "Image: $image\n");
	}
	fputs($aus, "\n");
	fputs($aus, "=====\n");
}

function showComments($id) {
	include("language.php");
	$query = "SELECT * FROM dat_comments WHERE recipe = $id";
	$result = mysql_query($query) or die(mysql_error());
	$anzahl = mysql_num_rows($result);
	if ($anzahl > 0) {
		echo "<table border=\"1\" cellpadding=\"2\" cellspacing=\"0\" width=\100%\">\n";
		while ($row = mysql_fetch_row($result)) {
			$datum = setDate($row[3]);
			echo "<tr><td class=\"zcells\"><b>$row[4] $datum</b><br>$row[2]</td></tr>\n";
		}
		echo "</table>\n";
	}
	else {
		$wert = explode("|", $_COOKIE['logged']);
		if ($wert[0] == 1) {
			echo "$sys_com_first\n";
		}
		else {
			echo "$sys_com_notfound $sys_com_logon\n";
		}
	}
}

function showCominput($id, $user) {
	include("language.php");
	echo "<form name=\"kommentarneu\" method=\"post\" action=\"savecomment.php\">\n";
	echo "<textarea name=\"kommentar\" cols=\"140\" rows=\"5\"></textarea><br>\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<input type=\"hidden\" name=\"user\" value=\"$user\">\n";
	echo "<input type=\"submit\" value=\"$sys_com_save\">\n";
	echo "</form>\n";
}

function showRanking($id) {
	initDB();
	$query = "SELECT * FROM dat_bewertung WHERE recipe = $id";
	$result = mysql_query($query);
	$anzahl = mysql_num_rows($result);
	$wert = explode("|", $_COOKIE['logged']);
	if ($anzahl == 0) {
		$rank = 0;
	}
	else {
		$rank = 0;
		while ($row = mysql_fetch_row($result)) {
			$rank = $rank + $row[3];
		}
		$rank = $rank + 100;
		$anzahl = $anzahl + 20;
		$rank = $rank / $anzahl;
	}
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n";
	echo "<tr>\n";
	if ($_COOKIE['logged'] != "") {
		if ($rank > 0) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 1) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 2) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 3) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 4) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 5) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 6) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 7) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 8) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
		if ($rank > 9) {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/goldknopf.jpg\" border=\"0\"></a></td>\n";
		}
		else {
			echo "<td><a href=\"./?mode=9&id=$id\"><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></a></td>\n";
		}
	}
	else {
		if ($rank > 0) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 1) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 2) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 3) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 4) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 5) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 6) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 7) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 8) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
		if ($rank > 9) {
			echo "<td><img src=\"images/system/goldknopf.jpg\" border=\"0\"></td>\n";
		}
		else {
			echo "<td><img src=\"images/system/stahlknopf.jpg\" border=\"0\"></td>\n";
		}
	}
	echo "</tr>\n";
	echo "</table>\n";
}

?>