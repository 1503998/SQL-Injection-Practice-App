<?php
/*
+ ----------------------------------------------------------------------------+
|     PHPDirector.
|		$License: GNU General Public License
|		$Website: phpdirector.co.uk
|
| THIS FILE IS AN UPDATED VIDEOS.PHP FILE.
+----------------------------------------------------------------------------+
*/

	$idc = mysql_real_escape_string($_GET["id"]);
	
	if(isset($idc) && is_numeric($idc)){
	
		if(!isset($_COOKIE[$idc])){
			$twomonths = 60 * 60 * 24 * 60 + time();
			setcookie("$idc", $idc, $twomonths);
			$viewaddone = true;
		}
		
	}

	require('header.php');
	if(isset($_COOKIE["id"]))
{
     
	$res = mysql_query("select plays from pp_user WHERE id = '$_COOKIE[id]'") or die(mysql_error());
		$row_plays = mysql_fetch_assoc($res);
		$new_plays = $row_plays["plays"] + 1;
		mysql_query("UPDATE pp_user SET plays = $new_plays WHERE id = '$_COOKIE[id]'");
			mysql_free_result($res);
			if(isset($idc))
			{
	$leader = mysql_query("select * from pp_user,pp_files,pp_config WHERE pp_user.id = '$_COOKIE[id]' AND pp_files.id='$idc'") or die(mysql_error());
		}
		else
		{
		$leader = mysql_query("select * from pp_user,pp_files,pp_config WHERE approved='1' order by rand() LIMIT 1") or die(mysql_error());
	
		}
		$row = mysql_fetch_assoc($leader);
$smarty->assign('leaderboard' , '<script src="http://xs.mochiads.com/static/pub/swf/leaderboard.js" type="text/javascript"></script>
<script type="text/javascript">
// Mochi Bridge
var options = {partnerID: "'.$row['mochi_id'].'", id: "leaderboard_bridge"};
options.userID = "'.$_COOKIE['id'].'";
options.username = "'.$row['user'].'";
// optional
options.sessionID = "'.$_COOKIE['id'].'";
// optional
options.gateway = "http://www.example.com/bridge/";
// optional
options.profileURL = "'.$row['site_url'].'/user.php?u='.$row['user'].'";
options.thumbURL = "'.$row['site_url'].'/avatar/'.$row['avatar'].'";
// optional
options.logoURL = "http://www.example.com/images/icon_16x16.jpg";
// optional
options.siteURL = "'.$row['site_url'].'";
// optional
options.siteName = "'.$row['name'].'";
// optional
options.callback = function (params) { alert(params.name + " (" + params.username + ") just scored " + params.score + "!"); }
// uncomment this to display global scores
// options.globalScores = "true";

// optional
options.denyScores = "Login to '.$row['name'].' to submit scores!";

// optional
// options.denyFriends = "true";
// optional
// options.denyChallenges = "true";
/*
// uncomment this block for debug mode
options.width = 320;
options.height = 240;
options.debug = "true";
*/
Mochi.addLeaderboardIntegration(options);
</script>');
$smarty->assign('widget' ,'<div id="leaderboard_widget"></div>
<script src="http://xs.mochiads.com/static/pub/swf/leaderboard.js" type="text/javascript"></script>
<script type="text/javascript">
// Customized example: Embeds a 500x500 leaderboard widget, replacing the "leaderboard_widget" div
var options = {game: "'.$row['file2'].'", width: 500, height: 500, id: "leaderboard_widget"};
// Select a specific leaderboard for a game - optional (if not included, a selectable list will be used)
options.leaderboard = "Level 1";
// your publisher ID - optional (if not included, ALL scores from all sites will be shown)
options.partnerID = "'.$row['mochi_id'].'";
// username link prefix - optional
options.profileURL = "'.$row['site_url'].'/user.php?u='.$row['user'].'";
// leaderboard background color - optional
options.backgroundColor = "#ffffff";
// Show the widget!
options.defaultTab = "alltime";
// Default to the all time tab!
Mochi.showLeaderboardWidget(options);
</script>');
}


$ip=$_SERVER['REMOTE_ADDR'];
$requete = "SELECT COUNT(ip) as ip FROM pp_comment WHERE ip='$ip' and file_id='$_GET[id]'";
$reponse = mysql_query ($requete);
$reponse= mysql_fetch_array($reponse);
if ($reponse['ip']==0) {

if(isset($_POST['go']) && !empty($_POST['comment']) && !empty($_POST['nom']))
{
mysql_query("INSERT INTO pp_comment (file_id, nom, comment,ip) VALUES ('$_POST[id]', '$_POST[nom]','".mysql_real_escape_string($_POST['comment'])."','$ip')");

$smarty->assign('message', 'Thanks Guy, Your comment has been added');
}
}
else
{
$smarty->assign('message', 'Sorry, You have already comment this game');
}

if(isset($_GET['id']))
{
$query_co = "SELECT * FROM pp_comment,pp_user WHERE pp_comment.nom = pp_user.user AND pp_comment.file_id=$_GET[id] ORDER BY pp_comment.id DESC";
$result_co = mysql_query($query_co);
while ($row_co = mysql_fetch_assoc($result_co)){
   $game_co[] = $row_co;
}
$smarty->assign('game_co', $game_co);
}

	if(isset($idc) && is_numeric($idc)){
		$id = mysql_real_escape_string($idc);
		$result = mysql_query("SELECT * FROM pp_files WHERE id=$id AND `approved` = '1' LIMIT 1") or die(mysql_error());  
	}else{
		$result = mysql_query("select * from pp_files WHERE approved='1' AND reject='0' order by rand() LIMIT 1") or die(mysql_error());  
	}
	
	// For each result that we got from the Database
	while ($row = mysql_fetch_assoc($result))
	{
 	
 		$video[] = $row;

	 	if($viewaddone == true){
			$new_views = $row["views"] + 1;
			mysql_query("UPDATE pp_files SET views = '$new_views' WHERE id = '$id'");
		}
		
	if($row['feature'] == 1)
{
$score = "SELECT * FROM pp_scores WHERE gameidname = '$row[name]'";
$scoreres = mysql_query($score)or die(mysql_error());
while($rowscore = mysql_fetch_assoc($scoreres))
{	
   $row_score[] = $rowscore;
}
$smarty->assign('hscore', $row_score);
}
	}

	// Assign this array to smarty

	$smarty->assign('video', $video);
	$smarty->assign('id', $row['id']);


	if(isset($_GET["pop"])){
		$smarty->display('viewvidpop.tpl');
	}else{
		$smarty->display('viewvid.tpl');
	}

	mysql_close($mysql_link);
?>