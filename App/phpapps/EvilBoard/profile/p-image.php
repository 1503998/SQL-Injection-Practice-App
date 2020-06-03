<? 
/*******************************/
/** EvilBoard Image Uploader ***/
/*******************************/

upload_file("p_image", "Upload/"); 
?>
<style type="text/css">
<!--
.style1 {
	color: #FF0000;
	font-weight: bold;
}
.style2 {
	color: #0099CC;
	font-weight: bold;
}
-->
</style>

<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_forum">&nbsp;<strong>Select Profile Image</strong></td>
  </tr>
  <tr>
    <td class="forum_footer">
				<table width="100%"  border="0" cellspacing="1">
                  <tr>
                    <td width="84%" valign="top"><fieldset><legend class="eb_txt" style="color: #0099CC"><span style="font-weight: bold">Upload new profile image:</span></legend>
                   <form action="<?=$PHP_SELF?>" method="post" enctype="multipart/form-data">
				   <span class="eb_txt">Max size: 450kb - Width: 80px - Height: 80px (image will automaticly change width and height)</span>
                     <table width="100%"  border="0" cellspacing="0">
                       <tr>
                         <td width="232"><span class="eb_txt"></span><input type="file" name="p_image" style="width:230px;" class="eb_header" border="0"></td>
                         <td width="394" valign="bottom"><input name="Submit" type="submit" class="eb_menu1" value="Upload Image."></td>
                       </tr>
                     </table>
                   </form></fieldset><br><fieldset><legend class="eb_txt style2">Gallery</legend>
                   <div align="center"><span class="style1">Gallery will come in version 0.1b</span>
                   </div>
                   </fieldset></td>
                    <td width="16%" valign="top"> <fieldset class="eb_txt" style="margin-bottom: 6px">
				<legend style="color: #0099CC"><span style="font-weight: bold">Current Profile Image:	</span></legend>
				<div align="center"><?
				$p_id = $_SESSION['userid'];
				$p_sql = "SELECT logo FROM `eb_profile` WHERE `id` = '$p_id';";
				$p_connect = mysql_query( $p_sql );
				while( $p_get = mysql_fetch_object( $p_connect ) ) {
				$logo = $p_get -> logo;
				}
		    if ( $logo !== "" ) { echo "<img src='$logo' alt='$logo' width='80' height='80'>"; }
		   elseif ( $logo == "" ) { echo "<img src='Themes/Default/Images/noimage.gif'>"; }   ?>
				</div>
                    </fieldset></td>
                  </tr>

                </table>
	 
  </tr>
</table>
