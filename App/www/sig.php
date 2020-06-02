<?php
/*********************************************
<!-- 
*   	DOTA OPENSTATS
*   
*	Developers: Ivan.
*	Contact: ivan.anta@gmail.com - Ivan
*
*	
*	Please see http://openstats.iz.rs
*	and post your webpage there, so I know who's using it.
*
*	Files downloaded from http://openstats.iz.rs
*
*	Copyright (C) 2010  Ivan
*
*
*	This file is part of DOTA OPENSTATS.
*
* 
*	 DOTA OPENSTATS is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    DOTA OPEN STATS is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with DOTA OPEN STATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/
  if (!isset($_GET["u"])) {echo "Unknown username!";die;}
  
  if (!extension_loaded('gd') && !function_exists('gd_info')) {
    echo "GD is not installed!"; die;
    }
	
	function get_data($url)
      {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
      }
	
	  
	  if (!function_exists("safeEscape"))
{
     require_once ('config.php');
	 require_once("./lang/$default_language.php");
	 require_once('./includes/class.database.php');
	 require_once('./includes/common.php');
	 require_once('./includes/db_connect.php');
}
	 $username=strtolower(safeEscape($_GET["u"]));

	 if($_GET)
	 {
	 //$created = filemtime("./img/sig/$username.jpg");
	 //USE CACHED IMAGE
	 if (file_exists("./img/sig/$username.jpg") AND time() - $sigCacheTime*60 < filemtime("./img/sig/$username.jpg"))
	 {  $NewImage =imagecreatefromjpeg("img/sig/$username.jpg");//image create by existing image
         header("Content-type: image/jpeg");// out out the image 
         //imagejpeg($NewImage);//Output image to browser 
         echo file_get_contents("img/sig/".$username.".jpg"); exit;}
		 else 
		 {
  
  $BANNED =  "";
  $COLOR = "";
  
  $sql = "
  SELECT 
  gp.name AS name, bans.name AS banname, bans.reason AS banreason, count(1) AS counttimes, gp.ip AS ip
  FROM 
  gameplayers gp 
  JOIN dotaplayers dp ON dp.colour = gp.colour 
  AND 
  dp.gameid = gp.gameid 
  LEFT JOIN bans ON bans.name = gp.name
  WHERE 
  LOWER(gp.name) = LOWER('$username') GROUP BY gp.name 
  ORDER BY 
  gp.gameid DESC, counttimes DESC, gp.name DESC";
  
  $result = $db->query($sql);
  
  if ($db->num_rows($result) <=0) {echo $lang["err_user"] ; die;}

  $list = $db->fetch_array($result,'assoc'); 
  
  if (strtolower("$list[name]") == strtolower($list['banname'])) {$BANNED =  "(Banned) $list[banreason]"; $COLOR = "style='color:#DC0000;'";}

  $realname = $list['name'];
  $myFlag = "";
  $IPaddress = $list["ip"];
  
  if ($CountryFlags == 1 AND file_exists("./includes/ip_files/countries.php") AND $IPaddress!="" )
		{
		$two_letter_country_code=iptocountry($IPaddress);
		include("./includes/ip_files/countries.php");
		$three_letter_country_code=$countries[$two_letter_country_code][0];
        $country_name=convEnt2($countries[$two_letter_country_code][1]);
		$file_to_check="./includes/flags/$two_letter_country_code.gif";
		if (file_exists($file_to_check)){
		        $flagIMG = "<img src=$file_to_check>";
                $flag = "<img onMouseout='hidetooltip()' onMouseover='tooltip(\"".$flagIMG." $country_name\",100); return false' src='$file_to_check' width='20' height='13'>";
                }else{
                $flag =  "<img title='$country_name' src='./includes/flags/noflag.gif' width='20' height='13'>";
                }	
		$myFlag = $flag;
		}
  
  /////////////////////////////////// HERO STATS ///////////////////////////////////
	
	
	//////////////////////////////
	//Get hero you have played most with
	
	$sql = "SELECT SUM(`left`) AS timeplayed, original, description, 
	COUNT(*) AS played 
	FROM gameplayers 
	LEFT JOIN games ON games.id=gameplayers.gameid 
	LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
	AND dotaplayers.colour=gameplayers.colour  
	LEFT JOIN dotagames ON games.id=dotagames.gameid 
    JOIN heroes on hero = heroid 
	WHERE LOWER(name)=LOWER('$username')
	GROUP BY original 
	ORDER BY played DESC LIMIT 1";
	
	    $result = $db->query($sql);
	    $list = $db->fetch_array($result,'assoc'); 
		$mostplayedhero=strtoupper($list["original"]);
		$mostplayedheroname=$list["description"];
		$mostplayedcount=$list["played"];
		$mostplayedtime=secondsToTime($list["timeplayed"]);

	//////////////////////////////
	//Using score table
	if ($DBScore != 1) {
	
	$sql = "SELECT 
	($scoreFormula) as score 
	FROM(SELECT *, (kills/deaths) as killdeathratio 
	   FROM (
		SELECT avg(dp.courierkills) as courierkills, 
	    avg(dp.raxkills) as raxkills,
		avg(dp.towerkills) as towerkills, 
		avg(dp.assists) as assists, 
		avg(dp.creepdenies) as creepdenies, 
		avg(dp.creepkills) as creepkills,
		avg(dp.neutralkills) as neutralkills, 
		avg(dp.deaths) as deaths, 
		avg(dp.kills) as kills,
		COUNT(*) as totgames, 
		SUM(case when(((dg.winner = 1 and dp.newcolour < 6) 
		or (dg.winner = 2 and dp.newcolour > 6)) 
		AND gp.`left`/ga.duration >= 0.8) then 1 else 0 end) as wins, 
		SUM(case when(((dg.winner = 2 and dp.newcolour < 6) 
		or (dg.winner = 1 and dp.newcolour > 6)) 
		AND gp.`left`/ga.duration >= 0.8) then 1 else 0 end) as losses
		FROM gameplayers as gp 
		LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid 
		LEFT JOIN games as ga ON ga.id = dg.gameid 
		LEFT JOIN dotaplayers as dp on dp.gameid = dg.gameid 
		and gp.colour = dp.colour where dg.winner <> 0 
		and gp.name = '$username') as h) as i LIMIT 1";} else
		{$sql = "SELECT scores.score from scores WHERE LOWER(name) = LOWER('$username')";}
		
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc');
	$score=$list["score"];

	//FINAL STEP
	$result = $db->query("SELECT 
	COUNT(dp.id), 
	SUM(kills), 
	SUM(deaths), 
	SUM(creepkills), 
	SUM(creepdenies), 
	SUM(assists), name 
	FROM dotaplayers AS dp 
	LEFT JOIN gameplayers AS b ON b.gameid = dp.gameid 
	and dp.colour = b.colour 
	WHERE name= '$username' 
	GROUP BY name 
	ORDER BY sum(kills) desc 
	LIMIT 1");
	
	$row = $db->fetch_array($result,'assoc');

	$kills=number_format($row["SUM(kills)"],"0",".",",");
	$kills2=$row["SUM(kills)"];
	$death=number_format($row["SUM(deaths)"],"0",".",",");
	$death2=$row["SUM(deaths)"];
    $assists=number_format($row["SUM(assists)"],"0",".",",");
	$assists2=$row["SUM(assists)"];
	$creepkills=number_format($row["SUM(creepkills)"],"0",".",",");
	$creepkills2=$row["SUM(creepkills)"];
	$creepdenies=number_format($row["SUM(creepdenies)"],"0",".",",");
	$creepdenies2=$row["SUM(creepdenies)"];
	//$neutralkills=number_format($row["SUM(neutralkills)"],"0",".",",");
	//$neutralkills2=$row["SUM(neutralkills)"];
	//$towerkills=number_format($row["SUM(towerkills)"],"0",".",",");
	//$towerkills2=$row["SUM(towerkills)"];
	//$raxkills=number_format($row["SUM(raxkills)"],"0",".",",");
	//$courierkills=number_format($row["SUM(courierkills)"],"0",".",",");
	//$courierkills2=$row["SUM(courierkills)"];
	$name=$row["name"];
	//$totgames=number_format($row["COUNT(dp.id)"],"0",".",",");
    //$totgames2=$row["COUNT(dp.id)"];
	
	//calculate wins
    $wins=$db->getUserWins($username);
    //calculate losses
    $losses=$db->getUserLosses($username);

	if ($death2 >=1)
	{$kdratio = ROUND($kills2/$death2,1);} else {$kdratio =0;}
	
	$totgames = number_format($wins+$losses,"0",".",",");
	$totgames2 = $wins+$losses;
	$totscore = number_format(ROUND($score,2),"0",".",",");
	
	if($wins == 0 and $wins+$losses == 0)
	{$winloose = 0;}
	else
	{$winloose = round($wins/($wins+$losses), 3)*100;}
	
	if ($totgames2 <$sigMinGames)
	{echo "You must have at least $sigMinGames games played to create signature!"; die;}
	   
   $sql = "SELECT 
   SUM(`left`) 
   FROM gameplayers 
   WHERE LOWER(name)=LOWER('$username') LIMIT 1";
	
	$result = $db->query($sql);
	
	$row = $db->fetch_array($result,'assoc');
	//$db->close($result);

		$totalDuration=secondsToTime($row["SUM(`left`)"]);
		
		$totalHours=ROUND($row["SUM(`left`)"]/ 3600,1);
		$totalMinutes=ROUND($row["SUM(`left`)"]/ 3600*60,1);
  
		if ($totalMinutes>0)
		{$killsPerMin = ROUND($kills2/$totalMinutes,2);
		if ($totalHours>0)
		$killsPerHour = ROUND($kills2/$totalHours,2);
		else
		$killsPerHour = 0;
		$deathsPerMin = ROUND($death2/$totalMinutes,2);
		$creepsPerMin = ROUND($creepkills2/$totalMinutes,2);
		}  
		    else 
		    {
		    $killsPerMin = 0; 
		    $deathsPerMin = 0; 
		    $killsPerHour = 0; 
		    $creepsPerMin =0;
			}

		
	if ($displayUsersDisconnects == 1 OR $ScoreMethod == 2)	
	{
	$sql = "SELECT 
   SUM(
	 (gp.`leftreason` LIKE ('%has lost the connection%'))  
	 OR (gp.`leftreason` LIKE ('%was dropped%')) 
	 OR (gp.`leftreason` LIKE ('%Lagged out%')) 
	 OR (gp.`leftreason` LIKE ('%Dropped due to%'))
	 OR (gp.`leftreason` LIKE ('%Lost the connection%'))
	 ) as disc 
   FROM gameplayers as gp 
   LEFT JOIN games ON games.id=gp.gameid 
   LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
   AND dotaplayers.colour=gp.colour 
   LEFT JOIN dotagames ON games.id=dotagames.gameid 
   WHERE LOWER(gp.name)=LOWER('$username') 
   AND dotagames.winner <>0 
   LIMIT 1";
	
	$sql2222 = " SELECT COUNT(*) 
    FROM `gameplayers`
    WHERE 
	(
	   `leftreason` LIKE('%has lost the connection%') 
	OR `leftreason` LIKE('%was dropped%') 
	OR `leftreason` LIKE('%Lagged out%') 
	OR `leftreason` LIKE('%Dropped due to%')
	OR (gp.`leftreason` LIKE ('%Lost the connection%'))
	) 
	AND name= '$username' LIMIT 1";
	
	$result = $db->query($sql);
	$row = $db->fetch_array($result,'assoc');
    $disc = $row["disc"]; }
	
		if ($totgames2>0)
		{
		$killsPerGame = ROUND($kills2/$totgames2,1);	
		$deathsPerGame = ROUND($death2/$totgames2,2);
		} 
		else {$killsPerGame = 0; $DiscPercent = 0; $deathsPerGame =0;}
		
		if ($kills2 >0)
	    {$KillsPercent = ROUND($kills2/($kills2+$death2), 4)*100; } else {$KillsPercent = 0;}
		
		if ($totgames2 >0)
		{$AssistsPerGame = ROUND($assists2/$totgames2,2);} else {$AssistsPerGame = 0;}
		
		if ($ScoreMethod == 2 AND $DBScore == 0)
		{$totscore = number_format($ScoreStart + ($wins * $ScoreWins) + ($losses * $ScoreLosses) + ($disc*$ScoreDisc),"0",".",",") ; 
		//$totscore = ROUND( $ScoreStart+(($wins * 5) - ($losses * 3)) , 2) ; 
		if ($BANNED !="") {$totscore  = $ScoreStart + ($ScoreDisc*10);}
		}
	
  $this_url = "http://".$_SERVER["SERVER_NAME"].dirname($_SERVER["PHP_SELF"]); // root URL
  
  $last = $this_url[strlen($this_url)-1];
  if ($last != "/") {$this_url.="/";}
  
  /*
  $img_link = $this_url.'includes/get_sig.php?u='.$realname.'&k='.$kills.'&d='.$death.'&a='.$assists.'&g='.$totgames.'&online='.$totalDuration.'&s='.$totscore.'&w='.$winloose;
  
  $img_link = 'includes/get_user_signature.php?u='.$realname.'&k='.$kills.'&d='.$death.'&a='.$assists.'&g='.$totgames.'&online='.$totalDuration.'&s='.$totscore.'&w='.$winloose.'&ck='.$creepkills.'&cd='.$creepdenies.'&kpg='.$killsPerGame;

  $img_link = str_replace(" ","%20",$img_link);
  */
  
