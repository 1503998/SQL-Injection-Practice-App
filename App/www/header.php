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
 if (!ini_get("short_open_tag"))
 {echo "Please enable <a href='http://www.php.net/manual/en/ini.core.php#ini.short-open-tag'><strong>short_open_tag</strong></a> in your php.ini"; die;}
  
  if (!defined('IN_OPENSTATS')) define('IN_OPENSTATS', true);
  $time = microtime();
  $time = explode(' ', $time);
  $time = $time[1] + $time[0];
  $start = $time;

   require_once('config.php');
   require_once('./lang/'.$default_language.'.php');
   
   //Caching START 
   require_once('./includes/cache.php');
   //Caching END 
   
   require_once('./includes/class.database.php');
   require_once('./includes/db_connect.php');
   require_once('./includes/common.php');
   require_once('./includes/get_style.php');
   
   if ($enableItemsPage == 1) {$itemButton = "<a class='middle pill menuButtons' href='items.php'>$lang[items]</a>";} 
   else {$itemButton = "";}
   
   if ($enable_chat == 1)
   {$chatPage = "<a class='middle pill menuButtons' href='chat.php'>$lang[chat]</a>";}
   else {$chatPage = "";}
   
   if ($enableSafeListPage == 1)
   {$safeListLink = "<a class='middle pill menuButtons' href='safelist.php'>".$lang['safelist']."</a>";} 
   else {$safeListLink = "";}
   
   if ($WarnAndExpireDate == 1) {
   $warnsLink = "<a class='middle pill menuButtons' href='warn.php'>".$lang['warn']."</a>";
   } else {$warnsLink = "";}
   //HEADER
   $data = array($default_style,"<img alt='' style='vertical-align: middle;' src='style/$default_style/img/logo.png'/>",
   $lang["site_name"],
   $lang["bans"],
   $lang["top_players"],
   $lang["monthly_top"],
   $lang["home"],
   $lang["search"],
   $lang["meta"],
   $lang["meta_desc"],
   $lang["dota_games"],
   $lang["admins"],
   $minGamesPlayed,
   $lang["heroes"],
   $itemButton,
   $safeListLink,
   $chatPage,
   $warnsLink);
   
   $tags = array('{STYLE}', 
   '{LOGO}',
   '{NAME}',
   '{BANS}',
   '{TOP}',
   '{MONTHLY}',
   '{HOME}',
   '{SEARCH}',
   '{METAKEY}',
   '{METADESC}',
   '{GAMES}',
   '{ADMINS}',
   '{MINGAMES}',
   '{HEROES}',
   '{ITEMS}',
   '(SAFELIST)',
   '(CHAT)',
   '{WARN}');
   
   echo str_replace($tags, $data, file_get_contents("./style/$default_style/header.html"));
   
?>