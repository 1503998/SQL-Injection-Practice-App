<?php
    if (!function_exists('curl_init')) {
	echo "CURL function is disabled. Please check your php configuration."; die;
	}
	
    function get_data($url, $timeout = 8)
      {
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)');
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
      }

      $gURL = "http://www.playdota.com/heroes/$pdHero";
      $content = get_data($gURL);

$dom = new DOMDocument();
@$dom->loadHTML($content);
$xpath = new DOMXPath($dom);

//  $_imgs[0] = Hero icon;
//  $_imgs[1] = Hero name;
//  $_imgs[2] = STR;
//  $_imgs[3] = AGI;
//  $_imgs[4] = INT;
//  $_imgs[5] = Animated Hero image;
//  $_imgs[6] = SKILL 1;
//  $_imgs[7] = SKILL 2;
//  $_imgs[8] = SKILL 3;
//  $_imgs[9] = SKILL 4 - ULTI;

//  $_notes[0] = SKILL 1 NOTES
//  $_notes[1] = SKILL 2 NOTES
//  $_notes[2] = SKILL 3 NOTES
//  $_notes[3] = SKILL 4 NOTES

$c = 0;
$_imgs        = array();
$_notes       = array();
$_atr         = array();
$_intro       = array();
$_skills_desc = array();
$_adv_stats = "";
//All images
$entries = $xpath->query("//div[@class='hLeft']//img");
foreach($entries as $e)
  {$_imgs[$c] =  $e->getAttribute("src"); $c++;}
  
 $c = 0;
//Attributes
$entries = $xpath->query("//div[@class='hLeft']//li");
foreach($entries as $e)
   {$_atr[$c] = $e->textContent; $c++;}
   
//Hero Name
$entries = $xpath->query("//h1[@class='class']");
foreach($entries as $e)
   $Hero_NAME =  $e->textContent;

 $c = 0;   
//Skills Descr
$entries = $xpath->query("//div[@class='hLeft']//p");
foreach($entries as $e)
  {$_skills_desc[$c] = $e->textContent; $c++;}
  
//Advanced stats
$entries = $xpath->query("//ul[@class='adv']//li");
foreach($entries as $e)
   {$_adv_stats.= $e->textContent . '<br />';}

$c = 0;
//Hero Introduction
$entries = $xpath->query("//div[@id='info']//p");
foreach($entries as $e)
   {$_intro[$c] = $e->textContent; $c++;}

$c = 0;   
//ADV Skills
$entries = $xpath->query("//div[@class='notes']");
foreach($entries as $e)
   {$_notes[$c]= $e->textContent; $c++;}
   
$c = 0;   
//Skilla name
$entries = $xpath->query("//div[@class='hLeft']//h2");
foreach($entries as $e)
   {$_skills_name[$c]= $e->textContent; $c++;}

$_adv_stats = str_replace(array(
"Affiliation:",
"Attack Animation:",
"Damage:",
"Casting Animation:",
"Armor:",
"Base Attack Time:",
"Movespeed:",
"Missile Speed:",
"Attack Range:", 
"Sight Range:"), 

array(
"<span style='color:#FFAA00'>Affiliation:</span></td><td>",
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Attack Animation:</span></td><td>",
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Damage:</span></td><td>",
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Casting Animation:</span></td><td>",
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Armor:</span></td><td>",
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Base Attack Time:</span></td><td>",
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Movespeed:</span></td><td>",
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Affiliation:</span></td><td>",
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Attack Range:</span></td><td>", 
"</td></tr><tr><td class='padLeft'><span style='color:#FFAA00'>Sight Range:</span></td><td>",
), $_adv_stats);  

$_notes[0] = str_replace("Notes","<span style='color:#FFAA00;'><b>Notes</b></span>",$_notes[0]); 
$_notes[1] = str_replace("Notes","<span style='color:#FFAA00;'><b>Notes</b></span>",$_notes[1]); 
$_notes[2] = str_replace("Notes","<span style='color:#FFAA00;'><b>Notes</b></span>",$_notes[2]); 
$_notes[3] = str_replace("Notes","<span style='color:#FFAA00;'><b>Notes</b></span>",$_notes[3]); 

