<?php
/*
+ ----------------------------------------------------------------------------+
|     PHPDirector.
|		$License: GPL General Public License
|		$Website: phpdirector.co.uk
|		$Author: Ben Swanson
|		$Contributors - Dennis Berko and Monte Ohrt (Monte Ohrt)
+----------------------------------------------------------------------------+
*/

require('header.php');
if(isset($_GET['act']) && $_GET['act'] = 'Arcade')
{
if(empty($_COOKIE["id"]))
 { 
 $msg= 'Vous devez &ecirc;tre connect&eacute; pour inscrire votre score';
 }
 else
 {
 
$id = $_POST['gname'];
if($id==NULL) $id= $_REQUEST['id'];
$last = date('d/m/Y à H:i');
$det = mysql_query("SELECT * FROM pp_files WHERE file2='$id'") or die(mysql_error());
$info = mysql_fetch_assoc($det);
$d = mysql_query("SELECT user FROM pp_user WHERE id=$_COOKIE[id]") or die(mysql_error());
$u = mysql_fetch_assoc($d);
$username= $u['user'];

//v32=index.php?autocom=arcade&do=savescore
if ($_GET['autocom'] == 'arcade' || $_POST['autocom'] == 'arcade' ) {
if ($_GET['do'] == 'savescore' || $_GET['do'] == 'newscore') {

$thescore = $_POST['gscore'];
if ($thescore==NULL) $thescore= $_POST['thescore'];
if ($thescore==NULL) $thescore= $_GET['scoreVar'];
}
}
if ($_GET['act'] == 'Arcade' || $_POST['act'] == 'Arcade' ) {
if ($_GET['do'] == 'savescore' || $_GET['do'] == 'newscore') {

$thescore = $_POST['gscore'];
if ($thescore==NULL) $thescore= $_POST['thescore'];
if ($thescore==NULL) $thescore= $_GET['scoreVar'];
}
}
//v2=index.php?act=Arcade&do=newscore
if ($_GET['do'] == 'savescore' || $_GET['do'] == 'newscore') {

$thescore = $_POST['gscore'];
if ($thescore==NULL) $thescore= $_POST['thescore'];
if ($thescore==NULL) $thescore= $_GET['scoreVar'];
}
$check = mysql_query("SELECT * FROM pp_scores WHERE gameidname='$id' ORDER BY thescore DESC LIMIT 0,1")or die(mysql_error());
$checkTOPscore = mysql_fetch_array($check);

if ($thescore > $checkTOPscore['thescore']) { // We have a champion!  
echo "<div class='tableborder'><table width='100%'><td class='arcade1' width='100%' align='center'>F&eacute;licitation, vous &ecirc;tes le NOUVEAU champion!</td></table></div><br /><br />";
  $del= mysql_query("DELETE FROM `pp_leaderboard` WHERE `gamename`='$id'")or die(mysql_error());
  $add= mysql_query("INSERT INTO pp_leaderboard (username,thescore,gamename) VALUES ('$username',$thescore,'$id')")or die(mysql_error()); 
		
  }
$mysql = mysql_query("SELECT * FROM pp_scores WHERE username='$username' AND gameidname = '$id'") or die(mysql_error());
$row = mysql_fetch_assoc($mysql);
if(empty($row))
{
$query= mysql_query("INSERT INTO pp_scores (username,thescore,gameidname) VALUES ('$username','$thescore','$id')")or die(mysql_error());
$msg= 'Votre nouveau score est '.$thescore.'<br />
<center><FORM>
<INPUT type="button" value="Rejouer" onclick="history.back()">
</FORM></center>
';
}
else{
if($row['thescore'] < $thescore)
{
$query= mysql_query("UPDATE `pp_scores` SET `thescore` = '$thescore' WHERE gameidname='$id' && username='$username'")or die(mysql_error());
$msg= 'F&eacute;licitation, vous venez de battre votre meilleur score.<br />
Votre nouveau score '.$thescore.'<br />
<center><FORM>
<INPUT type="button" value="Rejouer" onclick="history.back()">
</FORM></center>';
}
else
 {
$msg= 'D&eacute;sol&eacute;, vous n\'avez pas battu votre meilleur score qui est de '.$row['thescore'].'<br />
<center><FORM>
<INPUT type="button" value="R&eacute;essayer" onclick="history.back()">
</FORM></center>';
}
}

}
$smarty->assign('msg', $msg);
}
else
{
$query_lc = "SELECT * FROM pp_comment ORDER BY id DESC LIMIT 5";
$result_lc = mysql_query($query_lc);
while ($row_lc = mysql_fetch_assoc($result_lc)){
   $lastcomment[] = $row_lc;
}
$smarty->assign('lastcomment', $lastcomment);

$query_lu = "SELECT * FROM pp_user ORDER BY id DESC LIMIT 9";
$result_lu = mysql_query($query_lu);
while ($row_lu = mysql_fetch_assoc($result_lu)){
   $lastuser[] = $row_lu;
}
$smarty->assign('lastuser', $lastuser);


// required connect
    SmartyPaginate::connect();
	
// set items per page
	$limit = config('vids_per_page');
    SmartyPaginate::setLimit($limit);

//SORTING???
		
switch ($_GET["sort"]){
case "name":
	$sort = "name";
	break;
case "date":
	$sort = "id";
	break;
case "views":
	$sort = "views";
	break;
default:
	$sort = "id";
	$order1 = "DESC";
}

//Check if theres a Get called order then is its down order by DESC if its up dont order by DESC if no get varriable order by non DESC
if(isset($_GET["order"])){
	$order = $_GET["order"];
	if($order == "up"){
		$order1 = "";
	}
	if($order == "down"){
		$order1 = "DESC";
	}
}
		
		//SORTING END ???
if ($_GET["pt"] == "all") {		
		$_query = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM pp_files WHERE `approved` = '1' AND `reject` = '0' ORDER BY $sort $order1 LIMIT %d,%d",
		 SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
}elseif ($_GET["pt"] == "feature") {
		$_query = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM pp_files WHERE `approved` = '1' AND `feature` = '1' AND `reject` = '0' ORDER BY $sort $order1 LIMIT %d,%d",
            SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
}elseif (isset($_GET["cat"])) {
$cat = $_GET["cat"];
		$_query = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM pp_files WHERE `category` = '$cat' AND `approved` = '1' ORDER BY $sort $order1 LIMIT %d,%d",
            SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
}elseif (isset($_POST["searching"])){
		
		$search = $_POST[searching];
		$_query = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM pp_files WHERE `name` like '%%$search%%' AND `approved` = '1' ORDER BY $sort $order1 LIMIT %d,%d",
            SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
}else{
		$_query = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM pp_files WHERE `approved` = '1' AND `reject` = '0' ORDER BY $sort $order1 LIMIT %d,%d",
            SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
};
        $_result = mysql_query($_query);   // assign your db results to the template

$_data = array();
$i=0;
       while ($_row = mysql_fetch_array($_result, MYSQL_ASSOC)) {
		
		
		$month = date("M", strtotime($_row[date]));
		$day = date("d", strtotime($_row[date]));
		//image
		if ($_row['picture'] !== null){
			$tehpic = $_row[picture];
			$amp = array("&amp;");
			$new_replace  = array("&");
			$newphrase = str_replace("$amp", "$new_replace", "$tehpic");
			$picture = $newphrase;
		}
		//image
		
		$tmp = array(
			'id' => $_row['id'], 
			'month' => $month, 
			'day' => $day,
			'name' => $_row['name'],
			'creator' => $_row['creator'],
			'picture' => $picture,
			'description' => $_row['description'],
			'br' => $br
		);
			
			$_data[$i++] = $tmp;
		
		
            // collect each record into $_data
        }
        
        // now we get the total number of records from the table
// now we get the total number of records from the table
        $_query = "SELECT FOUND_ROWS() as total";
        $_result = mysql_query($_query);
        $_row = mysql_fetch_array($_result, MYSQL_ASSOC);
        SmartyPaginate::setTotal($_row['total']);

        mysql_free_result($_result);
 	
	///DB
	
	
	$smarty->assign('videos', $_data);
	
		if ($_row['total'] == 0){ //if no videos display error.
	$error = $smarty->get_template_vars('LAN_29');
	$smarty->assign_by_ref('error', $error);
	$smarty->display('error.tpl');
	exit;
}
    // assign {$paginate} var
    SmartyPaginate::assign($smarty);
	if (isset($_GET["pt"])){
	SmartyPaginate::setUrl('index.php?pt='.$_GET["pt"]);
	}elseif(isset($_GET["cat"])){
	SmartyPaginate::setUrl('index.php?cat='.$_GET["cat"]);
	}else{
	
	}
	}
    // display results
    $smarty->display('index.tpl');	
SmartyPaginate::disconnect();
mysql_close($mysql_link);
?>