$player = $realname;
$deaths = $death;
$games = $totgames;
$duration = $totalDuration;
$score = $totscore;
$wins = $winloose;
$kpg = $killsPerGame;

//Remove seconds??? Uncomment below if you want to remove seconds
//$duration = substr($duration,0,-3);

//Player name | website name
$ResultStr = "$player | $lang[site_name]";
//or Player: player name
$ResultStr = "Player: $player";
//or player name
$ResultStr = "$player";

$NewImage =imagecreatefromjpeg("./img/sig.jpg");//image create by existing image

//$LineColor = imagecolorallocate($NewImage,255,55,55);//line color 
//$LineColor2 = imagecolorallocate($NewImage,55,55,255);//line color 
$TextColor = imagecolorallocate($NewImage, 255, 246, 0);    //text color - RGB
$TextColor2 = imagecolorallocate($NewImage, 255, 255, 255); //text color - RGB
$TextColor3 = imagecolorallocate($NewImage, 253, 193, 193); //text color - RGB

// :
//imagestring(IMG_SOURCE, FONT_SIZE, X, Y, TEXT, COLOR);

imagestring($NewImage, 5, 10, 1, $ResultStr, $TextColor);

//score
imagestring($NewImage, 2, 10, 26,$lang["score"].":", $TextColor3);
imagestring($NewImage, 2, 68, 26,$score, $TextColor2);

