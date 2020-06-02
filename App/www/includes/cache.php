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
if (!defined('IN_OPENSTATS')) exit;

   if ($cachePages == '1')
   {
  $break = Explode('/',$_SERVER["SCRIPT_NAME"]);
  $cPage = $break[count($break) - 1];
  $cPage = str_replace(".php","",$cPage);
   
  $CacheTopPage = "./$cacheDir/$cPage"."_".$_SERVER['QUERY_STRING'].".html";
  $CacheTopPage = str_replace("=","_",$CacheTopPage);
  $CacheTopPage = str_replace("&","_",$CacheTopPage);

  //cache TOP
  if (file_exists($CacheTopPage) AND $_SERVER['REQUEST_METHOD'] == 'POST')
  			{
			   if (is_numeric($_POST['gp'])){
			   $gplay = trim(strip_tags($_POST['gp']));
			   $games = $gplay;
			   $CacheTopPage = str_replace("gp_$minGamesPlayed","gp_$gplay",$CacheTopPage);
			   }
			}
   //cache MONTHLY
    if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST["months"]) AND isset($_POST["years"])) {
	$dd = str_replace(array(":","*","?","\\","/","."),array("","","","","",""),strip_tags($_POST["days"]));
	$mm = str_replace(array(":","*","?","\\","/","."),array("","","","","",""),strip_tags($_POST["months"]));
	$yyyy = str_replace(array(":","*","?","\\","/","."),array("","","","","",""),strip_tags($_POST["years"]));
    $CacheTopPage = "./$cacheDir/$cPage-$dd-$mm-$yyyy.html";
    }

  if (file_exists($CacheTopPage) AND time() - $cachetime < filemtime($CacheTopPage))
  {
   //echo $CacheTopPage;
  $created = filemtime($CacheTopPage);
  $nextUpdate = $cachetime + $created;
  $cached = file_get_contents($CacheTopPage); 
  echo $cached;
       if ($showUpdate == '1')
           {
		  $cached = str_replace("</body></html>","",$cached);
	      echo "<table><tr>
		  <td style='padding-left:12px;' align='left'><b>Status:</b> Cached</td>
          <td align='right'>".$lang["last_update"]." ".date($date_format, filemtime($CacheTopPage))."</td>
          <td style='padding-right:12px;' align='right'>".$lang["next_update"]." ".date($date_format, $nextUpdate)."</td></tr></table><br></body></html>"; 
		  }
		 exit;
	   }
	}
?>