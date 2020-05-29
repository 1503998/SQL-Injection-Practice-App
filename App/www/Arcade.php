<?php
include 'header.php';
 if(empty($_COOKIE["id"]))
 { 
 echo'Vous devez &ecirc;tre connect&eacute; pour inscrire votre score';
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
echo 'Votre nouveau score est '.$thescore.'<br />
<center><FORM>
<INPUT type="button" value="Rejouer" onclick="history.back()">
</FORM></center>
';
}
else{
if($row['thescore'] < $thescore)
{
$query= mysql_query("UPDATE `pp_scores` SET `thescore` = '$thescore' WHERE gameidname='$id' && username='$username'")or die(mysql_error());
echo 'F&eacute;licitation, vous venez de battre votre meilleur score.<br />
Votre nouveau score '.$thescore.'<br />
<center><FORM>
<INPUT type="button" value="Rejouer" onclick="history.back()">
</FORM></center>';
}
else
 {
 echo 'D&eacute;sol&eacute;, vous n\'avez pas battu votre meilleur score qui est de '.$row['thescore'].'<br />
<center><FORM>
<INPUT type="button" value="R&eacute;essayer" onclick="history.back()">
</FORM></center>';
}
}

}

 ?>
