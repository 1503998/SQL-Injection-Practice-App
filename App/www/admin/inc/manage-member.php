<?
/*
* Memeber Management
* Description: Add, Edit and Delete members
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
## If not defined : IN_ACP do PHP::DIE()
if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
## Define $template
$template = new template;
## Change title to "Evilboard Admin Control Panel - User Management :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - User Management :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
## Include file:: lib // functions
include("inc/lib/functions.php");
## Define $db && Connect
$db = new db;
$db->connect();
if ( isset($_GET['update'])){
		$r = manage::update();
		return $r;
}
switch($_GET['manage'])
{
	case 'add':
	return manage::frm_add();
	break;
	
	case 'edit':
	return manage::frm_edit();
	break;
	
	case 'edit_frm':
	$id = $_GET['id'];
	return manage::edit_frm($id);
	break;
	
	case 'del':
	return manage::del();
	break;
	
	default:
	return manage::frm_add();
	break;
}
class manage
{
	function frm_edit()
	{?>
		  <script type="text/javascript">
						function open_search() {
							var pop=window.open("inc/usr_src.php","","width=415,height=200")
						}
						function ins_usr(usr) {
							document.ins.usrn.value = '' + usr + '';
							//document.write("EL D");
						}
		</script>
						<?
						if ( isset($_POST['Submit']) && !empty($_POST['usrs'])) { 
							return manage::edit_frm();
							exit();
						}
						$this->top = "<div align=\"center\"><div style=\"width: 800px;\"><div align=\"left\"><a href=\"index.php?code=08&amp;manage=add\"><img src=\"Images/m-add.gif\" border=\"0\" alt=\"\"></a><a href=\"index.php?code=08&amp;manage=edit\"><img src=\"Images/m-edit.gif\" border=\"0\" alt=\"\"></a><a href=\"index.php?code=08&amp;manage=del\"><img src=\"Images/m-del.gif\" border=\"0\" alt=\"\"></a></div></div></div>";
		$this->frm .= "{$this->top}
						
<table width=\"800\" border=\"0\" align=\"center\" cellspacing=\"0\">
  <tr>
    <td class=\"a_inf\" style=\"padding-bottom: 10px;\"><form action=\"$PHP_SELF\" method=\"post\" name=\"ins\"><br class=\"eb_txt\">
<table width=\"600\" border=\"0\" cellspacing=\"0\" align=\"center\">
								<tr>
									  <td colspan=\"4\" class=\"eb_forum\"><div align=\"center\"><b>Edit Member:</b></div></td>
								</tr>
								<tr>
								  <td class=\"a_inf\" style=\"width: 100px; border-top-width: 0;border-right-width:0;\">Username:</td>
								  <td class=\"a_inf\" style=\"width: 300px; border-top-width: 0; border-right-width:0;\"><input type=\"text\" id=\"usrn\" name=\"usrs\" style=\"width: 300px; border: 1px solid #a6a6a6;\"></td>
								  <td class=\"a_inf\" style=\"width: 200px; border-top-width: 0;border-right-width:0; border-left-width: 0;\"><input type=\"submit\" name=\"Submit\" value=\"Edit Username\"></td>
								  <td class=\"a_inf\" style=\"width: 200px; border-top-width: 0;border-left-width: 0;\"><input type=\"button\" name=\"is\" value=\"Search after username\" onClick=\"javascript: open_search();\"></td>
								</tr>
							  </table>
							   </form> </td>
							  </tr>
							</table>";
		return $this->frm;
	}
	function frm_add() {
		if ( isset($_POST['Submit'])) {
			$this->usr = $_POST['usr'];
			$this->pw1 = $_POST['pw1'];
			$this->pw2 = $_POST['pw2'];
			$this->email = $_POST['email'];
			
			if ($this->pw1 !== $this->pw2) { $this->msg = "Passwords does not match"; }
			else {
			$this->md5pw1 = md5($this->pw1);
			$db = new db;
			$db->connect();
			$db->query("INSERT INTO eb_members (first_name, last_name, 
        email_address, username, password, info, signup_date, activated)
        VALUES('' , '' , '{$this->email}', 
        '$this->usr', '$this->md5pw1', '' , now(), '1');") or die("ERROR: " . mysql_error());
		$db->query("INSERT INTO `eb_profile` ( `name` , `logo` , `rank` , `email` , `msn` , `yahoo` , `icq` , `aim` , `location` , `website` , `intr` , `alias` , `age` , `mpad` , `hps` , `mouse` , `cpu` , `mboard` , `ram` , `monit` , `gpcard` , `id` )
		VALUES (
		'X', '', '4', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
		);
		") or die(mysql_error());
		$db->query("INSERT INTO `eb_psettings` ( `UserID` , `h_mail` , `s_pm` , `s_update` , `p_sig` , `p_avy` )
		VALUES (
		'', '1', '1', '1', '1', '1'
		)");
			$this->msg = "User added";
			
			}
		}
						$this->top = "<div align=\"center\"><div style=\"width: 800px;\"><div align=\"left\"><a href=\"index.php?code=08&amp;manage=add\"><img src=\"Images/m-add.gif\" border=\"0\" alt=\"\"></a><a href=\"index.php?code=08&amp;manage=edit\"><img src=\"Images/m-edit.gif\" border=\"0\" alt=\"\"></a><a href=\"index.php?code=08&amp;manage=del\"><img src=\"Images/m-del.gif\" border=\"0\" alt=\"\"></a></div></div></div>";
		$this->frm = "{$this->top}
						<table width=\"800\" border=\"0\" align=\"center\" cellspacing=\"0\">
					  <tr>
						<td class=\"a_inf\" style=\"padding-bottom: 10px;\">
						<form name=\"form1\" method=\"post\" action=\"\">
						<div align=\"center\"><strong id=\"b\">Add User <br><br></strong>
					  {$this->msg}
					  
						<table width=\"53%\"  border=\"0\" cellspacing=\"0\">
						  <tr>
							<td width=\"30%\" class=\"eb_txt style1\">Username:</td>
							<td width=\"70%\"><input name=\"usr\" type=\"text\" id=\"usr\" style=\"width: 305px\"></td>
						  </tr>
						  <tr>
							<td class=\"eb_txt style1\">Password:</td>
							<td><input name=\"pw1\" type=\"text\" id=\"pw1\" style=\"width: 305px\"></td>
						  </tr>
						  <tr>
							<td class=\"eb_txt style1\">Repeat Password:</td>
							<td><input name=\"pw2\" type=\"text\" id=\"pw2\"  style=\"width: 305px\"></td>
						  </tr>
						  <tr>
							<td class=\"eb_txt style1\">E-Mail:</td>
							<td><input name=\"email\" type=\"text\" id=\"email\" style=\"width: 305px\"></td>
						  </tr>
						  <tr>
							<td colspan=\"2\"><div align=\"center\">
							  <input type=\"submit\" name=\"Submit\" value=\"Add User\">
							</div></td>
							</tr>
						</table>
					 
					</div> </form></td></tr></table>";
		return $this->frm;
	}
	function rank(){
		$db = new db;
		$db->connect();
		$this->frm = "<select name=\"rank\">";
		$x = $db->query("SELECT * FROM eb_ranks ORDER BY `rname`");
		while($row = mysql_fetch_array($x)) {
			$this->frm .= "<option value=\"{$row[rid]}\">{$row[rname]}</option>";
		}
		$this->frm .= "</select>";
		return $this->frm;
	}
	function edit_frm(){
		$manage = new manage;
		$usr = $_POST['usrs'];
		$db = new db;
		$db->connect();
		if ( isset($_GET['update'])) {
		/* Start the submiting */
			 $usr = $_POST['usrs'];
			 $uid = $db->query("SELECT * FROM eb_members WHERE username = '$usr'");
			 $uid = mysql_fetch_array($uid);
			 $uid = $uid['userid'];
			 
			 $f_msn = $_POST['msn'];
			 $f_yahoo = $_POST['yahoo'];
			 $f_aim = $_POST['aim'];
			 $f_icq = $_POST['icq'];
			 $f_location = $_POST['location'];
			 $f_website = $_POST['website'];
			 $f_intr = $_POST['intr'];
			 $f_age = $_POST['age'];
			 $rank = $_POST['rank'];
		
		$db->query("UPDATE `eb_profile` SET `rank` = '$rank', `msn` = '$f_msn', `yahoo` = '$f_yahoo', `icq` = '$f_icq', `aim` = '$f_aim', `location` = '$f_location', `website` = '$f_website',
`intr` = '$f_intr', `age` = '$f_age' WHERE `id` = '{$uid}' LIMIT 1 ;");
		manage::frm_edit();
		exit();
		}
		$email = $db->query("SELECT * FROM eb_members WHERE username = '$usr'");
		$uidx = ebforum::un_id($usr);
		$email = mysql_fetch_array($email);
		$email = $email['email_address'];
	    $results = $db->query("SELECT * FROM `eb_profile` WHERE `id` = '{$uidx}'");
		while( $getinfo = mysql_fetch_object( $results ) )
		{
			$uid = $getinfo -> id;
			 $msn = $getinfo -> msn;
			 $yahoo = $getinfo -> yahoo;
			 $aim = $getinfo -> aim;
			 $icq = $getinfo -> icq;
			 $locat = $getinfo -> location;
			 $website = $getinfo -> website;
			 $intr = $getinfo -> intr;
			 $age = $getinfo -> age;
			 $this->rank = $manage->rank();
		 }
			
	$this->frm = "<form action=\"index.php?code=08&manage=edit&update=true\" method=\"post\" name=\"profile_edit\">
