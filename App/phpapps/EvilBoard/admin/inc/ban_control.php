<?
/*
* Manage Forum
* Description: Manage forums and categorys
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
## If not defined : IN_ACP do PHP::DIE()
if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
## Define $template
$template = new template;
## Change title to "Evilboard Admin Control Panel - Ban Control :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Ban Control :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
## Define $db && Connect
$db = new db;
$db->connect();
$ban = new ban;
switch($_GET['ban']) {
	case 'add':
	$r = $ban->add();
	$r .= $ban->tpl();
	return $r;
	break;
	
	case 'delete':
	$r = $ban->delete();
	$r .= $ban->tpl();
	return $r;
	break;
	
	default:
	return $ban->tpl();
	break;
}

class ban {
	var $template;
	function tpl()
	{
	$db = new db;
	$db->connect();
	$q = $db->query("SELECT * FROM eb_members WHERE user_level = '1'");
	$num_r = mysql_num_rows($q);
	$template = "<table width=\"800\" border=\"0\" align=\"center\" cellspacing=\"0\">
				  <tr>
					<td class=\"a_inf\" style=\"padding-bottom: 10px;\"><div align=\"center\"><strong id=\"b\">Ban Control <br>
					  <br>
					</strong>
					   </div> 
					 <table width=\"500\" border=\"0\" align=\"center\" cellspacing=\"0\">
						 <tr>
						   <td colspan=\"2\" class=\"eb_forum\" style=\"width: 500px;\"><div align=\"center\"><strong>Ban Control </strong></div></td>
						 </tr>
						 <tr>
						   <td class=\"a_inf\" style=\"width: 200px; border-top-width: 0;\">Ban member: </td>
						   <td class=\"a_inf\" style=\"width: 300px; border-top-width: 0; border-left-width:0; padding-left: 5px;\"><form action=\"index.php?code=47&ban=add\" method=\"post\"><input type=\"text\" name=\"aB\" style=\"width: 250px; border: 1px solid #a6a6a6;\" border=\"0\" onclick=\"javascript: this.value='';\" value=\"Username to ban...\"><input type=\"submit\" name=\"AddB\" value=\"Ban\"></form></td>
						 </tr>
						 <tr>
						   <td colspan=\"2\" class=\"a_inf\" style=\"width: 500px; border-top-width: 0;\"><form name=\"form1\" method=\"post\" action=\"index.php?code=47&ban=delete\">
							 <div align=\"center\">
							   <br>
							   <select name=\"menu1\" size=\"10\" style=\"width: 250px;\">
								";
									if ( $num_r == "0") { $template .= "<option>No users banned.</option>"; }
									elseif( $num_r !== "0" ) {
										while($banct = mysql_fetch_object($q)) {
										$un = $banct->username;
											$template .= "<option>{$un}</option>";
										}
									}
							$template .= "  </select>
							   <br>
							   <br>
							   <input type=\"submit\" name=\"Submit\" value=\"Remove Ban\">
							 </div>
						   </form></td>
						 </tr>
					   </table>
				</td>
				  </tr>
				</table>";
				return $template;
	}
	function add()
	{
		$b_id = $_POST['aB'];
		if (empty($b_id)) { return "Error, Missing field :: Add Ban Username or invalid username"; exit(); };
		$db = new db;
		$db->connect();
		$add->q = $db->query("SELECT * FROM eb_members WHERE `username` = '{$b_id}'");
		$num_rows = mysql_num_rows($add->q);
		if ( $num_rows < "1" ) { return "Error, cannot find this username, check if you have written a letter wrong or something."; exit(); }
		else { $db->query("UPDATE eb_members SET user_level = '1' WHERE username = '$b_id'"); }
	}
	function delete()
	{
		$b_id = $_POST['menu1'];
		$db = new db;
		$db->connect();
		$add->q = $db->query("SELECT * FROM eb_members WHERE `username` = '{$b_id}' AND `user_level` = '1'");
		$num_rows = mysql_num_rows($add->q);
		if ( $num_rows < "1" ) { return "Error, cannot find this username, check if you have written a letter wrong or something."; exit(); }
		else { $db->query("UPDATE eb_members SET user_level = '0' WHERE username = '$b_id'"); }
	}
}