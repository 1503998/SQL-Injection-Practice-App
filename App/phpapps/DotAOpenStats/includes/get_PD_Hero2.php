<?PHP
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
	//include "header.php";
	
	if (!function_exists('curl_init')) {
	echo "CURL function is disabled. Please check your php configuration."; die;
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
	  
	//$pdHero = "windrunner";
	
   $pageTitle = "$lang[site_name] | $pdHero";
   $pageContents = ob_get_contents();
   ob_end_clean();
   echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);

    //$data2 = @file_get_contents("http://www.playdota.com/heroes/$pdHero");
	$data2 = get_data("http://www.playdota.com/heroes/$pdHero");
    //file_put_contents("test.html", $data2);
	
	if ($data2 =="") {echo "Unknown hero!";}
	
	//$data2 = @file_get_contents("test.html");
	$lnStartPos = strpos($data2, '<li>Strength <img src=');
    $lnEndPos = strpos($data2, '<div id="info">');
	$var_end = substr($data2, $lnStartPos, $lnEndPos);
	
	//CLEAN HTML
	//$var_end = str_replace("Strength","Strength<br>",$var_end);
	   
   $var_end = str_replace('Strength','<b>Strength</b>',$var_end);
   $var_end = str_replace('Agility','<br><b>Agility</b>',$var_end);
   $var_end = str_replace('Intelligence','<br><b>Intelligence</b>',$var_end);
   
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/agility-c.jpg',
	'<img style="vertical-align:middle" src="./img/agility-c.gif',$var_end);
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/strength-c.jpg',
	'<img style="vertical-align:middle" src="./img/strength-c.gif',$var_end);
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/intelligence-c.jpg',
	'<img style="vertical-align:middle" src="./img/intelligence-c.gif',$var_end);
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/agility.jpg',
	'<img style="vertical-align:middle" src="./img/agility.gif',$var_end);
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/strength.jpg',
	'<img style="vertical-align:middle" src="./img/strength.gif',$var_end);
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/intelligence.jpg',
	'<img style="vertical-align:middle" src="./img/intelligence.gif',$var_end);

	$var_end = str_replace("<br />","",$var_end);
	$var_end = str_replace("<h2>","<br><br><b>",$var_end);
	$var_end = str_replace("</h2>","</b>",$var_end);
	//$var_end = str_replace('<ul class="menuhero" id="menuhero">',"",$var_end);
	//$var_end = preg_replace('/<a href="#" onclick="return herotoggle[^>]+\>/i', '', $var_end);
	$var_end = preg_replace('/<a href="(.+?)">(.+?)<\/a>/is', '\\2', $var_end);
    $var_end = str_replace('character.gif">','character.gif"/><br>',$var_end);
	
	$var_end = preg_replace('/<div class=[^>]+\>/i', '', $var_end);
	$var_end = preg_replace('/<div style=[^>]+\>/i', '', $var_end);
	$var_end = preg_replace('/<li class=[^>]+\>/i', '', $var_end);
	$var_end = preg_replace('/<p class=[^>]+\>/i', '', $var_end);
	$var_end = preg_replace('/<ul class=[^>]+\>/i', '', $var_end);
	$var_end = preg_replace('/<ul class=[^>]+\>/i', '', $var_end);
	$var_end = preg_replace('/<div id=[^>]+\>/i', '', $var_end);
	$var_end = str_replace(array(
	'</a>',
	'<li>','</li>',
	'<div>','</div>',
	'<span>','</span>'
	,'<label>','</label>',
	'</ul>'),"",$var_end);
	$var_end = preg_replace(array(
	'/Information/',
	'/Comments/',
	'/Guides/',
	'/Sounds/',
	'/Artwork/',
	'/Video/',
	'/Replays/'),'', $var_end);
	
	//$var_end = str_replace('class="skill" border="0"/>','border="0"/><br>',$var_end);
	$var_end = str_replace('<img src=','<br><img src=',$var_end);
	$var_end = str_replace('Affiliation:','<table><tr><td><b>Affiliation:</b></td><td>',$var_end);
	$var_end = str_replace('Attack Animation:','</td><td><b>Attack Animation:</b></td><td>',$var_end);
	$var_end = str_replace('Damage:','</td></tr><tr><td><b>Damage:</b></td><td>',$var_end);
	$var_end = str_replace('Casting Animation:','</td><td><b>Casting Animation:</b></td><td>',$var_end);
	$var_end = str_replace('Armor:','</td></tr><tr><td><b>Armor:</b></td><td>',$var_end);
	$var_end = str_replace('Base Attack Time:','</td><td><b>Base Attack Time:</b></td><td>',$var_end);
	$var_end = str_replace('Movespeed:','</td></tr><tr><td><b>Movespeed:</b></td><td>',$var_end);
	$var_end = str_replace('Missile Speed:','</td><td><b>Missile Speed:</b></td><td>',$var_end);
	$var_end = str_replace('Attack Range:','</td></tr><tr><td><b>Attack Range:</b></td><td>',$var_end);
	$var_end = str_replace('Sight Range:','</td><td><b>Sight Range:</b></td><td>',$var_end);
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/asblue.gif"/>',
	"<br><table><tr><th><b>Advanced statistics</b></th></td></table>",$var_end);
	
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/herointroblue.gif" />',
	"</td></tr></table><br><table><tr><th><b>Hero Introduction</b></th></td></table>",$var_end);
	
	$var_end = str_replace(
	'<img src="http://media.playdota.com/site/bkstory.gif" />',
	"<table><tr><th><b>Background story</b></th></td></table>",$var_end);
	
	$var_end = str_replace('border="0"/>','border="0"/><br>',$var_end);
	$var_end = preg_replace(
	'/<img src="http:\/\/media.playdota.com\/hero\/(.+?)" alt=(.+?)"\/>/is', 
	'<img src="http://media.playdota.com/hero/$1"/> <br>', $var_end);
	$lnStartPos = strpos($var_end, 'Strength <img');
    $lnEndPos = strpos($var_end, '<script type="text/javascript">');
	$var_end = substr($var_end, $lnStartPos, $lnEndPos);
	
	$var_end = str_replace('<b>Strength</b> <img style=','<table><tr><td width="130px;"><b>Strength</b></td><td><img style=',$var_end);
	$var_end = str_replace('<br><b>Agility</b> <img style','</tr></td><tr><td><b>Agility</b></td><td><img style',$var_end);
	$var_end = str_replace('<br><b>Intelligence</b> <img style','</tr></td> <tr><td><b>Intelligence</b></td><td><img style',$var_end);
	
	//ANIMATED CHARACTER 
	$var_end = str_replace('character.gif"/> <br>','character.gif"/> <br></tr></td></table>',$var_end);
	$var_end = preg_replace(
	'/<br><img src="http:\/\/media.playdota.com\/hero\/(.+?)\/character.gif"\/> <br><\/tr><\/td><\/table>/is', 
	'</tr></td></table><img src="http://media.playdota.com/hero/$1/character.gif"/> <br>', $var_end);
	
		$var_end = preg_replace('/alt="(.+?)\/>/is', 'alt="*" />', $var_end);
	
   $data = $var_end;
   $pattern = "/src=[\"']?([^\"']?.*(png|jpg|gif))[\"']?/i";
   preg_match_all($pattern, $data, $images);
	
	//CACHE Animated character
	/*
	if (!file_exists("./img/heroes/anim/$pdHero.gif") AND $var_end !="")
    {
    $ch = curl_init($images[1][3]);
    $fp = fopen("./img/heroes/anim/$pdHero.gif", "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
	}
	
	$var_end = preg_replace(
	'/<\/table><img src="http:\/\/media.playdota.com\/hero\/(.+?)\/character.gif"\/> <br>/is', 
	"</table><div align='center'><img alt='' src='./img/heroes/anim/$pdHero.gif'/></div>", $var_end);
	*/
	
	//CACHE ALL IMAGES
	//SKILLS
	// Skill 1 - $images[1][4]
	// Skill 2 - $images[1][5]
	// Skill 3 - $images[1][6]
	// Ulty    - $images[1][7]
	/*
	if (!file_exists("./img/heroes/skills/$pdHero-s1.gif") AND $var_end !="")
    {
    $ch = curl_init($images[1][4]);
    $fp = fopen("./img/heroes/skills/$pdHero-s1.gif", "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
	
    curl_close($ch);
    fclose($fp);
	}
	
	if (!file_exists("./img/heroes/skills/$pdHero-s2.gif") AND $var_end !="")
    {
    $ch = curl_init($images[1][5]);
    $fp = fopen("./img/heroes/skills/$pdHero-s2.gif", "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
	
    curl_close($ch);
    fclose($fp);
	}
	
	if (!file_exists("./img/heroes/skills/$pdHero-s3.gif") AND $var_end !="")
    {
    $ch = curl_init($images[1][6]);
    $fp = fopen("./img/heroes/skills/$pdHero-s3.gif", "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
	
    curl_close($ch);
    fclose($fp);
	}

	if (!file_exists("./img/heroes/skills/$pdHero-s4.gif") AND $var_end !="")
    {
    $ch = curl_init($images[1][7]);
    $fp = fopen("./img/heroes/skills/$pdHero-s4.gif", "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
	
    curl_close($ch);
    fclose($fp);
	}
	
	$var_end = preg_replace(
	'/<img src="http:\/\/media.playdota.com\/hero\/(.+?)\/skill-0.jpg" class="skill" border="0"\/>/is', 
	"<div><img src='./img/heroes/skills/$pdHero-s1.gif' class='skill' border='0'/></div><br>", $var_end);
	
	$var_end = preg_replace(
	'/<img src="http:\/\/media.playdota.com\/hero\/(.+?)\/skill-1.jpg" class="skill" border="0"\/>/is', 
	"<br><div><img src='./img/heroes/skills/$pdHero-s2.gif' class='skill' border='0'/></div><br>", $var_end);
	
	$var_end = preg_replace(
	'/<img src="http:\/\/media.playdota.com\/hero\/(.+?)\/skill-2.jpg" class="skill" border="0"\/>/is', 
	"<br><div><img src='./img/heroes/skills/$pdHero-s3.gif' class='skill' border='0'/></div><br>", $var_end);
	
	$var_end = preg_replace(
	'/<br><img src="http:\/\/media.playdota.com\/hero\/(.+?)\/skill-3.jpg" class="skill" border="0"\/><br>/is', 
	"<br><div><img src='./img/heroes/skills/$pdHero-s4.gif' class='skill' border='0'/></div><br>", $var_end);
	*/

	
	//END SKILLS
   echo "<div align='center'><table style='width:600px;margin:8px;'><tr>
   <td style='padding:8px;'>
   <div align='left'>".$var_end."</div></td>
   </tr></table></div>";

   $var_end = str_replace("'",'"',$var_end );
   
   //$var_end = safeEscape($var_end);
   //$pdHero = str_replace("-"," ",$pdHero);
   file_put_contents("./includes/_heroes/$pdHero.html", $var_end);
   //$sql = "UPDATE heroes SET summary='$var_end', stats='',skills='' WHERE LOWER(description) LIKE ('%$pdHero%')";
   //$result = $db->query($sql);

    ?>