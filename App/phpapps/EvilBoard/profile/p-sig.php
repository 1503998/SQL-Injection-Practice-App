<?
/**********************************************/
/** Signature Editor (WYSIWYG) for EvilBoard **/
/**********************************************/
include("tinymce.php");
$id = $_SESSION['userid'];
if( isset( $_POST['textarea'] ) )  {
	$f_sig = $_POST['textarea'];
	$p_update_s = "UPDATE `eb_profile` SET `sig` = '$f_sig' WHERE `id` = '$id'";
	mysql_query ( $p_update_s );
}

$p_sig_q = "SELECT sig FROM eb_profile WHERE `id` = '$id'";
$p_connect = mysql_query( $p_sig_q );
while ( $p_sig = mysql_fetch_object( $p_connect ) ) {
	$sig = $p_sig -> sig;
}
?>
<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_forum">&nbsp;<strong>Edit Signature</strong></td>
  </tr>
  <tr>
    <td class="forum_footer"><div align="center">
     <form name="form1" method="post" action="<?=$PHP_SELF?>"> <table width="100%"  border="0" cellspacing="0">
        <tr>
          <td width="12%" valign="top" class="eb_txt"><div align="right"><span style="font-weight: bold">Signature</span>:</div></td>
          <td width="88%">
            <textarea name="textarea" id="sg" rows="6" class="eb_header" style="width: 100%"><?=$sig?></textarea>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="Submit" type="submit" class="eb_menu1" value="Submit"></td>
        </tr>
      </table></form>
    </div></td>
  </tr>
</table>
<script language="javascript1.2">
  generate_wysiwyg('sg');
</script>