//games
imagestring($NewImage, 2, 10, 42,$lang["games"].":", $TextColor3);
imagestring($NewImage, 2, 68, 42,$games, $TextColor2);

//wins
imagestring($NewImage, 2, 10, 58,$lang["wins"].":", $TextColor3);
imagestring($NewImage, 2, 68, 58,$wins."%", $TextColor2);


//Kills Per Game
imagestring($NewImage, 2, 10, 82,"$kpg ".$lang["kills_per_game"], $TextColor2);
//imagestring($NewImage, 2, 102, 82,$kpg, $TextColor2);


//duration
imagestring($NewImage, 2, 10, 98,"Time:", $TextColor3);
imagestring($NewImage, 2, 52, 98,$duration, $TextColor2);

//Creep Kills
imagestring($NewImage, 2, 140, 82,$lang["creep_kills"].":", $TextColor3);
imagestring($NewImage, 2, 225, 82,$creepkills, $TextColor2);

//Creep Denies
imagestring($NewImage, 2, 140, 98,$lang["creep_denies"].":", $TextColor3);
imagestring($NewImage, 2, 225, 98,$creepdenies, $TextColor2);


//kills
imagestring($NewImage, 2, 140, 26,$lang["kills"].":", $TextColor3);
imagestring($NewImage, 2, 200, 26,$kills, $TextColor2);

