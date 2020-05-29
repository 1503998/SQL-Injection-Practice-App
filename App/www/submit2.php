<?php
/*
+ ----------------------------------------------------------------------------+
|     PHPDirector.
|		$License: GNU General Public License
|		$Website: phpdirector.co.uk
+----------------------------------------------------------------------------+
*/
require('header.php');
			
		//Categories
		$catresult = mysql_query("SELECT * FROM pp_categories WHERE disable='0'") or die("Error: " . mysql_error());
		//Gets Categories
		while ($catrow2 =  mysql_fetch_array($catresult)){
			$result11[] = $catrow2;
		}
		//pass the results to the template
		$smarty->assign('cat', $result11);
		
		//Display Images page
		
$insertthumb = $_POST["picture"];
$inserttitle = $_POST["titletext"];
$insertauthor = $_POST["authortext"];
$insertwebsite = $_POST["creator_url"];
$insertwidth = $_POST["width"];
$insertheight = $_POST["height"];
$insertdes = safe_sql_insert($_POST["descriptiontext"]);
$insetycat = $_POST["category"];
$videoid = $_POST["file"];


			
$sql = "INSERT INTO pp_files (name, url_creator, creator, description, date, file, file2, approved, ip, picture, category, width, height) 
VALUES ('$inserttitle', '$insertwebsite' , '$insertauthor', '$insertdes', CURRENT_DATE(), '$videoid', '', '0', '$ip', '$insertthumb', '$insetycat','$insertwidth','$insertheight')";
mysql_query($sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());

    // on affiche le résultat pour le visiteur
    $smarty->assign('message','Your game has been submitted.<br /><a href="submit.php">Submit another game</a> ?'); 
$smarty->display('submit2.tpl');
	


?>