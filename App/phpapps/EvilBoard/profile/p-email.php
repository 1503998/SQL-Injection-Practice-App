<?
###########################################
## EvilBoard Email Settings By El Diablo ##
##		 Copyright 2006 El Diablo		 ##
###########################################
$s_id = $_SESSION['userid'];
if( isset( $_POST['submit'] ) ) {
	$h_mail = $_POST['h_mail'];
	$s_pm = $_POST['s_pm'];
	$s_update = $_POST['s_update'];
	
	$r_mail = str_replace("","0",$h_mail);
	$r_update = str_replace("","0",$s_update);	
	$r_pm = str_replace("","0",$s_pm);
	
	$s_sql = "UPDATE eb_psettings SET h_mail = '$r_mail', `s_pm` = '$r_pm', `s_update` = '$r_update' WHERE `UserID` = '$s_id'";
	mysql_query($s_sql);
}
	$s_sql = "SELECT * FROM eb_psettings WHERE `UserID` = '$s_id'";
	$s_connect = mysql_query( $s_sql );
while ( $s_get = mysql_fetch_object ( $s_connect ) ) {
	$id = $s_get -> UserID;
	$mail = $s_get -> h_mail;
	$update = $s_get -> s_update;
	$pm = $s_get -> s_pm;
	
	$rep_mail = str_replace("0","",$mail);
	$rep_update = str_replace("0","",$update);	
	$rep_pm = str_replace("0","",$pm);
	
	$rep_mail = str_replace("1","checked",$mail);
	$rep_update = str_replace("1","checked",$update);
	$rep_pm = str_replace("1","checked",$pm);
}
?>
<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_forum">&nbsp;<strong>Email Settings</strong></td>
  </tr>
  <tr>
    <td class="forum_footer"><div align="center">
      <form name="form1" method="post" action="<?=$PHP_SELF?>">
        <table width="100%"  border="0" cellspacing="0">
          <tr>
            <td width="37%" class="eb_txt"><div align="right" style="font-weight: bold">Send mail when i recive a PM: </div></td>
            <td width="63%"><input name="s_pm" type="checkbox" value="1" <?=$rep_pm?>></td>
          </tr>
          <tr>
            <td class="eb_txt"><div align="right" style="font-weight: bold">Hide E mail from members: </div></td>
            <td><input name="h_mail" type="checkbox" value="1" <?=$rep_mail?>></td>
          </tr>
          <tr>
            <td class="eb_txt"><div align="right" style="font-weight: bold">Send me updates by the board administrator: </div></td>
            <td><input name="s_update" type="checkbox" value="1" <?=$rep_update?>></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="submit" type="submit" class="eb_menu1" value="Update my settings"></td>
          </tr>
        </table>
      </form>
    </div></td>
  </tr>
</table>