<?
/******************************/
/** EvilBoard - Profile Edit **/
/******************************/
load_wysiwyg();

dbconnect();
if ( isset( $_POST['website'] ) ) {
			 $f_id = $_SESSION['userid'];
			 $f_msn = $_POST['msn'];
			 $f_yahoo = $_POST['yahoo'];
			 $f_aim = $_POST['aim'];
			 $f_icq = $_POST['icq'];
			 $f_location = $_POST['location'];
			 $f_website = $_POST['website'];
			 $f_intr = $_POST['intr'];
			 $f_age = $_POST['age'];
			 
$f_p_i_sql = "UPDATE `eb_profile` SET `msn` = '$f_msn', `yahoo` = '$f_yahoo', `icq` = '$f_icq', `aim` = '$f_aim', `location` = '$f_location', `website` = '$f_website',
`intr` = '$f_intr', `age` = '$f_age' WHERE `id` =" . $f_id . " LIMIT 1 ;";

mysql_query ( $f_p_i_sql );
}
/***** GET::FORUMNAME *****/
$getforumname = "SELECT * FROM eb_settings LIMIT 0,1;";
$get_fname = mysql_query("$getforumname");
while( $getnub = mysql_fetch_object( $get_fname ) )
	{
		$forumname = $getnub -> forum_name;
	}
/***** END::FORUMNAME *****/

/*****************************/
/** Get Profile Information **/
/*****************************/
$gp_id = $_SESSION['userid'];
    $query = "SELECT * FROM `eb_profile` WHERE `id` = '$gp_id'";
	$results = mysql_query( $query );
while( $getinfo = mysql_fetch_object( $results ) )
		{
			$uid = $getinfo -> id;
			 $msn = $getinfo -> msn;
			 $yahoo = $getinfo -> yahoo;
			 $aim = $getinfo -> aim;
			 $icq = $getinfo -> icq;
			 $LOCATION = $getinfo -> location;
			 $WEBSITE = $getinfo -> website;
			 $intr = $getinfo -> intr;
			 $age = $getinfo -> age;
			 }
?>
<form action="<?=$PHP_SELF?>" method="post" name="xfx">
<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_forum">&nbsp;<strong>Profile Edit :: <? $NAME = $_SESSION['user_name']; echo "$NAME"; ?></strong></td>
  </tr>
  <tr>
    <td class="forum_footer">			<fieldset class="eb_txt" style="margin-bottom: 6px">
				<legend style="color: #0099CC"><span style="font-weight: bold">Contact 
				<?=$NAME?> 
				</span></legend>
				<table cellpadding="0" cellspacing="3" border="0" width="68%">
				<tr>
				  <td width="113" valign="top" class="eb_txt"><div align="right"><span style="font-weight: bold">Website</span>:</div></td>
				  <td width="312" class="eb_txt"><input name="website" type="text" class="eb_header" style="width: 100%" value="<?=$WEBSITE?>"></td>
				  </tr>
				<tr>
				  <td width="113" valign="top" class="eb_txt"><div align="right"><span style="font-weight: bold">Your Age </span>:</div></td>
				  <td class="eb_txt"><input name="age" type="text" class="eb_header" style="width: 100%" value="<?=$age?>"></td>
				</tr>
				<tr>
				  <td width="113" valign="top" class="eb_txt"><div align="right"><span style="font-weight: bold">Your Location</span>:</div></td>
				  <td class="eb_txt"><input name="location" type="text" class="eb_header" style="width: 100%" Value="<?=$LOCATION?>"></td>
				  </tr>
				<tr>
				  <td width="113" valign="top" class="eb_txt"><div align="right"><span style="font-weight: bold">Your interests</span>:</div></td>
				  <td class="eb_txt"><textarea name="intr" id="csx" class="forum_footer" style="width: 50%; height: 100px;"><?=$intr?></textarea></td>
				  </tr>
				</table>
			</fieldset><br>			<fieldset class="eb_txt" style="margin-bottom: 6px">
				<legend style="color: #0099CC"><span style="font-weight: bold">All About 
				<?=$NAME?> 
				</span></legend>
				<table cellpadding="0" cellspacing="3" border="0" width="68%">
				<tr>
				  <td width="117" valign="top" class="eb_txt"><div align="right"></div>				    <div align="right"><span style="font-weight: bold">Your ICQ UIN</span>:</div></td>
				  <td width="375" class="eb_txt"><input name="icq" type="text" class="eb_header" style="width: 100%" Value="<?=$icq?>"></td>
				  </tr>
				<tr>
				  <td width="117" valign="top" class="eb_txt"><div align="right"><span style="font-weight: bold">Your AOL</span>:</div></td>
				  <td class="eb_txt"><input name="aim" type="text" class="eb_header" style="width: 100%" value="<?=$aim?>"></td>
				  </tr>
				<tr>
				  <td width="117" valign="top" class="eb_txt"><div align="right"><span style="font-weight: bold">Your MSN Messenger</span>:</div></td>
				  <td class="eb_txt"><input name="msn" type="text" class="eb_header" style="width: 100%" Value="<?=$msn?>"></td>
				  </tr>
				<tr>
				  <td width="117" valign="top" class="eb_txt"><div align="right"><span style="font-weight: bold">Your Yahoo!</span>:</div></td>
				  <td rowspan="2" class="eb_txt"><input name="yahoo" type="text" class="eb_header" style="width: 100%" Value="<?=$yahoo?>"></td>
				  </tr>
				<tr>
				  <td width="117" rowspan="2" valign="top" class="eb_txt">&nbsp;</td>
				  </tr>
				<tr>
				  <td class="eb_txt"><input name="id" type="hidden" value="<?=$id?>"></td>
				  </tr>
				<tr>
				  <td width="117" class="eb_txt">&nbsp;</td>
				  <td class="eb_txt"><input name="Submit" type="submit" class="eb_menu1" value="Submit" style="width: 100%"></td>
				  </tr>
				</table>
			</fieldset></td>
  </tr>
</table><br></form>
<script language="javascript1.2">
  generate_wysiwyg('csx');
</script>
<? include("include/footer.php"); ?>