<? include("include/header.php"); ?>
<?
if ( isset ( $_GET['memberid'] ) ) {
   dbconnect();
	$id = $_GET['memberid'];
    $query = "SELECT * FROM `eb_profile` WHERE `id` =" . $id . "";
	$results = mysql_query( $query );
	
	if( $results )
	{
		while( $getinfo = mysql_fetch_object( $results ) )
		{
			$uid = $getinfo -> id;
			 $rank = $getinfo -> rank;
			 $logo = $getinfo -> logo;
			 $email = $getinfo -> email;
			 $msn = $getinfo -> msn;
			 $yahoo = $getinfo -> yahoo;
			 $aim = $getinfo -> aim;
			 $icq = $getinfo -> icq;
			 $mpad = $getinfo -> mpad;
			 $hps = $getinfo -> hps;
			 $mouse = $getinfo -> mouse;
			 $cpu = $getinfo -> cpu;
			 $mboard = $getinfo -> mboard;
			 $ram = $getinfo -> ram;
			 $monit = $getinfo -> monit;
			 $GPCARD = $getinfo -> gpcard;
			 $LOCATION = $getinfo -> location;
			 $WEBSITE = $getinfo -> website;
			 $intr = $getinfo -> intr;
			 $age = $getinfo -> age;
			 $alias = $getinfo -> alias;
			 $rr = id_rank($rank);
			 }
/******************************/
/** GET INFO FROM EB MEMBERS **/
/******************************/
$query = "SELECT username,signup_date,last_login FROM eb_members WHERE `userid` = '$id'";
$connect = mysql_query( $query );
while ( $getall = mysql_fetch_object( $connect ) )
	{
	$username = $getall -> username;
	$regdate = $getall -> signup_date;
	$lastlogin = $getall -> last_login;
	}
?>
<br>
    <script type="text/javascript" src="usercp/menu.js"></script>
   <table width="100%"  border="0" cellspacing="0">
     <tr>
       <td colspan="3"  class="eb_forum">&nbsp;<strong>Viewing Profile :: <?=$username?></strong></td>
     </tr>
     <tr>
       <td width="50%" valign="top" class="forum_footer">&nbsp;
         <table width="100%"  border="0" cellspacing="0">
         <tr>
           <td class="eb_header"><div align="center">All about <?=$username?> </div></td>
         </tr>
         <tr>
           <td class="eb_showpost_footer"><table width="99%" align="center" cellpadding="0" cellspacing="0">
             <tr>
               <td width="106" align="right" valign="middle" class="eb_txt"><b><span class="style6">Location</span></b>:&nbsp; </td>
               <td width="364" class="eb_txt"><?=$LOCATION?></td>
             </tr>
             <tr>
               <td align="right" valign="middle" class="eb_txt"><b><span class="style6">Website</span></b>:&nbsp; </td>
               <td valign="middle" class="eb_txt"><? if ($WEBSITE !== "") { echo "<a href='$WEBSITE'>$WEBSITE</a>"; } ?></td>
             </tr>
             <tr>
               <td align="right" valign="top" class="eb_txt"><b><span class="style6">Interests</span></b>:&nbsp; </td>
               <td class="eb_txt"><?=$intr?></td>
             </tr>
             <tr>
               <td align="right" valign="top" class="eb_txt"><b>Age</b>:&nbsp;</td>
               <td class="eb_txt"><?=$age?></td>
             </tr>
             <tr>
               <td align="right" valign="top" class="eb_txt"><span style="font-weight: bold">Registering Date</span>:&nbsp; </td>
               <td class="eb_txt"><?=$regdate?>&nbsp;</td>
             </tr>
             <tr>
               <td align="right" valign="top" class="eb_txt"><strong>Last Login</strong>:&nbsp; </td>
               <td class="eb_txt"><?=$lastlogin?></td>
             </tr>
           </table></td>
         </tr>
       </table>
       <br></td>
       <td width="0%">&nbsp;</td>
       <td width="50%" valign="top" class="forum_footer">&nbsp;
         <table width="100%"  border="0" cellspacing="0">
         <tr>
           <td class="eb_header"><div align="center">Contact <?=$username?></div></td>
         </tr>
         <tr>
           <td class="eb_showpost_footer"><table width="99%" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="20%" height="18" align="right" valign="middle" class="eb_txt"><b><span class="text style6">E-mail</span></b><span class="eb_txt">:</span></b></td>
              <td width="80%" valign="middle" class="eb_txt"><?=$email?></td>
            </tr>
            <tr>
              <td class="eb_txt" align="right" valign="middle"><b><span class="style6">MSN</span></b>:</td>
              <td class="eb_txt" valign="middle"><?=$msn?></td>
            </tr>
            <tr>
              <td class="eb_txt" align="right" valign="middle"><b><span class="style6">Yahoo</span></b>:</td>
              <td class="eb_txt" valign="middle"><?=$yahoo?></td>
            </tr>
            <tr>
              <td class="eb_txt" align="right" valign="middle"><b><span class="style6">AIM Address</span></b>:</td>
              <td class="eb_txt" valign="middle"><?=$aim?></td>
            </tr>
            <tr>
              <td class="eb_txt" align="right" valign="middle"><b><span class="style6">ICQ Number</span></b>:</td>
              <td class="eb_txt"><? if ( $icq !== "" ) { echo "<a href='http://wwp.icq.com/scripts/search.dll?to=$icq'></a>"; } ?>
       </td>
            </tr>
          </table></td>
         </tr>
       </table><br>
       <table width="100%"  border="0" cellspacing="0">
         <tr>
           <td class="eb_header"><div align="center">Account Information</div></td>
         </tr>
         <tr>
           <td class="eb_showpost_footer"><div align="center">
		   <? if ( $logo !== "" ) { echo "<img src='$logo' alt='$logo' width='80' height='80'>"; }
		   elseif ( $logo == "" ) { echo "<img src='Themes/Default/Images/noimage.gif'>"; }   ?><br>
             <? echo $rr; ?>    </strong></div></td>
         </tr>
       </table></td>
     </tr>
   </table>
<br>
<?
	}
	};
	?>
<? include("include/footer.php"); ?>