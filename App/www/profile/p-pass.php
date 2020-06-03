<?
	###############################
	## EVILBOARD CHANGE PASSWORD ##
	###############################
	
	include("functions/crypt.class.php");
	
	###############################
	##	 ANTIBOT SYSTEM CONFIG 	 ##
	###############################
	
	$antibot = new antibot;
	
	if ( !isset( $_POST['Submit'] ) ) {
	$antibot->create();
	};
	
	$file = "antibot/antibot.crypt";
	$antibot->open($file);
	
	###############################
	##	  END ANTIBOT SYSTEM   	 ##
	###############################
	
	if ( isset( $_POST['Submit'] ) ) {
		$c_pw = $_POST['c_pw'];
		$n_pw = $_POST['n_pw'];
		$anti_check = $_POST['valid_a'];
		$id = $_SESSION['userid'];
		
		$valid = $antibot->open($file);
		
		# CHECK IF VALIDATION CODE IS VALID #
		if ( $anti_check !== $valid ) {
			$error_v = "<br><b><font color=\"red\" class=\"eb_txt\">Error: Wrong Validation Code.</span></b>";
			$antibot->create();
		}
		if ( $anti_check == "" ) {
			$error_v = "<br><b><font color=\"red\" class=\"eb_txt\">Error: Validation Code is blank.</span></b>";
			$antibot->create();
		}
		
		# CHECK IF CURRENT PASSWORD MATCH DATABASE PASSWORD #
		$check = "SELECT password FROM eb_members WHERE `userid` = '$id'";
		$connect = mysql_query($check);
		$pw_check = mysql_fetch_array($connect);
		$pw = $pw_check['password'];
		
		if ( md5($c_pw) !== $pw ) {
			$pw_error = "<b><font color=\"red\" class=\"eb_txt\">Error: Wrong Password.</span></b>";
		}
		$md5_npw = md5($n_pw);
		if ( !empty($n_pw) && empty($error_v) && empty($pw_error) ) {
		$update = "UPDATE `eb_members` SET `password` = '$md5_npw' WHERE `userid` = '$id'";
		mysql_query($update);
		echo "UPDATED";
		}
	}
	
?>

<form name="form1" method="post" action="<?=$PHP_SELF?>">
<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_forum">&nbsp;<strong>Change Password</strong></td>
  </tr>
  <tr>
    <td class="forum_footer"><table width="63%"  border="0" align="center" cellspacing="0" style="border: 1px dotted  #a6a6a6; background-color: #1d1d1d;">
      <tr>
        <td width="21%"  style="border: 1px dotted  #a6a6a6; background-color: #1d1d1d; border-left-width: 0; border-top-width: 0; "><input name="c_pw" type="password" class="eb_header" style="width: 200px;" maxlength="15"></td>
        <td width="79%" style="border: 1px dotted  #a6a6a6; background-color: #1d1d1d; border-left-width: 0; border-top-width: 0; border-right-width: 0;">:<strong class="eb_txt">Current Password <?=$pw_error?></strong></td>
      </tr>
      <tr>
        <td style="border: 1px dotted  #a6a6a6; background-color: #1d1d1d; border-left-width: 0; border-top-width: 0; "><input name="n_pw" type="password" class="eb_header" style="width: 200px;" maxlength="15"></td>
        <td  style="border: 1px dotted  #a6a6a6; background-color: #1d1d1d; border-left-width: 0; border-top-width: 0; border-right-width: 0;">:<strong class="eb_txt">New Password </strong></td>
      </tr>
      <tr>
        <td height="98" colspan="2"><div align="center">          <img src="antibot.php"><br>
            <strong class="eb_txt">Validation Code:
            </strong><br>
          <input name="valid_a" type="text" class="eb_header" id="valid_a" maxlength="8">
        <? if ( $error_v ) { echo $error_v; }; $error_v = ""; ?></div></td>
  </tr>
      <tr>
        <td height="1" colspan="2" style="border: 1px dotted  #a6a6a6; background-color: #1d1d1d; border-bottom-width: 0; border-left-width: 0; border-right-width: 0;"><div align="center">            <input type="submit" name="Submit" value="Update Password" class="eb_menu1"></div> </td>
  </tr>
</table>
    <div align="center">
     
      <?=$passwd?>
    </div></td>
  </tr>
</table></form>