<?
/*
* Import SQL
* Description: Import MySQL Database from *.sql (mainly used for restoring backups
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
## If not defined : IN_ACP do PHP::DIE()
if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
## Define $template
$template = new template;
## Change title to "Evilboard Admin Control Panel - Forum Management :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Import MySQL Backups :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
if ( isset($_POST['Submit'])) {
	$x = upload_sql("sql","../tmp");
	$fp = fopen("../tmp/" . $x,"r+");
	$f = fread($fp,200000000);
	#$f = nl2br($f);
	$db = new db;
	$db->connect();
	# @todo :: get it working :P
	mysql_query($f);
	return "Backup Restored sucsessfully";
	fclose($f);
}
$import->ret = "<span id=\"b\">&nbsp;MySQL Import Backup</span><br>&nbsp;Use this simple form to import a SQL backup <form action=\"{$PHP_SELF}\" method=\"post\" enctype=\"multipart/form-data\">
                     <table width=\"100%\"  border=\"0\" cellspacing=\"0\">
                       <tr>
                         <td width=\"212\"><span class=\"eb_txt\"></span>&nbsp;<input type=\"file\" name=\"sql\" style=\"width:230px;\" border=\"0\"></td>
                         <td width=\"472\" valign=\"bottom\"><input name=\"Submit\" type=\"submit\" value=\"Upload SQL backup and update database\"></td>
                       </tr>
                     </table>
                   </form>";
return $import->ret;
/* Working version to read from SQL file 
$file = "sql.sql";
if (!file_exists($file)) { die ("FUCK! :("); }
$fp = fopen($file,"r+");
$f = fread($fp,200000000);
$f = nl2br($f);
return $f;
fclose($fp);
*/
function upload_sql($input_name, $path)
	 {
	global $file_name;
	global $HTTP_POST_FILES;
	 
	if(isset($HTTP_POST_FILES) && is_uploaded_file($HTTP_POST_FILES[$input_name]["tmp_name"]))
		{
		$rand = rand(1,126161);
		$file_name = $HTTP_POST_FILES[$input_name]["name"];   
	   move_uploaded_file($HTTP_POST_FILES[$input_name]["tmp_name"], 
		 $path . "/"  . $file_name);
		 return $file_name;
   }
}
?>