//deaths
imagestring($NewImage, 2, 140, 42,$lang["deaths"].":", $TextColor3);
imagestring($NewImage, 2, 200, 42,$deaths, $TextColor2);

//assists
imagestring($NewImage, 2, 140, 58,$lang["assists"].":", $TextColor3);
imagestring($NewImage, 2, 200, 58,$assists, $TextColor2);

          if ($sigCountryFlags == 1) {
              //COUNTRY FLAGS
              //image create by existing image
              $NewImage2 = imagecreatefromgif($file_to_check);
              imagecopy($NewImage, $NewImage2, 280, 2, 0, 0, imagesx($NewImage2), imagesy($NewImage2));
              imagedestroy($NewImage2);
              if ($two_letter_country_code != "ZZ")
                  imagestring($NewImage, 2, 305, 2, $two_letter_country_code, $TextColor2);
          }

//FAVORITE HERO
        $NewImage2 = imagecreatefromgif("./img/heroes/$mostplayedhero.gif");
		imagecopyresized($NewImage,$NewImage2,280,54,0,0,28,28,64,64);
		imagedestroy ($NewImage2); 
		
		imagestring($NewImage, 2, 280, 25, "Favorite Hero", $TextColor3);
		imagestring($NewImage, 1, 280, 39, $mostplayedheroname, $TextColor2);


//CACHE IMAGE
header("Content-type: cache");// out out the image 
imagejpeg($NewImage, "./img/sig/$username.jpg", $sigImageQuality);//Output image to browser 
imagedestroy($NewImage);

//OUTPUT IMAGE TO BROWSER
         $NewImage =imagecreatefromjpeg("img/sig/$username.jpg");//image create by existing image
         header("Content-type: image/jpeg");// out out the image 
         imagejpeg($NewImage);//Output image to browser 
         imagedestroy($NewImage); exit;
          }
   }
?>