<table width=\"800\"  border=\"0\" cellspacing=\"0\" align=\"center\">
  <tr>
    <td class=\"eb_forum\">&nbsp;<strong>Profile Edit :: {$usr}</strong></td>
  </tr>
  <tr>
    <td class=\"eb_header\" style=\"border-top-width: 0;\"><div align=\"center\"><strong>Login Information</strong></div></td>
  </tr>
  <tr>
    <td class=\"forum_footer\"><table width=\"100%\"  border=\"0\" cellspacing=\"3\">
      <tr>
        <td width=\"34%\" class=\"eb_txt\"><div align=\"right\"><strong>Username:</strong></div></td>
        <td width=\"66%\"><input type=\"text\" name=\"un\" style=\"width: 100%; background: #cccccc;\" class=\"a_inf\" value=\"{$usr}\" disabled></td>
      </tr>
      <tr>
        <td class=\"eb_txt\"><div align=\"right\"><strong>Password:</strong></div></td>
        <td><input type=\"password\" name=\"pw1\" style=\"width: 100%;\" class=\"a_inf\"></td>
      </tr>
      <tr>
        <td class=\"eb_txt\"><div align=\"right\"><strong>Repeat Password: </strong></div></td>
        <td><input type=\"password\" name=\"pw2\" style=\"width: 100%;\" class=\"a_inf\"></td>
      </tr>
      <tr>
        <td class=\"eb_txt\"><div align=\"right\"><strong>Email:</strong></div></td>
        <td><input type=\"text\" name=\"email\" style=\"width: 100%; background: #cccccc;\" class=\"a_inf\" value=\"{$email}\" disabled></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class=\"eb_header\" style=\"border-top-width: 0;\"><div align=\"center\"><strong>General</strong></div></td>
  </tr>
  <tr>
    <td class=\"forum_footer\"><table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
      <tr>
        <td width=\"268\" valign=\"top\" class=\"eb_txt\"><div align=\"right\"><span style=\"font-weight: bold\">Website</span>:</div></td>
        <td width=\"521\" class=\"eb_txt\"><input name=\"website\" type=\"text\" class=\"a_inf\" style=\"width: 100%\" value=\"{$website}\"></td>
      </tr>
      <tr>
        <td valign=\"top\" class=\"eb_txt\"><div align=\"right\"><span style=\"font-weight: bold\">Your Age </span>:</div></td>
        <td class=\"eb_txt\"><input name=\"age\" type=\"text\" class=\"a_inf\" style=\"width: 100%\" value=\"{$age}\"></td>
      </tr>
      <tr>
        <td valign=\"top\" class=\"eb_txt\"><div align=\"right\"><span style=\"font-weight: bold\">Your Location</span>:</div></td>
        <td class=\"eb_txt\"><input name=\"location\" type=\"text\" class=\"a_inf\" style=\"width: 100%\" value=\"{$locat}\"></td>
      </tr>
      <tr>
        <td valign=\"top\" class=\"eb_txt\"><div align=\"right\"><span style=\"font-weight: bold\">Your interests</span>:</div></td>
        <td class=\"eb_txt\"><textarea name=\"intr\" id=\"csx\" class=\"a_inf\" style=\"width: 100%; height: 100px;\">{$intr}</textarea></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class=\"eb_header\" style=\"border-top-width: 0;\"><div align=\"center\"><span style=\"font-weight: bold\">All About
          {$usr}
    </span></div></td>
  </tr>
  <tr>
    <td class=\"forum_footer\"><table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
      <tr>
        <td valign=\"top\" class=\"eb_txt\"><div align=\"right\"></div>
            <div align=\"right\"><span style=\"font-weight: bold\">Your ICQ UIN</span>:</div></td>
        <td width=\"520\" class=\"eb_txt\"><input name=\"icq\" type=\"text\" class=\"a_inf\" style=\"width: 100%\" value=\"{$icq}\"></td>
      </tr>
      <tr>
        <td valign=\"top\" class=\"eb_txt\"><div align=\"right\"><span style=\"font-weight: bold\">Your AOL</span>:</div></td>
        <td class=\"eb_txt\"><input name=\"aim\" type=\"text\" class=\"a_inf\" style=\"width: 100%\" value=\"{$aim}\"></td>
      </tr>
      <tr>
        <td valign=\"top\" class=\"eb_txt\"><div align=\"right\"><span style=\"font-weight: bold\">Your MSN Messenger</span>:</div></td>
        <td class=\"eb_txt\"><input name=\"msn\" type=\"text\" class=\"a_inf\" style=\"width: 100%\" value=\"{$msn}\"></td>
      </tr>
      <tr>
        <td valign=\"top\" class=\"eb_txt\"><div align=\"right\"><span style=\"font-weight: bold\">Your Yahoo!</span>:</div></td>
        <td rowspan=\"2\" class=\"eb_txt\"><input name=\"yahoo\" type=\"text\" class=\"a_inf\" style=\"width: 100%\" value=\"{$yahoo}\"></td>
      </tr>
      <tr>
        <td rowspan=\"2\" valign=\"top\" class=\"eb_txt\">&nbsp;</td>
      </tr>
      <tr>
        <td class=\"eb_txt\"><input name=\"usr\" type=\"hidden\" value=\"{$usr}\"></td>
      </tr>
      <tr>
        <td class=\"eb_txt\"><div align=\"right\"><b>Rank</b>: </div></td>
		<td class=\"eb_txt\">{$this->rank}</td>
      </tr>
      <tr>
        <td colspan=\"2\" class=\"eb_txt\"><div align=\"center\"><input name=\"EDT\" type=\"submit\" class=\"a_inf\" value=\"Submit\" style=\"width: 100px;\"></div></td>
        </tr>
    </table></td>
  </tr>
