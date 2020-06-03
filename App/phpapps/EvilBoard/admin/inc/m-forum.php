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
## Change title to "Evilboard Admin Control Panel - Forum Management :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Forum Management :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
## If we use $_GET do not display default function :)
switch($_GET['act']) {
	case '2': // Add Category
		return m_cat();
	break;
	case '81':
		return m_nf(); // Add Forum
	break;
	case '16': // Delete {Forum}
		return m_del();
	break;
	case '12': // Delete {Category}
		return m_del(TRUE);
	break;
	case '19': // Edit {Forum}
		return m_ef();
	break;
	case '18': // Edit {Category}
		return m_ec();
	break;
	default:
		return m_index();
	break;
}
## Function m_index // Forum Management Index
function m_index()
{
$f = '
<table width="800"  border="0" cellspacing="0" align="center">
  <tr>
    <td colspan="4" class="eb_forum"><div align="center"><strong>Forum Management </strong></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="eb_forum_header" style="border-top-width: 0;">&nbsp;Forum // Category:</td>
    <td width="11%" class="eb_forum_header" style="border-top-width: 0;border-left-width: 0; border-right-width: 0;" height="20"><div align="center">&nbsp;</div></td>
    <td width="13%" class="eb_forum_header" style="border-top-width: 0;"><div align="center" height="20">&nbsp;</div></td>
  </tr>	
';
$db = new db;
$db->connect();
	// include our much-needed database info.
	
	// selects all the rows from the "categories" table, then orders them by id, ascending.
	$result = mysql_query("select * from eb_category order by id asc") or die(mysql_error());
	   // a while loop to repeat code until all our rows have been echo'd out..
	   while($r = mysql_fetch_array($result))
	   {
		  // redefine our category row variables.
		  $category_id = $r['id'];
		  $category_title = $r['category'];
		  $category_description = $r['description'];
	$f .= '
 <tr>
		<td height="30" colspan="2" class="a_item" style="color: #0099CC; height: 5px;">&nbsp; ' . $category_title . '</td>
		<td class="a_item" style="border-left-width: 0; border-right-width: 0; height: 5px;"><div align="center"><a href="index.php?code=24&act=12&id=' . $category_id . '">Delete</a></div>
		<td class="a_item" style=" height: 5px;"><div align="center"><a href="index.php?code=24&act=18&id=' . $category_id . '">Edit</a></div></td>
	  </tr>
	';
		 // selects our forums if the "cid" is the same as our $category_id.
		 $result2 = mysql_query("select * from eb_forums where cid = '$category_id'");
		 
		 // the while loop to echo out our corresponding forums
		 while($r2 = mysql_fetch_array($result2))
		 {
			// redefine our forum row variables.
			$forum_id = $r2['id'];
			$forum_title = $r2['title'];
			$forum_description = $r2['description'];
			$forum_last_post_title = $r2['last_post_title'];
			$forum_last_post_username = $r2['last_post_username'];
					// Get Rows 
			$query = "SELECT * FROM `eb_topic` WHERE `ForumID` = '$forum_id'";
			$connect = mysql_query($query);
			$num_topic_rows = mysql_num_rows($connect);
			// End Get Rows
			// Get Rows 
			$query2 = "SELECT * FROM `eb_post` WHERE `ForumID` = '$forum_id'";
			$connect2 = mysql_query($query2);
			$num_replay_rows = mysql_num_rows($connect2);
	$f .= '  <tr>
		<td width="4%" class="forum_footer" style="border-right-width:0;"><div align="center"><img src="Images/cat.gif" width="20" height="20"></div></td>
		<td width="72%" class="forum_footer" style="border-left-width:0;">
		<a href="../index.php?act=showforum&f=' . $forum_id . '">&nbsp;' . $forum_title . '</a><br />
		&nbsp;' . $forum_description .'
		</td>
		<td width="11%"  class="forum_footer" style="border-left-width: 0; border-right-width: 0;"><div align="center"><a href="index.php?code=24&act=16&id=' . $forum_id . '">Delete</a></div>
	   </td>
		<td width="13%"  class="forum_footer"><div align="center"><a href="index.php?code=24&act=19&id=' . $forum_id . '">Edit</a></div></td>
	  </tr>
	 ';
		  } // end while loop
	} // end while loop
	$f .= "</table><br class='eb_txt'><div align='center'><div style=\"width: 800px;\"><div align='right'><a href=\"index.php?code=24&act=2\"><img src=\"Images/new-category.gif\" border='0'></a>&nbsp;<a href=\"index.php?code=24&act=81\"><img src=\"Images/new-forum.gif\" border='0'></a></div></div></div>";
	
	return $f;
}
## Function m_index // End
## Function m_cat // Add Categorys
function m_cat()
{
if( isset($_POST['Submit'])) {
	$db = new db;
	$template = new template;
	$db->connect();
	$db->query("INSERT INTO `eb_category` ( `id` , `category` )
	VALUES (
	NULL , '{$_POST[textfield]}'
	);");
	$template->short = $template->shortbox("Category Added" , "Category has been added to forum database, Click <a href='index.php?code=24'>Here</a> To get back to Forum Management.");
	return "{$template->short}";
	exit();
}
return '<table width="800px"  border="0" cellspacing="0" align="center">
  <tr>
    <td class="eb_forum"><div align="center"><strong>Create Category</strong></div></td>
  </tr>
  <tr>
    <td class="forum_footer"><form name="form1" method="post" action="' . $PHP_SELF . '">
      <table width="100%"  border="0" cellspacing="0">
        <tr>
          <td width="50%" class="eb_txt"><div align="right">Category name:</div></td>
          <td width="50%"><input name="textfield" type="text" class="eb_header"></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center"><input name="Submit" type="submit" class="eb_menu1" value="Submit"></div></td>
          </tr>
      </table>
        </form></td>
  </tr>
</table>';
}
## Function m_cat // End
## Function m_nf // New Forum
function m_nf()
	{
	$db = new db;
	$db->connect();
	$template = new template;
	if(isset($_POST['addforum']) && !empty($_POST['title']))
	{
	   $title = $_POST['title'];
	   $description = $_POST['description'];
	   $cid = $_POST['cid'];
	   $desc = $_POST['desc'];
	
	   $db->query("insert into eb_forums (id, cid, title, description)
		  values('null', '$cid', '$title', '$desc')");
			$sh = $template->shortbox("Forum Added","Your forum have succesfully been added. Click <a href=\"index.php?code=24\">Here</a> To return to forum management.");
			return "{$sh}";
	}
	$result = $db->query("select * from eb_category order by id asc");
	if(mysql_num_rows($result) < 1)
	{
		$sh = $template->shortbox("Error:","You need to add categories first.");
	   return "{$sh}";
	}
	else
	{
	$ret = "<form action=\"\" method=\"post\">
	<table width=\"800\"  border=\"0\" cellspacing=\"0\" align=\"center\">
	  <tr>
		<td class=\"eb_forum\"><div align=\"center\"><strong>Add Forum: </strong></div></td>
	  </tr>
	  <tr>
		<td class=\"forum_footer\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\">
		  <tr>
			<td width=\"49%\" class=\"eb_txt\"><div align=\"right\">Forum Title:<br />
			</div></td>
			<td width=\"51%\"><input name=\"title\" type=\"text\" class=\"eb_header\" style=\"width: 200px;\"/></td>
		  </tr>
		  		  <tr>
			<td width=\"49%\" class=\"eb_txt\"><div align=\"right\">Description:<br />
			</div></td>
			<td width=\"51%\"><input name=\"desc\" type=\"text\" class=\"eb_header\" style=\"width: 200px;\"/></td>
		  </tr>
		  <tr>
			<td class=\"eb_txt\"><div align=\"right\">Category:</div></td>
			<td><select name=\"cid\" class=\"eb_header\" style=\"width: 200px;\">";
	   while($r = mysql_fetch_array($result))
	   {
		  $ret .= "<option value=\"". $r['id'] ."\">". $r['category'] ."</option> \n";
	   }
	$ret .= "</select></td>
				  </tr>
				  <tr>
					<td colspan=\"2\"><div align=\"center\">
					  <input name=\"addforum\" type=\"submit\" class=\"eb_header\" value=\"Add Category\" />
					</div></td>
					</tr>
				</table></td>
			  </tr>
			</table>
			<br />
			</form>";
	return $ret;
	}
}
## Function m_nf // End
## Function m_del // Delete Forum/Catecory
function m_del($d_cat = FALSE) {
	if ( $d_cat == FALSE ) { $w = "Forum"; $n = "16"; }
	elseif ( $d_cat == TRUE ) { $w = "Category"; $n = "12"; }
		if ( $_GET['do'] == "true" ) { 
			$id = $_GET['id'];
			$template = new template;
			$db = new db;
			if ( $d_cat == FALSE ) {
				$db->connect();
				$d_id = $_GET['id'];
				$db->query("DELETE FROM eb_forums WHERE id = '{$d_id}' LIMIT 1;");
				$sh = $template->shortbox("{$w} deleted.","{$w} has been deleted, <a href=\"index.php?code=24\">Click here </a>to return to forum management");
				return "{$sh}";
			}
			if ( $d_cat == TRUE ) {
				$db->connect();
				$c_id = $_GET['id'];
				$x2 = $db->query("DELETE FROM eb_forums WHERE cid = '{$c_id}'");
				$x = $db->query("DELETE FROM eb_category WHERE id = '{$c_id}'");
				if ( $x ) {
					$sh = $template->shortbox("{$w} deleted.","{$w} has been deleted, <a href=\"index.php?code=24\">Click here </a>to return to forum management");
				}
				return "{$sh}";
			}
		exit();
	}
	$id = $_GET['id'];
	$template = new template;
	$db = new db;
	$db->connect();
	$sh = $template->shortbox("Do you want to delete {$w} :: #{$id}?","Do you really want to delete {$w} :: #{$id}<br><a href=\"index.php?code=24&act={$n}&id={$id}&do=true\">Yes</a>&nbsp;|&nbsp;<a href=\"index.php?code=24\">No</a>");
	return "{$sh}";
}
## Function m_ef // Edit Forum
function m_ef()
{
	$id = $_GET['id'];
	$db = new db;
	$db->connect();
	if ( isset($_POST['editforum'] )) {
		$t = new template;
		$title = $_POST['title'];
		$desc = $_POST['desc'];
		$fm->query = $db->query("UPDATE `eb_forums` SET `title` = '$title', `description` = '$desc' WHERE `id` = {$id};");
		$b = $t->shortbox("Changes updated.", "Forum settings has been changed, click <a href=\"index.php?code=24\">here</a> to return to forum management.");
		return $b;
	}
	$fm->query = $db->query("SELECT * FROM eb_forums WHERE `id` = '{$id}'");
	$fm->query = mysql_fetch_array($fm->query);
	$ret = "<form action=\"\" method=\"post\">
	<table width=\"800\"  border=\"0\" cellspacing=\"0\" align=\"center\">
	  <tr>
		<td class=\"eb_forum\"><div align=\"center\"><strong>Edit Forum: </strong></div></td>
	  </tr>
	  <tr>
		<td class=\"forum_footer\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\">
		  <tr>
			<td width=\"49%\" class=\"eb_txt\"><div align=\"right\">Forum Title:<br />
			</div></td>
			<td width=\"51%\"><input name=\"title\" type=\"text\" class=\"eb_header\" style=\"width: 200px;\" value=\"{$fm->query[title]}\"/></td>
		  </tr>
		  		  <tr>
			<td width=\"49%\" class=\"eb_txt\"><div align=\"right\">Description:<br />
			</div></td>
			<td width=\"51%\"><input name=\"desc\" type=\"text\" class=\"eb_header\" style=\"width: 200px;\" value=\"{$fm->query[description]}\"/></td>
		  </tr>
				  <tr>
					<td colspan=\"2\"><div align=\"center\">
					  <input name=\"editforum\" type=\"submit\" class=\"eb_header\" value=\"Edit Forum\" />
					</div></td>
					</tr>
				</table></td>
			  </tr>
			</table>
			<br />
			</form>";
return $ret;
}
## END m_ef
## function m_ec // Edit Category
function m_ec()
{
	$db = new db;
	$db->connect();
	$id = $_GET['id'];
	if ( isset($_POST['Submit']) ) {
		$t = new template;
		$c_n = $_POST['c_n'];
		$fm->query = $db->query("UPDATE `eb_category` SET `category` = '$c_n' WHERE `id` = {$id};");
		$b = $t->shortbox("Changes updated.", "Category settings has been changed, click <a href=\"index.php?code=24\">here</a> to return to forum management.");
		return $b;
	}
	$q = $db->query("SELECT * FROM eb_category WHERE `id` = '{$id}'");
	$q = mysql_fetch_array( $q );
	$r = "<table width=\"800px\"  border=\"0\" cellspacing=\"0\" align=\"center\">
	  <tr>
		<td class=\"eb_forum\"><div align=\"center\"><strong>Edit Category</strong></div></td>
	  </tr>
	  <tr>
		<td class=\"forum_footer\"><form name=\"form1\" method=\"post\" action=\"\">
		  <table width=\"100%\"  border=\"0\" cellspacing=\"0\">
			<tr>
			  <td width=\"50%\" class=\"eb_txt\"><div align=\"right\">Category name:</div></td>
			  <td width=\"50%\"><input name=\"c_n\" type=\"text\" class=\"eb_header\" value=\"{$q[category]}\"></td>
			</tr>
			<tr>
			  <td colspan=\"2\"><div align=\"center\"><input name=\"Submit\" type=\"submit\" class=\"eb_menu1\" value=\"Update\"></div></td>
			  </tr>
		  </table>
			</form></td>
	  </tr>
	</table>";
	return $r;
}
?>