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
		

$smarty->display('submit.tpl');
	


?>