</table>
<br></form>";
return $this->frm;
	}
	function update() {
		$usr = $_POST['usr'];
		$db = new db;
		$db->connect();
		/* Start the submiting */
			$uid = $db->query("SELECT * FROM eb_members WHERE username = '$usr'");
			$uid = mysql_fetch_array($uid);
			$uid = $uid['userid'];
			
			$f_msn = $_POST['msn'];
			$f_yahoo = $_POST['yahoo'];
			$f_aim = $_POST['aim'];
			$f_icq = $_POST['icq'];
			$f_location = $_POST['location'];
			$f_website = $_POST['website'];
			$f_intr = $_POST['intr'];
			$f_age = $_POST['age'];
			#$uidx = ebforum::un_id($usr);
			/*if ( !empty($_POST['pw1']) && !empty($_POST['pw2'])){
				if ( $_POST['pw1'] == $_POST['pw2'] ) {
				#$db->query("UPDDATE `eb_members` SET `password` = '{$pw}' WHERE `userid` = '{$uidx}' LIMIT 1;");
				}
				else
				{
				/* Do nothing 
				}
			}*/
		$db->query("UPDATE `eb_profile` SET `msn` = '$f_msn', `yahoo` = '$f_yahoo', `icq` = '$f_icq', `aim` = '$f_aim', `location` = '$f_location', `website` = '$f_website',
`intr` = '$f_intr', `age` = '$f_age' WHERE `id` = '{$uid}' LIMIT 1 ;") or die("mysql error: " . mysql_error());
		return template::shortbox("Information", "Users settings has been modified<br><a href=\"index.php?code=08\">Click here</a> to return to member management");
	}
	function del()
	{
	?>
		  <script type="text/javascript">
						function open_search() {
							var pop=window.open("inc/usr_src.php","","width=415,height=200")
						}
						function ins_usr(usr) {
							document.ins.usrn.value = '' + usr + '';
							//document.write("EL D");
						}
		</script>
						<?
						if ( isset($_POST['Submit'])) {
							$db = new db;
							$this->usrn = $_POST['usrs'];
							$this->uid = $db->query("SELECT * FROM eb_members WHERE `username` = '{$this->usrn}'");
							$this->uid = mysql_fetch_array($this->uid);
							$this->uid = $this->uid['userid'];
							$db->query("DELETE FROM eb_members WHERE `username` = '{$this->usrn}'");
							$db->query("DELETE FROM eb_profiles WHERE `id` = '{$this->uid}'");
							$template = new template;
							return $template->shortbox("Information","User has been deleted");
						}
						$this->top = "<div align=\"center\"><div style=\"width: 800px;\"><div align=\"left\"><a href=\"index.php?code=08&amp;manage=add\"><img src=\"Images/m-add.gif\" border=\"0\" alt=\"\"></a><a href=\"index.php?code=08&amp;manage=edit\"><img src=\"Images/m-edit.gif\" border=\"0\" alt=\"\"></a><a href=\"index.php?code=08&amp;manage=del\"><img src=\"Images/m-del.gif\" border=\"0\" alt=\"\"></a></div></div></div>";
		$this->frm .= "{$this->top}
						
<table width=\"800\" border=\"0\" align=\"center\" cellspacing=\"0\">
  <tr>
    <td class=\"a_inf\" style=\"padding-bottom: 10px;\"><form action=\"$PHP_SELF\" method=\"post\" name=\"ins\"><br>
<table width=\"600\" border=\"0\" cellspacing=\"0\" align=\"center\">
								<tr>
									  <td colspan=\"4\" class=\"eb_forum\"><div align=\"center\"><b>Delete Member:</b></div></td>
								</tr>
								<tr>
								  <td class=\"a_inf\" style=\"width: 100px; border-top-width: 0;border-right-width:0;\">Username:</td>
								  <td class=\"a_inf\" style=\"width: 300px; border-top-width: 0; border-right-width:0;\"><input type=\"text\" id=\"usrn\" name=\"usrs\" style=\"width: 300px; border: 1px solid #a6a6a6;\"></td>
								  <td class=\"a_inf\" style=\"width: 200px; border-top-width: 0;border-right-width:0; border-left-width: 0;\"><input type=\"submit\" name=\"Submit\" value=\"Delete Username\"></td>
								  <td class=\"a_inf\" style=\"width: 200px; border-top-width: 0;border-left-width: 0;\"><input type=\"button\" name=\"is\" value=\"Search after username\" onClick=\"javascript: open_search();\"></td>
								</tr>
							  </table>
							   </form> </td>
							  </tr>
							</table>";
		return $this->frm;
	}
}
?>