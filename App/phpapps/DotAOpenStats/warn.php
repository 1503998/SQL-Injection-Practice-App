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
  include('header.php');
  $pageTitle = $lang["warn"];
  if ($WarnAndExpireDate == 1){
   $sql = "SELECT COUNT(*) FROM bans WHERE warn = 1 LIMIT 1";
   $result = $db->query($sql);
   $r = $db->fetch_row($result);
   $numrows = $r[0];
   $result_per_page = $bans_per_page;
  include('pagination.php');
  $order = 'id';
  if (isset($_GET['order']))
  {
  if ($_GET['order'] == 'name') {$order = ' LOWER(name) ';}
  if ($_GET['order'] == 'date') {$order = ' date ';}
  if ($_GET['order'] == 'game') {$order = ' LOWER(gamename) ';}
  if ($_GET['order'] == 'reason') {$order = ' LOWER(reason) ';}
  if ($_GET['order'] == 'bannedby') {$order = ' LOWER(admin) ';}
  if ($_GET['order'] == 'id') {$order = ' id ';}
  }
  $sort = 'DESC';
  if (isset($_GET['sort']) AND $_GET['sort'] == 'asc')
  {$sort = 'desc'; $sortdb = 'ASC';} else {$sort = 'asc'; $sortdb = 'DESC';}
  $sql = "
  SELECT * FROM bans 
  WHERE warn = 1
  ORDER BY 
  $order $sortdb LIMIT $offset, $rowsperpage";
  $result = $db->query($sql)  or die(mysql_error());
  ?>
  <div align='center'>
  <table style='width:95%;margin:8px;'> 
  <tr>
   <th><div align='center'><a href='<?=$_SERVER['PHP_SELF']?>?order=id&sort=<?=$sort?>'><?=$lang['id']?></a></div></th>
  <th><div align='left'><a href='<?=$_SERVER['PHP_SELF']?>?order=name&sort=<?=$sort?>'><?=$lang['name']?></a></div></th>
  <th><div align='left'><a href='<?=$_SERVER['PHP_SELF']?>?order=reason&sort=<?=$sort?>'><?=$lang['reason']?></a></div></th>
  <th><div align='left'><a href='<?=$_SERVER['PHP_SELF']?>?order=game&sort=<?=$sort?>'><?=$lang['game_name']?></a></div></th>
  <th><div align='left'><a href='<?=$_SERVER['PHP_SELF']?>?order=date&sort=<?=$sort?>'><?=$lang['date']?></a></div></th>
  <th><a href='<?=$_SERVER['PHP_SELF']?>?order=bannedby&sort=<?=$sort?>'><?=$lang['warnedby']?></a></th>
  </tr><?php
  while ($list = $db->fetch_array($result,'assoc')) {
  $get_date = date($date_format,strtotime($list['date']) );
  $name = trim("$list[name]");
  $reason = convEnt2(trim($list["reason"])); 
  $myFlag = "";
  $IPaddress = $list["ip"];
  //COUNTRY FLAGS
		if ($CountryFlags == 1 AND file_exists("./includes/ip_files/countries.php") AND $IPaddress!="")
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
  $celltitle = " title='$reason'";
  if (strlen($reason)>=40) {$reason = "".strtolower(substr($reason,0,40))."...";}
   if ($WarnAndExpireDate == 1)
   {
      if ($list["expiredate"]!="")
      {$reason.="<br /><strong>$lang[expire]</strong> (".date($date_format,strtotime($list["expiredate"])).")";}
   }
  $data = array($celltitle, $myFlag, $list['id'], $name, $reason, $list['gamename'], $get_date, strtolower($list['admin']),$list['admin']);
   
   $tags = array('{CELL}', '{FLAG}', '{ID}', '{NAME}', '{REASON}', '{GAMENAME}', '{DATE}', '{ADMIN}','{ADMIN_NAME}'
   );
   echo str_replace($tags, $data, file_get_contents("./style/$default_style/bans.html"));
  }
  ?></table>
  </div><?php
  include('pagination.php');
   }
   ?><div style="clear: both;">&nbsp;</div><?php
  include('footer.php');
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
?>