<?php
  /*********************************************
   <!--
   *     DOTA OPENSTATS
   *
   *  Developers: Ivan.
   *  Contact: ivan.anta@gmail.com - Ivan
   *
   *
   *  Please see http://openstats.iz.rs
   *  and post your webpage there, so I know who's using it.
   *
   *  Files downloaded from http://openstats.iz.rs
   *
   *  Copyright (C) 2010  Ivan
   *
   *
   *  This file is part of DOTA OPENSTATS.
   *
   *
   *   DOTA OPENSTATS is free software: you can redistribute it and/or modify
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
  $pageTitle = "$lang[create_sig]";
  
  if ($enableSignatures == 1) {
      //SUBMIT NICKNAME
      if (!isset($_GET["u"]) and $_SERVER['REQUEST_METHOD'] != 'POST') {
?>
  <form name="myForm" method="post" action="signature.php"/>
  <div align="center">
  <table class="ItemInfo"><tr><th><div align="center"><?=$lang["create_sig"]?></div></th></tr></table>
  <br />
  <table class="ItemInfo"><tr><th><?=$lang["enter_name"]?>:</th></tr>
   <tr>
       <td><br />&nbsp;<input name="nick" class="inputSearch" size="30" maxlength="30" type="text"> <br /><br /></td>
   </tr>
       <tr>
       <td><br />&nbsp;<input type="submit" type="button" value="<?=$lang["create_sig"]?>" class="inputButton"> 
     <br /><br /></td>
     </tr>
  </table>
  </div>
  </form>
  <br />
  <?php
      } else {
          if (!isset($_GET["u"]) and $_SERVER['REQUEST_METHOD'] != 'POST') {
              echo "Unknown username!";
              die;
          }
          
          if (!extension_loaded('gd') && !function_exists('gd_info')) {
              echo "GD is not installed!";
              die;
          }
          
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
              $username = trim(strtolower(safeEscape($_POST["nick"])));
          } else {
              $username = trim(strtolower(safeEscape($_GET["u"])));
          }
          
          //Check if username exists    
          $sql = "SELECT gp.name FROM gameplayers as gp
  WHERE LOWER(gp.name) = LOWER('$username') LIMIT 1";
          $result = $db->query($sql);
          if ($db->num_rows($result) <= 0) {
              echo $lang["err_user"] . "<br /><br /> <A href='signature.php'>Back to previous page</a>";
              die;
          }
          
          // current URL
          $this_url = "http://" . $_SERVER["SERVER_NAME"] . dirname($_SERVER["PHP_SELF"]);
          
          $last = $this_url[strlen($this_url) - 1];
          if ($last != "/") {
              $this_url .= "/";
          }
          
          $this_url = str_replace("\\", "", $this_url);
          //echo $this_url;
          //include('sig.php');
          $username = urlencode($username);
?> 
<br />
<body onload='requestActivities2("<?=$this_url;?>sig.php?u=<?=$username?>");'>
<div style="display:none;">
<div id='divActivities2'></div>
</div>
   <div align="center">
   <img alt=" " border=0 src="<?=$this_url;?>sig.php?u=<?=$username?>" />
   <img alt=" " width=0 height= 0 border=0 src="<?=$this_url;?>img/sig/<?=$username?>.jpg" />
  <br />
   <table class="tableHeroPageTop"><tr>
     <th><div align="center"><a target="_blank" href="<?=$this_url;?>sig.php?u=<?=$username?>">View your signature</a></div></th>
     </tr>
   <br />
   
   <table class="tableHeroPageTop"><tr>
     <th>BBCode</th></tr>
     <tr>
       <td align="left">
       <textarea style="width:600px;height:65px;">[url=<?=$this_url?>user.php?u=<?=$username?>][img]<?php
          echo $this_url;
?>sig.php?u=<?=$username?>[/img][/url]</textarea>
       </tr>
       <tr>
       <th>HTML</th></tr>
       <tr>
           <td align="left">
           <textarea style="width:600px;height:65px;"><a href="<?=$this_url?>user.php?u=<?=$username?>"><img alt="*" src="<?=$this_url?>sig.php?u=<?=$username?>" /></a></textarea>
           </td></tr>
         <th>Direct Link</th></tr><tr>
           <td align="left"><a title="" target="_blank" href="<?=$this_url?>sig.php?u=<?=$username?>"><?=$this_url?>sig.php?u=<?=$username?></a></td></tr>
   </table>
   
  </div>
  <br /><br />
<?php
      }
  }
  include('footer.php');
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
?>