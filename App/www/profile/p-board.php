<?
###########################################
## EvilBoard Board Settings By El Diablo ##
##		 Copyright 2006 El Diablo		 ##
###########################################
$s_id = $_SESSION['userid'];
if( isset( $_POST['submit'] ) ) {
	$p_sig = $_POST['p_sig'];
	$p_avy = $_POST['p_avy'];
	
	$r_sig = str_replace("","0",$p_sig);
	$r_avy = str_replace("","0",$p_avy);	
	
	$s_sql = "UPDATE eb_psettings SET `p_sig` = '$r_sig', `p_avy` = '$r_avy' WHERE `UserID` = '$s_id'";
	mysql_query($s_sql);
}
	$s_sql = "SELECT * FROM eb_psettings WHERE `UserID` = '$s_id'";
	$s_connect = mysql_query( $s_sql );
while ( $s_get = mysql_fetch_object ( $s_connect ) ) {
	$id = $s_get -> UserID;
	$sig = $s_get -> p_sig;
	$avy = $s_get -> p_avy;
	
	$rep_sig = str_replace("0","",$sig);
	$rep_avy = str_replace("0","",$avy);	
	
	$rep_sig = str_replace("1","checked",$sig);
	$rep_avy = str_replace("1","checked",$avy);
}
?>

<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_forum">&nbsp;<strong>Board Settings</strong></td>
  </tr>
  <tr>
    <td class="forum_footer"><div align="center">
      <form name="form1" method="post" action="<?=$PHP_SELF?>">
        <table width="100%"  border="0" cellspacing="0">
          <tr>
            <td width="37%" class="eb_txt"><div align="right" style="font-weight: bold">View member signatures in topics: </div></td>
            <td width="63%"><input name="p_sig" type="checkbox" value="1" <?=$rep_sig?>></td>
          </tr>
          <tr>
            <td class="eb_txt"><div align="right" style="font-weight: bold">View members avitar when reading topics: </div></td>
            <td><input name="p_avy" type="checkbox" value="1" <?=$rep_avy?>></td>
          </tr>
          <tr>
            <td class="eb_txt"><div align="right"><strong>Theme:</strong></div></td>
            <td style="padding-left: 5px;"><select name="select" >
              <option value="T_default" selected>EvilBoard</option>
            </select></td>
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