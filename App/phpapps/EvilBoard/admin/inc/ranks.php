<?
/*
* Ranks
* Description: Add, Edit and Delete ranks
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
## If not defined : IN_ACP do PHP::DIE()
if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
## Define $template
$template = new template;
## Change title to "Evilboard Admin Control Panel - User Management :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Ranks Administrator :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
## Define $db && Connect
$db = new db;
$db->connect();
$ranks = new ranks;
switch($_GET['do']) {
	case 'create':
	return $ranks->create();
	break;
	
	case 'edit':
	return $ranks->edit();
	break;
	
	case 'delete':
	return $ranks->delete();
	break;
	
	default:
	return $ranks->idx();
	break;
}
class ranks
{
	function idx()
	{
	$db = new db;
	$db->connect();
	$ranks = $db->query("SELECT * FROM eb_ranks ORDER by `rname`");
		$this->idx = "<table width=\"800\" border=\"0\" align=\"center\" cellspacing=\"0\">
		  <tr>
			<td class=\"a_inf\" style=\"padding-bottom: 10px;\">&nbsp;<span id=\"b\">Rank Administrator</span><br>&nbsp;If you use this form you can edit, view, create and delete ranks.<br />
<br>
			  <table width=\"398\" border=\"0\" align=\"center\" cellspacing=\"0\">
				<tr>
				  <td colspan=\"3\" class=\"eb_forum\"><div align=\"center\"><b>Ranks:</b></div></td>
				</tr>";
	while($row = mysql_fetch_array($ranks)) {
		$this->idx .= "<tr>
				  <td width=\"308\" class=\"a_ranks\">{$row[rname]} </td>
				  <td width=\"33\" class=\"a_ranks\" style=\"border-left-width: 0; padding-right: 0px;\"><a href=\"index.php?code=91&do=edit&id={$row[rid]}\">Edit</a></td>
				  <td width=\"51\" class=\"a_ranks\" style=\"border-left-width: 0;\"><a href=\"index.php?code=91&do=delete&id={$row[rid]}\">Delete</a></td>
				</tr>";
		}
	$this->idx .= "				<tr>
				  <td colspan=\"3\" class=\"style1\"><div class=\"eb_txt\" align=\"right\">&nbsp;<a href=\"index.php?code=91&do=create\">Create new&nbsp;</a></div> </td>
				</tr>
			  </table></td>
		  </tr>
		</table>";
	return $this->idx;	
	}
	function create()
	{
	if(isset($_POST['Submit'])) { 
	$db = new db;
	$db->connect();
	$rname = $_POST['textfield'];
	$db->query("INSERT INTO `eb_ranks` ( `rid` , `rname` ) VALUES ( NULL , '{$rname}' ); ");
	$template = new template;
	$this->ret = $template->shortbox("Information","Rank Created<br><a href=\"index.php?code=91\">Return back</a>");
	return $this->ret;
	exit();
	}
	$this->create = "<table width=\"800\" border=\"0\" align=\"center\" cellspacing=\"0\">
  <tr>
    <td class=\"a_inf\"><span id=\"b\">&nbsp;Create Rank</span><br />
    &nbsp;Fill in these fields and click create rank, to create a rank.<br />
    <br />
    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"\">
      <table width=\"350\" border=\"0\" align=\"center\" cellspacing=\"0\">
        <tr>
          <td width=\"95\" class=\"eb_txt\"><div class=\"style1\">Rank name:</div> </td>
          <td width=\"251\"><input name=\"textfield\" type=\"text\" class=\"a_ranks\" style=\"width: 100%; padding: 0 0 0 0;\" /></td>
        </tr>
        <tr>
          <td colspan=\"2\"><div align=\"center\">
            <input name=\"Submit\" type=\"submit\" style=\"padding: 0 0 0 0;\" class=\"a_ranks\" value=\"Create Rank\" align=\"middle\" />
          </div></td>
        </tr>
      </table>
        </form>    <br />
</td>
  </tr>
</table>";
	return $this->create;
	}
	function delete()
	{
		$db = new db;
		$db->connect();
		$rid = $_GET['id'];
		$db->query("DELETE FROM eb_ranks WHERE rid = '{$rid}'");
		$template = new template;
		$this->del = $template->shortbox("Information","Rank deleted<br><a href=\"index.php?code=91\">Return</a>");
		return $this->del;
	}
	function edit()
	{
	$db = new db;
	$db->connect();
	$rid = $_GET['id'];
	if(isset($_POST['Submit'])) {
	$rname = $_POST['textfield'];
	$db->query("UPDATE `eb_ranks` SET `rname` = '{$rname}' WHERE `rid` = '{$rid}' LIMIT 1 ;");
	$template = new template;
	$this->edit = $template->shortbox("Information","Member rank has been correctly edited<br><a href=\"index.php?code=91\">Return</a>");
	return $this->edit;
	exit();
	}
	$check = $db->query("SELECT * FROM eb_ranks WHERE rid = '{$rid}'");
	$check = mysql_fetch_array($check);
	$check = $check['rname'];
		$this->edit = "<table width=\"800\" border=\"0\" align=\"center\" cellspacing=\"0\">
  <tr>
    <td class=\"a_inf\"><span id=\"b\">&nbsp;Create Rank</span><br />
    &nbsp;Fill in these fields and click edit rank, to edit the rank.<br />
    <br />
    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"\">
      <table width=\"350\" border=\"0\" align=\"center\" cellspacing=\"0\">
        <tr>
          <td width=\"95\" class=\"eb_txt\"><div class=\"style1\">Rank name:</div> </td>
          <td width=\"251\"><input name=\"textfield\" type=\"text\" class=\"a_ranks\" style=\"width: 100%; padding: 0 0 0 0;\" value=\"{$check}\" /></td>
        </tr>
        <tr>
          <td colspan=\"2\"><div align=\"center\">
            <input name=\"Submit\" type=\"submit\" style=\"padding: 0 0 0 0;\" class=\"a_ranks\" value=\"Edit rank\" align=\"middle\" />
          </div></td>
        </tr>
      </table>
        </form>    <br />
</td>
  </tr>
</table>";
	return $this->edit;
	}
}
?>