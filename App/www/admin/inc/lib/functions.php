<?
class ebforum
{
	var $uid;
	var $usr;
	function id_un($uid) {
		$db = new db;
		$uid = $db->query("SELECT * FROM eb_members WHERE userid = '{$uid}'");
		$uid = $uid['username'];
		return $uid;
	}
	function un_id($usr) {
		$db = new db;
		$usr = $db->query("SELECT * FROM eb_members WHERE username = '{$usr}'");
		$usr = mysql_fetch_array($usr);
		$usr = $usr['userid'];
		return $usr;
	}
}
?>