$_skills_name[0] = str_replace($_skills_name[0],"<span style='color:#83BAFF;'><b>$_skills_name[0]</b></span>",$_skills_name[0]); 
$_skills_name[1] = str_replace($_skills_name[1],"<span style='color:#83BAFF;'><b>$_skills_name[1]</b></span>",$_skills_name[1]); 
$_skills_name[2] = str_replace($_skills_name[2],"<span style='color:#83BAFF;'><b>$_skills_name[2]</b></span>",$_skills_name[2]); 
$_skills_name[3] = str_replace($_skills_name[3],"<span style='color:#83BAFF;'><b>$_skills_name[3]</b></span>",$_skills_name[3]); 

$HeroHTML = '<div align="center">
   <table style="width:690px;margin:8px;">
         <tr>
             <td>
			     <table>
				 <tr>
				     <th><div align="center">'.$hero.'</div></th>
					 </tr>
				 </table>
				 <table>
				  <tr>
				      <td class="padLeft" width="80"><img src="'.$_imgs[0].'" alt="" border=0 /></td>
					  <td class="padLeft" width="180" valign="top"><img src="'.$_imgs[1].'" alt="" border=0  /><br />'.$Hero_NAME.'</td>
					  <td><img width="80" height="80" src="'.$_imgs[5].'" alt="" border=0 /></td>
				  </tr>
				  </table>
				  <table>
				  				 <tr><th></th><th></th></tr>
				  <tr>
				      <td width = "35"><img width="32" height="32" src="'.$_imgs[2].'" alt="" border=0 /></td>
					  <td>'.str_replace("Strength","<b>Strength:</b>",$_atr[0]).'</td>
				  </tr>
				  <tr>
				      <td><img width="32" height="32" src="'.$_imgs[3].'" alt="" border=0 /></td>
					  <td>'.str_replace("Agility","<b>Agility:</b>",$_atr[1]).'</td>
				  </tr>
				  <tr>
				      <td><img width="32" height="32" src="'.$_imgs[4].'" alt="" border=0 /></td>
					  <td>'.str_replace("Intelligence","<b>Intelligence:</b>",$_atr[2]).'</td>
				  </tr>
				  
				 </table>
				 <table>
				 <tr>
				     <th style="padding-left:6px;">Advanced statistics</th><th></th>
				 </tr>
				 <tr>
				     <td width="150" class="padLeft">'.$_adv_stats.'</td>
				 </tr>
				 </table>
				 <table>
				 <tr><th></th><th></th></tr>
				 <tr>
				     <td class="padLeft" valign="top" width = "150"><b>'.$_skills_name[0].'</b><br /><img width="64" height="64" src="'.$_imgs[6].'" alt="" border=0 /></td>
					 <td>'.$_skills_desc[0].'<br /><br />'.$_notes[0].'<br /><br /></td>
				 </tr>
				 <tr>
				     <td class="padLeft" valign="top" width = "69"><b>'.$_skills_name[1].'</b><br /><img width="64" height="64" src="'.$_imgs[7].'" alt="" border=0 /></td>
					 <td>'.$_skills_desc[1].'<br /><br />'.$_notes[1].'<br /><br /></td>
				 </tr>
				 <tr>
				     <td class="padLeft" valign="top" width = "69"><b>'.$_skills_name[2].'</b><br /><img width="64" height="64" src="'.$_imgs[8].'" alt="" border=0 /></td>
					 <td>'.$_skills_desc[2].'<br /><br />'.$_notes[2].'<br /><br /></td>
				 </tr>
				 <tr>
				     <td class="padLeft" valign="top" width = "69"><b>'.$_skills_name[3].'</b><br /><img width="64" height="64" src="'.$_imgs[9].'" alt="" border=0 /></td>
					 <td>'.$_skills_desc[3].'<br /><br />'.$_notes[3].'<br /><br /></td>
				 </tr>
				 </table>
				 
				 <table>
				 <tr><th class="padLeft">Hero Introduction</th></tr>
				 <tr>
				      <td class="padLeft">'.$_intro[0].'</td>
				 </tr>
				 <tr><th class="padLeft">Background story</th></tr>
				 <tr>
				      <td class="padLeft">'.$_intro[1].'</td>
				 </tr>
				 </table>
			     
		    </td>
		 </tr>
		 
   </table>
   </div>';
   echo $HeroHTML;
   file_put_contents("./includes/_heroes/$pdHero.html", $HeroHTML);
   ?>
 <!--  -->