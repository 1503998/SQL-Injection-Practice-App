<?
/*
* Bad word
* Description: With this document, you can add, and delete bad words,
* wich will be changed to what you have set in admin panel.
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
## If not defined : IN_ACP do PHP::DIE()
if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
## Define $template
$template = new template;
## Change title to "Evilboard Admin Control Panel - Badwords :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Badwords :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
## Define $db && Connect
$db = new db;
$db->connect();
$badword = new badword;
switch($_GET['do'])
{
	case 'add':
	return $badword->add();
	break;
	
	case 'delete':
	return $badword->del();
	break;
	
	default:
	return $badword->idx();
	break;
}
class badword
{
	function idx() {
	$db = new db;
$db->connect();
	$badword->form = "<span id=\"b\">&nbsp;Bad words</span><br> &nbsp;With this form you can add bad words, and delete them.<br /><br />";
	$badword->form .= "<table width=\"300\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr>
		<td colspan=\"2\" class=\"eb_forum\"><div align=\"center\"><strong>Bad words: </strong></div></td>
	  </tr>
	  <tr>
		<td width=\"257\" class=\"eb_header\" style=\"border-top-width: 0; border-right-width: 0; height:20px;\">&nbsp;Bad word: </td>
		<td class=\"eb_header\" style=\"border-left-width: 0;border-top-width: 0; height:20px;\"> <div align=\"center\">Delete </div></td>
	  </tr>";
	  $query = $db->query("SELECT * FROM eb_badword ORDER BY `bid`");
	  $num_rows = @mysql_num_rows($query);
	  if($num_rows > 0) {
		  while($row = mysql_fetch_array($query)) {
			  $badword->form .= "<tr>
				<td class=\"forum_footer\" style=\"border-right-width: 0; height: 20px;\">&nbsp;&nbsp;{$row[text]}</td>
				<td class=\"forum_footer\" style=\"border-left-width: 0; height: 20px;\"><div align=\"center\"><a href=\"index.php?code=61&amp;do=delete&id={$row[bid]}\">Delete</a></div></td>
			  </tr>";
		  }
	  }
	  else {
			$badword->form .= "<tr>
			<td class=\"forum_footer\" colspan=\"2\"><div align=\"center\"><i>Badwords database is empty</i></div></td></tr>";
	  }
	  $badword->form .= "</table><div align=\"center\"><div style=\"width: 298px; height: 20px; border-top-width: 0;\" class=\"eb_header\"><br style=\"font-size:2px;\" \><form name=\"\" method=\"post\" action=\"index.php?code=61&do=add\"><input type=\"text\" name=\"aB\" style=\"width: 245px; border-top-width: 0; border-bottom-width: 0;\" border=\"0\" value=\"Add Badword...\" onClick=\"javascript: this.value='';\">&nbsp;<input name=\"Submit\" type=\"submit\" value=\"Add\" style=\"border-top-width: 0; border-bottom-width: 0;\" /></form></div></div><br />";
	return $badword->form;
	}
	function add(){
		$word = $_POST['aB'];
		if(!empty($word)) {
			$word = $_POST['aB'];
			$db = new db;
			$db->connect();
			$db->query("INSERT INTO `eb_badword` ( `bid` , `text` )
						VALUES (
						NULL , '$word'
						);");
			 return $this->idx();
		}
		elseif(empty($word)) {
			return $this->idx();
		}
	}
	function del()
	{
		$db = new db;
		$db->connect();
		$id = $_GET['id'];
		if($id) {
			$db->query("DELETE FROM eb_badword WHERE bid = '{$id}'");
		}
		return $this->idx();
	}
}
?>