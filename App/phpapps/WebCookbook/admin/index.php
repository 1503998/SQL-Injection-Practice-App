<?php
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

include("../language.php");
include("../env_db.php");
include("../env_sc.php");
InitDB();
if (!isset($_POST['peng'])) {
	die("$sys_err_admincall<br><a href=\"../\">$sys_savelinkmain</a>");
}
$wert = explode("|", $_COOKIE['logged']);
$query = "SELECT * FROM mlf2_userdata WHERE user_name = '$wert[1]'";
$result = mysql_query($query) or die(mysql_error());
$stat = mysql_result($result, 0, "user_type");
// $peng = "user: $wert[1] stat: $stat";
// echo "<script>alert( $peng )</script>\n";
if ($stat != 2) {
	die("$sys_err_admincall<br><a href=\"../\">$sys_savelinkmain</a>");
}


echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../index.css\">\n";

if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
}
else {
	if (isset($_POST['mode'])) {
		$mode = $_POST['mode'];
	}
	else {
		$mode = 0;
	}
}

echo "</head>\n";

echo "<body bgcolor=\"White\">\n";
echo "<div class=\"display\">\n";
echo "<div class=\"title\">$sys_admin</div>\n";

switch($mode) {
	case 11: //Manage recipe sections
		echo "<div class=\"rdisplay\">\n";
		if (isset($_POST['rubriktext'])) {
			$rubriktext = $_POST['rubriktext'];
			if ($rubriktext != "") {
				$query = "INSERT INTO dat_category (id, text) VALUES (";
				switch ($_POST['sectiontype']) {
					case $sys_menutype:
						$query = $query . $_POST['idcat'] . ", '";
						break;
					case $sys_region:
						$query = $query . $_POST['idregio'] . ", '";
						break;
					case $sys_international2:
						$query = $query . $_POST['idint'] . ", '";
						break;
				}
				$query = $query . $_POST['rubriktext'] . "')";
				$result = mysql_query($query);
				echo "<br><br>$sys_rubriksaved<br><br><a href=\"../\">$sys_savelinkmain</a>\n";
				if (!$result) {
				    die('Error: ' . mysql_error());
				}
			}
			else {
				echo "$sys_saverr2";
			}
		}
		else {
			echo "$sys_saverr2";
		}
		echo "<div class=\"footer\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
		echo "</div>\n";
		break;
	case 12: // Insert single recipes
		echo "<div class=\"rdisplay\">\n";
		if (isset($_POST['title'])) {
			$title = $_POST['title'];
			if ($title != "") {
				$section = $_POST['section'];
				$portion = $_POST['portionen'];
				if (intval($portion) == 0) {
					$portion = 2;
				}
				$zutaten = $_POST['zutaten'];
				$prefix = $_POST['prefix'];
				$preparation = $_POST['preparation'];
				$postfix = $_POST['postfix'];
				$tipp = $_POST['tipp'];
				$datum = $_POST['datum'];
				$arbeitszeit = $_POST['arbeitszeit'];
				$calories = $_POST['calories'];
				$image = $_POST['image'];
				InitDB();
// Select the next recipe id
				$query = "SELECT MAX(id) FROM das_rezept";
				$result = mysql_query($query);
				$row = mysql_fetch_row($result);
				$id = $row[0];
				$id = $id + 1;
// Get the id of the section from the dat_categories table
				$query = "SELECT id FROM dat_category WHERE text = '$section'";
				$result = mysql_query($query);
				$secid = mysql_result($result,0,"id");
				saveIngredients($zutaten, $id);
				$query = "INSERT INTO dat_rezepttocat (recipe, category) VALUES ($id, $secid)";
				$result = mysql_query($query);
				if (!$result) {
				    die('Error: ' . mysql_error());
				}
				$query = "INSERT INTO sys_zaehler (id, zaehler, datum) VALUES ($id, 0, '$datum')";
				$result = mysql_query($query);
				if (!$result) {
				    die('Error: ' . mysql_error());
				}
				$query = "INSERT INTO das_rezept (id, title, servings, prefix, preparation, postfix, tipp, arbeitszeit, brennwert, image) VALUES ($id, '$title', $portion, ";
				if ($prefix != "") {
					$query = $query . "'$prefix', ";
				}
				else {
					$query = $query . "NULL, ";
				}
				$query = $query . "'$preparation', ";
				if ($postfix != "") {
					$query = $query . "'$postfix', ";
				}
				else {
					$query = $query . "NULL, ";
				}
				if ($tipp != "") {
					$query = $query . "'$tipp', ";
				}
				else {
					$query = $query . "NULL, ";
				}
				if ($arbeitszeit != "") {
					$query = $query . "'$arbeitszeit', ";
				}
				else {
					$query = $query . "NULL, ";
				}
				if ($brennwert != "") {
					$query = $query . "$brennwert, ";
				}
				else {
					$query = $query . "NULL, ";
				}
				if ($image != "") {
					$query = $query . "'$image')";
				}
				else {
					$query = $query . "NULL)";
				}
				$result = mysql_query($query);
				if (!$result) {
				    die('Error: ' . mysql_error());
				}
				echo "<br><br>Datensatz $id gespeichert!";
				echo "<br><br><a href=\"../\">$sys_savelinkmain</a>&nbsp;&nbsp;<a href=\"../rezeptanzeige.php?currid=$id&category=$secid\">$sys_savelinkrecipe</a>\n";
			}
			else {
				echo "$sys_saverr";
			}
		}
		else {
			echo "$sys_saverr";
		}
		echo "<div class=\"footer\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
		echo "</div>\n";
		break;
	case 13: // Sort recipes in additional sections
		echo "<div class=\"rdisplay\">\n";
		if (isset($_POST['titel'])) {
			$title = $_POST['titel'];
			if ($title != "") {
				$query = "SELECT id FROM das_rezept WHERE title = '$title'";
				$result = mysql_query($query);
				if (!$result) {
				    die('Error: ' . mysql_error());
				}
				$id = mysql_result($result,0,"id");
				$query = "SELECT id FROM dat_category WHERE text = '".$_POST['section']."'";
				$result = mysql_query($query);
				if (!$result) {
				    die('Error: ' . mysql_error());
				}
				$category = mysql_result($result,0,"id");
				$query = "INSERT INTO dat_rezepttocat (recipe, category) VALUES ($id, $category)";
				$result = mysql_query($query);
				if (!$result) {
				    die('Error: ' . mysql_error());
				}
				echo "<br><br>$sys_rubriksaved<br><br><a href=\"../\">$sys_savelinkmain</a>\n";
			}
		}
		echo "<div class=\"footer\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
		echo "</div>\n";
		break;
	case 14: // Import files with several recipes
		if ($_POST['ieflag'] == "import") { // Import of recipes
			echo "<div class=\"rdisplay\">\n";
// Uploads the file into the /upload subdirectory und rename it to rezepte.txt
			$mode = $_POST['mode'];
			$fileformat = $_POST['fileformat'];
			move_uploaded_file($_FILES['filename']['tmp_name'], "../upload/rezepte.txt");
// Get the charcterset of the file
			$peng = shell_exec("file ../upload/rezepte.txt");
			$zeichensatz = 0;
			$pos = strpos($peng, "UTF-8");
			if ($pos !== false) {
				$zeichensatz = 1;
			}
			else {
				$pos = strpos($peng, "ISO-8859");
				if ($pos !== false) {
					$zeichensatz = 2;
				}
				else {
					$pos = strpos($peng, "ASCII");
					if ($pos !== false) {
						$zeichensatz = 3;
					}
				}
			}
			if ($zeichensatz == 0) { die("Falscher Dateityp"); }
// Opens the rezepte.txt and stores the content in the database
			switch ($fileformat) {
				case $sys_myown:
					$file = fopen("../upload/rezepte.txt", "r");
					switch ($zeichensatz) {
						case 1:
							iconv_set_encoding("input_encoding", "UTF-8");
							break;
						case 2:
							iconv_set_encoding("input_encoding", "ISO-8859-15");
							break;
						case 3:
							iconv_set_encoding("input_encoding", "ASCII");
							break;
					}
					iconv_set_encoding("internal_encoding", "UTF-8");
					iconv_set_encoding("output_encoding", "UTF-8");
					ob_start("ob_iconv_handler");
					mysql_query("SET NAMES 'utf8'");
					mysql_query("SET CHARACTER SET 'utf8'");
					$rez_anzahl = 0;
					while ($eingabe = fgets($file)) {
						$eingabe = trim($eingabe);
						if (substr($eingabe, 0, 34) == "========== Gabel Rezept ==========") {
// Begin of recipe and reset of all variables
							$rez_title = "";
							$rez_kategorie = "";
							$rez_servings = "";
							$rez_prefix = "";
							$rez_prefixflag = false;
							$rez_preparation = "";
							$rez_preparationflag = false;
							$rez_postfix = "";
							$rez_postfixflag = false;
							$rez_tipp = "";
							$rez_tippflag = false;
							$rez_arbeitszeit = "";
							$rez_brennwert = "";
							$rez_image = "";
							$ing_amount = "";
							$ing_value = "";
							$ing_description = "";
							$ing_redientflag = false;
// Select the next recipe id
							$query = "SELECT MAX(id) FROM das_rezept";
							$result = mysql_query($query);
							$row = mysql_fetch_row($result);
							$id = $row[0];
							$id = $id + 1;
						}
// Reading the datafields
						if (substr($eingabe, 0, 6) == "Titel:") {
							$rez_title = substr($eingabe, 6);
						}
						if (substr($eingabe, 0, 10) == "Kategorie:") {
							$rez_kategorie = substr($eingabe, 10);
						}
						if (substr($eingabe, 0, 8) == "Portion:") {
							$rez_servings = substr($eingabe, 8);
						}
// Flags for multiline fields
						if (substr($eingabe, 0, 8) == "Zutaten:") {
							$ing_redientflag = true;
						}
						if (substr($eingabe, 0, 7) == "Prefix:") {
							$rez_prefixflag = true;
						}
						if (substr($eingabe, 0, 12) == "Zubereitung:") {
							$rez_preparationflag = true;
						}
						if (substr($eingabe, 0, 8) == "Postfix:") {
							$rez_postfixflag = true;
						}
						if (substr($eingabe, 0, 5) == "Tipp:") {
							$rez_tippflag = true;
						}
						if (substr($eingabe, 0, 8) == "Zutaten!") {
							$ing_redientflag = false;
						}
						if (substr($eingabe, 0, 7) == "Prefix!") {
							$rez_prefixflag = false;
						}
						if (substr($eingabe, 0, 12) == "Zubereitung!") {
							$rez_preparationflag = false;
						}
						if (substr($eingabe, 0, 8) == "Postfix!") {
							$rez_postfixflag = false;
						}
						if (substr($eingabe, 0, 5) == "Tipp!") {
							$rez_tippflag = false;
						}
// Reading multiline fields
						if ($ing_redientflag) {
							if (substr($eingabe, 0, 8) != "Zutaten:") {
								$ing_redient = $eingabe;
								$ingredline = explode("|", $ing_redient);
								$query = "INSERT INTO dat_ingredient (amount, value, description, recipe) VALUES (";
								if ($ingredline[0] != "") {
									$query = $query . $ingredline[0] . ", ";
								}
								else {
									$query = $query . "NULL, ";
								}
								if ($ingredline[1] != "") {
									$query = $query . "'" . $ingredline[1] . "', '";
								}
								else {
									$query = $query . "NULL, '";
								}
								$query = $query . $ingredline[2] . "', " . $id . ")";
							}
							$result = mysql_query($query);
							if (!$result) {
								die (mysql_error());
							}
						}
						if ($rez_prefixflag) {
							if (substr($eingabe, 0, 7) != "Prefix:") {
								$rez_prefix = $rez_prefix . $eingabe;
							}
						}
						if ($rez_preparationflag) {
							if (substr($eingabe, 0, 12) != "Zubereitung:") {
								$rez_preparation = $rez_preparation . $eingabe;
							}
						}
						if ($rez_postfixflag) {
							if (substr($eingabe, 0, 8) != "Postfix:") {
								$rez_postfix = $rez_postfix . $eingabe;
							}
						}
						if ($rez_tippflag) {
							if (substr($eingabe, 0, 5) != "Tipp:") {
								$rez_tipp = $rez_tipp . $eingabe;
							}
						}
						if (substr($eingabe, 0, 12) == "Arbeitszeit:") {
							$rez_arbeitszeit = substr($eingabe, 13);
						}
						if (substr($eingabe, 0, 10) == "Brennwert:") {
							$rez_brennwert = substr($eingabe, 11);
						}
						if (substr($eingabe, 0, 6) == "Image:") {
							$rez_image = substr($eingabe, 7);
						}
// End of recipe
						if ($eingabe == "=====") {
							$query = "INSERT INTO das_rezept (id, title, servings, prefix, preparation, postfix, tipp, arbeitszeit, brennwert, image) VALUES (";
							$query = $query . $id . ", '" . $rez_title . "', " . $rez_servings . ", ";
							if ($rez_prefix != "") {
								$query = $query . "'" . $rez_prefix . "', '";
							}
							else {
								$query = $query . "NULL, '";
							}
							$query = $query . $rez_preparation . "', ";
							if ($rez_postfix != "") {
								$query = $query . "'" . $rez_postfix . "', ";
							}
							else {
								$query = $query . "NULL, ";
							}
							if ($rez_tipp != "") {
								$query = $query . "'" . $rez_tipp . "', ";
							}
							else {
								$query = $query . "NULL, ";
							}
							if ($rez_arbeitszeit != "") {
								$query = $query . "'" . $rez_arbeitszeit . "', ";
							}
							else {
								$query = $query . "NULL, ";
							}
							if ($rez_brennwert != "") {
								$query = $query . $rez_brennwert . ", ";
							}
							else {
								$query = $query . "NULL, ";
							}
							trim($rez_image);
							if ($rez_image != "") {
								$query = $query . "'" . $rez_image . "')";
							}
							else {
								$query = $query . "NULL)";
							}
// Insert into das_rezept
							$result = mysql_query($query);
							if (!$result) {
								die (mysql_error());
							}
// Insert into dat_rezepttocat
							$query = "INSERT INTO dat_rezepttocat (recipe, category) VALUES (" . $id . ", " . $rez_kategorie . ")";
							$result = mysql_query($query);
							if (!$result) {
								die (mysql_error());
							}
// Insert into sys_zaehler
							$zeit = date('Y-m-d');
							$query = "INSERT INTO sys_zaehler (id, zaehler, datum) VALUES (" . $id . ", 0, '" . $zeit . "')";
							$result = mysql_query($query);
							if (!$result) {
								die (mysql_error());
							}
							$rez_anzahl = $rez_anzahl + 1;
						}
					}
					echo "$rez_anzahl $sys_recipe $sys_saved<br><br>\n";
					echo "<a href=\"./peng.php\">$sys_admin</a><br><br>\n";
					fclose($file);
					fclose($fh2);
					break;
				case $sys_mealmaster:
					$file = fopen("../upload/rezepte.txt", "r");
					switch ($zeichensatz) {
						case 1:
							iconv_set_encoding("input_encoding", "UTF-8");
							break;
						case 2:
							iconv_set_encoding("input_encoding", "ISO-8859-15");
							break;
						case 3:
							iconv_set_encoding("input_encoding", "ASCII");
							break;
					}
					iconv_set_encoding("internal_encoding", "UTF-8");
					iconv_set_encoding("output_encoding", "UTF-8");
					ob_start("ob_iconv_handler");
					mysql_query("SET NAMES 'utf8'");
					mysql_query("SET CHARACTER SET 'utf8'");
					$rez_anzahl = 0;
					while ($eingabe = fgets($file)) {
						$eingabe = str_replace("'", "", $eingabe);
						$pos = strpos($eingabe, "Meal-Master");
						if ($pos !== false) {
// Begin of recipe and reset of all variables
							$rez_title = "";
							$rez_kategorie = "124";
							$rez_servings = "";
							$rez_preparation = "";
							$rez_fieldflag = 0;
							$ing_amount = "";
							$ing_value = "";
							$ing_description = "";
// Select the next recipe id
							$query = "SELECT MAX(id) FROM das_rezept";
							$result = mysql_query($query);
							$row = mysql_fetch_row($result);
							$id = $row[0];
							$id = $id + 1;
						}
// Reading the datafields
						$pos = strpos($eingabe, "Title:");
						if ($pos !== false) {
							$rez_title = trim(substr($eingabe, 13));
						}
						$pos = strpos($eingabe, "Yield:");
						if ($pos !== false) {
							$rez_servings = intval(substr($eingabe, 13, 3));
							$rez_fieldflag = 1;
						}
// Reading multiline fields
						switch ($rez_fieldflag) {
							case 0:
								break;
							case 1:
								if (trim($eingabe != "")) {
									if (trim(substr($eingabe, 7, 4)) != "") {
										if (substr($eingabe, 7, 4) == "----") {
											$peng = trim($eingabe, "-");
											$query = "INSERT INTO dat_ingredient (amount, value, description, recipe) VALUES (NULL, NULL,'<b>" . $peng . "</b>', ". $id . ")";
											$result = mysql_query($query);
											$mcode = "X";
										}
										else {
											$query = "SELECT * FROM sys_mm WHERE mcode = '" . trim(substr($eingabe, 7, 4)) . "'";
											$result = mysql_query($query) or die(mysql_error());
											$mid = mysql_result($result,0,"id");
											$mcode = mysql_result($result,0,"mcode");
										}
									}
									elseif (substr($eingabe, 7, 4) == "    ") {
										$peng = trim($eingabe);
										$query = "INSERT INTO dat_ingredient (amount, value, description, recipe) VALUES (NULL, NULL,'" . $peng . "', ". $id . ")";
										$result = mysql_query($query);
										$mcode = "X";
									}
									if (trim($mcode) != "") {
										if ($mcode != "X") {
											$peng = trim(substr($eingabe, 0, 7));
											$value = explode(" ", $peng);
											$value[0] = trim($value[0]);
											$value[1] = trim($value[1]);
											if ($value[1] == "") {
												if (substr($value[0], 1, 1) == "/") {
													$nenner = intval(substr($value[0], 0, 1));
													$teiler = intval(substr($value[0], 2, 1));
													$ing_amount = getAmount($nenner, $teiler);
												}
												else {
													$ing_amount = intval($value[0]);
												}
											}
											else {
												if (substr($value[1], 1, 1) == "/") {
													$nenner = intval(substr($value[1], 0, 1));
													$teiler = intval(substr($value[1], 2, 1));
													$ing_amount = getAmount($nenner, $teiler);
													$ing_amount = intval($value[0]) + $ing_amount;
												}
											}
											$ing_value = trim(substr($eingabe, 8, 2));
											$ing_description = trim(substr($eingabe, 11));
											$query = "INSERT INTO dat_ingredient (amount, value, description, recipe) VALUES (";
											if ($ing_amount != "" || $ing_amount != 0) {
												$query = $query . $ing_amount . ", ";
											}
											else {
												$query = $query . "NULL, ";
											}
											if ($ing_value != "") {
												$query = $query . "'" . $ing_value . "', ";
											}
											else {
												$query = $query . "NULL, ";
											}
											$query = $query . "'" . $ing_description . "', " . $id .")";
											if ($ing_description != "") {
												$result = mysql_query($query) or die(mysql_error());
											}
										}
									}
									else {
										$pos = strpos($eingabe, "Yield:");
										if ($pos === false) {
											if (trim($eingabe) != "-----" && trim($eingabe) != "MMMMM") {
												$rez_preparation = $rez_preparation . trim($eingabe) . "<br>\n";
											}
										}
									}
									if (trim($eingabe) == "-----" || trim($eingabe) == "MMMMM") {
										$query = "INSERT INTO das_rezept (id, title, servings, preparation) VALUES (" . $id . ", '" . $rez_title . "', " . $rez_servings . ", '" . $rez_preparation . "<br>')";
										$result = mysql_query($query) or die(mysql_error());
										$query = "INSERT INTO dat_rezepttocat (recipe, category) VALUES (" . $id . ", " . $rez_kategorie . ")";
										$result = mysql_query($query) or die(mysql_error());
										$zeit = date('Y-m-d');
										$query = "INSERT INTO sys_zaehler (id, zaehler, datum) VALUES (" . $id . ", 0, '" . $zeit . "')";
										$result = mysql_query($query) or die(mysql_error());
										$rez_anzahl = $rez_anzahl + 1;
									}
								}
								break;
							default:
								break;
						}
					}
					echo "$rez_anzahl $sys_recipe $sys_saved<br><br>\n";
					echo "<a href=\"./peng.php\">$sys_admin</a><br><br>\n";
					fclose($file);
					fclose($fh2);
					break;
				case $sys_rezkonv:
					$file = fopen("../upload/rezepte.txt", "r");
					switch ($zeichensatz) {
						case 1:
							iconv_set_encoding("input_encoding", "UTF-8");
							break;
						case 2:
							iconv_set_encoding("input_encoding", "ISO-8859-15");
							break;
						case 3:
							iconv_set_encoding("input_encoding", "ASCII");
							break;
					}
					if (!iconv_set_encoding("internal_encoding", "ISO-8859-15")) { die("Fehler bei der Konvertierung (internal)"); }
					if (!iconv_set_encoding("output_encoding", "UTF-8")) { die("Fehler bei der Konvertierung (output)"); }
					ob_start("ob_iconv_handler");
					$rez_anzahl = 0;
					while ($eingabe = fgets($file)) {
						$eingabe = str_replace("'", "", $eingabe);
						$pos = strpos($eingabe, "RezkonvSuite");
						if ($pos !== false) {
// Begin of recipe and reset of all variables
							$rez_title = "";
							$rez_kategorie = "124";
							$rez_servings = "";
							$rez_preparation = "";
							$rez_tipp = "";
							$rez_fieldflag = 0;
							$ing_amount = "";
							$ing_value = "";
							$ing_description = "";
							$zflag = false;
// Select the next recipe id
							$query = "SELECT MAX(id) FROM das_rezept";
							$result = mysql_query($query);
							$row = mysql_fetch_row($result);
							$id = $row[0];
							$id = $id + 1;
						}
// Reading the datafields
						$pos = strpos($eingabe, "Titel:");
						if ($pos !== false) {
							$rez_title = trim(substr($eingabe, 12));
						}
						$pos = strpos($eingabe, "Menge:");
						if ($pos !== false) {
							$rez_servings = intval(substr($eingabe, 12, 3));
							$rez_fieldflag = 1;
						}
// Reading multiline fields
						switch ($rez_fieldflag) {
							case 0:
								break;
							case 1:
// Set ingredient flag
								if (substr($eingabe, 0, 8) == "Zutaten:") {
									$zflag = true;
								}
// if ingredient flag is set text is splittet and goes to the ingredient block
								if (trim($eingabe) != "") {
									if (($zflag) && trim($eingabe) != "Zutaten:") {
										$query = "INSERT INTO dat_ingredient (amount, value, description, recipe) VALUES (";
										if (substr($eingabe, 0, 7) == "=======") {
											$pos = strpos($eingabe, "QUELLE");
											if ($pos !== false) {
												$rez_preparation = trim($eingabe) . "<br>";
												$zflag = false;
											}
											else {
												$ing_description = trim($eingabe, "=");
												$query = "INSERT INTO dat_ingredient (amount, value, description, recipe) VALUES (NULL, NULL, '<b>" . $ing_description . "</b>', " . $id . ")";
												$result = mysql_query($query) or die(mysql_error());
											}
										}
										else {
											$pos = strpos(substr($eingabe, 0, 7), "/");
											if ($pos === false) {
												$ing_amount = intval(substr($eingabe, 0, 7));
											}
											else {
												$ing_amount = trim(substr($eingabe, 0, 7));
												$bruch = explode("/", $ing_amount);
												$ing_amount = getAmount($bruch[0], $bruch[0]);
											}
											if ($ing_amount != "") {
												$query = $query . $ing_amount . ", ";
											}
											else {
												$query = $query . "NULL, ";
											}
											if (trim(substr($eingabe, 8, 10)) != "") {
												$query = $query . "'" . trim(substr($eingabe, 8, 10)) . "', ";
											}
											else {
												$query = $query . "NULL, ";
											}
											$query = $query . "'" . trim(substr($eingabe, 19)) . "', ". $id . ")";
											$result = mysql_query($query) or die(mysql_error());
										}
									}
// else text belongs to preparation
									else {
										if (trim($eingabe) == "Zubereitung:") { $rez_preparation = $rez_preparation . "<br>"; }
										if ((trim($eingabe) != "Zubereitung:") && !strpos($eingabe, "Menge:")) {
											if (trim($eingabe) != "=====") {
												$rez_preparation = $rez_preparation . trim($eingabe) . "<br>";
											}
										}
									}
								}
								if (trim($eingabe) == "=====") {
									$query = "INSERT INTO das_rezept (id, title, servings, preparation) VALUES (" . $id . ", '" . $rez_title . "', " . $rez_servings . ", '" . $rez_preparation . "<br>')";
									$result = mysql_query($query) or die(mysql_error());
									$query = "INSERT INTO dat_rezepttocat (recipe, category) VALUES (" . $id . ", " . $rez_kategorie . ")";
									$result = mysql_query($query) or die(mysql_error());
									$zeit = date('Y-m-d');
									$query = "INSERT INTO sys_zaehler (id, zaehler, datum) VALUES (" . $id . ", 0, '" . $zeit . "')";
									$result = mysql_query($query) or die(mysql_error());
									$rez_anzahl = $rez_anzahl + 1;
								}
								break;
						}
					}
					echo "$rez_anzahl $sys_recipe $sys_saved<br><br>\n";
					echo "<a href=\"./peng.php\">$sys_admin</a><br><br>\n";
					fclose($file);
					fclose($fh2);
					break;
			}
		}
		else { // Export recipes
			echo "<div class=\"rdisplay\">\n";
			$fileformat = $_POST['fileformat'];
			$outfile = "rezepte.";
			switch ($fileformat) {
				case $sys_myown:
					$outfile = $outfile . "wcb";
					$outfiled = "../upload/" . $outfile;
					$aus = fopen($outfiled, "w");
					$r_query = "SELECT * FROM das_rezept ORDER BY title";
					$r_result = mysql_query($r_query);
					while ($row = mysql_fetch_row($r_result)) {
						saveWCBFile($aus, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[9]);
					}
					fclose($aus);
					echo "<a href=\"dumpdb.php?outfile=$outfile\">$sys_outfile</a> ";
					echo "<a href=\"./\">$sys_admin</a>\n";
					break;
				case $sys_mealmaster:
					$outfile = $outfile . "mmf";
					$outfiled = "../upload/" . $outfile;
					$aus = fopen($outfiled, "w");
					$r_query = "SELECT * FROM das_rezept ORDER BY title";
					$r_result = mysql_query($r_query);
					while ($row = mysql_fetch_row($r_result)) {
						saveMMFile($aus, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[9]);
					}
					fclose($aus);
					echo "<a href=\"dumpdb.php?outfile=$outfile\">$sys_outfile</a> ";
					echo "<a href=\"./\">$sys_admin</a>\n";
					break;
				case $sys_rezkonv:
					$outfile = $outfile . "rk";
					$outfiled = "../upload/" . $outfile;
					$aus = fopen($outfiled, "w");
					$r_query = "SELECT * FROM das_rezept ORDER BY title";
					$r_result = mysql_query($r_query);
					while ($row = mysql_fetch_row($r_result)) {
						saveRKFile($aus, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[9]);
					}
					fclose($aus);
					echo "<a href=\"dumpdb.php?outfile=$outfile\">$sys_outfile</a> ";
					echo "<a href=\"./peng.php\">$sys_admin</a>\n";
					break;
				}
		}
		echo "<div class=\"footer\"><br>&copy; Joachim Gabel (<a href=\"http://www.gabel-it.com\" target=\"_blank\">Gabel IT</a>) 2011 - 2012</div>\n";
		echo "</div>\n";
		break;
	case 15: // Edit recipes
		echo "<div class=\"rdisplay\">\n";
		echo "<div class=\"admin\">\n";
		if ($_POST['title'] != "") {
			$query = "SELECT * FROM das_rezept WHERE title = '" . $_POST['title'] . "'";
			$result = mysql_query($query);
			$rez_id = intval(mysql_result($result, 0, "id"));
			if ($rez_id > 0) {
				$rez_title = mysql_result($result, 0, "title");
				$rez_portion = mysql_result($result, 0, "servings");
				$rez_prefix = mysql_result($result, 0, "prefix");
				$rez_preparation = mysql_result($result, 0, "preparation");
				$rez_postfix = mysql_result($result, 0, "postfix");
				$rez_tipp = mysql_result($result, 0, "tipp");
				$rez_arbeitszeit = mysql_result($result, 0, "arbeitszeit");
				$rez_image = mysql_result($result, 0, "image");
				echo "<h3 align=\"center\">$sys_insert</h3>\n";
				echo "<form name=\"Neueingabe\" method=\"post\" action=\"./\">\n";
				echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\">\n";
				echo "<tr>\n";
				echo "<td class=\"zcells\" align=\"right\">$sys_mtitle</td><td class=\"zcells\" colspan=\"3\"><input type=\"text\" name=\"title\" size=\"80\" value=\"$rez_title\"></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "</td><td class=\"zcells\" colspan=\"2\">$sys_mes_section</td>\n";
				echo "<td class=\"zcells\" align=\"right\">$sys_portions:</td><td class=\"zcells\"><input type = \"text\" name=\"portionen\" value=\"$rez_portion\">\n";
				echo "</tr>\n";
				$i_query = "SELECT * FROM dat_ingredient WHERE recipe = " . $rez_id;
				$i_result = mysql_query($i_query);
				$rez_ingredient = "";
				while ($row = mysql_fetch_row($i_result)) {
					$rez_ingredient = $rez_ingredient . $row[1] . "|" . $row[2] . "|" . $row[3] . "\n";
				}
				echo "<tr>\n";
				echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_ingred:</td><td class=\"zcells\" valign=\"top\"><textarea name=\"zutaten\" cols=\"50\" rows=\"6\">$rez_ingredient</textarea>\n";
				echo "<td class=\"zcells\" valign=\"top\" colspan=\"2\">$sys_ingedformat</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_prefix</td><td class=\"zcells\"><textarea name=\"prefix\" cols=\"50\" rows=\"6\">$rez_prefix</textarea>\n";
				echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_preparation</td><td class=\"zcells\"><textarea name=\"preparation\" cols=\"50\" rows=\"6\">$rez_preparation</textarea>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_postfix</td><td class=\"zcells\"><textarea name=\"postfix\" cols=\"50\" rows=\"6\">$rez_postfix</textarea>\n";
				echo "<td class=\"zcells\" valign=\"top\" align=\"right\">$sys_tip</td><td class=\"zcells\"><textarea name=\"tipp\" cols=\"50\" rows=\"6\">$rez_tipp</textarea>\n";
				echo "</tr>\n";
				$zeit = date('Y-m-d');
				echo "<input type=\"hidden\" name=\"datum\" value=\"$zeit\">\n";
				echo "<input type=\"hidden\" name=\"mode\" value=\"25\">\n";
				echo "<input type=\"hidden\" name=\"id\" value=\"$rez_id\">";
				echo "<input type=\"hidden\" name=\"peng\" value=\"peng\">";
				echo "<tr>\n";
				echo "<td class=\"zcells\" align=\"right\">$sys_wt</td><td class=\"zcells\"><input type=\"text\" name=\"arbeitszeit\" value=\"$rez_arbeitszeit\">\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td class=\"zcells\" align=\"right\">$sys_image</td><td class=\"zcells\" colspan=\"3\"><input type=\"text\" name=\"image\" size=\"80\" value=\"$rez_image\"></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td class=\"zcells\" align=\"center\" colspan=\"4\">\n";
				echo "<input type=\"submit\" value=\"$sys_save\">&nbsp;\n";
				echo "<input type=\"reset\" value=\"$sys_reset\">\n";
				echo "</td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				echo "</form>\n";
			}
			else {
				echo "$sys_err_notfound<br>\n";
				echo "<a href=\"./\">$sys_admin</a>\n";
			}
		}
		else {
			echo "$sys_err_title<br>\n";
			echo "<a href=\"./\">$sys_admin</a>\n";
		}
		break;
	case 25: // Update edited recipe
		echo "<div class=\"rdisplay\">\n";
		echo "<div class=\"admin\">\n";
		$rez_id = $_POST['id'];
		$rez_title = $_POST['title'];
		$rez_portion = $_POST['portionen'];
		$rez_prefix = $_POST['prefix'];
		$rez_zutaten = $_POST['zutaten'];
		$rez_preparation = $_POST['preparation'];
		$rez_postfix = $_POST['postfix'];
		$rez_tipp = $_POST['tipp'];
		$rez_arbeitszeit = $_POST['arbeitszeit'];
		$rez_image = $_POST['image'];
		$query = "UPDATE das_rezept SET title = '" . $rez_title . "', servings = " . $rez_portion;
		if ($rez_prefix != "") {
			$query = $query . ", prefix = '" . $rez_prefix . "'";
		}
		$query = $query . ", preparation = '" . $rez_preparation . "'";
		if ($rez_postfix != "") {
			$query = $query . ", postfix = '" . $rez_postfix . "'";
		}
		if ($rez_tipp != "") {
			$query = $query . ", tipp = '" . $rez_tipp . "'";
		}
		if ($rez_arbeitszeit != "" && $rez_arbeitszeit > 0) {
			$query = $query . ", arbeitszeit = '" . $rez_arbeitszeit . "'";
		}
		if ($rez_image != "") {
			$query = $query . ", image = '" . $rez_image ."'";
		}
		$query = $query . " WHERE id = " . $rez_id;
		$result = mysql_query($query) or die(mysql_error());
		$query = "DELETE FROM dat_ingredient WHERE recipe = " . $rez_id;
		$result = mysql_query($query) or die('Error deleting ingredients:' . mysql_error());
		saveIngredients($rez_zutaten, $rez_id);
		echo "$rez_title $sys_saved !<br>\n";
		echo "<a href=\"./peng.php\">$sys_admin</a>\n";
		break;
	default:
		showAdministration($mode);
		break;
}

echo "</div>\n";
echo "</body>\n";
echo "</html>\n";

?>