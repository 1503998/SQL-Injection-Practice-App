<? 
/*
* EvilBoard Admin Panel
* Description: Core - Admin Control Panel for Admins (User Level 3)
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL License  - 2006
*********** License *************
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
* 
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
* http://www.gnu.org/licenses/lgpl.html
********* End of License **********
*/
session_start();
if(!$_SESSION['user_level'] || $_SESSION['user_level'] !== "3" ) { header("Location: ../index.php"); }
if (!$_GET) { header("Location: index.php?act=idx"); }
global $db,$template;
$template = new template;
define("IN_ACP","IN_ACP");
switch($_GET['code']) {
	case '02':
	$template->inc("preview.php");
	break;
	
	case '03':
	$template->inc("conf.php");
	break;
	
	case '19':
	$template->inc("smileys.php");
	break;
	
	case '24':
	$template->inc("m-forum.php",'inc/',TRUE);
	break;
	
	case '47':
	$template->inc("ban_control.php",'inc/',TRUE);
	break;
	
	case '12':
	$template->inc("backup.php",'inc/',FALSE);
	break;
	
	case '66':
	$template->inc("import-sql.php");
	break;
	
	case '91':
	$template->inc("ranks.php",'inc/', TRUE);
	break;
	
	case '61':
	$template->inc("badword.php");
	break;
	
	case '08':
	$template->inc("manage-member.php", 'inc/', TRUE);
	break;
	
	
	case '52':
	$template->inc("grp_admin.php",'inc/', TRUE);
	break;
	
	case '81':
	$template->inc("user-permission.php");
	break;
	
	default:
	$template->top("EvilBoard Admin Control Panel - Index :: Powered by EvilBoard");
	index();
	break;
}
class admin
{
	function posts()
	{
		$db = new db;
		$db->connect();
		$this->posts = $db->query("SELECT * FROM eb_post");
		$this->count_post = mysql_num_rows( $this->posts );
		return $this->count_post;
	}
	function topics()
	{
		$db = new db;
		$db->connect();
		$this->topic = $db->query("SELECT * FROM eb_topic");
		$this->count_topic = mysql_num_rows( $this->topic );
		return $this->count_topic;
	}
	function dbsize()
	{
		$sql = "SHOW TABLE STATUS";
		$result = mysql_query($sql); 
		while($row = mysql_fetch_array($result))
		{
			$dbsize = $row['Data_length']+$row['Index_length'];
		}
		$dbsize = round($dbsize/1024, 2); 
		return "{$dbsize} kB"; 
	} 
	function ruser()
	{
		$db = new db;
		$db->connect();
		$this->mem = $db->query("SELECT * FROM eb_members");
		$this->mem = mysql_num_rows( $this->mem );
		return $this->mem;
	}
	function avg_post()
	{
		$db = new db;
		$db->connect();
		$this->avg = $db->query("SELECT * FROM eb_posts ORDER BY `PostID` ASC");
		$row = mysql_fetch_row($this->avg);
		$num2 = $row[0];
		return $num2;
	}
	function average($a){
	  return array_sum($a)/count($a) ;
	}
	function version () {
	$db = new db;
	$db->connect();
	$ver = $db->query("SELECT * FROM eb_settings LIMIT 0,1;");
	$ver = mysql_fetch_array( $ver );
	$this->ver = $ver['eb_version'];
	return "<iframe src=\"http://gladz-cs.clanservers.com/evilboard/ver_check.php?ver={$this->ver}\" scrolling=\"no\" frameborder=\"0\" width=\"120\" height=\"70\"></iframe>";
	
	}
	function uname_id($uid)
	{
		$db = new db;
		$db->connect();
		$un = $db->query("SELECT * FROM eb_members WHERE username = '{$uid}'");
		$un = mysql_fetch_array($un);
		$un = $un['userid'];
		return $un;
	}
	function rank($rank)
	{
		$db = new db;
		$db->connect();
		$v_rank = $db->query("SELECT * FROM eb_ranks WHERE rid = '{$rank}'");
		$v_rank = mysql_fetch_array($v_rank);
		$v_rank = $v_rank['rname'];
		return $v_rank;
	}
}
//==================
## Class: Template // Templates
//===================
class template
{
	function top($title)
	{
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
\"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<title>{$title}</title>
<script language=\"javascript\" type=\"text/javascript\" src=\"eb_dropdown.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../Themes/Default/styles.css\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"admin.css\">
</head>";
	}
	function _header()
	{
	echo '<div align="center"><div class="a_logo" align="center"><div align="center"><img src="Images/logo.gif" /></div></div></div>
			<br class="eb_txt">
			<div align="center">
			  <div class=toolbar><a class=menu href="index.php?act=idx" id="eb_gen">&nbsp;General</a><a class=menu href="index.php" style="border-right-width: 0; border-left-width: 0;" id="eb_fa">&nbsp;Forum Admin </a><a class=menu href="index.php" id="eb_gena">&nbsp;General Admin</a><a class=menu href="index.php" style="border-right-width: 0; border-left-width: 0;" id="eb_grp">&nbsp;Group Admin </a><a class=menu href="index.php" id="eb_tpl">&nbsp;Template Admin</a><a class=menu href="index.php"  style="border-left-width: 0;" id="eb_usr">&nbsp;User Admin </a></div>
			</div>
			<!-- GENERAL -->
			<div id="eb_gen_ch">
			<div class="a_item"><a href="index.php?">Admin Index</a></div>
			<div class="a_item" style="border-top-width: 0;"><a href="../index.php">Forum Index</a></div>
			<div class="a_item" style="border-top-width: 0;"><a href="index.php?code=02">Forum Preview</a></div>
			</div>
			<!-- END GENERAL && START Forum Admin-->
			<div id="eb_fa_ch"><div class="a_item"><a href="index.php?code=24">Manage forums</a></div>
			<div class="a_item" style="border-top-width: 0; color: #000000;"><i>Permissions</i></div></div>
			<!-- END FORUM ADMIN && START GENERAL ADMIN -->
			<div id="eb_gena_ch">
			<div class="a_item" style="width: 110px;"><a href="index.php?code=03">Configuration</a></div>
			<div class="a_item" style="width: 110px;"><a href="index.php?code=12">Backup </a></div>
			<div class="a_item" style="width: 110px;"><a href="index.php?code=19">Smilies </a></div>
			<div class="a_item" style="width: 110px;"><a href="index.php?code=66">Restore Backup </a></div><div class="a_item" style="width: 110px;"><a href="index.php?code=61">Bad word filter</a></div> </i></div></div>
			<!-- END GENERAL ADMIN && START GROUP ADMIN -->
			<div id="eb_grp_ch">
			<div class="a_item"><a href="index.php?code=52">Manage groups</a></div>
			<div class="a_item" style="color: #000000;"><I>Permissions</i></div></div>
			<!-- END GROUP ADMIN && START TEMPLATE ADMIN -->
			<div id="eb_tpl_ch">
			<div class="a_item" style="width: 110px;color: #000000;"><I>Create</i></div>
			<div class="a_item" style="width: 110px;color: #000000;"><I>Edit</i></div>
			<div class="a_item" style="width: 110px;color: #000000;"><I>Export Theme</i></div>
			<div class="a_item" style="width: 110px;color: #000000;"><I>Manage Templates</i></div></div>
			<!-- END TEMPLATE ADMIN && START USER ADMIN -->
			<div id="eb_usr_ch">
			<div class="a_item" style="width: 110px;"><a href="index.php?code=47">Ban Control</a></div>
			<div class="a_item" style="width: 110px;"><a href="index.php?code=08">Management</a></div>
			<div class="a_item" style="width: 110px;"><a href="index.php?code=91">Ranks</a></div>
			<div class="a_item" style="width: 110px;"><a href="index.php?code=81">Permissions</a></div></div>
			<!-- END USER ADMIN --><script type="text/javascript">
			at_attach("eb_gen", "eb_gen_ch", "hover", "y", "pointer");
			at_attach("eb_fa", "eb_fa_ch", "hover", "y", "pointer");
			at_attach("eb_gena", "eb_gena_ch", "hover", "y", "pointer");
			at_attach("eb_grp", "eb_grp_ch", "hover", "y", "pointer");
			at_attach("eb_tpl", "eb_tpl_ch", "hover", "y", "pointer");
			at_attach("eb_usr", "eb_usr_ch", "hover", "y", "pointer");
			</script><br class="eb_txt">';
	}
	
