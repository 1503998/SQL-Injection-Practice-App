<?
/*
* Smileys
* Description: Smileys
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
## If not defined : IN_ACP do PHP::DIE()
if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
## Define $template
$template = new template;
## Change title to "Evilboard Admin Control Panel - Forum Management :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Smileys :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
## Define $db && Connect
$db = new db;
$db->connect();
## Get all Smileys
$smiley = new smiley;
if ( isset($_GET['u'] ) ) {
	$id_sm = $_GET['u'];
	if ( isset($_POST['Submit2']) ) {
	$p_cmd = $_POST['textfield'];
	$db->query("UPDATE `eb_smiley` SET `cmd` = '{$p_cmd}' WHERE `id` = '{$id_sm}' LIMIT 1 ;");
	return "<span id=\"b\">&nbsp;Edit Smiley</span><br>&nbsp;Smileys have been updated, please click <a href=\"index.php?code=19\">here</a> to return to smileys management.";
	}
	// @todo :: Create Clickable Smileys and non clickable ones =]
	$sm->q = $db->query("SELECT * FROM `eb_smiley` WHERE `id` = '{$id_sm}'");
	$sm->q = mysql_fetch_array($sm->q);
	$u = "<span id=\"b\">&nbsp;Edit Smiley</span>";
	$u .= "<form name=\"form1\" method=\"post\" action=\"\"><table width=\"55%\"  border=\"0\" cellspacing=\"0\" align=\"center\">
							 <tr>
							   <td width=\"1%\"><input type=\"text\" name=\"textfield\" value=\"{$sm->q[cmd]}\"></td>
							   <td width=\"99%\" colspan=\"3\" class=\"eb_txt\" style=\"color: black;\">Emoticon Command (Example: :emoty:) <font color=\"black\">&nbsp;</font></td>
							 </tr>
							 <tr>
							   <td colspan=\"2\"><div align=\"center\">
								 <input name=\"Submit2\" type=\"submit\" id=\"Submit2\" value=\"Submit\">
							   </div></td>
							 </tr>
						   </table></form>";
	return $u;
}
if ( isset($_POST['textfield']) ) {
	$smiley->upload_file("sm","../Emoticons/1/");
}
$template->smileyform = $smiley->sql_frm();
$template->sm_frm = $smiley->form();
$t->b = "<table width=\"100%\"  border=\"0\" cellspacing=\"0\">
  <tr>
    <td width=\"140\" valign=\"top\">{$template->sm_frm}</td>
    <td>{$template->smileyform}</td>
  </tr>
</table>";
return $t->b;
## Class Smiley // Smileys Class
class smiley
{
	function form()
	{
	$top = '<fieldset class="eb_txt" style="width: 135px;">
<legend id="b">Current Smileys</legend><span class="eb_txt" style="color: black;">Click on any of the emoticons to edit</span><br><br>';
	    ########################################
 		## Add in the smilies box             ##
 		########################################
		
		$connect = mysql_query("SELECT * FROM eb_smiley");
		while ($emo = mysql_fetch_object( $connect ) )
		{
		$img = $emo -> img;
		$cmd = $emo -> cmd;
		$id = $emo ->id;

			$top .= "<a href=\"index.php?code=19&u={$id}\"><img src=\"../Emoticons/1/" .$img. "\" alt='smilie' border='0'></a>&nbsp;\n";

		}
		$top .= "</fieldset>";
		return $top;
	}
	function sql_frm()
	{
	return '<font color="black"><fieldset><legend id="b">Upload Emoticon</legend><form action="' . $PHP_SELF . '" method="post" enctype="multipart/form-data">
				   <span class="eb_txt">Emoticon max filesize: 150kb</span>
                     <table width="100%"  border="0" cellspacing="0">
                       <tr>
                         <td width="295"><span class="eb_txt"></span><input type="file" name="sm" style="width:230px;" border="0"></td>
                         <td width="690" valign="bottom" class="eb_txt" style="color: black;">Max file size: 150kB </td>
                       </tr>
                       <tr>
                         <td width="295"><input type="text" name="textfield" style="width: 146px;"></td>
                         <td valign="bottom" class="eb_txt" style="color: black;">Emoticon Command (Example: :emoty:) </td>
                       </tr>
                       <tr>
                         <td colspan="2"><div align="center">
                           <input type="submit" name="Submit" value="Submit">
                         </div></td>
                       </tr>
                     </table>                   </form></fieldset></font>';
	}
	function upload_file($input_name, $path)
			 {
			 global $HTTP_POST_FILES;
							 
			 if(isset($HTTP_POST_FILES) && is_uploaded_file($HTTP_POST_FILES[$input_name]["tmp_name"]))
			   {
			   $file_name = $HTTP_POST_FILES[$input_name]["name"];   
			   if($HTTP_POST_FILES[$input_name]['type'] != "image/gif" AND $HTTP_POST_FILES[$input_name]['type'] != "image/pjpeg" AND $HTTP_POST_FILES[$input_name]['type'] !="image/jpeg") {
	  $error = "This file type is not allowed";
	  echo "$error";
	  unlink($HTTP_POST_FILES[$input_name]['tmp_name']);
	} else {
	$maxfilesize=500000;
	
	if ($HTTP_POST_FILES[$input_name]['size'] > $maxfilesize) {
	  $error = "file is too large";
	  echo $error;
	  unlink($HTTP_POST_FILES[$input_name]['tmp_name']);
	} else {
	  //the file is under the specified number of bytes.
	
			   //For those who have policies about the file types that can be uploaded
			   //to their sites, $file_name can be modified to disable unwanted behaviour
			   //like the serving of unwanted content.
			   $cmd = $_POST['textfield'];
			   $f_n_x = $HTTP_POST_FILES[$input_name]["name"];
			   move_uploaded_file($HTTP_POST_FILES[$input_name]["tmp_name"], 
								  $path . "/" . $file_name);
								  $xfx = $HTTP_POST_FILES[$input_name]["name"];
								  $uid = $_SESSION['userid'];
								  $db = new db;
								  $db->connect();
				$db->query("INSERT INTO `eb_smiley` ( `cmd` , `img` , `id` )
							VALUES ('{$cmd}', '{$f_n_x}', NULL);");         
			   //I do this because some servers will set the permissions on uploaded files
			   //to 0600 or 0700.  That makes recently uploaded images unviewable.
			   chmod($path . $file_name, 0644);
			   }
			 }
			}  
		}
}
?>