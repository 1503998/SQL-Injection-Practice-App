<?
/*
* User Searcher
* Description: Searching for members of forum
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL 2006
*/
#if (!defined(IN_ACP)) { die("Error, you may not include this file, or visit it if your not admin"); }
$db = new db;
$db->connect();
if ( isset($_POST['Submit']) ) {
$wd = $_POST['wd'];
/* Do a query to look after the members */
$nwd = str_replace(" ", "%", $wd);
$connect_db = $db->query("SELECT * FROM eb_members WHERE `username` LIKE '%$nwd%'");
$USR->resultselect = "<span class=\"eb_txt\">Select user from this dropdown menu: <select class=\"a_inf\" style=\"width: 200px\" id=\"usrn\">";
	while( $row = mysql_fetch_array($connect_db)) {
		$USR->resultselect .= "<option value=\"{$row[username]}\">{$row[username]}</option>";
	}
$USR->resultselect .= "</select></span>";
$USR->submit = '<input name="Insert Username" type="submit" class="a_inf" style="width: 100px;" OnClick="javascript: insert_usr();">';
 }
	class db
	{
		function connect()
			{
				include("../../config.php");
				mysql_connect($eb_server,$eb_user,$eb_password);
				mysql_select_db($eb_db);
			}
		function query($query) 
			{
				$db_connected = mysql_query($query);
				return $db_connected;
			}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript" type="text/javascript">
/* Insert Username from this form */
function insert_usr() {
	var username = document.getElementById("usrn").value;
	window.opener.ins_usr(username);
	window.close();
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>EvilBoard :: User Search</title>
<link rel="stylesheet" type="text/css" href="../../Themes/Default/styles.css">
<link rel="stylesheet" type="text/css" href="../admin.css">
</head>
<body><form action="" method="post">
<table width="400"  border="0" cellspacing="0">
  <tr>
    <td class="eb_forum"><div align="center"><strong>Find member </strong></div></td>
  </tr>
  <tr>
    <td class="forum_footer" style="height: 20px;"><p>Use this form to find an username your looking for, you do not need to insert the users full name, just a part is good enouth. </p>
      <table width="100%"  border="0" cellspacing="0">
        <tr>
          <td class="eb_txt">Name: 
          <input name="wd" type="text"class="a_inf" id="wd" style="width: 350px;"></td>
        </tr>
        <tr>
          <td><div align="center">
            <input type="submit" name="Submit" value="Find!" class="a_inf" style="width: 50px;" >
          </div></td>
        </tr>
        <tr>
          <td><div align="center"><? echo "{$USR->resultselect}"; ?></div></td>
        </tr>
        <tr>
          <td><div align="center"><?=$USR->submit?></div></td>
        </tr>
      </table> 
  </tr>
</table></form>