	function inc ($file,$folder="inc/",$nf = FALSE)
	{
		$file = include($folder . $file);
		if ( $nf == TRUE ) { $x = "{$file}"; }
		elseif ($nf == FALSE) {
		$x = "<div align=\"center\"><div class=\"a_inf\" align=\"left\">{$file}</div></div>";
		}
		$x .= "<br class=\"eb_txt\" /><div align=\"center\"><div style=\"width: 800px; height: 20px;\" class=\"eb_header\"><span style=\"font-size: 3px;\"><br /></span>EvilBoard Admin Panel Version: 0.1a &copy; EvilBoard 2006</div></div>";
		echo $x;
	}
	function shortbox ($title,$text,$center = TRUE)
	{
	if ( $center == TRUE ) { $title = "<div align=\"center\"><strong>{$title}</strong></div>"; }
	elseif ( $center == FALSE ) { $title = "&nbsp;<strong>{$title}</strong>"; }
	return "<table width=\"800px\"  border=\"0\" cellspacing=\"0\" align=\"center\">
			  <tr>
				<td class=\"eb_forum\">{$title}</td>
			  </tr>
			  <tr>
				<td class=\"forum_footer\"><div align=\"center\">{$text}</div></td>
			  </tr>
			</table>";
	}
}
//==================
## Class: DB // Database Connection
//==================
	class db
	{
		function connect()
			{
				include("../config.php");
				mysql_connect($eb_server,$eb_user,$eb_password);
				mysql_select_db($eb_db);
			}
		function query($query) 
			{
				$db_connected = mysql_query($query);
				return $db_connected;
			}
	}
function index()
{
	$acp = new admin;
	include("inc/index.php");
}
?>